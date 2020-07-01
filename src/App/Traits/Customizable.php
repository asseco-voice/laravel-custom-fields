<?php

namespace Voice\CustomFields\App\Traits;

use Voice\CustomFields\App\CustomField;

trait Customizable
{
    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }

}
