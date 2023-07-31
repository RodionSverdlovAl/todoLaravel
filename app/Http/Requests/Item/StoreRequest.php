<?php

namespace App\Http\Requests\Item;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
        $rules = [
            'list_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ];

        if ($this->hasFile('photo')) {
            $rules['photo'] = 'image|max:2048';
        }

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
            'photo.image' => 'Файл в поле "Прикрепить фотографию" должен быть изображением.',
            'photo.max' => 'Файл в поле "Прикрепить фотографию" должен быть не более :max КБ.',
        ];
    }

    protected function failedValidation(Validator $validator) : void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }

}
