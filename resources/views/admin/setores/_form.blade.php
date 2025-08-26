@csrf
<div class="mb-3">
    <label for="nome" class="form-label">Nome do Setor</label>
    <input type="text" name="nome" id="nome"
           class="form-control @error('nome') is-invalid @enderror"
           value="{{ old('nome', $setor->nome) }}"
           placeholder="Ex.: Tecnologia da Informação, Recursos Humanos">
    @error('nome')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="sigla" class="form-label">Sigla</label>
    <input type="text" name="sigla" id="sigla"
           class="form-control @error('sigla') is-invalid @enderror"
           value="{{ old('sigla', $setor->sigla) }}"
           placeholder="Ex.: TI, RH, DSI">
    <div class="form-text">2–20 caracteres. Letras/números e . _ -</div>
    @error('sigla')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-1"></i> Salvar
    </button>
    <a href="{{ route('setores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
