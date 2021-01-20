<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Http\Requests\CustomFieldRequest;
use Asseco\CustomFields\App\Models\CustomField;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(CustomField::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(CustomFieldRequest $request): JsonResponse
    {
        $customField = CustomField::query()->create($request->all());

        return response()->json($customField->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param CustomField $customField
     * @return JsonResponse
     */
    public function show(CustomField $customField): JsonResponse
    {
        return response()->json($customField);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CustomField $customField
     * @return JsonResponse
     */
    public function update(CustomFieldRequest $request, CustomField $customField): JsonResponse
    {
        $customField->update($request->except(CustomField::LOCKED_FOR_EDITING));

        return response()->json($customField->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomField $customField
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(CustomField $customField): JsonResponse
    {
        $isDeleted = $customField->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }
}
