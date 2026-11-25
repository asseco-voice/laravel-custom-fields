<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\Database\Factories\SelectionValueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class SelectionValue extends Model implements \Asseco\CustomFields\App\Contracts\SelectionValue
{
    use HasFactory;

    protected $table = 'custom_field_selection_values';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function newFactory()
    {
        return SelectionValueFactory::new();
    }

    public function selectionType(): BelongsTo
    {
        return $this->belongsTo(get_class(app(SelectionType::class)));
    }

    public function type(): HasOneThrough
    {
        return $this->hasOneThrough(
            get_class(app(PlainType::class)),
            get_class(app(SelectionType::class)),
            'id',
            'id',
            'selection_type_id',
            'plain_type_id'
        );
    }

    /**
     * Accessor for casting value to appropriate type based on the actual plain type.
     *
     * @param  $value
     * @return bool|float|int
     */
    public function getValueAttribute($value)
    {
        $plainType = optional($this->type)->name;

        switch ($plainType) {
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'boolean':
                // "false", "0", "no" and "off" are false; a (bool) cast made every non-empty string true,
                // so both options of a Yes/No field read as true. Other strings keep the old cast.
                return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (bool) $value;
            default:
                return $value;
        }
    }
}
