<?php

use Illuminate\Support\Facades\Route;
use Voice\CustomFields\App\Http\Controllers\CustomFieldController;
use Voice\CustomFields\App\Http\Controllers\CustomFieldValueController;
use Voice\CustomFields\App\Http\Controllers\FormController;
use Voice\CustomFields\App\Http\Controllers\ModelController;
use Voice\CustomFields\App\Http\Controllers\PlainCustomFieldController;
use Voice\CustomFields\App\Http\Controllers\RelationController;
use Voice\CustomFields\App\Http\Controllers\RemoteCustomFieldController;
use Voice\CustomFields\App\Http\Controllers\RemoteValuesController;
use Voice\CustomFields\App\Http\Controllers\SelectionCustomFieldController;
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

Route::pattern('plainType', PlainType::getRegexSubTypes());

Route::prefix('api')
    ->middleware('api')
    ->group(function () {

        Route::apiResource('custom-fields', CustomFieldController::class);

        Route::prefix('custom-field')
            ->name('custom-field.')
            ->group(function () {

                Route::get('types', [TypeController::class, 'index'])->name('types.index');
                Route::get('models', [ModelController::class, 'index'])->name('models.index');

                Route::get('plain/{plainType?}', [PlainCustomFieldController::class, 'index'])->name('plain.index');
                Route::post('plain/{plainType}', [PlainCustomFieldController::class, 'store'])->name('plain.store');

                Route::apiResource('remote', RemoteCustomFieldController::class)->only(['index', 'store']);
                Route::get('remote-values', [RemoteValuesController::class, 'show']);

                Route::get('selection/{plainType?}', [SelectionCustomFieldController::class, 'index'])->name('selection.index');
                Route::post('selection/{plainType}', [SelectionCustomFieldController::class, 'store'])->name('selection.store');

                Route::apiResource('validations', ValidationController::class);
                Route::apiResource('relations', RelationController::class);
                Route::apiResource('values', CustomFieldValueController::class);

                Route::apiResource('forms', FormController::class);

            });
    });

