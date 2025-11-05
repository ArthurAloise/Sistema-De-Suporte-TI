@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">

      <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary border-0" title="Voltar">
          <i class="fas fa-arrow-left fs-4"></i>
        </a>
        <div>
          <h1 class="fw-bolder text-success mb-0">Criar Novo Perfil</h1>
          <p class="text-muted fs-6 mb-0">Defina um nome e as permissões.</p>
        </div>
      </div>

      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
          <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div class="mb-4">
              <label for="name" class="form-label fw-bold">Nome do Perfil</label>
              <input type="text" name="name" id="name" value="{{ old('name') }}"
                     class="form-control form-control-lg @error('name') is-invalid @enderror" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold mb-3">Permissões Associadas</label>
              <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                @foreach($permissions as $permission)
                  <div class="col">
                    <div class="form-check form-switch fs-6">
                      <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                             class="form-check-input" role="switch" id="perm-{{ $permission->id }}"
                             {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                      <label class="form-check-label" for="perm-{{ $permission->id }}">{{ $permission->name }}</label>
                    </div>
                  </div>
                @endforeach
              </div>
              @error('permissions') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between mt-5">
              <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-1"></i> Cancelar
              </a>
              <button type="submit" class="btn btn-success fw-bold px-4">
                <i class="fas fa-check me-1"></i> Salvar Perfil
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
