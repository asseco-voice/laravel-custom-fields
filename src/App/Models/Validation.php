<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\App\Contracts\CustomField;
use Asseco\CustomFields\App\Exceptions\FieldValidationException;
use Asseco\CustomFields\Database\Factories\ValidationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Validation extends Model implements \Asseco\CustomFields\App\Contracts\Validation
{
    use HasFactory;

    protected $table = 'custom_field_validations';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function newFactory()
    {
        return ValidationFactory::new();
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(get_class(app(CustomField::class)));
    }

    /**
     * @param  $input
     * @return void
     *
     * @throws FieldValidationException|\Throwable
     */
    public function validate($input): void
    {
        $pattern = trim($this->regex, '/');

        throw_if(!preg_match("/$pattern/", "$input"), new FieldValidationException("Provided data doesn't pass $pattern validation"));
    }
}
