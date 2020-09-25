<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\CustomField;
use Voice\CustomFields\App\PlainType;
use Voice\CustomFields\App\SelectionValue;

class SelectionCustomFieldController extends Controller
{
    protected string $selectionClass;

    public function __construct()
    {
        $this->selectionClass = Config::get('asseco-custom-fields.type_mappings.selection');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(CustomField::selection()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param string $type
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request, string $type): JsonResponse
    {
        if (!$request->has('selection')) {
            throw new Exception("Selection data needs to be provided");
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

            foreach ($selectionData['values'] as $value) {
                SelectionValue::query()->create(array_merge_recursive($value, ['selection_type_id' => $selectionType->id]))->toArray();
            }

            $selectableData = [
                'selectable_type' => $this->selectionClass,
                'selectable_id'   => $selectionType->id
            ];

            return CustomField::query()->create($request->merge($selectableData)->except('selection'));
        });

        return Response::json($customField->load('selectable.values'));
    }
}
