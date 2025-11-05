@csrf
{{-- Assume que $setor existe (seja um objeto Setor ou um new Setor()) --}}

<div class="mb-3">
    <label for="nome" class="form-label fw-bold">Nome do Setor</label>
    <input type="text" name="nome" id="nome"
           class="form-control form-control-lg @error('nome') is-invalid @enderror"
           value="{{ old('nome', $setor->nome ?? '') }}"
           placeholder="Ex.: Tecnologia da Informação, Recursos Humanos" required>
    @error('nome')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="sigla" class="form-label fw-bold">Sigla</label>
    <input type="text" name="sigla" id="sigla"
           class="form-control form-control-lg @error('sigla') is-invalid @enderror"
           value="{{ old('sigla', $setor->sigla ?? '') }}"
           placeholder="Ex.: TI, RH, DSI" required maxlength="20"> {{-- Adicionado maxlength --}}
    <div class="form-text">2–20 caracteres. Use letras maiúsculas, números e . _ -</div>
    @error('sigla')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-between mt-5">
    <a href="{{ route('setores.index') }}" class="btn btn-outline-secondary">
         <i class="fas fa-arrow-left me-1"></i> Cancelar
    </a>
    <button type="submit" class="btn btn-success fw-bold px-4">
        <i class="fas fa-save me-1"></i> Salvar Setor
    </button>
</div>
