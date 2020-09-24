<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RemoteType extends Model
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
}
