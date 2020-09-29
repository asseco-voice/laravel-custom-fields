<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class Value extends Model
{
    /**
     * Columns which are classified as value columns
     */
    public const VALUE_COLUMNS = ['string', 'integer', 'float', 'date', 'text', 'boolean'];

    /**
     * Fallback column if a concrete value column can't be extracted
     */
    public const FALLBACK_VALUE_COLUMN = 'string';

    protected $table   = 'custom_field_values';
    protected $guarded = ['id'];
    protected $hidden  = ['created_at', 'updated_at'];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    public function selectable(): MorphTo
    {
        return $this->customField->selectable();
    }

    public function validation(): BelongsTo
    {
        return $this->customField->validation();
    }

    public static function validateCreate(Request $request): void
    {
        /**
         * @var $customField CustomField
         */
        $customField = CustomField::query()
            ->with(['validation', 'selectable'])
            ->findOrFail($request->get('custom_field_id'));

        $mapToColumn = $customField->getMappingColumn();

        throw_if(!$request->has($mapToColumn), new Exception("Attribute '$mapToColumn' needs to be provided."));

        $customField->validate($request->get($mapToColumn));
    }

    public static function validateUpdate(Request $request, Value $value): void
    {
        /**
         * @var $customField CustomField
         */
        $customField = $value->customField->load(['validation', 'selectable']);

        $mapToColumn = $customField->getMappingColumn();

        if($request->has($mapToColumn)){
            $customField->validate($request->get($mapToColumn));
        }
    }
}
