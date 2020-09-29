<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Voice\CustomFields\Database\Factories\RemoteTypeFactory;

class RemoteType extends ParentType
{
    use HasFactory;

    protected $table   = 'custom_field_remote_types';
    protected $guarded = ['id'];
    protected $appends = ['name'];

    protected static function newFactory()
    {
        return RemoteTypeFactory::new();
    }

    public function customFields(): MorphMany
    {
        return $this->morphMany(CustomField::class, 'selectable');
    }

    public function getNameAttribute()
    {
        return 'remote';
    }

    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = json_encode($value);
    }

    public function setMappingsAttribute($value)
    {
        $this->attributes['mappings'] = json_encode($value);
    }
}
