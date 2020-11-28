<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\PlainTypes;

use Asseco\CustomFields\App\Contracts\Mappable;
use Asseco\CustomFields\App\PlainType;
use Illuminate\Database\Eloquent\Builder;

class BooleanType extends PlainType implements Mappable
{
    protected static function booted()
    {
        static::addGlobalScope('name', function (Builder $builder) {
            $builder->where('name', 'boolean');
        });
    }

    public static function mapToValueColumn(): string
    {
        return 'boolean';
    }
}
