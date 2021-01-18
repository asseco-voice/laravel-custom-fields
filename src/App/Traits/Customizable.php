<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Traits;

use Asseco\CustomFields\App\Models\Value;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Customizable
{
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(Value::class, 'model');
    }
}
