<?php

use Illuminate\Support\Facades\Route;

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

Route::namespace('Voice\CustomFields\App\Http\Controllers')
    ->prefix('api')
    ->middleware('api')
    ->group(function () {

        Route::apiResource('custom-fields', 'CustomFieldController');
        Route::apiResource('custom-field-types', 'CustomFieldTypeController');
        Route::apiResource('custom-field-validations', 'CustomFieldValidationController');
        Route::apiResource('forms', 'FormController');

    });

