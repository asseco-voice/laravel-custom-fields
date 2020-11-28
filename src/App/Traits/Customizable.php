<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Asseco\CustomFields\App\Value;

trait Customizable
{
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(Value::class, 'model');
    }
}
