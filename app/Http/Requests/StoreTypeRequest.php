<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ajuste se usar policies
    }

    public function rules(): array
    {
        return [
            'nome' => ['required','string','max:255','unique:types,nome'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.unique'   => 'Já existe um tipo com esse nome.',
        ];
    }
}
