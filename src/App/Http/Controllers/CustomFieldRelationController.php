<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\CustomFieldRelation;

class CustomFieldRelationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(CustomFieldRelation::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $customFieldRelation = CustomFieldRelation::query()->create($request->all());

        return Response::json($customFieldRelation);
    }

    /**
     * Display the specified resource.
     *
     * @param CustomFieldRelation $customFieldRelation
     * @return JsonResponse
     */
    public function show(CustomFieldRelation $customFieldRelation): JsonResponse
    {
        return Response::json($customFieldRelation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CustomFieldRelation $customFieldRelation
     * @return JsonResponse
     */
    public function update(Request $request, CustomFieldRelation $customFieldRelation): JsonResponse
    {
        $isUpdated = $customFieldRelation->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomFieldRelation $customFieldRelation
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(CustomFieldRelation $customFieldRelation): JsonResponse
    {
        $isDeleted = $customFieldRelation->delete();

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
