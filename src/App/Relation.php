<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Voice\CustomFields\Database\Factories\RelationFactory;

class Relation extends Model
{
    use HasFactory;

    protected $table = 'custom_field_relations';

    protected $fillable = ['parent_id', 'child_id'];

    protected static function newFactory()
    {
        return RelationFactory::new();
    }
}
