<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Throwable;
use Voice\CustomFields\Database\Factories\ValueFactory;

class Value extends Model
{
    use HasFactory;

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

    protected static function newFactory()
    {
        return ValueFactory::new();
    }

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

    /**
     * @param Request $request
     * @throws Throwable
     */
    public static function validateCreate(Request $request): void
    {
        /**
         * @var $customField CustomField
         */
        $customField = CustomField::query()
            ->with(['validation', 'selectable'])
            ->findOrFail($request->get('custom_field_id'));

        $mapToColumn = $customField->getMappingColumn();

        self::filterByAllowedColumn($mapToColumn, $request);

        throw_if(!$request->has($mapToColumn), new Exception("Attribute '$mapToColumn' needs to be provided."));

        $customField->validate($request->get($mapToColumn));
    }

    /**
     * @param Request $request
     * @throws Throwable
     */
    public function validateUpdate(Request $request): void
    {
        /**
         * @var $customField CustomField
         */
        $customField = $this->customField->load(['validation', 'selectable']);

        $mapToColumn = $customField->getMappingColumn();

        self::filterByAllowedColumn($mapToColumn, $request);

        if ($request->has($mapToColumn)) {
            $customField->validate($request->get($mapToColumn));
        }
    }

    /**
     * @param string $mapToColumn
     * @param Request $request
     * @throws Throwable
     */
    protected static function filterByAllowedColumn(string $mapToColumn, Request $request): void
    {
        foreach (self::VALUE_COLUMNS as $column) {
            if ($column === $mapToColumn) {
                continue;
            }

            throw_if($request->has($column), new Exception("Attribute '$column' is not allowed for this custom field, use '$mapToColumn' instead."));
        }
    }
}
