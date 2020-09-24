<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class CustomField extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden  = ['created_at', 'updated_at'];

    public function selectable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePlain(Builder $query, string $subType = null)
    {
        $selectable = $subType ? PlainType::getSubTypeClass($subType) : PlainType::subTypes();
        return $query->whereHasMorph('selectable', $selectable);
    }

    public function scopeRemote(Builder $query)
    {
        return $query->whereHasMorph('selectable', [RemoteType::class]);
    }

    public function scopeSelection(Builder $query)
    {
        return $query->whereHasMorph('selectable', [SelectionType::class]);
    }

    public function values(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class)->withTimestamps();
    }

    public function validation(): BelongsTo
    {
        return $this->belongsTo(Validation::class);
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            CustomField::class,
            'custom_field_relations',
            'parent_id',
            'child_id')
            ->withTimestamps();
    }

    public function parent(): BelongsToMany
    {
        return $this->belongsToMany(
            CustomField::class,
            'custom_field_relations',
            'child_id',
            'parent_id')
            ->withTimestamps();
    }

    public static function types()
    {
        $plain = Config::get('asseco-custom-fields.type_mappings.plain');
        $other = Arr::except(Config::get('asseco-custom-fields.type_mappings'), 'plain');

        return array_merge_recursive($plain, $other);
    }
}
