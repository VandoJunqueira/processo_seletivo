<?php

namespace App\Http\Requests\unidade;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UnidadeStoreRequest extends FormRequest
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
            'nome' => 'required|max:200',
            'sigla' => 'required|max:20',
            'endereco.tipo_logradouro' => 'required|string|max:50',
            'endereco.logradouro' => 'required|string|max:200',
            'endereco.numero' => 'required|integer|min:1',
            'endereco.bairro' => 'required|string|max:100',
            'endereco.cidade.nome' => 'required|string|max:200',
            'endereco.cidade.uf' => 'required|string|size:2'
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.max' => 'O campo nome não pode ter mais de 200 caracteres.',
            'sigla.required' => 'O campo sigla é obrigatório.',
            'sigla.max' => 'O campo sigla não pode ter mais de 20 caracteres.',

            // Endereço
            'endereco.tipo_logradouro.required' => 'O tipo de logradouro é obrigatório (Ex: Rua, Avenida).',
            'endereco.tipo_logradouro.string' => 'O tipo de logradouro deve ser um texto válido.',
            'endereco.tipo_logradouro.max' => 'O tipo de logradouro não pode exceder 50 caracteres.',

            'endereco.logradouro.required' => 'O logradouro é obrigatório.',
            'endereco.logradouro.string' => 'O logradouro deve ser um texto válido.',
            'endereco.logradouro.max' => 'O logradouro não pode exceder 200 caracteres.',

            'endereco.numero.required' => 'O número é obrigatório.',
            'endereco.numero.integer' => 'O número deve ser um valor inteiro.',
            'endereco.numero.min' => 'O número deve ser positivo.',

            'endereco.bairro.required' => 'O bairro é obrigatório.',
            'endereco.bairro.string' => 'O bairro deve ser um texto válido.',
            'endereco.bairro.max' => 'O bairro não pode exceder 100 caracteres.',

            // Cidade
            'endereco.cidade.nome.required' => 'O nome da cidade é obrigatório.',
            'endereco.cidade.nome.string' => 'O nome da cidade deve ser um texto válido.',
            'endereco.cidade.nome.max' => 'O nome da cidade não pode exceder 200 caracteres.',

            'endereco.cidade.uf.required' => 'A UF é obrigatória.',
            'endereco.cidade.uf.string' => 'A UF deve ser um texto válido.',
            'endereco.cidade.uf.size' => 'A UF deve ter exatamente 2 caracteres.',
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
