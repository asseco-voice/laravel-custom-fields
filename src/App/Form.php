<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Voice\CustomFields\Database\Factories\FormFactory;

class Form extends Model
{
    use SoftDeletes, HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return FormFactory::new();
    }

    public function customFields(): BelongsToMany
    {
        return $this->belongsToMany(CustomField::class)->withTimestamps();
    }

    public function setDefinitionAttribute($value)
    {
        $this->attributes['definition'] = json_encode($value);
    }

    public function getDefinitionAttribute($value)
    {
        return json_decode($value, true);
    }
}
