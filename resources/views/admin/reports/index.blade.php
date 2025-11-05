<x-app-layout>
    <x-slot name="header">
        {{-- ==== INÍCIO DA MODIFICAÇÃO ==== --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                {{-- Botão Voltar --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition duration-150 ease-in-out"
                   title="Voltar ao Painel Principal">
                    {{-- Ícone de Seta (SVG) --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>

                {{-- Título Original --}}
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Relatórios & Gráficos') }}
                </h2>
            </div>
            {{-- Espaço para outros botões se necessário no futuro --}}
        </div>
        {{-- ==== FIM DA MODIFICAÇÃO ==== --}}
    </x-slot>

    {{-- O id="report-content" é ótimo para o JS/CSS --}}
    <div id="report-content" class="container mx-auto px-4 py-6">

        {{-- SEÇÃO DE FILTROS --}}
        <div id="filters" class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm p-4 mb-6 grid grid-cols-1 md:grid-cols-7 gap-4">
            {{-- MELHORIA: Labels com font-medium para melhor legibilidade --}}
            <div>
                <label for="f_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select id="f_status" class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Todos --</option>
                    <option value="aberto">aberto</option>
                    <option value="andamento">andamento</option>
                    <option value="pendente">pendente</option>
                    <option value="resolvido">resolvido</option>
                </select>
            </div>
            <div>
                <label for="f_priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridade</label>
                <select id="f_priority" class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Todas --</option>
                    <option value="baixa">baixa</option>
                    <option value="media">média</option>
                    <option value="alta">alta</option>
                    <option value="muito alta">muito alta</option>
                </select>
            </div>
            <div>
                <label for="f_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                <select id="f_category" class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Todas --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select id="f_type" class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Todos --</option>
                    @foreach($types as $t)
                        <option value="{{ $t->id }}">{{ $t->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Período</label>
                <select id="f_period" class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Custom (usar datas)</option>
                    <option value="7d">Últimos 7 dias</option>
                    <option value="30d">Últimos 30 dias</option>
                    <option value="90d">Últimos 90 dias</option>
                    <option value="12m">Últimos 12 meses</option>
                </select>
            </div>
            <div>
                <label for="f_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">de</label>
                <input id="f_from" type="date" class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 focus:border-indigo-500 focus:ring-indigo-500" />
            </div>
            <div>
                <label for="f_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">até</label>
                <input id="f_to" type="date" class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 focus:border-indigo-500 focus:ring-indigo-500" />
            </div>

            {{-- MELHORIA: Botões com classes hover e transition para feedback visual --}}
            <div class="md:col-span-7 flex flex-wrap gap-2 justify-end mt-2">
                <button id="btnExportPDF" class="px-3 py-2 text-sm rounded-md bg-red-600 hover:bg-red-700 text-white transition duration-150 ease-in-out">Gerar PDF</button>
                <a id="btnExportTickets" href="{{ route('reports.export.tickets.csv') }}" class="px-3 py-2 text-sm rounded-md bg-indigo-600 hover:bg-indigo-700 text-white transition duration-150 ease-in-out">Export Tickets CSV</a>
                <a id="btnExportLogs" href="{{ route('reports.export.logs.csv') }}" class="px-3 py-2 text-sm rounded-md bg-slate-600 hover:bg-slate-700 text-white transition duration-150 ease-in-out">Export Logs CSV</a>
                <button id="btnApply" class="px-3 py-2 text-sm rounded-md bg-emerald-600 hover:bg-emerald-700 text-white transition duration-150 ease-in-out">Aplicar</button>
                <button id="btnClear" class="px-3 py-2 text-sm rounded-md bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-gray-200 transition duration-150 ease-in-out">Limpar</button>
            </div>
        </div>

        {{-- KPIs --}}
        @php
            $kpis = ['Total','Abertos','Abertos (Período)','Resolvidos','Overdue','SLA %','MTTR (h)'];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-7 gap-4 mb-6" id="kpis">
            @foreach ($kpis as $k)
                {{-- MELHORIA: Cards de KPI com estilo padronizado e hover --}}
                <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $k }}</div>
                    <div class="text-2xl font-semibold mt-1 text-gray-800 dark:text-gray-200"
                         data-kpi="{{ Str::of($k)->lower()->replace([' ','%','(período)','(h)'],'')->replace('í','i') }}">
                        —
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Gráficos de Tickets --}}
        {{-- MELHORIA: Cards de Gráfico padronizados com o estilo "Ranking & SLA" --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5"><h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Tickets por Status</h3><canvas id="ch_status"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5"><h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Tickets por Prioridade</h3><canvas id="ch_priority"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5"><h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Tickets por Categoria</h3><canvas id="ch_category"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5"><h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Tickets por Tipo</h3><canvas id="ch_type"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5 md:col-span-2"><h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Criados por Dia</h3><canvas id="ch_created"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5 md:col-span-2"><h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Resolvidos por Dia</h3><canvas id="ch_resolved"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5"><h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Envelhecimento (Backlog)</h3><canvas id="ch_aging"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5"><h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">SLA % por Mês</h3><canvas id="ch_sla"></canvas></div>
        </div>

        {{-- Ranking & SLA --}}
        {{-- Esta seção já tinha um ótimo estilo, apenas padronizei os <canvas> --}}
        <h2 class="text-xl font-semibold mt-10 mb-4 text-gray-800 dark:text-gray-200">Ranking & SLA</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-indigo-100 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 20h18M7 10v6m5-10v10m5-6v6"/></svg>
                    </span>
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Top Tipos de Serviços (Top 10)</h3>
                </div>
                <canvas id="ch_top_types" class="h-64 w-full"></canvas>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-rose-100 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h7"/></svg>
                    </span>
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Top Categorias (Top 10)</h3>
                </div>
                <canvas id="ch_top_categories_rank" class="h-64 w-full"></canvas>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M7 20H2v-2a4 4 0 014-4h1m10-6a4 4 0 11-8 0 4 4 0 018 0zM9 8a4 4 0 108 0"/></svg>
                    </span>
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Top Técnicos (Top 10)</h3>
                </div>
                <canvas id="ch_top_techs" class="h-64 w-full"></canvas>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11 2a10 10 0 109.95 8h-8.2A1.75 1.75 0 0111 8.25V2z"/></svg>
                    </span>
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Dentro do Prazo x Vencidos (SLA)</h3>
                </div>
                <canvas id="ch_sla_pie" class="h-64 w-full"></canvas>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-sky-100 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18M7 8l3 3-3 3m7 0h6"/></svg>
                    </span>
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Top Prioridades</h3>
                </div>
                <canvas id="ch_top_priorities" class="h-64 w-full"></canvas>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 shadow-sm hover:shadow-md transition p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-gray-100 text-gray-600 dark:bg-zinc-700/40 dark:text-zinc-300">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M8 12h8"/></svg>
                    </span>
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Técnicos Ociosos (no período)</h3>
                </div>
                {{-- MELHORIA: Adicionando classes de texto padrão para a lista que será populada pelo JS --}}
                <ul id="idle_list" class="text-sm list-disc pl-5 marker:text-gray-400 dark:marker:text-zinc-500 space-y-1 text-gray-700 dark:text-gray-300">
                    {{-- O JavaScript irá popular esta lista --}}
                </ul>
            </div>
        </div>

        {{-- Gráficos de Logs (Mantido comentado como no original)
        <h2 class="text-xl font-semibold mt-10 mb-4">Logs do Sistema</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Top Ações</h3><canvas id="lg_actions"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Logs por Dia</h3><canvas id="lg_byday"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Top Usuários</h3><canvas id="lg_users"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Top Rotas</h3><canvas id="lg_routes"></canvas></div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4 md:col-span-2"><h3 class="font-medium mb-2">Métodos HTTP</h3><canvas id="lg_methods"></canvas></div>
        </div> --}}

    </div> {{-- Fim do #report-content --}}

    {{-- O seu script JS que controla tudo isto --}}
    @vite(['resources/js/reports.js'])
</x-app-layout>
