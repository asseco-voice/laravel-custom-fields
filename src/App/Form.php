<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App;

use Asseco\CustomFields\Database\Factories\FormFactory;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Form extends Model
{
    use SoftDeletes, HasFactory;

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

    protected static function booted()
    {
        static::created(function (self $form) {
            $form->relateCustomFieldsFromDefinition();
        });

        static::updated(function (self $form) {
            $form->customFields()->detach();
            $form->relateCustomFieldsFromDefinition();
        });
    }

    protected function relateCustomFieldsFromDefinition()
    {
        $components = Arr::get($this->definition, 'components', []);

        $this->extractCustomFields($components);
    }

    protected function extractCustomFields(array $components): void
    {
        $key = Arr::get($components, 'key');

        $this->relateCustomField($key);

        $innerComponents = Arr::get($components, 'components', []);

        foreach ($innerComponents as $innerComponent) {
            $this->extractCustomFields($innerComponent);
        }
    }

    protected function relateCustomField(string $key): void
    {
        if (in_array($key, $this->ignoredFormComponents)) {
            return;
        }

        $customField = CustomField::query()->where('name', $key)->first();

        if ($customField) {
            $this->customFields()->attach($customField->id);
        }
    }

    protected static function newFactory()
    {
        return FormFactory::new();
    }

    public function customFields(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class)->withTimestamps();
    }

    public function setDefinitionAttribute($value)
    {
        $this->attributes['definition'] = json_encode($value);
    }

    public function getDefinitionAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * @param $formData
     * @throws Exception
     */
    public function validate(array $formData): void
    {
        /**
         * @var $customField CustomField
         */
        foreach ($this->customFields as $customField) {
            if ($this->notSetButRequired($customField, $formData)) {
                throw new Exception("The '$customField->name' field is required!");
            }

            if (!isset($formData[$customField->name])) {
                continue;
            }

            // $customField->validate($formData[$customField->name]);
        }
    }

    protected function notSetButRequired(CustomField $customField, array $formData): bool
    {
        return !array_key_exists($customField->name, $formData) && $customField->required;
    }

    /**
     * @param array $formData
     * @param string $modelType
     * @param int $modelId
     * @throws Exception
     */
    public function createValues(array $formData, string $modelType, int $modelId)
    {
        /**
         * @var $customField CustomField
         */
        foreach ($this->customFields as $customField) {
            if (!array_key_exists($customField->name, $formData)) {
                continue;
            }

            $type = $customField->getMappingColumn();

            $customField->values()->updateOrCreate([
                'model_type' => $modelType,
                'model_id'   => $modelId,
                $type        => Arr::get($formData, $customField->name),
            ]);
        }
    }
}
