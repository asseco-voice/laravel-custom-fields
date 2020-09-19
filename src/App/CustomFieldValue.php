<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CustomFieldValue extends Model
{
    protected $guarded = ['id'];
    protected $hidden  = ['created_at', 'updated_at'];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    public function selectable(): MorphTo
    {
        return $this->customField->selectable();
    }

    public function validation(): BelongsTo
    {
        return $this->customField->validation();
    }
}
