<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Relations\MorphMany;

class RemoteType extends ParentType
{
    protected $table   = 'custom_field_remote_types';
    protected $guarded = ['id'];
    protected $hidden  = ['created_at', 'updated_at'];
    protected $appends = ['name'];

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
