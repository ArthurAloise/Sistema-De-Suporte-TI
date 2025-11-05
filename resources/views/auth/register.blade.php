<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cadastro - Meu Sistema de Chamados</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .bg-dots {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
            background-repeat: repeat;
        }
        .auth-card {
            max-width: 480px; /* Um pouco maior para o registro */
            width: 100%;
        }
    </style>
</head>
<body class="bg-dots">

    <div class="auth-card">
        <div class="card shadow-lg border-0 rounded-4">

            <div class="text-center pt-4">
                <img src="{{ asset('logo_chamado.png') }}" alt="Logo Meu Chamado" style="max-height: 50px;">
                <h3 class="fw-bolder text-primary mt-3 mb-0">Criar sua Conta</h3>
            </div>

            <div class="card-body p-4 p-md-5">

                @if ($errors->any())
                    <div class="alert alert-danger small py-2">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nome Completo</label>
                        <input type="text" name="name" id="name"
                               class="form-control form-control-lg"
                               value="{{ old('name') }}"
                               required autofocus autocomplete="name"
                               placeholder="Seu nome">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control form-control-lg"
                               value="{{ old('email') }}"
                               required autocomplete="username"
                               placeholder="seuemail@exemplo.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Senha</label>
                        <input type="password" name="password" id="password"
                               class="form-control form-control-lg"
                               required autocomplete="new-password"
                               placeholder="Crie uma senha forte">
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-bold">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control form-control-lg"
                               required autocomplete="new-password"
                               placeholder="Repita a senha">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-user-plus me-1"></i> Registrar
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">Já tem uma conta?</p>
                    <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Acesse aqui</a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
