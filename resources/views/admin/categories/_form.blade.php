@csrf
{{-- Assume que $category existe (seja um objeto Category ou um new Category()) --}}

<div class="mb-3">
    <label for="nome" class="form-label fw-bold">Nome da Categoria</label>
    <input type="text" name="nome" id="nome"
           class="form-control form-control-lg @error('nome') is-invalid @enderror"
           value="{{ old('nome', $category->nome ?? '') }}" {{-- Usa ?? '' para o create --}}
           placeholder="Ex.: Software, Hardware, Rede..." required>
    @error('nome')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="default_priority" class="form-label fw-bold">Prioridade Padrão <span class="text-muted small">(Opcional)</span></label>
        <select name="default_priority" id="default_priority" class="form-select form-select-lg @error('default_priority') is-invalid @enderror">
            <option value="">(Usar fallback global)</option> {{-- Melhor opção padrão --}}
            @foreach (['baixa','media','alta','muito alta'] as $p)
                <option value="{{ $p }}" @selected((old('default_priority', $category->default_priority ?? '') === $p))>
                    {{ ucfirst($p) }}
                </option>
            @endforeach
        </select>
         <div class="form-text">Define a prioridade se o Tipo não especificar uma.</div>
        @error('default_priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="sla_hours" class="form-label fw-bold">SLA Alvo Padrão (horas) <span class="text-muted small">(Opcional)</span></label>
        <input type="number" min="1" step="0.1" name="sla_hours" id="sla_hours"
               class="form-control form-control-lg @error('sla_hours') is-invalid @enderror"
               value="{{ old('sla_hours', $category->sla_hours ?? '') }}" placeholder="Ex.: 8 ou 4.5">
         <div class="form-text">Define o SLA se o Tipo não especificar um.</div>
        @error('sla_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="d-flex justify-content-between mt-5">
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
         <i class="fas fa-arrow-left me-1"></i> Cancelar
    </a>
    <button type="submit" class="btn btn-success fw-bold px-4">
        <i class="fas fa-save me-1"></i> Salvar Categoria
    </button>
</div>
