<?php

declare(strict_types=1);

namespace Voice\CustomFields\App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Voice\CustomFields\Database\Factories\FormFactory;
use Log;

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

    public static function validate(Form $form, $form_data)
    {
        $outputValue = [];
        foreach($form->customFields as $formKey => $formValue)
        {
            if (isset($form_data[$formValue['name']])) {
                $type = $formValue->getMappingColumn();
                preg_all_match('/' . $formValue->validation->regex . '/', $form_data[$formValue['name']], $matches);
                //$form_data[$formValue['name']] = $matches[0];

                switch($type)
                {
                    case 'string':
                        $outputValue[$formValue['name']] = [
                            'type' => $type,
                            'value' => $form_data[$formValue['name']],
                        ];
                        break;
                    case 'text':
                        $outputValue[$formValue['name']] = [
                            'type' => 'string',
                            'value' => $form_data[$formValue['name']],
                        ];
                        break;
                    case 'integer':
                    //case 'long':
                        $outputValue[$formValue['name']] = [
                            'type' => $type,
                            'value' => $form_data[$formValue['name']],
                        ];
                        break;
                    case 'float':
                    //case 'double':
                        $outputValue[$formValue['name']] = [
                            'type' => 'double',
                            'value' => $form_data[$formValue['name']],
                        ];
                        break;
                    case 'date':
                        $outputValue[$formValue['name']] = [
                            'type' => $type,
                            'value' => $form_data[$formValue['name']],
                        ];
                        break;
                    case 'boolean':
                        $outputValue[$formValue['name']] = [
                            'type' => $type,
                            'value' => $form_data[$formValue['name']],
                        ];
                        break;
                }
            } else if ($formValue->required) {
                throw new Exception("This field is required: " . $formValue['name'] . "!");
            } else {
                continue;
            }
        }

        Log::debug($outputValue);
        return $outputValue;
    }
}
