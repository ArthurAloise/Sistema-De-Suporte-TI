@extends('admin.layouts.app')

@section('content')
    @push('styles')
        <style>
            :root{
                --card-radius: 0.75rem; /* Bordas mais suaves */
            }

            /* Animação para SLA Vencido */
            @media (prefers-reduced-motion: reduce) {
                .sla-overdue { animation: none !important; }
            }
            @keyframes blink-red { 0%,100% { background:#fff; } 50% { background:#ffe5e5; } }
            .sla-overdue { animation: blink-red 1s linear infinite; }
            .sla-overdue td, .sla-overdue .badge { color:#b00020 !important; }

            /* Estilo dos Cards KPI */
            .card-hero {
                border: 0;
                border-radius: var(--card-radius);
                transition: transform 0.3s ease;
                min-height: 180px; /* Garante altura mínima para alinhamento */
            }
            .card-hero:hover {
                transform: translateY(-4px);
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            }
            .card-hero .icon-wrap {
                font-size: 3rem; /* Ícones maiores */
                opacity: 0.9;
                margin-bottom: 0.5rem;
            }
            .card-hero .card-body {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            /* Estilo para Títulos de Ações Rápidas */
            .card-quick-action {
                transition: box-shadow 0.2s;
                height: 100%;
            }
            .card-quick-action:hover {
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.15) !important;
            }

            /* Tabela */
            .table-responsive {
                border-bottom-left-radius: var(--card-radius);
                border-bottom-right-radius: var(--card-radius);
                overflow: hidden;
            }
            .table thead th {
                position: sticky;
                top: 0;
                z-index: 10;
            }
            .cell-truncate { max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        </style>
    @endpush

    <div class="container-fluid py-4">
        <div class="mb-5">
            <h1 class="fw-bolder text-primary mb-1">Painel Administrativo</h1>
            <p class="text-muted fs-5 mb-0">Gerencie usuários, chamados e SLAs do sistema de TI.</p>
        </div>

        <div class="row g-4 mb-5">

            {{-- Linha 1: Cards de Navegação --}}
            @if(auth()->user()->hasRole('Admin'))
                <div class="col-md-4">
                    <div class="card card-hero bg-primary text-white shadow-lg">
                        <div class="card-body text-center p-4">
                            <div class="icon-wrap"><i class="fas fa-users"></i></div>
                            <h5 class="fw-bold mb-1">Usuários</h5>
                            <p class="mb-3 small opacity-75">Gerenciar Usuários</p>
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm fw-bold w-75">Ver Usuários</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-hero bg-success text-white shadow-lg">
                        <div class="card-body text-center p-4">
                            <div class="icon-wrap"><i class="fas fa-chart-line"></i></div>
                            <h5 class="fw-bold mb-1">Relatórios</h5>
                            <p class="mb-3 small opacity-75">Gráficos e Métricas</p>
                            <a href="{{ route('reports.index') }}" class="btn btn-light btn-sm fw-bold w-75">Ver Relatórios</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-hero bg-dark text-white shadow-lg">
                        <div class="card-body text-center p-4">
                            <div class="icon-wrap"><i class="fas fa-shield-alt"></i></div>
                            <h5 class="fw-bold mb-1">Perfis</h5>
                            <p class="mb-3 small opacity-75">Gerencie papéis e permissões</p>
                            <a href="{{ route('roles.index') }}" class="btn btn-light btn-sm fw-bold w-75">Ver Perfis</a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Linha 2: Cards de Estatísticas (KPIs) --}}
            <div class="col-md-4">
                <div class="card card-hero bg-warning shadow-lg text-dark">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrap"><i class="fas fa-clipboard-list text-warning"></i></div>
                        <h5 class="fw-semibold mb-1">Tickets Abertos/Andamento</h5>
                        <div class="display-4 fw-bolder mt-2">{{ $openTicketsCount }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-hero bg-danger text-white shadow-lg">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrap"><i class="fas fa-hourglass-end text-danger"></i></div>
                        <h5 class="fw-semibold mb-1">Tickets SLA Vencidos</h5>
                        <div class="display-4 fw-bolder mt-2">{{ $slaOverdueCount }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-hero bg-secondary text-white shadow-lg">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrap"><i class="fas fa-hourglass-half text-secondary"></i></div>
                        <h5 class="fw-semibold mb-1">Tickets Vencem em 24h</h5>
                        <div class="display-4 fw-bolder mt-2">{{ $slaDue24hCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="fw-bold text-dark mb-3 mt-4">Ações Rápidas de Configuração</h4>
        <div class="row g-3 row-cols-2 row-cols-sm-3 row-cols-lg-6 mb-5">

            {{-- Ações Rápidas (Agora em Cards/Links com estilo limpo) --}}
            @php
                $quickActions = [
                    ['route' => 'types.index', 'name' => 'Tipos', 'color' => 'primary', 'action' => 'Ver'],
                    ['route' => 'categories.index', 'name' => 'Categorias', 'color' => 'success', 'action' => 'Ver'],
                    ['route' => 'setores.index', 'name' => 'Setores', 'color' => 'dark', 'action' => 'Ver'],
                    ['route' => 'admin.logs', 'name' => 'Logs', 'color' => 'warning', 'action' => 'Abrir'],
                    ['route' => 'permissions.index', 'name' => 'Permissões', 'color' => 'danger', 'action' => 'Ver'],
                    ['route' => 'users.create', 'name' => 'Novo Usuário', 'color' => 'info', 'action' => 'Criar'],
                ];
            @endphp

            @foreach($quickActions as $action)
                @if(auth()->user()->hasRole('Admin') || $action['name'] === 'Novo Usuário') {{-- Mantendo a lógica de permissão --}}
                <div class="col">
                    <a href="{{ route($action['route']) }}" class="text-decoration-none d-block h-100">
                        <div class="card h-100 card-quick-action shadow-sm border-0">
                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                <h6 class="fw-bold text-dark mb-2">{{ $action['name'] }}</h6>
                                <span class="btn btn-outline-{{ $action['color'] }} fw-bold w-100 mt-auto">{{ $action['action'] }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
            @endforeach
        </div>

<div class="mt-5">
            <div class="card shadow-lg border-0 rounded-4"> <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between py-3 px-4 border-bottom-0">
                    <h4 class="fw-bolder mb-0 text-dark">Chamados Recentes</h4> <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                        <i class="fas fa-filter me-2"></i>Filtros
                    </button>
                </div>

                <div id="filtersCollapse" class="collapse show d-md-block border-bottom">
                    <div class="card-body pb-3 px-4">
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 align-items-end">

                            {{-- Linha 1 de Filtros --}}
                            <div class="col-12 d-flex flex-wrap gap-3">
                                <div class="col-md-2">
                                    <label for="ticket_id" class="form-label mb-1 small fw-bold">ID</label>
                                    <input type="number" class="form-control form-control-sm" id="ticket_id" name="ticket_id" value="{{ $ticketId }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="status" class="form-label mb-1 small fw-bold">Status</label>
                                    <select id="status" name="status" class="form-select form-select-sm">
                                        <option value="">Todos</option>
                                        @foreach(['aberto'=>'Aberto','andamento'=>'Em Andamento','pendente'=>'Pendente','resolvido'=>'Resolvido','fechado'=>'Fechado'] as $k=>$v)
                                            <option value="{{ $k }}" @selected($status===$k)>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="priority" class="form-label mb-1 small fw-bold">Prioridade</label>
                                    <select id="priority" name="priority" class="form-select form-select-sm">
                                        <option value="">Todas</option>
                                        @foreach(['baixa','media','alta','muito alta'] as $p)
                                            <option value="{{ $p }}" @selected($priority===$p)>{{ ucfirst($p) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label mb-1 small fw-bold">SLA</label>
                                    <select name="sla" class="form-select form-select-sm">
                                        <option value="">Todos</option>
                                        <option value="overdue" @selected($sla==='overdue')>Atrasados</option>
                                        <option value="due24h" @selected($sla==='due24h')>Vencem em 24h</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="per_page" class="form-label mb-1 small fw-bold">Por página</label>
                                    <select id="per_page" name="per_page" class="form-select form-select-sm">
                                        @foreach([5,10,15,25,50] as $n)
                                            <option @selected($perPage==$n)>{{ $n }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Botões de Ação --}}
                                <div class="col-12 col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-bold flex-fill mt-3 mt-md-0">
                                        <i class="fas fa-search me-1"></i>Pesquisar
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary flex-fill mt-3 mt-md-0">
                                        <i class="fas fa-undo me-1"></i>Limpar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0"> <thead class="table-light sticky-top shadow-sm"> <tr>
                            <th class="ps-4">ID</th>
                            <th class="w-25">Título</th>
                            <th class="d-none d-lg-table-cell">Criado em</th>
                            <th>Prazo (SLA)</th>
                            <th class="d-none d-sm-table-cell">Prioridade</th>
                            <th class="d-none d-xl-table-cell">Usuário</th>
                            <th class="d-none d-lg-table-cell">Categoria</th>
                            <th class="d-none d-lg-table-cell">Tipo</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th> </tr>
                        </thead>
                        <tbody>
                        @forelse($tickets as $ticket)
                            @php
                                // Configuração de cor para prioridade
                                $priorityColors = ['muito alta' => 'danger', 'alta' => 'warning', 'media' => 'primary', 'baixa' => 'info'];
                                $pColor = $priorityColors[strtolower($ticket->prioridade ?? '')] ?? 'secondary';

                                // Lógica de Overdue (SLA Vencido)
                                $now = now();
                                $hasDue = !is_null($ticket->due_at);
                                $isResolved = !is_null($ticket->resolved_at);
                                $overdue = !$isResolved && $hasDue && $now->gt($ticket->due_at)
                                            && in_array($ticket->status, ['aberto','andamento','pendente']);
                            @endphp
                            <tr class="{{ $overdue ? 'sla-overdue' : '' }}">
                                <td class="ps-4 fw-bold text-dark">#{{ $ticket->id }}</td>
                                <td class="cell-truncate text-wrap" title="{{ $ticket->titulo }}">{{ $ticket->titulo }}</td> <td class="d-none d-lg-table-cell">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($hasDue)
                                        <div class="fw-semibold small">
                                            {{ $ticket->due_at->format('d/m H:i') }}
                                        </div>
                                        <div class="small mt-1">
                                            @if($isResolved)
                                                @php
                                                    $diffSecs = abs($ticket->resolved_at->diffInSeconds($ticket->due_at));
                                                    $diffHuman = \Carbon\CarbonInterval::seconds($diffSecs)->cascade()->forHumans(short: true, parts: 2);
                                                @endphp

                                                @if($ticket->resolved_at->lte($ticket->due_at))
                                                    <span class="badge bg-success border border-success border-opacity-50">Resolvido dentro do SLA</span>
                                                    <span class="text-muted ms-1">({{ $diffHuman }} ant.)</span>
                                                @else
                                                    <span class="badge bg-danger border border-danger border-opacity-50">Resolvido após o SLA</span>
                                                    <span class="text-muted ms-1">(atraso {{ $diffHuman }})</span>
                                                @endif
                                            @else
                                                @if($now->lte($ticket->due_at))
                                                    <span class="badge bg-success border border-success border-opacity-50">
                                                        Vence em {{ $now->diffForHumans($ticket->due_at, ['parts'=>2, 'short'=>true]) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger border border-danger border-opacity-50">
                                                        SLA estourado há {{ $ticket->due_at->diffForHumans($now, ['parts'=>2, 'short'=>true]) }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <span class="badge text-capitalize bg-{{ $pColor }}">{{ $ticket->prioridade }}</span>
                                </td>
                                <td class="d-none d-xl-table-cell">{{ $ticket->usuario->name }}</td>
                                <td class="d-none d-lg-table-cell">{{ $ticket->category->nome }}</td>
                                <td class="d-none d-lg-table-cell">{{ $ticket->type->nome }}</td>
                                <td>
                                    @switch($ticket->status)
                                        @case('aberto')     <span class="badge bg-warning text-dark">Aberto</span> @break
                                        @case('andamento')  <span class="badge bg-primary">Andamento</span> @break
                                        @case('pendente')   <span class="badge bg-secondary">Pendente</span> @break
                                        @case('fechado')    <span class="badge bg-dark">Fechado</span> @break
                                        @default            <span class="badge bg-success">Resolvido</span>
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Ver Detalhes">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center text-muted py-4"><i class="fas fa-info-circle me-2"></i>Nenhum chamado encontrado.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $tickets->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-submit quando mudar o per_page
            document.getElementById('per_page')?.addEventListener('change', function(){
                this.form?.submit();
            });

            // Adicionado para manter os cabeçalhos fixos no card, se a tabela for muito longa
            // Isso requer que a tabela esteja dentro de um div com altura limitada e overflow-y: scroll;
            // No entanto, para fins de melhoria de UX, manter a classe sticky-top no thead é suficiente no layout atual.
        </script>
    @endpush
@endsection
