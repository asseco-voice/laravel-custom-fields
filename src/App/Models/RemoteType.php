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
        'body' => 'array',
        'headers' => 'array',
        'mappings' => 'array',
    ];

    const DEFAULT_IDENTIFIER_PROPERTY = 'id';
    const DEFAULT_SEARCH_QUERY_PARAMETER = 'q';

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

    private function fetchData(?string $value = null, bool $search = false)
    {
        $qParam = $this->identifier_property ?: self::DEFAULT_IDENTIFIER_PROPERTY;
        if ($search) {
            $qParam = self::DEFAULT_SEARCH_QUERY_PARAMETER;
        }

        $body = $this->body;
        $url = $this->url;

        if ($value) {
            // get by ID
            if ($this->method == 'POST') {
                empty($body) ? ($body = [$qParam => $value]) : ($body[$qParam] = $value);
            } else {
                $parsed = parse_url($url);
                parse_str($parsed['query'] ?? '', $params);
                $params[$qParam] = $value;
                $url = $parsed['scheme'] . '://' . $parsed['host'];
                if (!empty($parsed['port'])) {
                    $url .= ':' . $parsed['port'];
                }
                $url .= $parsed['path'] . '?' . http_build_query($params);
            }
        }

        return Http::withHeaders($this->getHeaders() ?: [])
            ->withBody($body, 'application/json')
            ->{$this->method}($url)->throw()->json();
    }

    public function getRemoteData(?string $identifierValue = null)
    {
        $cacheKey = 'remote_custom_field_' . $this->id;
        if (config('asseco-custom-fields.should_cache_remote') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = $this->fetchData($identifierValue, false);

        if (!$identifierValue) {
            // cache only all
            Cache::put($cacheKey, $response, config('asseco-custom-fields.remote_cache_ttl'));
        }

        return $response;
    }

    public function searchRemoteData(string $searchString)
    {
        return $this->fetchData($searchString, true);
    }

    protected function getHeaders()
    {
        return $this->headers;
    }
}
