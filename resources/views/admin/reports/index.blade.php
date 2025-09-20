<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Relatórios & Gráficos') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
      {{-- Filtros (somente inputs) --}}
      <div id="filters" class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-6 gap-3">
        <div>
          <label class="block text-sm mb-1">Status</label>
          <select id="f_status" class="w-full rounded-md border-gray-300 dark:bg-zinc-800">
            <option value="">-- Todos --</option>
            <option value="aberto">aberto</option>
            <option value="andamento">andamento</option>
            <option value="pendente">pendente</option>
            <option value="resolvido">resolvido</option>
          </select>
        </div>
        <div>
          <label class="block text-sm mb-1">Prioridade</label>
          <select id="f_priority" class="w-full rounded-md border-gray-300 dark:bg-zinc-800">
            <option value="">-- Todas --</option>
            <option value="baixa">baixa</option>
            <option value="media">média</option>
            <option value="alta">alta</option>
            <option value="muito alta">muito alta</option>
          </select>
        </div>
        <div>
          <label class="block text-sm mb-1">Categoria</label>
          <select id="f_category" class="w-full rounded-md border-gray-300 dark:bg-zinc-800">
            <option value="">-- Todas --</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}">{{ $c->nome }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm mb-1">Tipo</label>
          <select id="f_type" class="w-full rounded-md border-gray-300 dark:bg-zinc-800">
            <option value="">-- Todos --</option>
            @foreach($types as $t)
              <option value="{{ $t->id }}">{{ $t->nome }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm mb-1">de</label>
          <input id="f_from" type="date" class="w-full rounded-md border-gray-300 dark:bg-zinc-800" />
        </div>
        <div>
          <label class="block text-sm mb-1">até</label>
          <input id="f_to" type="date" class="w-full rounded-md border-gray-300 dark:bg-zinc-800" />
        </div>
        <div class="md:col-span-6 flex gap-2 justify-end">
          <a id="btnExportTickets" href="{{ route('reports.export.tickets.csv') }}" class="px-3 py-2 text-sm rounded-md bg-indigo-600 text-white">Export Tickets CSV</a>
          <a id="btnExportLogs" href="{{ route('reports.export.logs.csv') }}" class="px-3 py-2 text-sm rounded-md bg-slate-600 text-white">Export Logs CSV</a>
          <button id="btnApply" class="px-3 py-2 text-sm rounded-md bg-emerald-600 text-white">Aplicar</button>
          <button id="btnClear" class="px-3 py-2 text-sm rounded-md bg-gray-200 dark:bg-zinc-800">Limpar</button>
        </div>
      </div>

      {{-- KPIs --}}
      <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6" id="kpis">
        @foreach (['Total','Abertos','Resolvidos','Overdue','SLA %','MTTR (h)'] as $k)
          <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-3">
            <div class="text-xs text-gray-500">{{ $k }}</div>
            <div class="text-2xl font-semibold mt-1" data-kpi="{{ strtolower(str_replace([' ','%','(h)'],'',$k)) }}">—</div>
          </div>
        @endforeach
      </div>

      {{-- Gráficos de Tickets --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Tickets por Status</h3><canvas id="ch_status"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Tickets por Prioridade</h3><canvas id="ch_priority"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Tickets por Categoria</h3><canvas id="ch_category"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Tickets por Tipo</h3><canvas id="ch_type"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4 md:col-span-2"><h3 class="font-medium mb-2">Criados por Dia</h3><canvas id="ch_created"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4 md:col-span-2"><h3 class="font-medium mb-2">Resolvidos por Dia</h3><canvas id="ch_resolved"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Envelhecimento (Backlog)</h3><canvas id="ch_aging"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">SLA % por Mês</h3><canvas id="ch_sla"></canvas></div>
      </div>

      {{-- Gráficos de Logs --}}
      <h2 class="text-xl font-semibold mt-10 mb-4">Logs do Sistema</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Top Ações</h3><canvas id="lg_actions"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Logs por Dia</h3><canvas id="lg_byday"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Top Usuários</h3><canvas id="lg_users"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4"><h3 class="font-medium mb-2">Top Rotas</h3><canvas id="lg_routes"></canvas></div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4 md:col-span-2"><h3 class="font-medium mb-2">Métodos HTTP</h3><canvas id="lg_methods"></canvas></div>
      </div>
    </div>

    {{-- JS específico desta página --}}
    @vite(['resources/js/reports.js'])
</x-app-layout>
