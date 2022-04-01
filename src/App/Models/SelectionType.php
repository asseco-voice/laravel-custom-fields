<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Asseco\CustomFields\Database\Factories\SelectionTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SelectionType extends ParentType implements \Asseco\CustomFields\App\Contracts\SelectionType
{
    use HasFactory;

    protected $table = 'custom_field_selection_types';

    protected $fillable = ['plain_type_id', 'multiselect'];

    protected $appends = ['name'];

    protected $with = ['values'];

    protected static function newFactory()
    {
        return SelectionTypeFactory::new();
    }

    public function customFields(): MorphMany
    {
        return $this->morphMany(get_class(app(CustomField::class)), 'selectable');
    }

    public function values(): HasMany
    {
        return $this->hasMany(get_class(app(SelectionValue::class)));
    }

    public function getNameAttribute()
    {
        return 'selection';
    }
}
