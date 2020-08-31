<?php

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller; // Stock Laravel controller class
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Voice\CustomFields\App\Form;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Form::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $form = Form::create($request->all());

        return response()->json($form);
    }

    /**
     * Display the specified resource.
     *
     * @param Form $form
     * @return JsonResponse
     */
    public function show(Form $form)
    {
        return response()->json($form);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Form $form
     * @return JsonResponse
     */
    public function update(Request $request, Form $form)
    {
        $isUpdated = $form->update($request->all());

        return response()->json($isUpdated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Form $form
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Form $form)
    {
        $isDeleted = $form->delete();

        return response()->json($isDeleted);
    }
}
