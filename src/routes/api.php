<?php

use Illuminate\Support\Facades\Route;
use Voice\CustomFields\App\Http\Controllers\CustomFieldController;
use Voice\CustomFields\App\Http\Controllers\CustomFieldConfigController;
use Voice\CustomFields\App\Http\Controllers\CustomFieldConfigTypeController;
use Voice\CustomFields\App\Http\Controllers\CustomFieldValidationController;

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

Route::apiResource('api/custom-fields', 'CustomFieldController');
Route::apiResource('api/custom-field-configs', 'CustomFieldConfigController');
Route::apiResource('api/custom-field-config-types', 'CustomFieldConfigTypeController');
Route::apiResource('api/custom-field-validations', 'CustomFieldValidationController');
Route::apiResource('api/forms', 'FormsController');
