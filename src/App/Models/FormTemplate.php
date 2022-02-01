<?php

namespace Asseco\CustomFields\App\Models;

use Asseco\CustomFields\App\Traits\Customizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Asseco\CustomFields\App\Contracts\Form;

class FormTemplate extends Model implements \Asseco\CustomFields\App\Contracts\FormTemplate
{
    use Customizable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function form(): BelongsTo
    {
        return $this->belongsTo(get_class(app(Form::class)));
    }

    /**
     * @param array $formData
     * @return array
     */
    public function createCustomFieldValues(array $formData = []): array
    {
        if (empty($formData)) {
            return [];
        }
        $modelType = get_class(app(\Asseco\CustomFields\App\Contracts\FormTemplate::class));
        $values = [];

        /**
         * @var \Asseco\CustomFields\App\Contracts\CustomField $customField
         */
        foreach ($this->form->customFields as $customField) {
            $formCustomField = Arr::get($formData, $customField->name);

            if (!$formCustomField) {
                continue;
            }

            $type = $customField->getValueColumn();

            $values[] = $customField->values()->updateOrCreate([
                'model_type' => $modelType,
                'model_id' => $this->id,
            ],
                [$type => $formCustomField]
            );
        }

        return $values;
    }

}