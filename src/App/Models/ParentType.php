<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\App\PlainTypes\StringType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class ParentType extends Model
{
    public function type(): BelongsTo
    {
        return $this->belongsTo(PlainType::class, 'plain_type_id');
    }

    public function subTypeClassPath(): string
    {
        $plainTypes = config('asseco-custom-fields.type_mappings.plain');
        $typeName = $this->type->name;

        if (array_key_exists($typeName, $plainTypes)) {
            return $plainTypes[$typeName];
        }

        return StringType::class;
    }
}
