<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ValueRequest extends FormRequest
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
            'custom_field_id' => 'required|integer|exists:custom_fields,id',
            'model_type'      => 'required|string',
            'model_id'        => 'required|integer',
            'string'          => 'nullable|string|max:255',
            'integer'         => 'nullable|integer',
            'float'           => 'nullable|numeric',
            'date'            => 'nullable|string',
            'text'            => 'nullable|string',
            'boolean'         => 'nullable|boolean',
        ];
    }

    /**
     * Dynamically set validator from 'required' to 'sometimes' if resource is being updated.
     *
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $requiredOnCreate = ['custom_field_id', 'model_type', 'model_id'];

        $validator->sometimes($requiredOnCreate, 'sometimes', function () {
            return $this->value !== null;
        });
    }
}
