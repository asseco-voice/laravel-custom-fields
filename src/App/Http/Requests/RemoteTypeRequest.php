<?php

declare(strict_types=1);

namespace Asseco\CustomFields\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoteTypeRequest extends FormRequest
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
            'url'                 => 'url',
            'method'              => 'in:GET,POST,PUT',
            'body'                => 'nullable|array',
            'headers'             => 'nullable|array',
            'mappings'            => 'nullable|array',
            'data_path'           => 'nullable|string',
            'identifier_property' => 'nullable|string',
        ];
    }
}
