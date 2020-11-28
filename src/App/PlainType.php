<?php

namespace Asseco\CustomFields\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Asseco\CustomFields\Database\Factories\PlainTypeFactory;

class PlainType extends Model
{
    use HasFactory;

    protected $table = 'custom_field_plain_types';

    protected $fillable = ['name'];

    protected static function newFactory()
    {
        return PlainTypeFactory::new();
    }

    public static function subTypes()
    {
        return config('asseco-custom-fields.type_mappings.plain');
    }

    public static function getSubTypeClass(string $type)
    {
        return Arr::get(self::subTypes(), $type, null);
    }

    public static function getRegexSubTypes()
    {
        return implode('|', array_keys(self::subTypes()));
    }
}
