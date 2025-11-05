@extends('user.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="d-flex align-items-center gap-3 mb-4">
                    {{-- O botão de voltar leva ao perfil --}}
                    <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary border-0" title="Voltar ao Perfil">
                        <i class="fas fa-arrow-left fs-4"></i>
                    </a>
                    <div>
                        <h1 class="fw-bolder text-primary mb-0">Alterar Senha</h1>
                        <p class="text-muted fs-6 mb-0">Para sua segurança, informe sua senha atual e a nova.</p>
                    </div>
                </div>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <form action="{{ route('user.change-password.update') }}" method="POST">
                            @csrf

                            {{-- Bloco para exibir mensagens de sucesso ou erro --}}
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                </div>
                            @endif

                            {{-- Exibir erros de validação gerais --}}
                            @if ($errors->any() && !$errors->has('current_password') && !$errors->has('new_password'))
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-bold">Senha Atual</label>
                                <input type="password" name="current_password" id="current_password"
                                       class="form-control form-control-lg @error('current_password') is-invalid @enderror"
                                       required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label fw-bold">Nova Senha</label>
                                <input type="password" name="new_password" id="new_password"
                                       class="form-control form-control-lg @error('new_password') is-invalid @enderror"
                                       required>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label fw-bold">Confirmar Nova Senha</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                       class="form-control form-control-lg"
                                       required>
                                {{-- O erro de confirmação geralmente é associado ao 'new_password' --}}
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary fw-bold px-4 py-2">
                                    <i class="fas fa-save me-1"></i> Alterar Senha
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
