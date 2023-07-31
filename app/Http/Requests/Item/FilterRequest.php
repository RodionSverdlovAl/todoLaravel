<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'string|max:255',
            'list_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Поле "Название" должно быть строкой.',
            'title.max' => 'Поле "Название" должно содержать не более 255 символов.',
        ];
    }
}
