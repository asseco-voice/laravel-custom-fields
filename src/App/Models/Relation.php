<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\Database\Factories\RelationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model implements \Asseco\CustomFields\App\Contracts\Relation
{
    use HasFactory;

    protected $table = 'custom_field_relations';

    protected $fillable = ['parent_id', 'child_id'];

    protected static function newFactory()
    {
        return RelationFactory::new();
    }
}
