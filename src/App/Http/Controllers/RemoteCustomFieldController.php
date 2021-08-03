<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Http\Requests\RemoteCustomFieldRequest;
use Asseco\CustomFields\App\Models\CustomField;
use Asseco\CustomFields\App\Models\PlainType;
use Asseco\CustomFields\App\Models\RemoteType;
use Asseco\CustomFields\App\Traits\TransformsOutput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * @group Remote Custom Fields
 * @model CustomField
 */
class RemoteCustomFieldController extends Controller
{
    use TransformsOutput;

    protected string $remoteClass;
    protected CustomField $customField;

    public function __construct()
    {
        $this->remoteClass = config('asseco-custom-fields.type_mappings.remote');
        $this->customField = app('cf-custom-field');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->customField::remote()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @except selectable_type selectable_id
     * @append remote RemoteType
     *
     * @param RemoteCustomFieldRequest $request
     * @return JsonResponse
     */
    public function store(RemoteCustomFieldRequest $request): JsonResponse
    {
        $data = $request->validated();

        /** @var CustomField $customField */
        $customField = DB::transaction(function () use ($data) {

            /**
             * @var Model $remoteTypeModel
             */
            $remoteTypeModel = $this->remoteClass;
            $remoteType = $remoteTypeModel::query()->create(Arr::get($data, 'remote'));

            $selectableData = [
                'selectable_type' => $this->remoteClass,
                'selectable_id'   => $remoteType->id,
            ];

            // Force casting remote types to string unless we decide on different implementation.
            /** @var PlainType $plainType */
            $plainType = app('cf-plain-type');
            $plainTypeId = $plainType::query()->where('name', 'string')->firstOrFail()->id;

            $cfData = Arr::except($data, 'remote');

            return $this->customField::query()->create(
                array_merge($cfData, $selectableData, ['plain_type_id' => $plainTypeId])
            );
        });

        return response()->json($customField->refresh()->load('selectable'));
    }

    /**
     * Display the specified resource.
     *
     * @param RemoteType $remoteType
     * @return JsonResponse
     */
    public function resolve(RemoteType $remoteType): JsonResponse
    {
        /**
         * @var Response $response
         */
        $response = Http::withHeaders($remoteType->headers ?: [])
            ->withBody($remoteType->body, 'application/json')
            ->{$remoteType->method}($remoteType->url);

        $transformed = $this->transform($response->json(), json_decode($remoteType->mappings, true));

        return response()->json($transformed);
    }
}
