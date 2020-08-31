<?php

namespace Voice\CustomFields\App\Traits;

use Voice\CustomFields\App\CustomField;

trait Customizable
{
    public function customFields()
    {
        return $this->belongsToMany(CustomField::class)
            ->withPivot('value_string', 'value_number', 'value_date', 'value_text')
            ->withTimestamps();
    }
}
