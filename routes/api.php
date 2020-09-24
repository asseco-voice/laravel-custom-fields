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
use Voice\CustomFields\App\PlainType;

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

        Route::prefix('custom-field')
            ->name('custom-field.')
            ->group(function () {
                Route::get('types', [TypeController::class, 'index'])->name('types.index');

                Route::get('plain/{type?}', [PlainCustomFieldController::class, 'index'])
                    ->where('type', PlainType::getRegexSubTypes())
                    ->name('plain.index');

                Route::post('plain/{type}', [PlainCustomFieldController::class, 'store'])
                    ->where('type', PlainType::getRegexSubTypes())
                    ->name('plain.store');

                Route::apiResource('remote', RemoteCustomFieldController::class)->only(['index', 'store']);
                // Route::apiResource('select', SelectCustomFieldController::class);


                Route::apiResource('validations', ValidationController::class);
                Route::apiResource('relations', RelationController::class);
                Route::apiResource('values', CustomFieldValueController::class);

                Route::apiResource('forms', FormController::class);

            });
    });

