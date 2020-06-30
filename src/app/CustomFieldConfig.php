<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomFieldConfig extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function customFieldValidation()
    {
        return $this->belongsTo(CustomFieldValidation::class);
    }

    public function customFields()
    {
        return $this->hasMany(CustomField::class);
    }

    public function customFieldConfigType()
    {
        return $this->belongsTo(CustomFieldConfigType::class);
    }
}
