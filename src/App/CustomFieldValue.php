<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomFieldValue extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    public function type()
    {
        return $this->hasOneThrough(
            CustomFieldType::class,
            CustomField::class,
            'id',
            'id',
            'custom_field_id',
            'custom_field_type_id');
    }

    public function customizable()
    {
        return $this->morphTo();
    }
}
