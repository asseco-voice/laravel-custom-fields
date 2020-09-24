<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelectionValue extends Model
{
    protected $table = 'custom_field_selection_values';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function selectionType(): BelongsTo
    {
        return $this->belongsTo(SelectionType::class);
    }
}
