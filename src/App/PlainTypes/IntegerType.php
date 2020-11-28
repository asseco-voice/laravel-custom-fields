<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\PlainTypes;

use Illuminate\Database\Eloquent\Builder;
use Asseco\CustomFields\App\Contracts\Mappable;
use Asseco\CustomFields\App\PlainType;

class IntegerType extends PlainType implements Mappable
{
    protected static function booted()
    {
        static::addGlobalScope('name', function (Builder $builder) {
            $builder->where('name', 'integer');
        });
    }

    public static function mapToValueColumn(): string
    {
        return 'integer';
    }
}
