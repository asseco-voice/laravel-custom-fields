<?php

namespace App\Http\Controllers;

use App\CustomFieldValidation;
use Illuminate\Http\Request;

class CustomFieldValidationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(CustomFieldValidation::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\\Illuminate\Http\Request $request
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customFieldValidation = CustomFieldValidation::create($request->all());

        return response()->json($customFieldValidation);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\CustomFieldValidation $customFieldValidation
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function show(CustomFieldValidation $customFieldValidation)
    {
        return response()->json($customFieldValidation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\\Illuminate\Http\Request $request
     * @param \App\CustomFieldValidation $customFieldValidation
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function update(Request $request, $tenant, CustomFieldValidation $customFieldValidation)
    {
        $customFieldValidation->update($request->all());

        return response()->json($customFieldValidation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\CustomFieldValidation $customFieldValidate
     * @return \Illuminate\Http\\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($tenant, CustomFieldValidation $customFieldValidate)
    {
        $isDeleted = $customFieldValidate->delete();

        return response()->json($isDeleted);
    }
}
