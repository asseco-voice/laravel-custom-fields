<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\RemoteType;
use Voice\CustomFields\App\Traits\TransformsOutput;

/**
 * @group Fetch Remote Custom Field Values
 */
class RemoteValuesController extends Controller
{
    use TransformsOutput;

    /**
     * Display the specified resource.
     *
     * @param CustomField $remoteType
     * @return JsonResponse
     */
    public function show(RemoteType $remoteType): JsonResponse
    {
        /**
         * @var $response \Illuminate\Http\Client\Response
         */
        $response = Http::withHeaders($remoteType->headers ?: [])
            ->withBody($remoteType->body, 'application/json')
            ->{$remoteType->method}($remoteType->url);

        $transformed = $this->transform($response->json(), json_decode($remoteType->mappings, true));

        return response()->json($transformed);
    }
}
