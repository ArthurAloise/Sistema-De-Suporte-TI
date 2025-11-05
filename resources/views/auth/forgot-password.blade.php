<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Senha - Meu Sistema de Chamados</title>

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
            max-width: 450px;
            width: 100%;
        }
    </style>
</head>
<body class="bg-dots">

    <div class="auth-card">
        <div class="card shadow-lg border-0 rounded-4">

            <div class="text-center pt-4">
                <img src="{{ asset('logo_chamado.png') }}" alt="Logo Meu Chamado" style="max-height: 50px;">
                <h3 class="fw-bolder text-primary mt-3 mb-0">Recuperar Senha</h3>
            </div>

            <div class="card-body p-4 p-md-5">

                <p class="text-muted text-center small mb-4">
                    Esqueceu sua senha? Sem problemas. Apenas informe seu e-mail e enviaremos um link para você criar uma nova.
                </p>

                @if (session('status'))
                    <div class="alert alert-success small" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger small py-2">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control form-control-lg"
                               value="{{ old('email') }}"
                               required autofocus
                               placeholder="seuemail@exemplo.com">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            Enviar Link de Redefinição
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">Lembrou a senha?</p>
                    <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Voltar ao Login</a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
