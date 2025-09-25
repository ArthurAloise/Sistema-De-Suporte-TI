@csrf
<div class="mb-3">
    <label for="nome" class="form-label">Nome do Tipo</label>
    <input type="text" name="nome" id="nome"
           class="form-control @error('nome') is-invalid @enderror"
           value="{{ old('nome', $type->nome) }}"
           placeholder="Ex.: Bug no sistema, Problema de rede, Acesso Wi‑Fi">
    @error('nome')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Prioridade Padrão (ITIL)</label>
        <select name="default_priority" class="form-select @error('default_priority') is-invalid @enderror">
            <option value="">(usar fallback)</option>
            @foreach (['baixa','media','alta','muito alta'] as $p)
                <option value="{{ $p }}" {{ old('default_priority', $type->default_priority) === $p ? 'selected' : '' }}>
                    {{ ucfirst($p) }}
                </option>
            @endforeach
        </select>
        @error('default_priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">SLA Alvo (horas)</label>
        <input type="number" min="1" name="sla_hours"
               class="form-control @error('sla_hours') is-invalid @enderror"
               value="{{ old('sla_hours', $type->sla_hours) }}" placeholder="ex.: 8">
        @error('sla_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">Se vazio, usa o alvo global da prioridade.</div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Categoria do Tipo</label>
    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
        <option value="" disabled {{ old('category_id', $type->category_id) ? '' : 'selected' }}>Selecione</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(old('category_id', $type->category_id)==$c->id)>{{ $c->nome }}</option>
        @endforeach
    </select>
    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>


<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-1"></i> Salvar
    </button>
    <a href="{{ route('types.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
