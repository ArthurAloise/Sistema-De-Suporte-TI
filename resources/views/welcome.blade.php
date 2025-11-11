<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale-1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Meu Chamado - Soluções em Suporte de TI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa; /* Fundo cinza claro */
        }
        /* Fundo pontilhado (dot grid) */
        .bg-dots {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
            background-repeat: repeat;
        }
        .navbar-brand img {
            max-height: 40px;
        }
        .feature-card {
            border: 0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,0.1) !important;
        }
        .testimonial-card {
            border: 0;
            transition: transform 0.2s ease;
        }
        .testimonial-card:hover {
            transform: scale(1.03);
        }
        .footer-links a {
            text-decoration: none;
            color: #adb5bd;
            transition: color 0.2s;
        }
        .footer-links a:hover {
            color: #ffffff;
        }
        .social-icons a {
            color: #dc3545; /* Vermelho principal */
            font-size: 1.5rem;
            transition: color 0.2s;
        }
        .social-icons a:hover {
            color: #ffffff;
        }
    </style>
</head>
<body class="bg-dots">

    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('logo_chamado.png') }}" alt="Logo Meu Chamado">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ route('user.dashboard') }}" class="btn btn-primary fw-bold">
                                    <i class="fas fa-tachometer-alt me-1"></i> Painel
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="btn btn-outline-primary fw-bold px-4">
                                    <i class="fas fa-sign-in-alt me-1"></i> Entrar
                                </a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <section class="text-center my-5 py-4">
            <div class="w-100">
                {{-- O banner deve ser chamativo e incluir texto, mas usamos o que você forneceu --}}
                <img src="{{ asset('banner1.png') }}" alt="Simplifique seu Suporte!" class="img-fluid rounded-4 shadow-lg">
            </div>
        </section>

        <section class="my-5 py-4">
            <h2 class="fw-bolder text-center text-dark mb-5">Tudo que você precisa em um só lugar</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm rounded-4 h-100 feature-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-list-check fa-3x text-primary mb-3"></i>
                            <h5 class="fw-bold">Gestão de Chamados</h5>
                            <p class="text-muted small">Visualize o status de cada chamado e gerencie o fluxo de trabalho da sua equipe com facilidade.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm rounded-4 h-100 feature-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-clock fa-3x text-success mb-3"></i>
                            <h5 class="fw-bold">Acompanhamento Real</h5>
                            <p class="text-muted small">Receba notificações em tempo real e acompanhe o tempo de resposta e solução de cada ticket.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm rounded-4 h-100 feature-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-plus-circle fa-3x text-danger mb-3"></i>
                            <h5 class="fw-bold">Abertura Rápida</h5>
                            <p class="text-muted small">Abra novos chamados de forma intuitiva. Esqueça o caos de ferramentas complicadas e e-mails.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm rounded-4 h-100 feature-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-chart-bar fa-3x text-warning mb-3"></i>
                            <h5 class="fw-bold">Relatórios Detalhados</h5>
                            <p class="text-muted small">Gere insights e acompanhe o desempenho da equipe com relatórios gráficos e personalizados.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-danger text-white py-5 rounded-4 shadow-lg my-5">
            <div class="container">
                <h2 class="display-5 fw-bolder text-center">O que nossos clientes dizem</h2>
                <p class="lead text-center mb-5">Confira o que os gestores estão falando sobre nossos serviços.</p>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card shadow-lg rounded-3 h-100 text-dark testimonial-card">
                            <div class="card-body p-4 d-flex flex-column align-items-center text-center">
                                <i class="fas fa-user-tie fa-3x text-primary mb-3"></i>
                                <h5 class="fw-bold">Carlos Silva</h5>
                                <p class="text-muted small">Coordenador de TI</p>
                                <blockquote class="blockquote mt-2 mb-3 fst-italic">
                                    "Desde que implementamos o sistema, a gestão de demandas ficou muito mais clara. Os relatórios nos dão visibilidade total."
                                </blockquote>
                                <div class="text-warning h5">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg rounded-3 h-100 text-dark testimonial-card">
                            <div class="card-body p-4 d-flex flex-column align-items-center text-center">
                                 <i class="fas fa-user-edit fa-3x text-success mb-3"></i>
                                <h5 class="fw-bold">Ana Julia</h5>
                                <p class="text-muted small">Analista Financeiro</p>
                                <blockquote class="blockquote mt-2 mb-3 fst-italic">
                                    "Abrir um chamado é muito rápido e consigo acompanhar o status em tempo real. Facilitou demais o meu dia a dia."
                                </blockquote>
                                <div class="text-warning h5">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg rounded-3 h-100 text-dark testimonial-card">
                            <div class="card-body p-4 d-flex flex-column align-items-center text-center">
                                 <i class="fas fa-user-cog fa-3x text-warning mb-3"></i>
                                <h5 class="fw-bold">Marcos Pereira</h5>
                                <p class="text-muted small">Técnico de Suporte N2</p>
                                <blockquote class="blockquote mt-2 mb-3 fst-italic">
                                    "Consigo ver todos os meus chamados em um só lugar. A priorização automática ajuda a focar no que é realmente urgente."
                                </blockquote>
                                <div class="text-warning h5">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row g-4">

                <div class="col-md-4">
                    <img src="{{ asset('logo_chamado_branco.png') }}" alt="Logo Meu Chamado" style="max-height: 40px;" class="mb-3">
                    <p class="text-secondary">Nós cuidamos de toda a tecnologia enquanto você foca no seu negócio. Simplifique seu suporte de TI.</p>
                    <div class="social-icons d-flex gap-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-md-2 col-6 footer-links">
                    <h5 class="fw-bold mb-3">Navegação</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Home</a></li>
                        <li class="mb-2"><a href="#">Serviços</a></li>
                        <li class="mb-2"><a href="#">Depoimentos</a></li>
                        <li class="mb-2"><a href="#">Quem Somos</a></li>
                        <li class="mb-2"><a href="#">Blog</a></li>
                    </ul>
                </div>

                <div class="col-md-3 col-6 footer-links">
                     <h5 class="fw-bold mb-3">Contato</h5>
                    <ul class="list-unstyled text-secondary">
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> contato@meuchamado.com</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +55 (69) 4858-4850</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Porto Velho/RO</li>
                    </ul>
                </div>
            </div>

            <div class="text-center text-secondary pt-3 mt-4 border-top border-secondary border-opacity-25">
                <small>© {{ date('Y') }} MeuChamado. Todos os direitos reservados.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
