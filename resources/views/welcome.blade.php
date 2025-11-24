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
.navbar-brand {
    display: inline-flex;
    align-items: center;
    gap: 0.9rem;
    padding: 0.55rem 1.2rem;   /* 👈 aumentei o padding */
    border-radius: 999px;
    background: #fff;
    border: 1px solid rgba(15,23,42,0.05);
    box-shadow: 0 0.45rem 1.1rem rgba(0,0,0,0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

                .navbar-brand:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 0.85rem 1.5rem rgba(0,0,0,0.12);
                }
/* Container circular que recorta a logo (faz o "zoom") */
.brand-badge {
    width: 80px;              /* tamanho visível do círculo */
    height: 80px;
    border-radius: 50%;
    overflow: hidden;         /* recorta a imagem maior por dentro */
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Imagem MAIOR do que o círculo, para parecer mais "dentro" */
/* Imagem MAIOR do que o círculo, para parecer mais "dentro" */
.brand-logo {
    width: 130px;
    height: 130px;
    object-fit: cover;
    border-radius: 50%;
    filter: drop-shadow(0 4px 9px rgba(0,0,0,0.12));
    transform: translateY(7px);
}

                .brand-text {
                    display: inline-flex;
                    flex-direction: column;
                }
                .brand-text span {
                    line-height: 1.1;
                }
                .brand-text .title {
                    font-weight: 900;
                    letter-spacing: 0.04em;
                    color: #111827;
                }
                .brand-text .subtitle {
                    font-size: 0.75rem;
                    text-transform: uppercase;
                    letter-spacing: 0.2em;
                    color: #6c757d;
                }
@media (max-width: 576px) {
    .navbar-brand {
        width: 100%;
        justify-content: center;
    }

    .brand-text {
        text-align: left;
    }

    .brand-badge {
        width: 70px;
        height: 70px;
    }

    .brand-logo {
        width: 95px;
        height: 95px;
    }
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
                    <span class="brand-badge">
                        <img src="{{ asset('logo_chamado.png') }}" alt="Logo Meu Chamado" class="img-fluid brand-logo">
                    </span>
                    <span class="brand-text">
                        <span class="title d-block">Meu Chamado</span>
                        <span class="subtitle d-block">Suporte Inteligente</span>
                    </span>
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
                                <i class="fas fa-chart-bar fa-3x text-waFrning mb-3"></i>
                                <h5 class="fw-bold">Relatórios Detalhados</h5>
                                <p class="text-muted small">Gere insights e acompanhe o desempenho da equipe com relatórios gráficos e personalizados.</p>
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
                        <img src="{{ asset('logo_chamado_branca.png') }}" alt="Logo Meu Chamado" style="max-height: 220px;" class="mb-3">
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
