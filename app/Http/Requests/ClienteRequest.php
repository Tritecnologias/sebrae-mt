<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $emailUnique = 'unique:clientes,email';

        // Na edição, ignora o próprio registro
        if ($this->route('cliente')) {
            $emailUnique .= ',' . $this->route('cliente')->id;
        }

        return [
            'nome'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', $emailUnique],
            'telefone' => ['required', 'string', 'max:20'],
            'cep'      => ['required', 'string', 'size:9'],
            'rua'      => ['required', 'string', 'max:255'],
            'bairro'   => ['required', 'string', 'max:255'],
            'cidade'   => ['required', 'string', 'max:255'],
            'estado'   => ['required', 'string', 'size:2'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'     => 'O nome é obrigatório.',
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Informe um e-mail válido.',
            'email.unique'      => 'Este e-mail já está cadastrado.',
            'telefone.required' => 'O telefone é obrigatório.',
            'cep.required'      => 'O CEP é obrigatório.',
            'cep.size'          => 'O CEP deve ter 9 caracteres (ex: 01310-100).',
            'rua.required'      => 'A rua é obrigatória.',
            'bairro.required'   => 'O bairro é obrigatório.',
            'cidade.required'   => 'A cidade é obrigatória.',
            'estado.required'   => 'O estado é obrigatório.',
            'estado.size'       => 'O estado deve ter 2 caracteres (ex: SP).',
        ];
    }
}
