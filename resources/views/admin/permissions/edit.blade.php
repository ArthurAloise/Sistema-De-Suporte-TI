@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="d-flex align-items-center gap-3 mb-4">
                    {{-- Botão Voltar --}}
                    <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary border-0" title="Voltar para Lista de Permissões">
                        <i class="fas fa-arrow-left fs-4"></i>
                    </a>
                    {{-- Título --}}
                    <div>
                        <h1 class="fw-bolder text-warning mb-0">Editar Permissão</h1>
                        <p class="text-muted fs-6 mb-0">Modifique o nome da permissão <span class="fw-bold">{{ $permission->name }}</span>.</p>
                    </div>
                </div>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">Nome (Identificador)</label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $permission->name) }}" required placeholder="Ex: criar_usuarios, ver_relatorios">
                                <div class="form-text">Use apenas letras minúsculas, números e sublinhados (_). Este é o identificador usado no código.</div>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Descrição comentada, conforme seu código original --}}
                            {{-- <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Descrição <span class="text-muted small">(Opcional)</span></label>
                                <input type="text" class="form-control form-control-lg @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $permission->description) }}">
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <div class="d-flex justify-content-between mt-5">
                                <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning fw-bold px-4">
                                     <i class="fas fa-save me-1"></i> Atualizar Permissão
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
