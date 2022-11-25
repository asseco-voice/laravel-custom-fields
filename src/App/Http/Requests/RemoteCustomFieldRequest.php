<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoteCustomFieldRequest extends FormRequest
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
            'name'                       => 'required|string|regex:/^[^\s]*$/i|unique:custom_fields,name' . ($this->custom_field ? ',' . $this->custom_field->id : null),
            'label'                      => 'required|string|max:255',
            'placeholder'                => 'nullable|string',
            'model'                      => 'required|string',
            'required'                   => 'boolean',
            'validation_id'              => 'nullable|exists:custom_field_validations',
            'group'                      => 'nullable|string',
            'order'                      => 'nullable|integer',
            'remote'                     => 'required|array',
            'remote.url'                 => 'required|url',
            'remote.method'              => 'required|in:GET,POST,PUT',
            'remote.body'                => 'nullable|array',
            'remote.headers'             => 'nullable|array',
            'remote.mappings'            => 'nullable|array',
            'remote.data_path'           => 'nullable|string',
            'remote.identifier_property' => 'nullable|string'
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
