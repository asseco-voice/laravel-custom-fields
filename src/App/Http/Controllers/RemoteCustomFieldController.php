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

class RemoteCustomFieldController extends Controller
{
    protected string $remoteClass;

    public function __construct()
    {
        $this->remoteClass = Config::get('asseco-custom-fields.type_mappings.remote');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(CustomField::remote()->get());
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
        if (!$request->has('remote')) {
            throw new Exception("Remote data needs to be provided");
        }

        $customField = DB::transaction(function () use ($request) {

            /**
             * @var $remoteTypeModel Model
             */
            $remoteTypeModel = $this->remoteClass;
            $remoteType = $remoteTypeModel::query()->create($request->get('remote'));

            $selectableData = [
                'selectable_type' => $this->remoteClass,
                'selectable_id'   => $remoteType->id
            ];

            return CustomField::query()->create($request->merge($selectableData)->except('remote'));
        });

        return Response::json($customField->load('selectable'));
    }
}
