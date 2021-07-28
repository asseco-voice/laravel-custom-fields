<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Traits;

use Asseco\CustomFields\App\Models\Value;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Customizable
{
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(get_class(app('cf-value')), 'model');
    }

    /**
     * Append custom field key-value pairs to event. Key is CF name, value is exact value.
     *
     * @param array|null $customFieldValues
     * @return array
     */
    public function flattenCustomFieldValues(?array $customFieldValues = null): array
    {
        $values = $customFieldValues ?: $this->customFieldValues->load('customField');

        $mapped = [];

        foreach ($values as $value) {
            if (!$value instanceof Value) {
                continue;
            }

            $mapped[$value->customField->name] = $value->value;
        }

        return $mapped;
    }
}
