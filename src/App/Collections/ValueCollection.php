<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Collections;

use Asseco\CustomFields\App\Models\Value;
use Illuminate\Database\Eloquent\Collection;

class ValueCollection extends Collection
{
    public function shortFormat(): array
    {
        $this->load('customField.selectable');

        return $this->map(function (Value $value) {
            return $value->shortFormat();
        })->toArray();
    }
}
