<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SelectionValueRequest extends FormRequest
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
            'selection_type_id' => 'required|exists:custom_field_selection_types,id',
            'label'             => 'nullable|string',
            'value'             => 'required|string',
            'preselect'         => 'boolean',
        ];
    }

    /**
     * Dynamically set validator from 'required' to 'sometimes' if resource is being updated
     *
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $requiredOnCreate = ['selection_type_id', 'value'];

        $validator->sometimes($requiredOnCreate, 'sometimes', function () {
            return $this->selection_value !== null;
        });
    }
}
