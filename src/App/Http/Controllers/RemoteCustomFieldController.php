<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Contracts\RemoteType;
use Asseco\CustomFields\App\Http\Requests\RemoteCustomFieldRequest;
use Asseco\CustomFields\App\Http\Requests\RemoteTypeRequest;
use Asseco\CustomFields\App\Traits\TransformsOutput;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group Remote Custom Fields
 *
 * @model CustomField
 */
class RemoteCustomFieldController extends Controller
{
    use TransformsOutput;

    protected CustomField $customField;
    protected RemoteType $remoteClass;
    protected PlainType $plainType;

    public function __construct(CustomField $customField, RemoteType $remoteType, PlainType $plainType)
    {
        $this->customField = $customField;
        $this->remoteClass = $remoteType;
        $this->plainType = $plainType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->customField::remote()->with('selectable')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @except selectable_type selectable_id
     *
     * @append remote RemoteType
     *
     * @param  RemoteCustomFieldRequest  $request
     * @return JsonResponse
     */
    public function store(RemoteCustomFieldRequest $request): JsonResponse
    {
        $data = $request->validated();

        /** @var CustomField $customField */
        $customField = DB::transaction(function () use ($data) {
            // Force casting remote types to string unless we decide on different implementation.
            $plainTypeId = $this->plainType::query()->where('name', 'string')->firstOrFail()->id;

            $remoteType = $this->remoteClass::query()->create(array_merge(
                Arr::get($data, 'remote'), ['plain_type_id' => $plainTypeId]));

            $selectableData = [
                'selectable_type' => get_class($this->remoteClass),
                'selectable_id' => $remoteType->id,
            ];

            $cfData = Arr::except($data, 'remote');

            return $this->customField::query()->create(
                array_merge($cfData, $selectableData, ['plain_type_id' => $plainTypeId])
            );
        });

        return response()->json($customField->refresh()->load('selectable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RemoteTypeRequest  $request
     * @param  RemoteType  $remoteType
     * @return JsonResponse
     */
    public function update(RemoteTypeRequest $request, RemoteType $remoteType): JsonResponse
    {
        $remoteType->update($request->validated());

        return response()->json($remoteType->refresh());
    }

    /**
     * Display the specified resource.
     *
     * @param  RemoteType  $remoteType
     * @return JsonResponse
     */
    public function resolve(RemoteType $remoteType): JsonResponse
    {
        $data = $remoteType->getRemoteData();

        $data = $remoteType->data_path ? Arr::get($data, $remoteType->data_path) : $data;
        $transformed = $this->transform($data, $remoteType->mappings);

        return response()->json($transformed);
    }

    /**
     * Display the specified resource.
     *
     * @param  RemoteType  $remoteType
     * @param  string  $identifierValue
     * @return JsonResponse
     */
    public function resolveByIdentifierValue(RemoteType $remoteType, string $identifierValue): JsonResponse
    {
        $data = $remoteType->getRemoteData($identifierValue);

        $data = $remoteType->data_path ? Arr::get($data, $remoteType->data_path) : $data;

        $data = collect($data)->where($remoteType->identifier_property, $identifierValue)->first();

        $transformed = is_array($data) ? $this->mapSingle($remoteType->mappings, $data) : $data;

        return response()->json($transformed);
    }

    public function search(Request $request, RemoteType $remoteType, string $q = ''): JsonResponse
    {
        // check query parameter
        $q = $q ?: $request->input('q');
        if (!empty($q)) {
            $data = $remoteType->searchRemoteData($q);
        } else {
            $data = $remoteType->getRemoteData();
        }

        $data = $remoteType->data_path ? Arr::get($data, $remoteType->data_path) : $data;
        $transformed = $this->transform($data, $remoteType->mappings);

        return response()->json($transformed);
    }
}
