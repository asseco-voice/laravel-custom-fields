<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\PlainType;
use Voice\CustomFields\App\RemoteType;

class RemoteTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(RemoteType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $remoteType = RemoteType::query()->create($request->all());

        return Response::json($remoteType);
    }

    /**
     * Display the specified resource.
     *
     * @param RemoteType $remoteType
     * @return JsonResponse
     */
    public function show(RemoteType $remoteType): JsonResponse
    {
        return Response::json($remoteType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param RemoteType $remoteType
     * @return JsonResponse
     */
    public function update(Request $request, RemoteType $remoteType): JsonResponse
    {
        $isUpdated = $remoteType->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RemoteType $remoteType
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(RemoteType $remoteType): JsonResponse
    {
        $isDeleted = $remoteType->delete();

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
