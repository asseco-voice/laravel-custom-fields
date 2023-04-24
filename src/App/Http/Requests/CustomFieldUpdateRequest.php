<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Requests;

use Asseco\CustomFields\App\Contracts\CustomField as CustomFieldContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

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
            'name'          => [
                'required',
                'string',
                'regex:/^[^\s]*$/i',
                Rule::unique('custom_fields')->ignore($this->custom_field)->where(function ($query) {
                    return $this->usesSoftDelete() ? $query->whereNull('deleted_at') : $query;
                }),
            ],
            'label'         => 'sometimes|string|max:255',
            'placeholder'   => 'nullable|string',
            'required'      => 'boolean',
            'hidden'        => 'boolean',
            'validation_id' => 'nullable|exists:custom_field_validations,id',
            'group'         => 'nullable|string',
            'order'         => 'nullable|integer',
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

    private function usesSoftDelete(): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive(app(CustomFieldContract::class)));
    }
}
