<?php

namespace App\Http\Controllers;

use App\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(CustomField::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\\Illuminate\Http\Request $request
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customField = CustomField::create($request->all());

        return response()->json($customField);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\CustomField $customField
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function show(CustomField $customField)
    {
        return response()->json($customField);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\\Illuminate\Http\Request $request
     * @param \App\CustomField $customField
     * @return \Illuminate\Http\\Illuminate\Http\Response
     */
    public function update(Request $request, $tenant, CustomField $customField)
    {
        $customField->update($request->all());

        return response()->json($customField);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\CustomField $customField
     * @return \Illuminate\Http\\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($tenant, CustomField $customField)
    {
        $isDeleted = $customField->delete();

        return response()->json($isDeleted);
    }
}
