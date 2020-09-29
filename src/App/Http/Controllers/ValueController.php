<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;
use Voice\CustomFields\App\Value;

class ValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(Value::all());
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

        return Response::json($value);
    }

    /**
     * Display the specified resource.
     *
     * @param Value $value
     * @return JsonResponse
     */
    public function show(Value $value): JsonResponse
    {
        return Response::json($value);
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

        $isUpdated = $value->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
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

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
