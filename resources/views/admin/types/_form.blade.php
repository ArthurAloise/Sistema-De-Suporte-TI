@csrf
{{-- Assume que $categories está disponível na view que inclui este partial --}}
{{-- Assume que $type existe (seja um objeto Type ou um new Type()) --}}

<div class="mb-3">
    <label for="nome" class="form-label fw-bold">Nome do Tipo</label>
    <input type="text" name="nome" id="nome"
           class="form-control form-control-lg @error('nome') is-invalid @enderror"
           value="{{ old('nome', $type->nome ?? '') }}" {{-- Usa ?? '' para o create --}}
           placeholder="Ex.: Bug no sistema, Problema de rede..." required>
    @error('nome')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="category_id" class="form-label fw-bold">Categoria do Tipo</label>
    <select name="category_id" id="category_id" class="form-select form-select-lg @error('category_id') is-invalid @enderror" required>
        <option value="" disabled {{ old('category_id', $type->category_id ?? '') ? '' : 'selected' }}>Selecione uma categoria</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected((old('category_id', $type->category_id ?? '') == $c->id))>
                {{ $c->nome }}
            </option>
        @endforeach
    </select>
    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="default_priority" class="form-label fw-bold">Prioridade Padrão <span class="text-muted small">(Opcional)</span></label>
        <select name="default_priority" id="default_priority" class="form-select form-select-lg @error('default_priority') is-invalid @enderror">
            <option value="">(Usar da Categoria)</option> {{-- Melhor opção padrão --}}
            @foreach (['baixa','media','alta','muito alta'] as $p)
                <option value="{{ $p }}" @selected((old('default_priority', $type->default_priority ?? '') === $p))>
                    {{ ucfirst($p) }}
                </option>
            @endforeach
        </select>
        <div class="form-text">Se vazio, usará a prioridade padrão da Categoria selecionada.</div>
        @error('default_priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="sla_hours" class="form-label fw-bold">SLA Alvo (horas) <span class="text-muted small">(Opcional)</span></label>
        <input type="number" min="1" step="0.1" name="sla_hours" id="sla_hours"
               class="form-control form-control-lg @error('sla_hours') is-invalid @enderror"
               value="{{ old('sla_hours', $type->sla_hours ?? '') }}" placeholder="Ex.: 8 ou 4.5">
        <div class="form-text">Se vazio, usará o SLA padrão da Categoria/Prioridade.</div>
        @error('sla_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="d-flex justify-content-between mt-5">
    <a href="{{ route('types.index') }}" class="btn btn-outline-secondary">
         <i class="fas fa-arrow-left me-1"></i> Cancelar
    </a>
    <button type="submit" class="btn btn-success fw-bold px-4">
        <i class="fas fa-save me-1"></i> Salvar Tipo
    </button>
</div>
