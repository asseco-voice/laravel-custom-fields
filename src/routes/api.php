<?php

use Illuminate\Support\Facades\Route;
use Voice\CustomFields\App\Http\Controllers\CustomFieldController;
use Voice\CustomFields\App\Http\Controllers\CustomFieldTypeController;
use Voice\CustomFields\App\Http\Controllers\CustomFieldValidationController;
use Voice\CustomFields\App\Http\Controllers\FormController;

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

Route::apiResource('api/custom-fields', CustomFieldController::class);
Route::apiResource('api/custom-field-types', CustomFieldTypeController::class);
Route::apiResource('api/custom-field-validations', CustomFieldValidationController::class);
Route::apiResource('api/forms', FormController::class);
