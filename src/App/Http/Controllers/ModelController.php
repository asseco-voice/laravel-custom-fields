<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Asseco\CustomFields\App\Traits\FindsTraits;

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
        $traitPath = config('asseco-custom-fields.trait_path');

        return response()->json($this->getModelsWithTrait($traitPath));
    }
}
