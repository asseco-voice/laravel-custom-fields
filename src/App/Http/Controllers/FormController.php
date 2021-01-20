<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Http\Requests\FormRequest;
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
     * @param FormRequest $request
     * @return JsonResponse
     */
    public function store(FormRequest $request): JsonResponse
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
     * @param FormRequest $request
     * @param Form $form
     * @return JsonResponse
     */
    public function update(FormRequest $request, Form $form): JsonResponse
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

    public function validateAgainstCustomInput(Request $request, $formName)
    {
        /**
         * @var $form Form
         */
        $form = Form::query()->where('name', $formName)->firstOrFail();

        return response()->json($form->validate($request->all()));
    }
}
