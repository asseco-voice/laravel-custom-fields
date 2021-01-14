<?php

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\Database\Factories\PlainTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class PlainType extends Model
{
    use HasFactory;

    protected $table = 'custom_field_plain_types';

    protected $fillable = ['name'];

    protected static function newFactory()
    {
        return PlainTypeFactory::new();
    }

    public static function subTypes(): array
    {
        return config('asseco-custom-fields.type_mappings.plain');
    }

    public static function getSubTypeClass(string $type): string
    {
        return Arr::get(self::subTypes(), $type);
    }

    public static function getRegexSubTypes(): string
    {
        return implode('|', array_keys(self::subTypes()));
    }
}
