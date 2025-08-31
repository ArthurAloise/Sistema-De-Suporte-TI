@csrf
<div class="mb-3">
    <label for="nome" class="form-label">Nome da Categoria</label>
    <input type="text" name="nome" id="nome"
           class="form-control @error('nome') is-invalid @enderror"
           value="{{ old('nome', $category->nome) }}"
           placeholder="Ex.: Software, Hardware, Rede, Backup">
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
                <option value="{{ $p }}" {{ old('default_priority', $category->default_priority) === $p ? 'selected' : '' }}>
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
               value="{{ old('sla_hours', $category->sla_hours) }}" placeholder="ex.: 8">
        @error('sla_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">Se vazio, usa o alvo global da prioridade.</div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-1"></i> Salvar
    </button>
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
