<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotoRequest extends FormRequest
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
            'photo' => 'required|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'photo.required' => 'Поле "Прикрепить фотографию" является обязательным.',
            'photo.image' => 'Файл в поле "Прикрепить фотографию" должен быть изображением.',
            'photo.max' => 'Файл в поле "Прикрепить фотографию" должен быть не более :max КБ.',
        ];
    }
}
