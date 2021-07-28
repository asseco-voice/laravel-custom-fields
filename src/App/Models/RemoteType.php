<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\Database\Factories\RemoteTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RemoteType extends ParentType
{
    use HasFactory;

    protected $table = 'custom_field_remote_types';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['name'];

    protected $casts = [
        'body'     => 'array',
        'mappings' => 'array',
    ];

    protected static function newFactory()
    {
        return RemoteTypeFactory::new();
    }

    public function customFields(): MorphMany
    {
        return $this->morphMany(get_class(app('cf-custom-field')), 'selectable');
    }

    public function getNameAttribute()
    {
        return 'remote';
    }
}
