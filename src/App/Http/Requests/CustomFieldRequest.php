<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomFieldRequest extends FormRequest
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
            // Reject name if it contains spaces
            'name' => 'required|string|regex:/^[^\s]*$/i',
        ];
    }
}
