<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\Database\Factories\ValueFactory;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Throwable;

class Value extends Model
{
    use HasFactory;

    /**
     * Columns which are classified as value columns.
     */
    public const VALUE_COLUMNS = ['string', 'integer', 'float', 'date', 'text', 'boolean'];

    /**
     * Fallback column if a concrete value column can't be extracted.
     */
    public const FALLBACK_VALUE_COLUMN = 'string';

    protected $table = 'custom_field_values';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['value'];

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

    public function getValueAttribute()
    {
        foreach (self::VALUE_COLUMNS as $valueColumn) {
            if (isset($this->{$valueColumn})) {
                return $this->{$valueColumn};
            }
        }

        return null;
    }

    /**
     * @param Request $request
     * @throws Throwable
     */
    public static function validateCreate(Request $request): void
    {
        /**
         * @var CustomField $customField
         */
        $customField = CustomField::query()
            ->with(['validation', 'selectable'])
            ->findOrFail($request->get('custom_field_id'));

        $valueColumn = $customField->getValueColumn();

        self::filterByAllowedColumn($valueColumn, $request);

        throw_if(!$request->has($valueColumn) || empty($request->get($valueColumn)),
            new Exception("Attribute '$valueColumn' needs to be provided."));

        $customField->validate($request->get($valueColumn));
    }

    /**
     * @param Request $request
     * @throws Throwable
     */
    public function validateUpdate(Request $request): void
    {
        /**
         * @var CustomField $customField
         */
        $customField = $this->customField->load(['validation', 'selectable']);

        $mapToColumn = $customField->getValueColumn();

        self::filterByAllowedColumn($mapToColumn, $request);

        if ($request->has($mapToColumn)) {
            $customField->validate($request->get($mapToColumn));
        }
    }

    /**
     * @param string $valueColumn
     * @param Request $request
     * @throws Throwable
     */
    protected static function filterByAllowedColumn(string $valueColumn, Request $request): void
    {
        foreach (self::VALUE_COLUMNS as $column) {
            if ($column === $valueColumn) {
                continue;
            }

            $requestHasDisallowedColumn = $request->has($column) && $request->get($column);

            throw_if($requestHasDisallowedColumn, new Exception("Attribute '$column' is not allowed for this custom field, use '$valueColumn' instead."));
        }
    }
}
