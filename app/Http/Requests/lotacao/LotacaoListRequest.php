<?php

namespace App\Http\Requests\lotacao;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LotacaoListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.min' => 'A quantidade mínima de itens por página é 1.',
            'per_page.max' => 'A quantidade máxima de itens por página é 100.',
            'per_page.integer' => 'A quantidade de itens por página deve ser um número inteiro.',
            'per_page.nullable' => 'O campo de itens por página pode ser deixado em branco, caso contrário, deve ser um número inteiro entre 1 e 100.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'code' => 422,
            'message' => 'Oops! Algo deu errado. Por favor, corrija os campos e tente novamente.',
            'errors' => $validator->errors(),
            'invalid_fields' => array_keys($validator->errors()->toArray()),
            'timestamp' => now()->toDateTimeString(),
            'path' => request()->path()
        ], 422));
    }
}
