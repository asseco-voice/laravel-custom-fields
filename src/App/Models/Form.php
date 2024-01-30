<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\FormTemplate;
use Asseco\CustomFields\App\Exceptions\FieldValidationException;
use Asseco\CustomFields\App\Exceptions\MissingRequiredFieldException;
use Asseco\CustomFields\Database\Factories\FormFactory;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;

class Form extends Model implements \Asseco\CustomFields\App\Contracts\Form
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'name', 'definition', 'action_url'];

    protected array $ignoredFormComponents = [
        'htmlelement',
        'content',
        'columns',
        'fieldset',
        'panel',
        'well',
        'button',
    ];

    protected $casts = [
        'definition' => 'array',
    ];

    protected static function newFactory()
    {
        return FormFactory::new();
    }

    protected static function booted()
    {
        static::creating(function (self $customField) {
            throw_if(preg_match('/\s/', $customField->name),
                new Exception('Form name must not contain spaces.'));
        });

        static::created(function (self $form) {
            $form->relateCustomFieldsFromDefinition();
            $form->refresh();
        });

        static::updated(function (self $form) {
            $form->customFields()->detach();
            $form->refresh();
            $form->relateCustomFieldsFromDefinition();
            $form->refresh();
        });
    }

    public function templates(): HasMany
    {
        return $this->hasMany(get_class(app(FormTemplate::class)));
    }

    protected function relateCustomFieldsFromDefinition()
    {
        $components = Arr::get($this->definition, 'components', []);

        $this->extractCustomFields($components);
    }

    protected function extractCustomFields(array $components): void
    {
        foreach ($components as $componentKey => $component) {
            if ($componentKey === 'key') {
                $this->relateCustomField($component);
            }

            if (!is_array($component)) {
                continue;
            }

            $this->extractCustomFields($component);
        }
    }

    protected function relateCustomField(string $key): void
    {
        if (in_array($key, $this->ignoredFormComponents)) {
            return;
        }

        /** @var CustomField $customFieldClass */
        $customFieldClass = app(CustomField::class);
        $customField = $customFieldClass::query()->where('name', $key)->first();

        if ($customField) {
            $this->customFields()->attach($customField->id);
        }
    }

    public function customFields(): BelongsToMany
    {
        return $this->belongsToMany(get_class(app(CustomField::class)))->withTimestamps();
    }

    /**
     * @param array $formData
     * @return array
     *
     * @throws Exception
     */
    public function validate(array $formData): array
    {
        $validatedFields = [];
        $missingRequiredFields = [];
        $validationErrors = [];

        /**
         * @var CustomField $customField
         */
        foreach ($this->customFields as $customField) {
            if ($this->notSetButRequired($customField, $formData)) {
                $missingRequiredFields[$customField->name] = "required";
            }

            if (!isset($formData[$customField->name])) {
                continue;
            }

            try {
                $customField->validate($formData[$customField->name]);
            } catch (FieldValidationException $e) {
                $validationErrors[$customField->name] = $e->getData();
            }

            $validatedFields = array_merge(
                $validatedFields, $customField->shortFormat($formData[$customField->name]));
        }

        if (!empty($missingRequiredFields)) {
            throw new MissingRequiredFieldException("Missing required fields", 422, null, $missingRequiredFields);
        }

        if (!empty($validationErrors)) {
            throw new FieldValidationException("Missing required fields", 400, null, $missingRequiredFields);
        }

        return $validatedFields;
    }

    protected function notSetButRequired(CustomField $customField, array $formData): bool
    {
        return !array_key_exists($customField->name, $formData) && $customField->required;
    }

    public function createValues(array $formData, string $modelType, $modelId): array
    {
        $values = [];

        /**
         * @var CustomField $customField
         */
        foreach ($this->customFields as $customField) {
            $formCustomField = Arr::get($formData, $customField->name);

            if (!$formCustomField) {
                continue;
            }

            $type = $customField->getValueColumn();

            $values[] = $customField->values()->updateOrCreate([
                'model_type' => $modelType,
                'model_id' => $modelId,
            ],
                [$type => $formCustomField]
            );
        }

        return $values;
    }

    public function updateDefinition(array $oldValues, array $newValues): void
    {
        $definition = str_replace("\"key\":\"{$oldValues['name']}\"", "\"key\":\"{$newValues['name']}\"", json_encode($this->definition));
        $this->update(['definition' => json_decode($definition)]);
    }
}
