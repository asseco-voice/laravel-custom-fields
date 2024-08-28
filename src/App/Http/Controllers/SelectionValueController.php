<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\SelectionValue as SelectionValueContract;
use Asseco\CustomFields\App\Http\Requests\SelectionValueRequest;
use Asseco\CustomFields\App\Models\SelectionValue;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * @group Custom Field Relations
 */
class SelectionValueController extends Controller
{
    protected SelectionValueContract $selectionValue;

    public function __construct(SelectionValueContract $selectionValue)
    {
        $this->selectionValue = $selectionValue;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->selectionValue::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SelectionValueRequest  $request
     * @return JsonResponse
     */
    public function store(SelectionValueRequest $request): JsonResponse
    {
        // check for deleted values
        $selectionValue = $this->selectionValue::withTrashed()
            ->where('selection_type_id', $request->get('selection_type_id'))
            ->where('value', $request->get('value'))
            ->first();

        if ($selectionValue) {
            if ($selectionValue->trashed()) {
                // restore
                $selectionValue->restoreQuietly();
                $selectionValue->update($request->validated());
            } else {
                throw new Exception('Selection value already exists.', 400);
            }
        }
        else {
            $selectionValue = $this->selectionValue::query()->create($request->validated());
        }

        return response()->json($selectionValue->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param  SelectionValue  $selectionValue
     * @return JsonResponse
     */
    public function show(SelectionValue $selectionValue): JsonResponse
    {
        return response()->json($selectionValue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SelectionValueRequest  $request
     * @param  SelectionValue  $selectionValue
     * @return JsonResponse
     */
    public function update(SelectionValueRequest $request, SelectionValue $selectionValue): JsonResponse
    {
        $selectionValue->update($request->validated());

        return response()->json($selectionValue->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SelectionValue  $selectionValue
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(SelectionValue $selectionValue): JsonResponse
    {
        $isDeleted = $selectionValue->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }
}
