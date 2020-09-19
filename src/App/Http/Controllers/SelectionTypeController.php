<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\SelectionType;

class SelectionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(SelectionType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $selectionType = SelectionType::query()->create($request->all());

        return Response::json($selectionType);
    }

    /**
     * Display the specified resource.
     *
     * @param SelectionType $selectionType
     * @return JsonResponse
     */
    public function show(SelectionType $selectionType): JsonResponse
    {
        return Response::json($selectionType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param SelectionType $selectionType
     * @return JsonResponse
     */
    public function update(Request $request, SelectionType $selectionType): JsonResponse
    {
        $isUpdated = $selectionType->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SelectionType $selectionType
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(SelectionType $selectionType): JsonResponse
    {
        $isDeleted = $selectionType->delete();

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
