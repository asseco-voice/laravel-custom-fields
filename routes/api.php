<?php

use Illuminate\Support\Facades\Route;
use Asseco\CustomFields\App\Http\Controllers\CustomFieldController;
use Asseco\CustomFields\App\Http\Controllers\FormController;
use Asseco\CustomFields\App\Http\Controllers\ModelController;
use Asseco\CustomFields\App\Http\Controllers\PlainCustomFieldController;
use Asseco\CustomFields\App\Http\Controllers\RelationController;
use Asseco\CustomFields\App\Http\Controllers\RemoteCustomFieldController;
use Asseco\CustomFields\App\Http\Controllers\RemoteValuesController;
use Asseco\CustomFields\App\Http\Controllers\SelectionCustomFieldController;
use Asseco\CustomFields\App\Http\Controllers\TypeController;
use Asseco\CustomFields\App\Http\Controllers\ValidationController;
use Asseco\CustomFields\App\Http\Controllers\ValueController;
use Asseco\CustomFields\App\PlainType;

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

Route::pattern('plain_type', PlainType::getRegexSubTypes());

Route::prefix('api')
    ->middleware('api')
    ->group(function () {
        Route::apiResource('custom-fields', CustomFieldController::class);

        Route::prefix('custom-field')
            ->name('custom-field.')
            ->group(function () {
                Route::get('types', [TypeController::class, 'index'])->name('types.index');
                Route::get('models', [ModelController::class, 'index'])->name('models.index');

                Route::get('plain/{plain_type?}', [PlainCustomFieldController::class, 'index'])->name('plain.index');
                Route::post('plain/{plain_type}', [PlainCustomFieldController::class, 'store'])->name('plain.store');

                Route::apiResource('remote', RemoteCustomFieldController::class)->only(['index', 'store']);
                Route::get('remote-values/{remote_type}', [RemoteValuesController::class, 'show']);

                Route::get('selection/{plain_type?}', [SelectionCustomFieldController::class, 'index'])->name('selection.index');
                Route::post('selection/{plain_type}', [SelectionCustomFieldController::class, 'store'])->name('selection.store');

                Route::apiResource('validations', ValidationController::class);
                Route::apiResource('relations', RelationController::class);
                Route::apiResource('values', ValueController::class);

                Route::apiResource('forms', FormController::class);
            });
    });
