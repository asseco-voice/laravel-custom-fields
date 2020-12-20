<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Asseco\CustomFields\App\SelectionValue;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Custom Field Relations
 */
class SelectionValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(SelectionValue::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $selectionValue = SelectionValue::query()->create($request->all());

        return response()->json($selectionValue->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param SelectionValue $selectionValue
     * @return JsonResponse
     */
    public function show(SelectionValue $selectionValue): JsonResponse
    {
        return response()->json($selectionValue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param SelectionValue $selectionValue
     * @return JsonResponse
     */
    public function update(Request $request, SelectionValue $selectionValue): JsonResponse
    {
        $selectionValue->update($request->all());

        return response()->json($selectionValue->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SelectionValue $selectionValue
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(SelectionValue $selectionValue): JsonResponse
    {
        $isDeleted = $selectionValue->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }
}
