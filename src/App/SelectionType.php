<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SelectionType extends ParentType
{
    protected $table   = 'custom_field_selection_types';
    protected $guarded = ['id'];
    protected $hidden  = ['created_at', 'updated_at'];
    protected $appends = ['name'];

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
