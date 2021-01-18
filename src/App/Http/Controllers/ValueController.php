<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Asseco\CustomFields\App\Models\Value;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * @group Custom Field Values
 */
class ValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Value::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        Value::validateCreate($request);

        $value = Value::query()->create($request->all());

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
     * @param Request $request
     * @param Value $value
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(Request $request, Value $value): JsonResponse
    {
        $value->validateUpdate($request);

        $value->update($request->all());

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
