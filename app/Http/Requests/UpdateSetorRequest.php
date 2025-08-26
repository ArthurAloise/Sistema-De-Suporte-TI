<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSetorRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nome' => [
                'required','string','max:255',
                Rule::unique('setores','nome')->ignore($this->route('setor')->id),
            ],
            'sigla' => [
                'required','string','max:20',
                Rule::unique('setores','sigla')->ignore($this->route('setor')->id),
                'regex:/^[A-Za-z0-9._-]{2,20}$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do setor é obrigatório.',
            'nome.unique'   => 'Já existe um setor com esse nome.',
            'sigla.required'  => 'A sigla é obrigatória.',
            'sigla.unique'    => 'Já existe um setor com essa sigla.',
            'sigla.regex'     => 'A sigla deve ter 2–20 caracteres (letras/números . _ -).',
        ];
    }
}
