<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\CustomField;

class PlainCustomFieldController extends Controller
{
    protected array $mappings;

    public function __construct()
    {
        $this->mappings = Config::get('asseco-custom-fields.type_mappings.plain');
    }

    /**
     * Display a listing of the resource.
     *
     * @param string|null $type
     * @return JsonResponse
     */
    public function index(string $type = null): JsonResponse
    {
        return Response::json(CustomField::plain($type)->get());
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
        /**
         * @var $typeModel Model
         */
        $typeModel = $this->mappings[$type];

        $data = $request->except('type');
        $data = array_merge_recursive($data, ['selectable_type' => $typeModel, 'selectable_id' => $typeModel::query()->first('id')->id]);

        $customField = CustomField::query()->create($data);

        return Response::json($customField);
    }
}
