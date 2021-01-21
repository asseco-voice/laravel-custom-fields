<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Models\RemoteType;
use Asseco\CustomFields\App\Traits\TransformsOutput;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

/**
 * @group Fetch Remote Custom Field Values
 */
class RemoteValuesController extends Controller
{
    use TransformsOutput;

    /**
     * Display the specified resource.
     *
     * @param RemoteType $remoteType
     * @return JsonResponse
     */
    public function show(RemoteType $remoteType): JsonResponse
    {
        /**
         * @var Response $response
         */
        $response = Http::withHeaders($remoteType->headers ?: [])
            ->withBody($remoteType->body, 'application/json')
            ->{$remoteType->method}($remoteType->url);

        $transformed = $this->transform($response->json(), json_decode($remoteType->mappings, true));

        return response()->json($transformed);
    }
}
