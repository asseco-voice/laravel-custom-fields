<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Asseco\CustomFields\App\CustomField;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

/**
 * @model CustomField
 */
class PlainCustomFieldController extends Controller
{
    protected array $mappings;

    public function __construct()
    {
        $this->mappings = config('asseco-custom-fields.type_mappings.plain');
    }

    /**
     * Display a listing of the resource.
     *
     * @path plain_type string One of the plain types (string, text, integer, float, date, boolean)
     * @multiple true
     *
     * @param string|null $type
     * @return JsonResponse
     */
    public function index(string $type = null): JsonResponse
    {
        return response()->json(CustomField::plain($type)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @path plain_type string One of the plain types (string, text, integer, float, date, boolean)
     * @except selectable_type selectable_id
     *
     * @param Request $request
     * @param string $type
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request, string $type): JsonResponse
    {
        /**
         * @var $typeModel Model
         */
        $typeModel = $this->mappings[$type];

        $selectableData = [
            'selectable_type' => $typeModel,
            'selectable_id'   => $typeModel::query()->first('id')->id,
        ];

        $customField = CustomField::query()->create($request->merge($selectableData)->except('type'));

        return response()->json($customField->refresh());
    }
}
