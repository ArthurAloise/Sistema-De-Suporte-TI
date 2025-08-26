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

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-1"></i> Salvar
    </button>
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
