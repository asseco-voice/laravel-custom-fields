<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CustomFieldValue extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function customizable(): MorphTo
    {
        return $this->morphTo();
    }

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    public function selectable()
    {
        return $this->customField->selectable();
    }

    public function validation(): HasOneThrough
    {
        return $this->hasOneThrough(
            Validation::class,
            CustomField::class,
            'id',
            'id',
            'custom_field_id',
            'validation_id');
    }

    public function type(): HasOneThrough
    {
        return $this->hasOneThrough(
            PlainType::class,
            CustomField::class,
            'id',
            'id',
            'custom_field_id',
            'custom_field_type_id');
    }
}
