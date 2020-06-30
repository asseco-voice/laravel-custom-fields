<?php

namespace App\Http\Controllers;

use App\CustomFieldConfigType;
use Illuminate\Http\Request;

class CustomFieldConfigTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(CustomFieldConfigType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\\Illuminate\Http\Request $request
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customFieldConfigType = CustomFieldConfigType::create($request->all());

        return response()->json($customFieldConfigType);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\CustomFieldConfigType $customFieldConfigType
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function show(CustomFieldConfigType $customFieldConfigType)
    {
        return response()->json($customFieldConfigType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\\Illuminate\Http\Request $request
     * @param \App\CustomFieldConfigType $customFieldConfigType
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function update(Request $request, $tenant, CustomFieldConfigType $customFieldConfigType)
    {
        $customFieldConfigType->update($request->all());

        return response()->json($customFieldConfigType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\CustomFieldConfigType $customFieldValidate
     * @return \Illuminate\Http\\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($tenant, CustomFieldConfigType $customFieldValidate)
    {
        $isDeleted = $customFieldValidate->delete();

        return response()->json($isDeleted);
    }
}
