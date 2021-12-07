<?php

use Asseco\CustomFields\App\Contracts\PlainType;
use Asseco\CustomFields\App\Http\Controllers\CustomFieldController;
use Asseco\CustomFields\App\Http\Controllers\FormController;
use Asseco\CustomFields\App\Http\Controllers\ModelController;
use Asseco\CustomFields\App\Http\Controllers\PlainCustomFieldController;
use Asseco\CustomFields\App\Http\Controllers\RelationController;
use Asseco\CustomFields\App\Http\Controllers\RemoteCustomFieldController;
use Asseco\CustomFields\App\Http\Controllers\SelectionCustomFieldController;
use Asseco\CustomFields\App\Http\Controllers\SelectionValueController;
use Asseco\CustomFields\App\Http\Controllers\TypeController;
use Asseco\CustomFields\App\Http\Controllers\ValidationController;
use Asseco\CustomFields\App\Http\Controllers\ValueController;
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

/** @var PlainType $plainType */
$plainType = app(PlainType::class);

Route::pattern('plain_type', $plainType::getRegexSubTypes());

Route::prefix('api')->middleware('api')->group(function () {
    Route::apiResource('custom-fields', CustomFieldController::class);

    Route::prefix('custom-field')->name('custom-field.')->group(function () {
        Route::get('types', [TypeController::class, 'index'])->name('types.index');
        Route::get('models', [ModelController::class, 'index'])->name('models.index');

        Route::get('plain/{plain_type?}', [PlainCustomFieldController::class, 'index'])->name('plain.index');
        Route::post('plain/{plain_type}', [PlainCustomFieldController::class, 'store'])->name('plain.store');

        Route::apiResource('remote', RemoteCustomFieldController::class)->only(['index', 'store']);
        Route::match(['put', 'patch'], 'remote/{remote_type}', [RemoteCustomFieldController::class, 'update'])->name('remote.update');
        Route::get('remote/{remote_type}/resolve', [RemoteCustomFieldController::class, 'resolve'])->name('remote.resolve');

        Route::get('selection', [SelectionCustomFieldController::class, 'index'])->name('selection.index');
        Route::post('selection/{plain_type}', [SelectionCustomFieldController::class, 'store'])->name('selection.store');
        Route::match(['put', 'patch'], 'selection/{selectable_type}', [SelectionCustomFieldController::class, 'update'])->name('selection.update');

        Route::apiResource('selection-values', SelectionValueController::class);

        Route::apiResource('validations', ValidationController::class);
        Route::apiResource('relations', RelationController::class);
        Route::apiResource('values', ValueController::class);

        Route::post('forms/{form_name}/validate', [FormController::class, 'validateAgainstCustomInput'])->name('forms.validate');
        Route::apiResource('forms', FormController::class);
    });
});
