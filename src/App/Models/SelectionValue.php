<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\App\Contracts\SelectionType;
use Asseco\CustomFields\Database\Factories\SelectionValueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function getValueAttribute($value)
    {
        $selectionType = $this->selectionType()->without('values')->first();

        switch ($selectionType->type->name) {
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'boolean':
                return (bool) $value;
            default:
                return $value;
        }
    }
}
