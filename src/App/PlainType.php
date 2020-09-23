<?php


namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;

class PlainType extends Model
{
    protected $table   = 'custom_field_plain_types';
    protected $guarded = ['id'];
    protected $hidden  = ['created_at', 'updated_at'];


}
