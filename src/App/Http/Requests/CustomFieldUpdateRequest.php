<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class CustomFieldUpdateRequest extends FormRequest
{
    /**
     * These fields should never be allowed to update.
     */
    public const LOCKED_FOR_EDITING = ['selectable_type', 'selectable_id', 'model'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'          => 'sometimes|string|regex:/^[^\s]*$/i|unique:custom_fields,name' . ($this->custom_field ? ',' . $this->custom_field->id : null),
            'label'         => 'sometimes|string|max:255',
            'placeholder'   => 'nullable|string',
            'required'      => 'boolean',
            'validation_id' => 'nullable|exists:custom_field_validations,id',
        ];

        return Arr::except($rules, self::LOCKED_FOR_EDITING);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.regex' => 'Custom field name must not contain spaces.',
        ];
    }
}
