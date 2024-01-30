<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\App\Contracts\CustomField as CustomFieldContract;
use Asseco\CustomFields\App\Contracts\Form;
use Asseco\CustomFields\App\Contracts\Mappable;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\RemoteType;
use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\App\Contracts\Validation;
use Asseco\CustomFields\App\Contracts\Value;
use Asseco\CustomFields\App\Exceptions\FieldValidationException;
use Asseco\CustomFields\Database\Factories\CustomFieldFactory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;

/**
 * @method static Builder plain(string $subType = null)
 * @method static Builder remote()
 * @method static Builder selection()
 *
 * Class CustomField
 */
class CustomField extends Model implements CustomFieldContract
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function newFactory()
    {
        return CustomFieldFactory::new();
    }

    protected static function booted()
    {
        static::creating(function (self $customField) {
            throw_if(preg_match('/\s/', $customField->name),
                new Exception('Custom field name must not contain spaces.'));
        });

        static::updated(function (self $customField) {
            $forms = $customField->forms;
            $newValues = $customField->getDirty();
            $oldValues = $customField->getOriginal();

            foreach ($forms as $form) {
                if (array_key_exists('name', $newValues)) {
                    $form->updateDefinition($oldValues, $newValues);
                    $form->refresh();
                }
            }
        });

        static::deleted(function (self $customField) {
            $customField->parent()->delete();
            $customField->children()->delete();
        });

        static::saving(function (self $customField) {
            if ($customField->isDirty('name') && $customField->exists()) {
                throw new Exception("Custom field with the name $customField->name already exists.");
            }
        });
    }

    public function selectable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePlain(Builder $query, string $subType = null): Builder
    {
        /** @var PlainType $plainType */
        $plainType = app(PlainType::class);

        $selectable = $subType ? $plainType::getSubTypeClass($subType) : $plainType::subTypes();

        return $query->whereHasMorph('selectable', $selectable);
    }

    public function scopeRemote(Builder $query): Builder
    {
        return $query->whereHasMorph('selectable', [get_class(app(RemoteType::class))]);
    }

    public function scopeSelection(Builder $query): Builder
    {
        return $query->whereHasMorph('selectable', [get_class(app(SelectionType::class))]);
    }

    public function values(): HasMany
    {
        return $this->hasMany(get_class(app(Value::class)));
    }

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(get_class(app(Form::class)))->withTimestamps();
    }

    public function validation(): BelongsTo
    {
        return $this->belongsTo(get_class(app(Validation::class)));
    }

    /**
     * @throws \Throwable
     * @throws FieldValidationException
     */
    public function validate($input): void
    {
        /**
         * @var Validation $validation
         */
        $validation = optional($this->validation);

        $validation->validate($input);
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(get_class(app(CustomFieldContract::class)),
            'custom_field_relations', 'parent_id', 'child_id')
            ->withTimestamps();
    }

    public function parent(): BelongsToMany
    {
        return $this->belongsToMany(get_class(app(CustomFieldContract::class)),
            'custom_field_relations', 'child_id', 'parent_id')
            ->withTimestamps();
    }

    public static function types()
    {
        $plain = config('asseco-custom-fields.plain_types');
        $other = [
            'remote' => get_class(app(RemoteType::class)),
            'selection' => get_class(app(SelectionType::class)),
        ];

        return array_merge_recursive($plain, $other);
    }

    public function getValueColumn(): string
    {
        /** @var Value $value */
        $value = app(Value::class);

        if (!class_exists($this->selectable_type)) {
            return $value::FALLBACK_VALUE_COLUMN;
        }

        $selectable = $this->selectable;

        if ($selectable instanceof Mappable) {
            return $selectable::mapToValueColumn();
        } elseif ($selectable instanceof ParentType) {
            /**
             * @var Mappable $mappable
             */
            $mappable = $selectable->subTypeClassPath();

            return $mappable::mapToValueColumn();
        }

        return $value::FALLBACK_VALUE_COLUMN;
    }

    public function shortFormat($value): array
    {
        /** @var Value $cfValue */
        $cfValue = app(Value::class);

        $type = null;

        if (!class_exists($this->selectable_type)) {
            Log::info("Custom field $this->name has an invalid selectable class, falling back to " . $cfValue::FALLBACK_VALUE_COLUMN);

            $type = $cfValue::FALLBACK_VALUE_COLUMN;
        }

        return [$this->name => [
            'type' => $type ?: $this->selectable->name,
            'value' => $value,
        ]];
    }

    protected function exists(): bool
    {
        return app(CustomFieldContract::class)::query()
            ->where('name', $this->name)
            ->exists();
    }
}
