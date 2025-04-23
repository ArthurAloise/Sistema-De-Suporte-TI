<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Chamados - Suporte Técnico</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Scripts -->
{{--    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js" defer></script>--}}

    <!-- Styles -->
    {{--    <link href="{{ mix('css/app.css') }}" rel="stylesheet">--}}
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900 selection:bg-red-500 selection:text-white bg-dots">
<style>
    .bg-dots {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
        background-repeat: repeat;
    }
</style>
<div class="relative sm:flex sm:justify-center sm:items-center min-h-screen">
    @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @auth
                <a href="{{ route('user.dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:outline-red-500">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:outline-red-500">Log in</a>
            @endauth
        </div>
    @endif


    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-center">
            <img src="{{ asset('logo_chamado.png') }}" alt="Logo Suporte Técnico" width="200">
        </div>
        <!-- Banner -->
        <div class="w-full">
            <img src="{{ asset('banner1.png') }}" alt="Banner Suporte Técnico" class="w-full h-auto">
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                <a href="#" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                    <div>
                        <div class="h-16 w-16 bg-blue-50 dark:bg-blue-800/20 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h7" />
                                <circle cx="18" cy="12" r="1.5" stroke="currentColor" stroke-width="1.5" />
                                <circle cx="18" cy="18" r="1.5" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </div>

                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Gestão de Chamados</h2>

                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            Acesse todos os chamados técnicos abertos pela sua equipe e visualize o status de cada um.
                            Gerencie chamados de TI com facilidade
                        </p>
                    </div>
                </a>

                <a href="#" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                    <div>
                        <div class="h-16 w-16 bg-green-50 dark:bg-green-800/20 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Acompanhamento em Tempo Real!</h2>

                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            Verifique todos os atendimentos realizados e o tempo de resposta aos chamados em notificações.
                        </p>
                    </div>
                </a>

                <a href="#" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                    <div>
                        <div class="h-16 w-16 bg-yellow-50 dark:bg-yellow-800/20 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-yellow-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m-8-8h16" />
                            </svg>
                        </div>

                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Novo Chamado, Rápido e Fácil</h2>

                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            Abra um novo chamado para solicitar suporte técnico para sua empresa.
                            Esqueça o caos de ferramentas ineficientes.
                        </p>
                    </div>
                </a>

                <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                    <div>
                        <div class="h-16 w-16 bg-purple-50 dark:bg-purple-800/20 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-purple-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v4m6-8v8m6-12v12m6-16v16" />
                            </svg>
                        </div>

                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Relatórios Detalhados</h2>

                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            Acompanhe o desempenho da equipe técnica com relatórios detalhados de atendimentos realizados.
                            Gere insights com relatórios personalizados.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <section class="mt-16 bg-gray-100 dark:bg-gray-900 py-12 cta">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-white dark:text-white text-center">O que nossos clientes dizem</h2>
                <p class="mt-4 text-lg text-white dark:text-gray-400 text-center">
                    Confira o que nossos clientes estão falando sobre nossos serviços.
                </p>

                <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Feedback Neymar -->
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg flex flex-col">
                        <div class="flex items-center">
                            <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcRUREvlCvHREdbT-Xsf2L2dmgO7AulT-6hqeDRUThJvVKKQwYuPwNatanNGyJiXSwubdlC8iTQHCPxOrsM-uuUCfg"
                                 alt="Neymar Jr." class="w-12 h-12 rounded-full object-cover">
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Neymar Jr.</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Jogador de Futebol</p>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            "Esse sistema é incrível, me ajuda a organizar tudo, até mesmo os treinos! Nota 10!"
                        </p>
                        <div class="mt-4 flex">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                        </div>
                    </div>
                    <!-- Feedback Elon Musk -->
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg flex flex-col">
                        <div class="flex items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ed/Elon_Musk_Royal_Society.jpg/640px-Elon_Musk_Royal_Society.jpg"
                                 alt="Elon Musk" class="w-12 h-12 rounded-full object-cover">
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Elon Musk</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Empresário e Visionário</p>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            "Com essa tecnologia, posso lançar um foguete e ainda ter tempo para gerenciar meus negócios. Revolucionário!"
                        </p>
                        <div class="mt-4 flex">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Feedback Donald Trump -->
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg flex flex-col">
                        <div class="flex items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Donald_Trump_official_portrait.jpg/640px-Donald_Trump_official_portrait.jpg"
                                 alt="Donald Trump" class="w-12 h-12 rounded-full object-cover">
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Donald Trump</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Empresário e Ex-Presidente</p>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            "Este é o melhor sistema. Eu vi muitos sistemas, mas este é simplesmente o melhor, incrível, fantástico!"
                        </p>
                        <div class="mt-4 flex">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.325 2.11 10.675 2.11 10.951 2.927l1.18 3.618a1 1 0 00.95.69h3.801c.969 0 1.371 1.24.588 1.81l-3.073 2.244a1 1 0 00-.364 1.118l1.181 3.618c.276.816-.713 1.493-1.451 1.017L10 14.347l-3.073 2.244c-.738.476-1.727-.201-1.451-1.017l1.181-3.618a1 1 0 00-.364-1.118L3.22 8.046c-.783-.57-.38-1.81.588-1.81h3.801a1 1 0 00.95-.69l1.18-3.618z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- Rodapé -->
<style>
    .cta {
        background: #dc3545;
        color: white;
        text-align: center;
        padding: 40px;
    }
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }
    .footer {
        background: #fff;
        padding: 40px 180px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
    }
    .footer ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .footer li {
        margin: 5px 0;
    }
    .footer-logo {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .footer p {
        color: #666;
        font-size: 14px;
        max-width: 300px;
    }
    .social-icons {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }
    .social-icons a {
        color: #ff0000;
        font-size: 20px;
        text-decoration: none;
    }
    .footer-column {
        flex: 1;
        min-width: 250px;
    }
    .footer-button {
        display: inline-block;
        background: #ff0000;
        color: white;
        padding: 10px 20px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: bold;
        margin-top: 10px;
    }
    .footer-bottom {
        background: #000000;
        color: white;
        padding: 10px 0;
        font-size: 14px;
        text-align: center;
    }
</style>
<footer class="footer">
    <div class="footer-column">
        <div class="footer-logo">
            <img src="{{ asset('logo_chamado.png') }}" alt="Logo Suporte Técnico" width="200">
        </div>
        <p>Nós cuidamos de toda a tecnologia enquanto você foca no seu negócio.</p>
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
            <a href="#"><i class="fab fa-whatsapp"></i></a>
        </div>
    </div>
    <div class="footer-column">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Serviços</a></li>
            <li><a href="#">Depoimentos</a></li>
            <li><a href="#">Quem somos?</a></li>
            <li><a href="#">Contato</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Política de Privacidade</a></li>
        </ul>
    </div>
    <div class="footer-column">
        <ul>
            <li>contato@gmail.com.br</li>
            <li>+55 (69) 4858-4850</li>
            <li>Rua Av. Carlos Gomes, nº 1046, 16º andar – Porto Velho/RO.</li>
        </ul>
        <a href="#" class="footer-button">Fale Conosco</a>
    </div>
</footer>
<div class="footer-bottom">
    ©2025 Todos os direitos reservados
</div>
</body>
</html>
