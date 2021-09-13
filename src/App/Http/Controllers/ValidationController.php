<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\Validation as ValidationContract;
use Asseco\CustomFields\App\Http\Requests\ValidationRequest;
use Asseco\CustomFields\App\Models\Validation;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * @group Custom Field Validations
 */
class ValidationController extends Controller
{
    protected ValidationContract $validation;

    public function __construct(ValidationContract $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->validation::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ValidationRequest  $request
     * @return JsonResponse
     */
    public function store(ValidationRequest $request): JsonResponse
    {
        $customFieldValidation = $this->validation::query()->create($request->validated());

        return response()->json($customFieldValidation->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param  Validation  $validation
     * @return JsonResponse
     */
    public function show(Validation $validation): JsonResponse
    {
        return response()->json($validation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ValidationRequest  $request
     * @param  Validation  $validation
     * @return JsonResponse
     */
    public function update(ValidationRequest $request, Validation $validation): JsonResponse
    {
        $validation->update($request->validated());

        return response()->json($validation->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Validation  $validation
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(Validation $validation): JsonResponse
    {
        $isDeleted = $validation->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }
}
