<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SelectValue extends Model
{
    protected $table = 'custom_field_select_values';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function selectType(): BelongsTo
    {
        return $this->belongsTo(SelectType::class);
    }
}
