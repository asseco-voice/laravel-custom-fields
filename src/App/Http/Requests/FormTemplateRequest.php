<?php

namespace Asseco\CustomFields\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

class FormTemplateRequest extends LaravelFormRequest
{
    public function rules()
    {
        return [
            'form_data' => 'array',
            'form_id' => 'required|string|exists:forms,id',
            'name' => 'required|string',
        ];
    }
}