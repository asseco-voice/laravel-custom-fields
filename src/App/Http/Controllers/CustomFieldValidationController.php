<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\Validation;

class CustomFieldValidationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(Validation::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $customFieldValidation = Validation::query()->create($request->all());

        return Response::json($customFieldValidation);
    }

    /**
     * Display the specified resource.
     *
     * @param Validation $customFieldValidation
     * @return JsonResponse
     */
    public function show(Validation $customFieldValidation): JsonResponse
    {
        return Response::json($customFieldValidation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Validation $customFieldValidation
     * @return JsonResponse
     */
    public function update(Request $request, Validation $customFieldValidation): JsonResponse
    {
        $isUpdated = $customFieldValidation->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Validation $customFieldValidation
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Validation $customFieldValidation): JsonResponse
    {
        $isDeleted = $customFieldValidation->delete();

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
