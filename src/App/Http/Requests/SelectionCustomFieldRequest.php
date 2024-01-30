<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Requests;

use Asseco\CustomFields\App\Contracts\CustomField as CustomFieldContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SelectionCustomFieldRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'regex:/^[^\s]*$/i',
                Rule::unique('custom_fields')->ignore($this->custom_field)->where(function ($query) {
                    return $this->usesSoftDelete() ? $query->whereNull('deleted_at') : $query;
                }),
            ],
            'label' => 'required|string|max:255',
            'placeholder' => 'nullable|string',
            'model' => 'required|string',
            'required' => 'boolean',
            'validation_id' => 'nullable|exists:custom_field_validations',
            'group' => 'nullable|string',
            'order' => 'nullable|integer',
            'selection' => 'array',
            'selection.multiselect' => 'boolean',
            'values' => 'array',
            'values.*.label' => 'nullable|string',
            'values.*.value' => 'string|required_with:values',
            'values.*.preselect' => 'boolean',
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

    private function usesSoftDelete(): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive(app(CustomFieldContract::class)));
    }
}
