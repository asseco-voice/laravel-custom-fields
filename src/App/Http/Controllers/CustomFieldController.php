<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\CustomField as CustomFieldContract;
use Asseco\CustomFields\App\Http\Requests\CustomFieldCreateRequest;
use Asseco\CustomFields\App\Http\Requests\CustomFieldUpdateRequest;
use Asseco\CustomFields\App\Models\CustomField;
use Exception;
use Illuminate\Http\JsonResponse;

class CustomFieldController extends Controller
{
    protected CustomFieldContract $customField;

    public function __construct(CustomFieldContract $customField)
    {
        $this->customField = $customField;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->customField::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomFieldCreateRequest $request
     * @return JsonResponse
     */
    public function store(CustomFieldCreateRequest $request): JsonResponse
    {
        $customField = $this->customField::query()->create($request->validated());

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
     * @param CustomFieldUpdateRequest $request
     * @param CustomField $customField
     * @return JsonResponse
     */
    public function update(CustomFieldUpdateRequest $request, CustomField $customField): JsonResponse
    {
        $customField->update($request->validated());

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
