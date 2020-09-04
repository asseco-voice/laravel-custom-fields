<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\CustomFieldValidation;

class CustomFieldValidationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(CustomFieldValidation::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $customFieldValidation = CustomFieldValidation::query()->create($request->all());

        return Response::json($customFieldValidation);
    }

    /**
     * Display the specified resource.
     *
     * @param CustomFieldValidation $customFieldValidation
     * @return JsonResponse
     */
    public function show(CustomFieldValidation $customFieldValidation): JsonResponse
    {
        return Response::json($customFieldValidation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CustomFieldValidation $customFieldValidation
     * @return JsonResponse
     */
    public function update(Request $request, CustomFieldValidation $customFieldValidation): JsonResponse
    {
        $isUpdated = $customFieldValidation->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomFieldValidation $customFieldValidation
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(CustomFieldValidation $customFieldValidation): JsonResponse
    {
        $isDeleted = $customFieldValidation->delete();

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
