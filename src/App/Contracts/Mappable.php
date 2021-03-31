<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Contracts;

interface Mappable
{
    /**
     * To which column in `custom_field_values` will the actual value be mapped to.
     * @return string
     */
    public static function mapToValueColumn(): string;
}
