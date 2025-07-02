<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\CustomField;
use Illuminate\Http\JsonResponse;

/**
 * @group CustomFields - Type-Class
 *
 * @tags CustomFields - Type-Class
 */
class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @response
     * plain array[string] true
     * remote string
     * selection string
     *
     * @operationId CustomFieldTypes
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        /** @var CustomField $customField */
        $customField = app(CustomField::class);

        return response()->json($customField::types());
    }
}
