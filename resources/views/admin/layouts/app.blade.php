<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">  {{-- ← ADICIONE ESTA LINHA --}}
    <title>Admin - Suporte TI</title>
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
        <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('logo_chamado.png') }}" alt="Minha Logo" width="100" class="me-2">
            <span class="fw-bold text-danger">| Painel Administrativo</span>
        </a>

        <a class="nav-link btn text-black fw-bold" href="{{ url('/') }}">
            <i class="fa fa-home"></i> Inicio
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-black fw-bold" href="{{ route('user.dashboard') }}">
                        <i class="fa fa-cogs"></i> Painel Usuário
                    </a>
                </li>

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
                        <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users"></i> Usuários</a></li>
                        @if(auth()->user()->hasPermission('acessar_perfis'))
                            <li><a class="dropdown-item" href="{{ route('roles.index') }}"><i class="fas fa-id-card"></i> Perfis</a></li>
                        @endif
                        @if(auth()->user()->hasPermission('acessar_permissoes'))
                            <li><a class="dropdown-item" href="{{ route('permissions.index') }}"><i class="fas fa-bars"></i> Permissões</a></li>
                        @endif
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

    {{-- CÓDIGO DA MENSAGEM DE SUCESSO ADICIONADO AQUI --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- FIM DO CÓDIGO DA MENSAGEM --}}

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- SCRIPT PARA FAZER A MENSAGEM DESAPARECER SOZINHA --}}
<script>
    // Procura pelo alerta de sucesso na página usando o ID que definimos
    const successAlert = document.getElementById('success-alert');

    // Se o alerta existir, espera 5 segundos e depois o fecha suavemente
    if (successAlert) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(successAlert);
            bsAlert.close();
        }, 5000); // 5000 milissegundos = 5 segundos
    }
</script>
@stack('scripts')
</body>
</html>
