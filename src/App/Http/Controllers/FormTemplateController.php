<?php

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\FormTemplate as FormTemplateContract;
use Asseco\CustomFields\App\Http\Requests\FormTemplateRequest;
use Asseco\CustomFields\App\Models\FormTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class FormTemplateController extends Controller
{
    protected FormTemplateContract $formTemplate;

    public function __construct(FormTemplateContract $formTemplate)
    {
        $this->formTemplate = $formTemplate;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->formTemplate::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FormTemplateRequest $request
     * @return JsonResponse
     */
    public function store(FormTemplateRequest $request): JsonResponse
    {
        $formTemplate = $this->formTemplate::query()
            ->create(Arr::except($request->validated(), 'form_data'));

        $formTemplate->createCustomFieldValues(Arr::get($request->validated(), 'form_data', []));

        return response()->json($formTemplate->refresh()->load('customFieldValues.customField.selectable'), JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param FormTemplate $formTemplate
     * @return JsonResponse
     */
    public function show(FormTemplate $formTemplate): JsonResponse
    {
        return response()->json($formTemplate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FormTemplateRequest $request
     * @param FormTemplate $formTemplate
     * @return JsonResponse
     */
    public function update(FormTemplateRequest $request, FormTemplate $formTemplate): JsonResponse
    {
        $formTemplate->createCustomFieldValues(Arr::get($request->validated(), 'form_data', []));
        $formTemplate->update(Arr::except($request->validated(), 'form_data'));
        return response()->json($formTemplate->refresh()->load('customFieldValues.customField.selectable'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FormTemplate $formTemplate
     * @return JsonResponse
     */
    public function destroy(FormTemplate $formTemplate): JsonResponse
    {
        $isDeleted = $formTemplate->delete();

        return response()->json($isDeleted ? 'true' : 'false');
    }
}