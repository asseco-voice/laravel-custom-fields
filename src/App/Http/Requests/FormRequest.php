<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

class FormRequest extends LaravelFormRequest
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
            'tenant_id' => 'nullable',
            'name' => 'required|string|regex:/^[^\s]*$/i|unique:forms,name' . ($this->form ? ',' . $this->form->id : null),
            'definition' => 'required|array',
            'action_url' => 'nullable|string',
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
            'name.regex' => 'Form name must not contain spaces.',
        ];
    }

    /**
     * Dynamically set validator from 'required' to 'sometimes' if resource is being updated.
     *
     * @param  Validator  $validator
     */
    public function withValidator(Validator $validator)
    {
        $requiredOnCreate = ['name', 'definition'];

        $validator->sometimes($requiredOnCreate, 'sometimes', function () {
            return $this->form !== null;
        });
    }
}
