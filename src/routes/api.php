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

Route::apiResource('custom-fields', 'CustomFieldController');
Route::apiResource('custom-field-configs', 'CustomFieldConfigController');
Route::apiResource('custom-field-config-types', 'CustomFieldConfigTypeController');
Route::apiResource('custom-field-validations', 'CustomFieldValidationController');
Route::apiResource('forms', 'FormsController');
