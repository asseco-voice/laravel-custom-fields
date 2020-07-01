<?php

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomFieldConfig extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function validation()
    {
        return $this->belongsTo(CustomFieldValidation::class, 'custom_field_validation_id');
    }

    public function customFields()
    {
        return $this->hasMany(CustomField::class);
    }

    public function type()
    {
        return $this->belongsTo(CustomFieldConfigType::class, 'custom_field_config_type_id');
    }
}
