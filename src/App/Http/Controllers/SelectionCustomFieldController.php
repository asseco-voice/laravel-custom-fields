<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\CustomField;

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
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->has('selection')) {
            throw new Exception("Selection data needs to be provided");
        }

        $customField = DB::transaction(function () use ($request) {

            /**
             * @var $selectionTypeModel Model
             */
            $selectionTypeModel = $this->selectionClass;
            $selectionType = $selectionTypeModel::query()->create($request->get('selection'));

            $selectableData = [
                'selectable_type' => $this->selectionClass,
                'selectable_id'   => $selectionType->id
            ];

            return CustomField::query()->create($request->merge($selectableData)->except('selection'));
        });

        return Response::json($customField->load('selectable'));
    }
}
