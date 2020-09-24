<?php

use Illuminate\Support\Facades\Route;
use Voice\CustomFields\App\Http\Controllers\CustomFieldController;
use Voice\CustomFields\App\Http\Controllers\CustomFieldValueController;
use Voice\CustomFields\App\Http\Controllers\FormController;
use Voice\CustomFields\App\Http\Controllers\PlainCustomFieldController;
use Voice\CustomFields\App\Http\Controllers\RelationController;
use Voice\CustomFields\App\Http\Controllers\RemoteCustomFieldController;
use Voice\CustomFields\App\Http\Controllers\TypeController;
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

Route::prefix('api/custom-fields')
    ->middleware('api')
    ->name('custom-fields.')
    ->group(function () {

        Route::get('types', [TypeController::class, 'index'])->name('types');

        Route::apiResource('plain', PlainCustomFieldController::class);
        Route::apiResource('remote', RemoteCustomFieldController::class);
//        Route::apiResource('select', SelectCustomFieldController::class);

        Route::apiResource('validations', ValidationController::class);
        Route::apiResource('relations', RelationController::class);
        Route::apiResource('values', CustomFieldValueController::class);

        Route::apiResource('forms', FormController::class);

        Route::apiResource('/', CustomFieldController::class);

    });

