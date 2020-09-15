<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Voice\CustomFields\App\CustomFieldValue;

trait Customizable
{
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'customizable');
    }
}
