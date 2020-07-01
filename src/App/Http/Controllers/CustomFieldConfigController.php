<?php

namespace App\Http\Controllers;

use App\CustomFieldConfig;
use Illuminate\Http\Request;

class CustomFieldConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(CustomFieldConfig::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\\Illuminate\Http\Request $request
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customFieldConfig = CustomFieldConfig::create($request->all());

        return response()->json($customFieldConfig);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\CustomFieldConfig $customFieldConfig
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function show(CustomFieldConfig $customFieldConfig)
    {
        return response()->json($customFieldConfig);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\\Illuminate\Http\Request $request
     * @param \App\CustomFieldConfig $customFieldConfig
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function update(Request $request, $tenant, CustomFieldConfig $customFieldConfig)
    {
        $customFieldConfig->update($request->all());

        return response()->json($customFieldConfig);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\CustomFieldConfig $customFieldConfig
     * @return \Illuminate\Http\\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($tenant, CustomFieldConfig $customFieldConfig)
    {
        $isDeleted = $customFieldConfig->delete();

        return response()->json($isDeleted);
    }
}
