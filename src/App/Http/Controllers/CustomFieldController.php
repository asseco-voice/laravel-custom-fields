<?php

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller; // Stock Laravel controller class
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Voice\CustomFields\App\CustomField;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(CustomField::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $customField = CustomField::create($request->all());

        return response()->json($customField);
    }

    /**
     * Display the specified resource.
     *
     * @param CustomField $customField
     * @return JsonResponse
     */
    public function show(CustomField $customField)
    {
        return response()->json($customField);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CustomField $customField
     * @return JsonResponse
     */
    public function update(Request $request, CustomField $customField)
    {
        $isUpdated = $customField->update($request->all());

        return response()->json($isUpdated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomField $customField
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(CustomField $customField)
    {
        $isDeleted = $customField->delete();

        return response()->json($isDeleted);
    }
}
