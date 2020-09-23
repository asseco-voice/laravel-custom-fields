<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\SelectType;

class SelectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(SelectType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $selectType = SelectType::query()->create($request->all());

        return Response::json($selectType);
    }

    /**
     * Display the specified resource.
     *
     * @param SelectType $selectType
     * @return JsonResponse
     */
    public function show(SelectType $selectType): JsonResponse
    {
        return Response::json($selectType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param SelectType $selectType
     * @return JsonResponse
     */
    public function update(Request $request, SelectType $selectType): JsonResponse
    {
        $isUpdated = $selectType->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SelectType $selectType
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(SelectType $selectType): JsonResponse
    {
        $isDeleted = $selectType->delete();

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
