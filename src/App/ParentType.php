<?php


namespace Voice\CustomFields\App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

abstract class ParentType extends Model
{
    public function type(): BelongsTo
    {
        return $this->belongsTo(PlainType::class, 'plain_type_id');
    }

    public function subTypeClassPath(): string
    {
        return Arr::get(Config::get('asseco-custom-fields.type_mappings'), $this->type->name, 'string');
    }
}
