<?php

namespace App\Http\Requests\List;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
        return [
            'name' => 'required|max:255|unique:to_do_lists,name',
            'user_id' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле "Название списка" обязательно для заполнения.',
            'name.max' => 'Поле "Название списка" должно быть не более 255 символов.',
            'user_id.required' => 'Поле "ID пользователя" обязательно для заполнения.',
            'user_id.integer' => 'Поле "ID пользователя" должно быть числом.',
            'name.unique' => 'Список дел с таким названием уже существует.',
        ];
    }

    protected function failedValidation(Validator $validator) : void
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY));
    }

}
