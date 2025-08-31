<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => [
                'required','string','max:255',
                Rule::unique('categories','nome')->ignore($this->route('category')->id),
            ],
            'default_priority' => ['nullable', Rule::in(['baixa','media','alta','muito alta'])],
            'sla_hours'        => ['nullable','integer','min:1','max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.unique'   => 'Já existe uma categoria com esse nome.',
            'default_priority.in' => 'Prioridade inválida.',
            'sla_hours.integer'   => 'SLA deve ser um número.',
            'sla_hours.min'       => 'SLA deve ser no mínimo 1 hora.',
        ];
    }
}
