<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\PlainTypes;

use Illuminate\Database\Eloquent\Builder;
use Voice\CustomFields\App\PlainType;

class TextType extends PlainType
{
    protected static function booted()
    {
        static::addGlobalScope('name', function (Builder $builder) {
            $builder->where('name', 'text');
        });
    }
}
