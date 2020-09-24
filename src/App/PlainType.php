<?php


namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class PlainType extends Model
{
    protected $table   = 'custom_field_plain_types';
    protected $guarded = ['id'];
    protected $hidden  = ['created_at', 'updated_at'];

    public static function subTypes()
    {
        return Config::get('asseco-custom-fields.type_mappings.plain');
    }
}
