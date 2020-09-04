<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\Form;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(Form::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $form = Form::query()->create($request->all());

        return Response::json($form);
    }

    /**
     * Display the specified resource.
     *
     * @param Form $form
     * @return JsonResponse
     */
    public function show(Form $form): JsonResponse
    {
        return Response::json($form);
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
        $isUpdated = $form->update($request->all());

        return Response::json($isUpdated ? 'true' : 'false');
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

        return Response::json($isDeleted ? 'true' : 'false');
    }
}
