<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Asseco\CustomFields\App\Http\Requests\CustomFieldRequest;
use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\PlainType;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Remote Custom Fields
 * @model CustomField
 */
class RemoteCustomFieldController extends Controller
{
    protected string $remoteClass;

    public function __construct()
    {
        $this->remoteClass = config('asseco-custom-fields.type_mappings.remote');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(CustomField::remote()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @except selectable_type selectable_id
     * @append remote RemoteType
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(CustomFieldRequest $request): JsonResponse
    {
        if (!$request->has('remote')) {
            throw new Exception('Remote data needs to be provided');
        }

        $customField = DB::transaction(function () use ($request) {

            /**
             * @var $remoteTypeModel Model
             */
            $remoteTypeModel = $this->remoteClass;
            $remoteType = $remoteTypeModel::query()->create($request->get('remote'));

            $selectableData = [
                'selectable_type' => $this->remoteClass,
                'selectable_id'   => $remoteType->id,
            ];

            // Force casting remote types to string unless we decide on different implementation.
            $plainTypeId = PlainType::query()->where('name', 'string')->firstOrFail()->id;

            return CustomField::query()->create(
                $request->merge($selectableData)->merge(['plain_type_id' => $plainTypeId])->except('remote')
            );
        });

        return response()->json($customField->load('selectable'));
    }
}
