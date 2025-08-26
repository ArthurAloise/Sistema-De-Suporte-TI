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

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-1"></i> Salvar
    </button>
    <a href="{{ route('types.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
