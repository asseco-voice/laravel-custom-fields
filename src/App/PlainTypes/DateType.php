<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\PlainTypes;

use Asseco\CustomFields\App\Contracts\Mappable;
use Asseco\CustomFields\App\Models\PlainType;
use Illuminate\Database\Eloquent\Builder;

class DateType extends PlainType implements Mappable, \Asseco\CustomFields\App\Contracts\PlainTypes\DateType
{
    protected static function booted()
    {
        static::addGlobalScope('name', function (Builder $builder) {
            $builder->where('name', 'date');
        });
    }

    public static function mapToValueColumn(): string
    {
        return 'date';
    }
}
