<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\Relation as RelationContract;
use Asseco\CustomFields\App\Http\Requests\RelationRequest;
use Asseco\CustomFields\App\Models\Relation;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * @group Custom Field Relations
 * @hidden
 */
class RelationController extends Controller
{
    protected RelationContract $relation;

    public function __construct(RelationContract $relation)
    {
        $this->relation = $relation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->relation::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RelationRequest  $request
     * @return JsonResponse
     */
    public function store(RelationRequest $request): JsonResponse
    {
        $relation = $this->relation::query()->create($request->validated());

        return response()->json($relation->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param  Relation  $relation
     * @return JsonResponse
     */
    public function show(Relation $relation): JsonResponse
    {
        return response()->json($relation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RelationRequest  $request
     * @param  Relation  $relation
     * @return JsonResponse
     */
    public function update(RelationRequest $request, Relation $relation): JsonResponse
    {
        $relation->update($request->validated());

        return response()->json($relation->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Relation  $relation
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(Relation $relation): JsonResponse
    {
        $isDeleted = $relation->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }
}
