<?php

namespace Asseco\CustomFields\App\Contracts;

interface Mappable
{
    /**
     * To which column in `custom_field_values` will the actual value be mapped to.
     * @return mixed
     */
    public static function mapToValueColumn(): string;
}
