<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function parents()
    {
        return $this->hasMany(CustomField::class, 'parent_id');
    }

    public function customFieldConfig()
    {
        return $this->belongsTo(CustomFieldConfig::class);
    }

}
