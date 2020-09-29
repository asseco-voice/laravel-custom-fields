<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Voice\CustomFields\Database\Factories\SelectionValueFactory;

class SelectionValue extends Model
{
    use HasFactory;

    protected $table   = 'custom_field_selection_values';
    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return SelectionValueFactory::new();
    }

    public function selectionType(): BelongsTo
    {
        return $this->belongsTo(SelectionType::class);
    }
}
