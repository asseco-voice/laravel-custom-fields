<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\CustomFieldValue;

class CustomFieldValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(CustomFieldValue::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        CustomFieldValue::validate($request);

        $customFieldValue = CustomFieldValue::query()->create($request->all());

        return Response::json($customFieldValue);
    }

    /**
     * Display the specified resource.
     *
     * @param CustomFieldValue $customFieldValue
     * @return JsonResponse
     */
    public function show(CustomFieldValue $customFieldValue): JsonResponse
    {
        return Response::json($customFieldValue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CustomFieldValue $customFieldValue
     * @return JsonResponse
     */
    public function update(Request $request, CustomFieldValue $customFieldValue): JsonResponse
    {
        $isUpdated = $customFieldValue->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomFieldValue $customFieldValue
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(CustomFieldValue $customFieldValue): JsonResponse
    {
        $isDeleted = $customFieldValue->delete();

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
