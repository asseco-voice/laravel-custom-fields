<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Voice\CustomFields\App\CustomField;

trait Customizable
{
    public function customFields(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class)
            ->withPivot('value_string', 'value_number', 'value_date', 'value_text')
            ->withTimestamps();
    }
}
