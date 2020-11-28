<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Asseco\CustomFields\App\Validation;

/**
 * @group Custom Field Validations
 */
class ValidationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Validation::all());
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

        return response()->json($customFieldValidation->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param Validation $validation
     * @return JsonResponse
     */
    public function show(Validation $validation): JsonResponse
    {
        return response()->json($validation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Validation $validation
     * @return JsonResponse
     */
    public function update(Request $request, Validation $validation): JsonResponse
    {
        $validation->update($request->all());

        return response()->json($validation->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Validation $validation
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Validation $validation): JsonResponse
    {
        $isDeleted = $validation->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }
}
