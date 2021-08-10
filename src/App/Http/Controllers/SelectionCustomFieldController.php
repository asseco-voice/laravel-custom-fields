<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\SelectionValue;
use Asseco\CustomFields\App\Http\Requests\SelectionCustomFieldRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @model CustomField
 */
class SelectionCustomFieldController extends Controller
{
    protected CustomField $customField;
    protected string $selectionClass;

    public function __construct(CustomField $customField)
    {
        $this->customField = $customField;
        $this->selectionClass = config('asseco-custom-fields.models.selection_type');
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
        return response()->json($this->customField::selection()->get());
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

            /** @var PlainType $plainType */
            $plainType = app(PlainType::class);
            $plainTypeId = $plainType::query()->where('name', $type)->firstOrFail()->id;

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
                /** @var SelectionValue $selectionValue */
                $selectionValue = app(SelectionValue::class);
                $selectionValue::query()->create(
                    array_merge($value, ['selection_type_id' => $selectionType->id])
                );
            }

            $selectableData = [
                'selectable_type' => $this->selectionClass,
                'selectable_id'   => $selectionType->id,
            ];

            $cfData = Arr::except($data, ['selection', 'values']);

            return $this->customField::query()->create(array_merge($cfData, $selectableData));
        });

        return response()->json($customField->refresh()->load('selectable.values'));
    }
}
