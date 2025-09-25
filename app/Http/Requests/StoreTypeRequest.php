<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ajuste se usar policies
    }

    public function rules(): array
    {
        return [
            //'nome' => ['required','string','max:255','unique:types,nome']
            'nome'             => ['required','string','max:255',
                Rule::unique('types','nome')->where(
                    fn($q) => $q->where('category_id', $this->input('category_id'))
                )
            ],
            'category_id'      => ['required','exists:categories,id'],
            'default_priority' => ['nullable', Rule::in(['baixa','media','alta','muito alta'])],
            'sla_hours'        => ['nullable','integer','min:1','max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.unique'   => 'Já existe um tipo com esse nome.',
            'default_priority.in' => 'Prioridade inválida.',
            'sla_hours.integer'   => 'SLA deve ser um número.',
            'sla_hours.min'       => 'SLA deve ser no mínimo 1 hora.',
        ];
    }
}
