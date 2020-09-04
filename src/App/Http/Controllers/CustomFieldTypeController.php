<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\CustomFieldType;

class CustomFieldTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(CustomFieldType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $customFieldType = CustomFieldType::query()->create($request->all());

        return Response::json($customFieldType);
    }

    /**
     * Display the specified resource.
     *
     * @param CustomFieldType $customFieldType
     * @return JsonResponse
     */
    public function show(CustomFieldType $customFieldType): JsonResponse
    {
        return Response::json($customFieldType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CustomFieldType $customFieldType
     * @return JsonResponse
     */
    public function update(Request $request, CustomFieldType $customFieldType): JsonResponse
    {
        $isUpdated = $customFieldType->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomFieldType $customFieldType
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(CustomFieldType $customFieldType): JsonResponse
    {
        $isDeleted = $customFieldType->delete();

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
