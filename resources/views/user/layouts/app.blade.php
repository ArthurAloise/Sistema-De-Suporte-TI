<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário | Meu Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        /* Estilos para um visual mais limpo e moderno */
        body {
            background-color: #f8f9fa; /* Cinza claro suave, padrão do Bootstrap 'bg-light' */
            font-family: 'Roboto', sans-serif; /* Fonte mais moderna */
        }
        .navbar {
            /* Sombra mais suave para a navbar */
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075) !important;
        }
        .nav-link, .dropdown-item {
            transition: all 0.2s ease-in-out; /* Transição suave para links */
        }
        .main-content {
            min-height: calc(100vh - 120px); /* Garante que o conteúdo empurre o rodapé para baixo */
        }
        .footer {
            font-size: 0.9rem;
            color: #6c757d; /* Cor de texto 'muted' */
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('user.dashboard') }}">
                <img src="{{ asset('logo_chamado.png') }}" alt="Minha Logo" width="100" class="me-2">
                <span class="fw-bold text-dark border-start ps-2">| Painel do Usuário</span>
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

                    @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Tecnico'))
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-danger btn-sm" href="{{ route('admin.dashboard') }}">
                                <i class="fa fa-cogs me-1"></i>Painel Administrativo
                            </a>
                        </li>
                    @endif

                    <li class="nav-item d-none d-lg-block mx-2">
                        <div class="vr"></div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if (Auth::user()->profile_picture)
                                <img src="data:image/jpeg;base64,{{ Auth::user()->profile_picture }}" alt="Foto de perfil" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                            @else
                                <i class="fas fa-user-circle fa-2x me-2 text-secondary"></i> @endif
                            <span class="fw-bold">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="fas fa-user-cog fa-fw me-2"></i>Configurações</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.change-password') }}"><i class="fas fa-key fa-fw me-2"></i>Alterar Senha</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="fas fa-sign-out-alt fa-fw me-2"></i>Sair
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
            @yield('content')
        </div>
    </main>

    <footer class="footer text-center py-3 bg-white border-top">
        © {{ date('Y') }} Meu Sistema de Chamados. Todos os direitos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
