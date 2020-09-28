<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\CustomField;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(CustomField::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $customField = CustomField::query()->create($request->all());

        return Response::json($customField);
    }

    /**
     * Display the specified resource.
     *
     * @param CustomField $customField
     * @return JsonResponse
     */
    public function show(CustomField $customField): JsonResponse
    {
        return Response::json($customField);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CustomField $customField
     * @return JsonResponse
     */
    public function update(Request $request, CustomField $customField): JsonResponse
    {
        $isUpdated = $customField->update($request->except(CustomField::EDIT_LOCK));

        return Response::json($isUpdated ? 'true' : 'false');
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

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
