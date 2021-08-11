<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\Database\Factories\RemoteTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RemoteType extends ParentType implements \Asseco\CustomFields\App\Contracts\RemoteType
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
        return $this->morphMany(get_class(app(CustomField::class)), 'selectable');
    }

    public function getNameAttribute()
    {
        return 'remote';
    }
}
