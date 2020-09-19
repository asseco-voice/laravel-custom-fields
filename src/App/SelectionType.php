<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SelectionType extends Model
{
    protected $table   = 'custom_field_selection_types';
    protected $guarded = ['id'];
    protected $hidden  = ['created_at', 'updated_at'];

    public function customFields(): MorphMany
    {
        return $this->morphMany(CustomField::class, 'selectable');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PlainType::class, 'plain_type_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(SelectionValue::class);
    }
}
