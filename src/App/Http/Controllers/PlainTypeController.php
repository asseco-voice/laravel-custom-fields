<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\PlainType;

class PlainTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(PlainType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $plainType = PlainType::query()->create($request->all());

        return Response::json($plainType);
    }

    /**
     * Display the specified resource.
     *
     * @param PlainType $plainType
     * @return JsonResponse
     */
    public function show(PlainType $plainType): JsonResponse
    {
        return Response::json($plainType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PlainType $plainType
     * @return JsonResponse
     */
    public function update(Request $request, PlainType $plainType): JsonResponse
    {
        $isUpdated = $plainType->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PlainType $plainType
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(PlainType $plainType): JsonResponse
    {
        $isDeleted = $plainType->delete();

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
