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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return Response::json(CustomField::plain()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // TODO: baci u form request
        if (!$request->has('type')) {
            throw new Exception("No type provided");
        }

        if (!array_key_exists($request->get('type'), $this->mappings)) {
            throw new Exception("Wrong type provided");
        }

        /**
         * @var $type Model
         */
        $type = $this->mappings[$request->get('type')];

        $data = $request->except('type');
        $data = array_merge_recursive($data, ['selectable_type' => $type, 'selectable_id' => $type::query()->first('id')->id]);

        $customField = CustomField::query()->create($data);

        return Response::json($customField);
    }
}
