<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Http\Requests\SelectionCustomFieldRequest;
use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\SelectionValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @model CustomField
 */
class SelectionCustomFieldController extends Controller
{
    protected string $selectionClass;

    public function __construct()
    {
        $this->selectionClass = config('asseco-custom-fields.type_mappings.selection');
    }

    /**
     * Display a listing of the resource.
     *
     * @multiple true
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(CustomField::selection()->get());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return response()->json(CustomField::selection()->findOrFail($id)->load('selectable.values'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @path plain_type string One of the plain types (string, text, integer, float, date, boolean)
     * @except selectable_type selectable_id
     * @append selection SelectionType
     * @append values SelectionValue
     *
     * @param SelectionCustomFieldRequest $request
     * @param string $type
     * @return JsonResponse
     */
    public function store(SelectionCustomFieldRequest $request, string $type): JsonResponse
    {
        $data = $request->validated();

        /** @var CustomField $customField */
        $customField = DB::transaction(function () use ($data, $type) {
            $selectionData = Arr::get($data, 'selection', []);
            $multiselect = Arr::get($selectionData, 'multiselect', false);
            $plainTypeId = PlainType::query()->where('name', $type)->firstOrFail()->id;

            /**
             * @var Model $selectionTypeModel
             */
            $selectionTypeModel = $this->selectionClass;
            $selectionType = $selectionTypeModel::query()->create([
                'plain_type_id' => $plainTypeId,
                'multiselect'   => $multiselect,
            ]);

            $selectionValues = Arr::get($data, 'values', []);

            foreach ($selectionValues as $value) {
                SelectionValue::query()->create(
                    array_merge($value, ['selection_type_id' => $selectionType->id])
                );
            }

            $selectableData = [
                'selectable_type' => $this->selectionClass,
                'selectable_id'   => $selectionType->id,
            ];

            $cfData = Arr::except($data, ['selection', 'values']);

            return CustomField::query()->create(array_merge($cfData, $selectableData));
        });

        return response()->json($customField->refresh()->load('selectable.values'));
    }
}
