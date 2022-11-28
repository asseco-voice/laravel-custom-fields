<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\Database\Factories\RemoteTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RemoteType extends ParentType implements \Asseco\CustomFields\App\Contracts\RemoteType
{
    use HasFactory;

    protected const CACHE_PREFIX = 'remote_custom_field_';

    protected $table = 'custom_field_remote_types';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['name'];

    protected $casts = [
        'body'     => 'array',
        'headers'  => 'array',
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

    public function getRemoteData()
    {
        $cacheKey = 'remote_custom_field_' . $this->id;

        if (config('asseco-custom-fields.should_cache_remote') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::withHeaders($this->getHeaders() ?: [])
            ->withBody($this->body, 'application/json')
            ->{$this->method}($this->url)->throw()->json();

        Cache::put($cacheKey, $response, config('asseco-custom-fields.remote_cache_ttl'));

        return $response;
    }

    protected function getHeaders()
    {
        return $this->headers;
    }
}
