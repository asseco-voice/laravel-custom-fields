<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function validation(): BelongsTo
    {
        return $this->belongsTo(CustomFieldValidation::class, 'custom_field_validation_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CustomFieldType::class, 'custom_field_type_id');
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class, 'custom_field_relations', 'custom_field_parent', 'custom_field_child');
    }

    public function parent(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class, 'custom_field_relations', 'custom_field_child', 'custom_field_parent');
    }
}
