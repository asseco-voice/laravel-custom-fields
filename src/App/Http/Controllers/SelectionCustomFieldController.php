<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Asseco\CustomFields\App\CustomField;
use Asseco\CustomFields\App\PlainType;
use Asseco\CustomFields\App\SelectionValue;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @path plain_type string One of the plain types (string, text, integer, float, date, boolean)
     * @multiple true
     *
     * @param string|null $type
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
     * @param Request $request
     * @param string $type
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request, string $type): JsonResponse
    {
        if (!$request->has('selection')) {
            throw new Exception('Selection data needs to be provided');
        }

        $customField = DB::transaction(function () use ($request, $type) {
            $selectionData = $request->get('selection');

            $multiselect = Arr::get($selectionData, 'multiselect', false);
            $plainTypeId = PlainType::query()->where('name', $type)->firstOrFail()->id;

            /**
             * @var $selectionTypeModel Model
             */
            $selectionTypeModel = $this->selectionClass;
            $selectionType = $selectionTypeModel::query()->create([
                'plain_type_id' => $plainTypeId,
                'multiselect'   => $multiselect,
            ]);

            $selectionValues = $request->get('values');

            foreach ($selectionValues as $value) {
                SelectionValue::query()->create(array_merge_recursive($value, ['selection_type_id' => $selectionType->id]))->toArray();
            }

            $selectableData = [
                'selectable_type' => $this->selectionClass,
                'selectable_id'   => $selectionType->id,
            ];

            return CustomField::query()->create($request->merge($selectableData)->except('selection'));
        });

        return response()->json($customField->load('selectable.values'));
    }
}
