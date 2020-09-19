<?php

use Illuminate\Support\Facades\Route;
use Voice\CustomFields\App\Http\Controllers\CustomFieldController;
use Voice\CustomFields\App\Http\Controllers\CustomFieldValueController;
use Voice\CustomFields\App\Http\Controllers\FormController;
use Voice\CustomFields\App\Http\Controllers\PlainTypeController;
use Voice\CustomFields\App\Http\Controllers\RelationController;
use Voice\CustomFields\App\Http\Controllers\RemoteTypeController;
use Voice\CustomFields\App\Http\Controllers\SelectionTypeController;
use Voice\CustomFields\App\Http\Controllers\ValidationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('api')
    ->middleware('api')
    ->group(function () {

        Route::apiResource('custom-fields', CustomFieldController::class);

        Route::apiResource('custom-field-plain-types', PlainTypeController::class);
        Route::apiResource('custom-field-remote-types', RemoteTypeController::class);
        Route::apiResource('custom-field-selection-types', SelectionTypeController::class);

        Route::apiResource('custom-field-validations', ValidationController::class);
        Route::apiResource('custom-field-relations', RelationController::class);
        Route::apiResource('custom-field-values', CustomFieldValueController::class);

        Route::apiResource('forms', FormController::class);

    });

