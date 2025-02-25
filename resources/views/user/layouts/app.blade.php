<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuário </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-dots">
    <style>
        .bg-dots {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
            background-repeat: repeat;
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-dark shadow" style="background-color: #ffffff;">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('user.dashboard') }}">
                <img src="{{ asset('logo_chamado.png') }}" alt="Minha Logo" width="100" class="me-2">
                <span class="fw-bold text-danger">| Painel do Usuário</span>
            </a>

            <!-- Inicio -->
            <a class="nav-link btn text-black fw-bold" href="{{ url('/') }}">
                <i class="fa fa-home"></i> Inicio
            </a>

            <!-- Botão responsivo -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Itens da Navbar -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- Botão que aparece apenas para Admin -->
                    @if(auth()->user()->hasRole('Admin'))
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-black fw-bold" href="{{ route('admin.dashboard') }}">
                                <i class="fa fa-cogs"></i> Painel Administrativo
                            </a>
                        </li>
                    @endif

                    <!-- Menu de Dropdown para o nome do usuário -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-black d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            @if (Auth::user()->profile_picture)
                                <img src="data:image/jpeg;base64,{{ Auth::user()->profile_picture }}"
                                     alt="Foto de perfil" class="rounded-circle" width="30" height="30" style="margin-right: 8px;">
                            @endif
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="fas fa-user-cog"></i> Configurações</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.change-password') }}"><i class="fas fa-key"></i> Alterar Senha</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="fas fa-sign-out-alt"></i> Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
