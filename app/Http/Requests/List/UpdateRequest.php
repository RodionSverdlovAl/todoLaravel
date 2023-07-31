<?php

namespace App\Http\Requests\List;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UpdateRequest extends FormRequest
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
    public function rules() : array
    {
        return [
            'name' => 'required|max:255|unique:to_do_lists,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле "Название списка" обязательно для заполнения.',
            'name.max' => 'Поле "Название списка" должно быть не более 255 символов.',
            'name.unique' => 'Список дел с таким названием уже существует.',
        ];
    }
}
