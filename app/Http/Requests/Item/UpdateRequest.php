<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        $rules = [
            'list_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Поле "Название" является обязательным.',
            'title.string' => 'Поле "Название" должно быть строкой.',
            'title.max' => 'Поле "Название" должно содержать не более 255 символов.',
            'description.required' => 'Поле "Описание" является обязательным.',
            'description.string' => 'Поле "Описание" должно быть строкой.',
        ];
    }
}
