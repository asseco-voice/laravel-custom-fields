<?php

declare(strict_types=1);

namespace Voice\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Voice\CustomFields\App\Traits\FindsTraits;

/**
 * @group Customizable Models
 */
class ModelController extends Controller
{
    use FindsTraits;

    /**
     * Display a listing of the resource.
     *
     * @response model array[string]
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $traitPath = Config::get('asseco-custom-fields.trait_path');

        return Response::json($this->getModelsWithTrait($traitPath));
    }
}
