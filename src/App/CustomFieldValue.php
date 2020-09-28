<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class CustomFieldValue extends Model
{
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

    public static function validate(Request $request): void
    {
        $customField = CustomField::query()
            ->with(['validation', 'selectable'])
            ->findOrFail($request->get('custom_field_id'));

        $mapToColumn = CustomField::getMappingColumn($customField->selectable);

        throw_if(!$request->has($mapToColumn), new Exception("Attribute '$mapToColumn' needs to be provided."));

        optional($customField->validation)->validate($request->get($mapToColumn));
    }
}
