<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\PlainType;

class CustomFieldTypeController extends Controller
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
        $customFieldType = PlainType::query()->create($request->all());

        return Response::json($customFieldType);
    }

    /**
     * Display the specified resource.
     *
     * @param PlainType $customFieldType
     * @return JsonResponse
     */
    public function show(PlainType $customFieldType): JsonResponse
    {
        return Response::json($customFieldType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PlainType $customFieldType
     * @return JsonResponse
     */
    public function update(Request $request, PlainType $customFieldType): JsonResponse
    {
        $isUpdated = $customFieldType->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PlainType $customFieldType
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(PlainType $customFieldType): JsonResponse
    {
        $isDeleted = $customFieldType->delete();

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
