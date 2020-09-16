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

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    public function type(): HasOneThrough
    {
        return $this->hasOneThrough(
            CustomFieldType::class,
            CustomField::class,
            'id',
            'id',
            'custom_field_id',
            'custom_field_type_id');
    }

    public function validation(): HasOneThrough
    {
        return $this->hasOneThrough(
            CustomFieldValidation::class,
            CustomField::class,
            'id',
            'id',
            'custom_field_id',
            'custom_field_validation_id');
    }

    public function customizable(): MorphTo
    {
        return $this->morphTo();
    }
}
