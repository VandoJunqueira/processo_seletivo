<?php

namespace App\Http\Requests\lotacao;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LotacaoStoreRequest extends FormRequest
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
            'pes_id' => 'required|integer|exists:pessoa,pes_id',
            'unid_id' => 'required|integer|exists:unidade,unid_id',
            'data_lotacao' => 'required|date',
            'data_remocao' => 'nullable|date',
            'portaria' => 'required|string|max:100'
        ];
    }

    public function messages(): array
    {
        return [
            'pes_id.required' => 'O campo "pes_id" é obrigatório.',
            'pes_id.integer' => 'O campo "pes_id" deve ser um número inteiro.',
            'pes_id.exists' => 'O "pes_id" informado não existe na base de dados.',

            'unid_id.required' => 'O campo "unid_id" é obrigatório.',
            'unid_id.integer' => 'O campo "unid_id" deve ser um número inteiro.',
            'unid_id.exists' => 'O "unid_id" informado não existe na base de dados.',

            'data_lotacao.required' => 'A data de lotação é obrigatória.',
            'data_lotacao.date' => 'A data de lotação deve estar no formato válido (YYYY-MM-DD).',

            'data_remocao.date' => 'A data de remoção deve estar no formato válido (YYYY-MM-DD).',

            'portaria.required' => 'O campo "portaria" é obrigatório.',
            'portaria.string' => 'O campo "portaria" deve ser um texto.',
            'portaria.max' => 'O campo "portaria" não pode ter mais que 100 caracteres.'
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
