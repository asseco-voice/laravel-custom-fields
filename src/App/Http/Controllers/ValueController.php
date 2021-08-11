<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\Value as ValueContract;
use Asseco\CustomFields\App\Http\Requests\ValueRequest;
use Asseco\CustomFields\App\Models\Value;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * @group Custom Field Values
 */
class ValueController extends Controller
{
    protected ValueContract $value;

    public function __construct(ValueContract $value)
    {
        $this->value = $value;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->value::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ValueRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ValueRequest $request): JsonResponse
    {
        $this->value::validateCreate($request);

        $value = $this->value::query()->create($request->validated());

        return response()->json($value->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param Value $value
     * @return JsonResponse
     */
    public function show(Value $value): JsonResponse
    {
        return response()->json($value);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ValueRequest $request
     * @param Value $value
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(ValueRequest $request, Value $value): JsonResponse
    {
        $value->validateUpdate($request);

        $value->update($request->validated());

        return response()->json($value->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Value $value
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Value $value): JsonResponse
    {
        $isDeleted = $value->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }
}
