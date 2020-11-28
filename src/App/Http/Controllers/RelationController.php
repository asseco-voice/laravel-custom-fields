<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Asseco\CustomFields\App\Relation;

/**
 * @group Custom Field Relations
 */
class RelationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Relation::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $relation = Relation::query()->create($request->all());

        return response()->json($relation->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param Relation $relation
     * @return JsonResponse
     */
    public function show(Relation $relation): JsonResponse
    {
        return response()->json($relation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Relation $relation
     * @return JsonResponse
     */
    public function update(Request $request, Relation $relation): JsonResponse
    {
        $relation->update($request->all());

        return response()->json($relation->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Relation $relation
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Relation $relation): JsonResponse
    {
        $isDeleted = $relation->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }
}
