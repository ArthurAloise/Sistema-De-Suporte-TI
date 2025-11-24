<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Painel Administrativo | Suporte TI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        /* --- ESTILO VISUAL LIMPO (Igual ao Usuário) --- */
        body {
            background-color: #f8f9fa; /* Cinza claro suave */
            font-family: 'Roboto', sans-serif;
        }

        .navbar {
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075) !important; /* Sombra suave */
        }

        .nav-link, .dropdown-item {
            transition: all 0.2s ease-in-out;
        }

        .main-content {
            min-height: calc(100vh - 120px);
            padding-bottom: 40px;
        }

        .footer {
            font-size: 0.9rem;
            color: #6c757d;
            background-color: #fff;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container-fluid">

            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('logo_chamado.png') }}" alt="Logo" width="100" class="me-2">
                <span class="fw-bold text-danger border-start ps-2">| Painel Administrativo</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="{{ url('/') }}">
                            <i class="fa fa-home me-1"></i>Início
                        </a>
                    </li>

                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-secondary btn-sm fw-bold" href="{{ route('user.dashboard') }}">
                            <i class="fa fa-user me-1"></i>Painel do Usuário
                        </a>
                    </li>

                    <li class="nav-item d-none d-lg-block mx-2">
                        <div class="vr h-100"></div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" id="manageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cogs me-1 text-danger"></i> Gerenciar
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="manageDropdown">
                            <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users me-2 text-secondary"></i>Usuários</a></li>

                            @if(auth()->user()->hasPermission('acessar_perfis'))
                                <li><a class="dropdown-item" href="{{ route('roles.index') }}"><i class="fas fa-id-card me-2 text-secondary"></i>Perfis (Roles)</a></li>
                            @endif

                            @if(auth()->user()->hasPermission('acessar_permissoes'))
                                <li><a class="dropdown-item" href="{{ route('permissions.index') }}"><i class="fas fa-list me-2 text-secondary"></i>Permissões</a></li>
                            @endif

                        </ul>
                    </li>

                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if (Auth::user()->profile_picture)
                                <img src="data:image/jpeg;base64,{{ Auth::user()->profile_picture }}" alt="Foto" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                            @else
                                <div class="bg-danger text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 32px; height: 32px;">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            @endif
                            <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="fas fa-user-cog me-2"></i>Minha Conta</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.change-password') }}"><i class="fas fa-key me-2"></i>Alterar Senha</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="fas fa-sign-out-alt me-2"></i>Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container py-4">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" id="success-alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')

        </div>
    </main>

    <footer class="footer text-center py-3">
        <div class="container">
            © {{ date('Y') }} Meu Sistema de Chamados. Painel Administrativo.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Script para fechar o alerta automaticamente
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(successAlert);
                bsAlert.close();
            }, 5000);
        }
    </script>
    @stack('scripts')
</body>
</html>
