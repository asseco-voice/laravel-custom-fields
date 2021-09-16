<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomFieldCreateRequest extends FormRequest
{
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
        return [
            'name'            => 'required|string|regex:/^[^\s]*$/i|unique:custom_fields,name' . ($this->custom_field ? ',' . $this->custom_field->id : null),
            'label'           => 'required|string|max:255',
            'placeholder'     => 'nullable|string',
            'selectable_type' => 'required',
            'selectable_id'   => 'required',
            'model'           => 'required|string',
            'required'        => 'boolean',
            'hidden'          => 'boolean',
            'validation_id'   => 'nullable|exists:custom_field_validations,id',
            'group'           => 'nullable|string',
            'order'           => 'nullable|integer',
        ];
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
