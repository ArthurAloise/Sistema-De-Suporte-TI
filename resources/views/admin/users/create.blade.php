@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="text-center mb-4">
                    <h1 class="fw-bolder text-primary mb-1">Criar Novo Usuário</h1>
                    <p class="text-muted fs-5">Preencha os dados para adicionar um novo usuário.</p>
                </div>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Nome</label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Senha</label>
                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="role_id" class="form-label fw-bold">Perfil</label>
                                    <select class="form-select form-select-lg @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                                        <option value="">Selecione um perfil</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="setor_id" class="form-label fw-bold">Setor</label>
                                    <select class="form-select form-select-lg @error('setor_id') is-invalid @enderror" id="setor_id" name="setor_id">
                                        <option value="">Nenhum / Não aplicável</option>
                                        @foreach ($setores as $setor)
                                            <option value="{{ $setor->id }}" {{ old('setor_id') == $setor->id ? 'selected' : '' }}>
                                                [{{ $setor->sigla }}] {{ $setor->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('setor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary fw-bold px-4">
                                    <i class="fas fa-check me-1"></i> Criar Usuário
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
