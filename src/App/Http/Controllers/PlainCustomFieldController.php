<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Http\Requests\PlainCustomFieldRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

/**
 * @model CustomField
 */
class PlainCustomFieldController extends Controller
{
    protected CustomField $customField;
    protected array $mappings;

    public function __construct(CustomField $customField)
    {
        $this->customField = $customField;
        $this->mappings = config('asseco-custom-fields.plain_types');
    }

    /**
     * Display a listing of the resource.
     *
     * @path plain_type string One of the plain types (string, text, integer, float, date, boolean)
     *
     * @multiple true
     *
     * @param  string|null  $type
     * @return JsonResponse
     */
    public function index(string $type = null): JsonResponse
    {
        return response()->json($this->customField::plain($type)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @path plain_type string One of the plain types (string, text, integer, float, date, boolean)
     *
     * @except selectable_type selectable_id
     *
     * @param  PlainCustomFieldRequest  $request
     * @param  string  $type
     * @return JsonResponse
     */
    public function store(PlainCustomFieldRequest $request, string $type): JsonResponse
    {
        $data = $request->validated();

        /** @var Model $typeModel */
        $typeModel = $this->mappings[$type];

        $selectableData = [
            'selectable_type' => $typeModel,
            'selectable_id' => $typeModel::query()->firstOrFail('id')->id,
        ];

        $customField = $this->customField::query()->create(array_merge($data, $selectableData));

        return response()->json($customField->refresh());
    }
}
