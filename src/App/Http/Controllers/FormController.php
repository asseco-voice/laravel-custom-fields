<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\Form as FormContract;
use Asseco\CustomFields\App\Http\Requests\FormRequest;
use Asseco\CustomFields\App\Models\Form;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group CustomField - Forms
 * @tags CustomField - Forms
 */
class FormController extends Controller
{
    protected FormContract $form;

    public function __construct(FormContract $form)
    {
        $this->form = $form;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->form::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FormRequest  $request
     * @return JsonResponse
     */
    public function store(FormRequest $request): JsonResponse
    {
        $form = $this->form::query()->create($request->validated());

        return response()->json($form->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param  Form  $form
     * @return JsonResponse
     */
    public function show(Form $form): JsonResponse
    {
        return response()->json($form);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FormRequest  $request
     * @param  Form  $form
     * @return JsonResponse
     */
    public function update(FormRequest $request, Form $form): JsonResponse
    {
        $form->update($request->validated());

        return response()->json($form->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Form  $form
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(Form $form): JsonResponse
    {
        $isDeleted = $form->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }

    /**
     * @param  Request  $request
     * @param  $formName
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function validateAgainstCustomInput(Request $request, $formName)
    {
        /**
         * @var Form $form
         */
        $form = $this->form::query()->where('name', $formName)->firstOrFail();

        return response()->json($form->validate($request->all()));
    }
}
