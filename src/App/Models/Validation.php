<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\Database\Factories\ValidationFactory;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Validation extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'custom_field_validations';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function newFactory()
    {
        return ValidationFactory::new();
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(CustomField::class);
    }

    public function validate($input): void
    {
        $pattern = trim($this->regex, '/');

        throw_if(!preg_match("/$pattern/", "$input"), new Exception("Provided data doesn't pass $pattern validation"));
    }
}
