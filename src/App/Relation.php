<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Relation extends Model
{
    use SoftDeletes;

    protected $table = 'custom_field_relations';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
