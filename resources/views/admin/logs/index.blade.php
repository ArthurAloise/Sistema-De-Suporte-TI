@extends('admin.layouts.app')

@section('content')
    {{-- Estilos CSS Refinados --}}
    @push('styles')
    <style>
        /* Timeline Principal */
        .timeline {
            position: relative;
            padding-left: 35px; /* Mais espaço */
            border-left: 4px solid #e9ecef; /* Linha principal mais espessa */
            margin-left: 15px;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 35px; /* Mais espaço entre itens */
            padding-left: 25px;
        }
        .timeline-item:before { /* Ponto colorido */
            content: '';
            position: absolute;
            left: -48px; /* Ajustado para centralizar na linha mais espessa */
            top: 5px;
            width: 20px; /* Ponto maior */
            height: 20px;
            border-radius: 50%;
            background: var(--bs-primary);
            border: 4px solid #f8f9fa; /* Borda mais clara (cinza claro) */
            z-index: 10;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        }
        /* Cores dos pontos (mantido) */
        .timeline-item[data-action="LOGIN"]::before { background: var(--bs-info); }
        .timeline-item[data-action="LOGOUT"]::before { background: var(--bs-secondary); }
        .timeline-item[data-action="CREATE"]::before { background: var(--bs-success); }
        .timeline-item[data-action="UPDATE"]::before { background: var(--bs-warning); }
        .timeline-item[data-action="DELETE"]::before { background: var(--bs-danger); }
        .timeline-item[data-action="ACCESS"]::before { background: #6f42c1; }

        /* Conteúdo do Log */
        .timeline-content {
            background: #ffffff;
            padding: 1.25rem 1.5rem; /* Padding generoso */
            border-radius: 0.8rem; /* Cantos mais arredondados */
            border: 1px solid #e0e5ec; /* Borda sutil */
            box-shadow: 0 6px 12px rgba(0,0,0,0.07); /* Sombra mais suave */
            transition: box-shadow 0.2s ease-in-out;
        }
        .timeline-content:hover {
             box-shadow: 0 8px 16px rgba(0,0,0,0.1); /* Sombra ao passar o mouse */
        }

        /* Detalhes (pre/code) */
        .log-details-section {
             background-color: #f8f9fa; /* Fundo cinza claro */
             border: 1px solid #e9ecef;
             border-radius: 0.5rem;
        }
        .log-details-section strong {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9em;
            color: #495057; /* Cinza escuro */
            border-bottom: 1px dashed #ced4da;
            padding-bottom: 0.3rem;
        }
        pre {
            background: #ffffff; /* Fundo branco para contraste */
            padding: 12px 15px;
            border-radius: 4px;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            font-size: 0.78rem;
            white-space: pre-wrap;
            word-break: break-all;
            margin-bottom: 0; /* Remover margem inferior padrão */
        }
        code { font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; color: #343a40; }

        /* Ícones e Metadados */
        .log-meta-item { display: inline-flex; align-items: center; gap: 0.4rem; color: #6c757d; }
        .log-meta-item .fas, .log-meta-item .far { opacity: 0.7; font-size: 0.9em; }

        /* Botão Detalhes */
        .btn-show-details { font-size: 0.8rem; padding: 0.2rem 0.6rem; }
        .btn-show-details .fas { transition: transform 0.2s ease-in-out; }
        .btn-show-details[aria-expanded="true"] .fa-chevron-down { transform: rotate(180deg); }

        /* Placeholder Estados Vazios */
        .empty-state { background-color: #f8f9fa; border: 1px dashed #ced4da; }
        .empty-state i { color: var(--bs-primary); opacity: 0.4; }

    </style>
    @endpush

    <div class="container-fluid py-4">

        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
             <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary border-0 p-1" title="Voltar ao Painel Principal">
                    <i class="fas fa-arrow-left fs-4 lh-1"></i>
                </a>
                <div>
                    <h1 class="fw-bolder text-primary mb-0">Logs do Sistema</h1>
                    <p class="text-muted fs-6 mb-0">Monitore as atividades realizadas no sistema.</p>
                </div>
            </div>
             {{-- Exportar --}}
             {{-- <a href="{{ route('reports.export.logs.csv', request()->query()) }}" class="btn btn-outline-info fw-bold shadow-sm mt-2 mt-md-0">
                 <i class="fas fa-file-csv me-1"></i> Exportar (CSV)
             </a> --}}
        </div>

        <div class="card shadow-lg border-0 rounded-4 mb-4">
            <div class="card-header bg-light border-bottom-0 pt-3 pb-0">
                 <h5 class="mb-2 fw-bold text-dark"><i class="fas fa-filter me-2 text-primary opacity-75"></i>Filtros Avançados</h5>
            </div>
            <div class="card-body pt-2 pb-3"> {{-- Reduzido padding bottom --}}
                <form action="{{ route('admin.logs') }}" method="GET" class="row g-3 align-items-end">
                    {{-- Inputs com tamanho padrão para melhor toque/leitura --}}
                    <div class="col-md-3">
                        <label for="user_id" class="form-label small fw-bold mb-1">Usuário</label>
                        <select name="user_id" id="user_id" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            @foreach($users as $user) <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="action" class="form-label small fw-bold mb-1">Ação</label>
                        <select name="action" id="action" class="form-select form-select-sm">
                            <option value="">Todas</option>
                            <option value="LOGIN" @selected(request('action') == 'LOGIN')>Login</option>
                            <option value="LOGOUT" @selected(request('action') == 'LOGOUT')>Logout</option>
                            <option value="CREATE" @selected(request('action') == 'CREATE')>Criação</option>
                            <option value="UPDATE" @selected(request('action') == 'UPDATE')>Atualização</option>
                            <option value="DELETE" @selected(request('action') == 'DELETE')>Exclusão</option>
                            <option value="ACCESS" @selected(request('action') == 'ACCESS')>Acesso</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_start" class="form-label small fw-bold mb-1">Data Inicial</label>
                        <input type="date" class="form-control form-control-sm" id="date_start" name="date_start" value="{{ request('date_start') }}">
                    </div>
                     <div class="col-md-2">
                        <label for="date_end" class="form-label small fw-bold mb-1">Data Final</label>
                        <input type="date" class="form-control form-control-sm" id="date_end" name="date_end" value="{{ request('date_end') }}">
                    </div>
                     <div class="col-md-3">
                        <label for="method" class="form-label small fw-bold mb-1">Método HTTP</label>
                        <select name="method" id="method" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            <option value="GET" @selected(request('method') == 'GET')>GET</option>
                            <option value="POST" @selected(request('method') == 'POST')>POST</option>
                            <option value="PUT" @selected(request('method') == 'PUT')>PUT</option>
                            <option value="DELETE" @selected(request('method') == 'DELETE')>DELETE</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="route" class="form-label small fw-bold mb-1">Rota (contém)</label>
                        <input type="text" class="form-control form-control-sm" id="route" name="route" value="{{ request('route') }}" placeholder="Ex: tickets, admin/users">
                    </div>
                    <div class="col-md-4">
                        <label for="ip" class="form-label small fw-bold mb-1">Endereço IP</label>
                        <input type="text" class="form-control form-control-sm" id="ip" name="ip" value="{{ request('ip') }}" placeholder="Ex: 192.168.1.100">
                    </div>
                    <div class="col-md-3 d-flex gap-2 mt-3 mt-md-auto"> {{-- Botões com margem ajustada --}}
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-search me-1"></i> Filtrar
                        </button>
                        <a href="{{ route('admin.logs') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                            <i class="fas fa-undo me-1"></i> Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-lg border-0 rounded-4">
             <div class="card-header bg-white border-bottom pt-3 pb-2 d-flex justify-content-between align-items-center">
                 <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2 text-primary opacity-75"></i>Registros de Atividade</h5>
                 @if($hasSearch && $logs instanceof \Illuminate\Pagination\LengthAwarePaginator && $logs->total() > 0)
                 <span class="text-muted small fw-semibold">Mostrando {{ $logs->firstItem() }}-{{ $logs->lastItem() }} de {{ $logs->total() }}</span>
                 @elseif($hasSearch && !$logs->isEmpty())
                  <span class="text-muted small fw-semibold">Mostrando {{ $logs->count() }} registro(s)</span>
                 @endif
            </div>
            <div class="card-body pt-4"> {{-- Aumentado padding top --}}
                @if(!$hasSearch)
                    <div class="text-center py-5 my-3 empty-state rounded-3">
                        <i class="fas fa-filter fa-3x mb-3"></i>
                        <p class="fw-bold fs-5 text-dark">Use os filtros acima para pesquisar.</p>
                        <p class="text-muted small">Nenhum filtro aplicado ainda.</p>
                    </div>
                @elseif($logs->isEmpty())
                    <div class="text-center py-5 my-3 empty-state rounded-3">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p class="fw-bold fs-5 text-dark">Nenhum log encontrado.</p>
                        <p class="text-muted small">Tente ajustar os filtros selecionados.</p>
                    </div>
                @else
                    <div class="timeline mt-2">
                        @foreach($logs as $log)
                            <div class="timeline-item" data-action="{{ $log->action }}">
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-1 border-bottom pb-2">
                                        <div class="d-flex align-items-center mb-1 mb-md-0">
                                            <i class="fas fa-user-circle text-muted me-2 fs-5" title="Usuário"></i>
                                            <strong class="text-dark me-2">{{ $log->user->name ?? 'Sistema' }}</strong>
                                            @php /* Badge de Ação */ @endphp
                                            @php
                                                $actionClass = match($log->action) {
                                                    'LOGIN' => 'bg-info text-dark', 'LOGOUT' => 'bg-secondary',
                                                    'CREATE' => 'bg-success', 'UPDATE' => 'bg-warning text-dark',
                                                    'DELETE' => 'bg-danger', default => 'bg-primary',
                                                };
                                            @endphp
                                            <span class="badge rounded-pill {{ $actionClass }} fw-semibold">{{ $log->action }}</span>
                                        </div>
                                        <small class="text-muted fw-bold flex-shrink-0 log-meta-item" title="Data e Hora">
                                            <i class="far fa-calendar-alt"></i>{{ $log->created_at->format('d/m/Y') }}
                                            <i class="far fa-clock ms-1"></i>{{ $log->created_at->format('H:i:s') }}
                                        </small>
                                    </div>

                                    <p class="mb-3 lead fs-6 fw-normal">{{ $log->description }}</p>

                                    <div class="d-flex flex-wrap justify-content-start gap-x-4 gap-y-1 mb-3 small border-top pt-2 mt-2">
                                        <span class="log-meta-item text-muted" title="Controller/Action">
                                            <i class="fas fa-terminal"></i> <code>{{ basename($log->controller ?? '?') }}/{{ $log->action_name ?? '?'}}</code>
                                        </span>
                                        <span class="log-meta-item text-muted" title="Rota Acessada e Método">
                                            <i class="fas fa-route"></i> <code>{{ $log->route }}</code>
                                             <span class="badge bg-light text-dark border ms-1 fw-bold">{{ $log->method }}</span>
                                        </span>
                                        <span class="log-meta-item text-muted" title="Endereço IP">
                                            <i class="fas fa-network-wired"></i> <code>{{ $log->ip_address }}</code>
                                        </span>
                                    </div>

                                    @php /* Verifica se há detalhes */ @endphp
                                    @php
                                        $hasDetails = ($log->request_data && $log->request_data !== '[]' && $log->request_data !== '{}') ||
                                                      ($log->old_values && $log->old_values !== '[]' && $log->old_values !== '{}') ||
                                                      ($log->new_values && $log->new_values !== '[]' && $log->new_values !== '{}') ||
                                                      $log->user_agent;
                                    @endphp
                                    @if($hasDetails)
                                        <div class="text-end">
                                            <button class="btn btn-sm btn-outline-secondary btn-show-details" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#details-{{ $log->id }}" aria-expanded="false">
                                                <i class="fas fa-chevron-down me-1 small"></i> Detalhes
                                            </button>
                                        </div>
                                        <div class="collapse mt-2" id="details-{{ $log->id }}">
                                            <div class="log-details-section p-3 mt-1">
                                                {{-- Dados da Requisição --}}
                                                @if($log->request_data && $log->request_data !== '[]' && $log->request_data !== '{}')
                                                    <div class="mb-3">
                                                        <strong><i class="fas fa-sign-in-alt me-1 text-primary"></i>Dados da Requisição:</strong>
                                                        <pre class="mb-0"><code>{{ json_encode(json_decode($log->request_data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                    </div>
                                                @endif
                                                {{-- Valores Anteriores --}}
                                                @if($log->old_values && $log->old_values !== '[]' && $log->old_values !== '{}')
                                                    <div class="mb-3">
                                                        <strong><i class="fas fa-history me-1 text-warning"></i>Valores Anteriores:</strong>
                                                        <pre class="mb-0"><code>{{ json_encode(json_decode($log->old_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                    </div>
                                                @endif
                                                 {{-- Novos Valores --}}
                                                @if($log->new_values && $log->new_values !== '[]' && $log->new_values !== '{}')
                                                    <div class="mb-3">
                                                        <strong><i class="fas fa-check-double me-1 text-success"></i>Novos Valores:</strong>
                                                        <pre class="mb-0"><code>{{ json_encode(json_decode($log->new_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                    </div>
                                                @endif
                                                {{-- User Agent --}}
                                                @if($log->user_agent)
                                                <div class="mt-2">
                                                    <strong><i class="fas fa-user-secret me-1 text-secondary"></i>User Agent:</strong>
                                                    <small class="text-muted d-block bg-white p-2 rounded border mt-1">{{ $log->user_agent }}</small>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div> {{-- Fim .timeline-content --}}
                            </div> {{-- Fim .timeline-item --}}
                        @endforeach
                    </div> {{-- Fim .timeline --}}

                    {{-- Paginação --}}
                    @if($logs instanceof \Illuminate\Pagination\LengthAwarePaginator && $logs->hasPages())
                        <div class="card-footer bg-white border-0 py-3 mt-3">
                            <div class="d-flex justify-content-center">
                                {{ $logs->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                @endif
            </div> {{-- Fim .card-body --}}
        </div> {{-- Fim .card (Timeline) --}}
    </div> {{-- Fim .container-fluid --}}
@endsection

{{-- Script JS para ícone do botão collapse --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ... (código JS para collapse que você já tem) ...
         const collapseElements = document.querySelectorAll('.collapse');
        collapseElements.forEach(function(collapseEl) {
            collapseEl.addEventListener('show.bs.collapse', function () {
                const button = document.querySelector(`[data-bs-target="#${collapseEl.id}"]`);
                if (button) {
                    const icon = button.querySelector('.fas');
                    if (icon && icon.classList.contains('fa-chevron-down')) {
                       icon.classList.remove('fa-chevron-down');
                       icon.classList.add('fa-chevron-up');
                    }
                }
            });
            collapseEl.addEventListener('hide.bs.collapse', function () {
                 const button = document.querySelector(`[data-bs-target="#${collapseEl.id}"]`);
                 if (button) {
                    const icon = button.querySelector('.fas');
                     if (icon && icon.classList.contains('fa-chevron-up')) {
                       icon.classList.remove('fa-chevron-up');
                       icon.classList.add('fa-chevron-down');
                     }
                 }
            });
        });
    });
</script>
@endpush
