<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Asseco\CustomFields\App\Models\Form;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Custom Field Forms
 */
class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Form::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        /**
         * @var $form Form
         */
        $form = Form::query()->create($request->all());

        return response()->json($form->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param Form $form
     * @return JsonResponse
     */
    public function show(Form $form): JsonResponse
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
    public function update(Request $request, Form $form): JsonResponse
    {
        $form->update($request->all());

        return response()->json($form->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Form $form
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Form $form): JsonResponse
    {
        $isDeleted = $form->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }
}
