<?php

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller; // Stock Laravel controller class
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Voice\CustomFields\App\CustomFieldType;

class CustomFieldTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(CustomFieldType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $customFieldType = CustomFieldType::create($request->all());

        return response()->json($customFieldType);
    }

    /**
     * Display the specified resource.
     *
     * @param CustomFieldType $customFieldType
     * @return JsonResponse
     */
    public function show(CustomFieldType $customFieldType)
    {
        return response()->json($customFieldType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CustomFieldType $customFieldType
     * @return JsonResponse
     */
    public function update(Request $request, CustomFieldType $customFieldType)
    {
        $isUpdated = $customFieldType->update($request->all());

        return response()->json($isUpdated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomFieldType $customFieldType
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(CustomFieldType $customFieldType)
    {
        $isDeleted = $customFieldType->delete();

        return response()->json($isDeleted);
    }
}
