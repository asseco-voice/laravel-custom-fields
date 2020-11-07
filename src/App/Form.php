<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Voice\CustomFields\Database\Factories\FormFactory;

class Form extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['custom_field_id', 'form_id'];

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

    /**
     * @param $formData
     * @throws Exception
     */
    public function validate($formData): void
    {
        /**
         * @var $customField CustomField
         */
        foreach ($this->customFields as $customField) {
            $notSetButRequired = !isset($formData[$customField->name]) && $customField->required;

            if ($notSetButRequired) {
                throw new Exception("The '$customField->name' field is required!");
            }

            $customField->validate($formData[$customField->name]);
        }
    }
}
