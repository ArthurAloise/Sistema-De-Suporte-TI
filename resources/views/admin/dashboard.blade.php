@extends('admin.layouts.app')

@section('content')
    @push('styles')
        <style>
            :root{
                --card-radius: 1rem;
            }
            /* Acessibilidade: respeita usuários que preferem menos animação */
            @media (prefers-reduced-motion: reduce) {
                .sla-overdue { animation: none !important; }
            }
            @keyframes blink-red { 0%,100% { background:#fff; } 50% { background:#ffe5e5; } }
            .sla-overdue { animation: blink-red 1s linear infinite; }
            .sla-overdue td, .sla-overdue .badge { color:#b00020 !important; }

            /* Cartões com leve gradiente */
            .card-hero {
                border: 0;
                border-radius: var(--card-radius);
                overflow: hidden;
            }
            /*.bg-primary-grad   { background: linear-gradient(135deg, #0d6efd, #5aa3ff); }*/
            /*.bg-warning-grad   { background: linear-gradient(135deg, #ffc107, #ffd761); color:#1f2d3d; }*/
            /*.bg-success-grad   { background: linear-gradient(135deg, #198754, #52c789); }*/
            /*.bg-dark-grad      { background: linear-gradient(135deg, #212529, #3a3f44); }*/
            /*.bg-danger-grad    { background: linear-gradient(135deg, #dc3545, #ff6b72); }*/
            /*.bg-secondary-grad { background: linear-gradient(135deg, #6c757d, #a2a9af); }*/

            .card-hero .icon-wrap { font-size: 2.25rem; opacity:.9; }
            .table thead th { position: sticky; top: 0; z-index: 1; }
            .table-hover tbody tr:hover { background:#f8f9fa; }

            /* Celulas mais legíveis em telas estreitas */
            .cell-truncate { max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            @media (max-width: 576px){
                .cell-truncate { max-width: 140px; }
            }
        </style>
    @endpush

    <div class="container-fluid mt-3 mt-md-4">
        <!-- Título -->
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
            <div>
                <h1 class="fw-bold text-danger mb-1">Painel Administrativo</h1>
                <p class="text-muted mb-0">Gerencie usuários, chamados e SLAs.</p>
            </div>
            {{--            <a href="{{ route('users.index') }}" class="btn btn-outline-dark">--}}
            {{--                <i class="fas fa-cogs me-2"></i> Administração--}}
            {{--            </a>--}}
        </div>

        <!-- KPIs / Cards (100% responsivo) -->
        <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-3">
            @if(auth()->user()->hasRole('Admin'))
                <div class="col">
                    <div class="card card-hero bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrap"><i class="fas fa-users"></i></div>
                            <div class="mt-2 fw-semibold">Usuários</div>
                            <p class="mb-0">Gerenciar Usuários</p>
                            {{--                        <div class="display-6 fw-bold">{{ $userCount }}</div>--}}
                            <a href="{{ route('users.index') }}" class="btn btn-light w-100 mt-3">Ver Usuários</a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card card-hero bg-success text-white h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrap"><i class="fas fa-chart-line"></i></div>
                            <div class="mt-2 fw-semibold">Relatórios</div>
                            <p class="mb-0">Gráficos</p>
                            <a href="#" class="btn btn-light w-100 mt-3">Ver Relatórios</a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card card-hero bg-dark text-white h-100">
                        <div class="card-body text-center">
                            <div class="icon-wrap"><i class="fas fa-shield-alt"></i></div>
                            <div class="mt-2 fw-semibold">Perfis</div>
                            <p class="mb-0">Gerencie papéis</p>
                            <a href="{{ route('roles.index') }}" class="btn btn-light w-100 mt-3">Ver Perfis</a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col">
                <div class="card card-hero bg-warning h-100">
                    <div class="card-body text-center">
                        <div class="icon-wrap"><i class="fas fa-clipboard-list"></i></div>
                        <div class="mt-2 fw-semibold">Tickets</div>
                        <p class="mb-0"> Abertos/Andamento</p>
                        <div class="display-6 fw-bold">{{ $openTicketsCount }}</div>
{{--                        <a href="{{ route('tickets.index') }}" class="btn btn-dark w-100 mt-3">Gerenciar</a>--}}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card card-hero bg-danger text-white h-100">
                    <div class="card-body text-center">
                        <div class="icon-wrap"><i class="fas fa-hourglass-end"></i></div>
                        <div class="mt-2 fw-semibold">Tickets</div>
                        <p class="mb-0"> SLA Vencidos</p>
                        <div class="display-6 fw-bold">{{ $slaOverdueCount }}</div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card card-hero bg-secondary text-white h-100">
                    <div class="card-body text-center">
                        <div class="icon-wrap"><i class="fas fa-hourglass-half"></i></div>
                        <div class="mt-2 fw-semibold">Tickets</div>
                        <p class="mb-0"> Vencem em 24h</p>
                        <div class="display-6 fw-bold">{{ $slaDue24hCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações rápidas -->
        <div class="mt-4">
            <div class="row g-3 row-cols-2 row-cols-sm-3 row-cols-lg-6">
                @if(auth()->user()->hasRole('Admin'))
                    <div class="col">
                        <a href="{{ route('types.index') }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="fw-bold mb-2">Tipos</h6>
                                    <span class="btn btn-outline-primary w-100">Ver</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('categories.index') }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="fw-bold mb-2">Categorias</h6>
                                    <span class="btn btn-outline-success w-100">Ver</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('setores.index') }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="fw-bold mb-2">Setores</h6>
                                    <span class="btn btn-outline-dark w-100">Ver</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('admin.logs') }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="fw-bold mb-2">Logs</h6>
                                    <span class="btn btn-outline-warning w-100">Abrir</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('permissions.index') }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="fw-bold mb-2">Permissões</h6>
                                    <span class="btn btn-outline-danger w-100">Ver</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                <div class="col">
                    <a href="{{ route('users.create') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="fw-bold mb-2">Novo Usuário</h6>
                                <span class="btn btn-outline-secondary w-100">Criar</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Chamados Recentes -->
        <div class="mt-4">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h4 class="fw-bold mb-0 text-dark">Chamados Recentes</h4>
                    <button class="btn btn-outline-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                        <i class="fas fa-filter me-2"></i>Filtros
                    </button>
                </div>

                <div id="filtersCollapse" class="collapse d-md-block">
                    <div class="card-body">
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-6 col-md-2">
                                <label for="ticket_id" class="form-label mb-1">ID</label>
                                <input type="number" class="form-control" id="ticket_id" name="ticket_id" value="{{ $ticketId }}">
                            </div>

                            <div class="col-6 col-md-2">
                                <label for="status" class="form-label mb-1">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="">Todos</option>
                                    @foreach(['aberto'=>'Aberto','andamento'=>'Em Andamento','pendente'=>'Pendente','resolvido'=>'Resolvido','fechado'=>'Fechado'] as $k=>$v)
                                        <option value="{{ $k }}" @selected($status===$k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-2">
                                <label for="priority" class="form-label mb-1">Prioridade</label>
                                <select id="priority" name="priority" class="form-select">
                                    <option value="">Todas</option>
                                    @foreach(['baixa','media','alta','muito alta'] as $p)
                                        <option value="{{ $p }}" @selected($priority===$p)>{{ ucfirst($p) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-2">
                                <label class="form-label mb-1">SLA</label>
                                <select name="sla" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="overdue" @selected($sla==='overdue')>Atrasados</option>
                                    <option value="due24h"  @selected($sla==='due24h')>Vencem em 24h</option>
                                </select>
                            </div>

                            {{--                            <div class="col-6 col-md-2">--}}
                            {{--                                <label for="date_from" class="form-label mb-1">De</label>--}}
                            {{--                                <input type="date" id="date_from" name="date_from" class="form-control" value="{{ $dateFrom }}">--}}
                            {{--                            </div>--}}

                            {{--                            <div class="col-6 col-md-2">--}}
                            {{--                                <label for="date_to" class="form-label mb-1">Até</label>--}}
                            {{--                                <input type="date" id="date_to" name="date_to" class="form-control" value="{{ $dateTo }}">--}}
                            {{--                            </div>--}}

                            <div class="col-6 col-md-2">
                                <label for="per_page" class="form-label mb-1">Por página</label>
                                <select id="per_page" name="per_page" class="form-select">
                                    @foreach([5,10,15,25,50] as $n)
                                        <option @selected($perPage==$n)>{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-search me-2"></i>Pesquisar
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary flex-fill">
                                    <i class="fas fa-redo me-2"></i>Limpar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    @php
                        $priorityColors = ['muito alta'=>'danger','alta'=>'warning','media'=>'primary','baixa'=>'secondary'];
                    @endphp
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th class="w-25">Título</th>
                            <th class="d-none d-lg-table-cell">Criado em</th>
                            <th>Prazo (SLA)</th>
                            <th class="d-none d-sm-table-cell">Prioridade</th>
                            <th class="d-none d-xl-table-cell">Usuário</th>
                            <th class="d-none d-lg-table-cell">Categoria</th>
                            <th class="d-none d-lg-table-cell">Tipo</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tickets as $ticket)
                            @php
                                // mapa de cor por prioridade
                                $priorityColors = [
                                    'muito alta' => 'danger',
                                    'alta'       => 'warning',
                                    'media'      => 'primary',
                                    'baixa'      => 'secondary',
                                ];
                                $pColor = $priorityColors[strtolower($ticket->prioridade ?? '')] ?? 'secondary';

                                // SLA / overdue
                                $now        = now();
                                $hasDue     = !is_null($ticket->due_at);
                                $isResolved = !is_null($ticket->resolved_at);
                                $overdue    = !$isResolved && $hasDue && $now->gt($ticket->due_at)
                                              && in_array($ticket->status, ['aberto','andamento','pendente']);
                            @endphp
                            <tr class="{{ $overdue ? 'sla-overdue' : '' }}">
                                <td>#{{ $ticket->id }}</td>
                                <td class="cell-truncate" title="{{ $ticket->titulo }}">{{ $ticket->titulo }}</td>
                                <td class="d-none d-lg-table-cell">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($hasDue)
                                        <div class="fw-semibold">
                                            {{ $ticket->due_at->format('d/m H:i') }}
                                        </div>
                                        <div class="small">
                                            @if($isResolved)
                                                @php
                                                    $diffSecs  = abs($ticket->resolved_at->diffInSeconds($ticket->due_at));
                                                    $diffHuman = \Carbon\CarbonInterval::seconds($diffSecs)->cascade()->forHumans(short: true, parts: 2);
                                                @endphp

                                                @if($ticket->resolved_at->lte($ticket->due_at))
                                                    <span class="badge bg-success">Resolvido dentro do SLA</span>
                                                    <span class="text-muted ms-1">({{ $diffHuman }} de antecedência)</span>
                                                @else
                                                    <span class="badge bg-danger">Resolvido após o SLA</span>
                                                    <span class="text-muted ms-1">(atraso de {{ $diffHuman }})</span>
                                                @endif
                                            @else
                                                @if($now->lte($ticket->due_at))
                                                    <span class="badge bg-success">
                            Vence em {{ $now->diffForHumans($ticket->due_at, ['parts'=>2, 'short'=>true]) }}
                        </span>
                                                @else
                                                    <span class="badge bg-danger">
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
                                <td>
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-dark">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center text-muted py-4">Nenhum chamado encontrado.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                    <div class="card-footer">
                        {{ $tickets->links('pagination::bootstrap-4') }}
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
        </script>
    @endpush
@endsection
