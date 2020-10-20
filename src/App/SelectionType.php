<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Voice\CustomFields\Database\Factories\SelectionTypeFactory;

class SelectionType extends ParentType
{
    use HasFactory;

    protected $table = 'custom_field_selection_types';
    protected $fillable = ['plain_type_id', 'multiselect'];
    protected $appends = ['name'];

    protected static function newFactory()
    {
        return SelectionTypeFactory::new();
    }

    public function customFields(): MorphMany
    {
        return $this->morphMany(CustomField::class, 'selectable');
    }

    public function values(): HasMany
    {
        return $this->hasMany(SelectionValue::class);
    }

    public function getNameAttribute()
    {
        return 'selection';
    }
}
