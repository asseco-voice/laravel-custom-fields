<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App;

use Asseco\CustomFields\App\Contracts\Mappable;
use Asseco\CustomFields\Database\Factories\CustomFieldFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class CustomField extends Model
{
    use SoftDeletes, HasFactory;

    public const LOCKED_FOR_EDITING = ['selectable_type', 'selectable_id', 'model'];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function newFactory()
    {
        return CustomFieldFactory::new();
    }

    public function selectable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePlain(Builder $query, string $subType = null)
    {
        $selectable = $subType ? PlainType::getSubTypeClass($subType) : PlainType::subTypes();

        return $query->whereHasMorph('selectable', $selectable);
    }

    public function scopeRemote(Builder $query)
    {
        return $query->whereHasMorph('selectable', [RemoteType::class]);
    }

    public function scopeSelection(Builder $query)
    {
        return $query->whereHasMorph('selectable', [SelectionType::class]);
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class);
    }

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class)->withTimestamps();
    }

    public function validation(): BelongsTo
    {
        return $this->belongsTo(Validation::class);
    }

    public function validate($input): void
    {
        optional($this->validation)->validate($input);
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class,
            'custom_field_relations', 'parent_id', 'child_id')
            ->withTimestamps();
    }

    public function parent(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class,
            'custom_field_relations', 'child_id', 'parent_id')
            ->withTimestamps();
    }

    public static function types()
    {
        $plain = config('asseco-custom-fields.type_mappings.plain');
        $other = Arr::except(config('asseco-custom-fields.type_mappings'), 'plain');

        return array_merge_recursive($plain, $other);
    }

    public function getMappingColumn(): string
    {
        $selectable = $this->selectable;

        if ($selectable instanceof Mappable) {
            return $selectable::mapToValueColumn();
        } elseif ($selectable instanceof ParentType) {
            /**
             * @var $mappable Mappable
             */
            $mappable = $selectable->subTypeClassPath();

            return $mappable::mapToValueColumn();
        }

        return Value::FALLBACK_VALUE_COLUMN;
    }
}
