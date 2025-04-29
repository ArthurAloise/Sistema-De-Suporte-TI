@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="fw-bold text-danger">Logs do Sistema</h1>
                <p class="text-muted">Monitore todas as atividades realizadas no sistema.</p>
            </div>
        </div>

        <!-- Filtros Avançados -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Filtros Avançados</h5>
                <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosCollapse">
                    <i class="fas fa-filter"></i> Mostrar/Ocultar Filtros
                </button>
            </div>
            <div class="collapse show" id="filtrosCollapse">
                <div class="card-body">
                    <form action="{{ route('admin.logs') }}" method="GET" class="row g-3">
                        <!-- Filtro por Usuário -->
                        <div class="col-md-3">
                            <label class="form-label">Usuário</label>
                            <select name="user_id" class="form-select">
                                <option value="">Todos os usuários</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro por Ação -->
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Ação</label>
                            <select name="action" class="form-select">
                                <option value="">Todas as ações</option>
                                <option value="LOGIN" {{ request('action') == 'LOGIN' ? 'selected' : '' }}>Login</option>
                                <option value="LOGOUT" {{ request('action') == 'LOGOUT' ? 'selected' : '' }}>Logout</option>
                                <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>Criação</option>
                                <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>Atualização</option>
                                <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>Exclusão</option>
                                <option value="ACCESS" {{ request('action') == 'ACCESS' ? 'selected' : '' }}>Acesso</option>
                            </select>
                        </div>

                        <!-- Filtro por Data -->
                        <div class="col-md-3">
                            <label class="form-label">Data Inicial</label>
                            <input type="date" class="form-control" name="date_start" value="{{ request('date_start') }}">
                        </div>

                        <!-- Filtro por Rota -->
                        <div class="col-md-4">
                            <label class="form-label">Rota</label>
                            <input type="text" class="form-control" name="route" value="{{ request('route') }}" placeholder="Ex: users/create">
                        </div>

                        <!-- Filtro por Método HTTP -->
                        <div class="col-md-4">
                            <label class="form-label">Método HTTP</label>
                            <select name="method" class="form-select">
                                <option value="">Todos os métodos</option>
                                <option value="GET" {{ request('method') == 'GET' ? 'selected' : '' }}>GET</option>
                                <option value="POST" {{ request('method') == 'POST' ? 'selected' : '' }}>POST</option>
                                <option value="PUT" {{ request('method') == 'PUT' ? 'selected' : '' }}>PUT</option>
                                <option value="DELETE" {{ request('method') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                            </select>
                        </div>

                        <!-- Filtro por IP -->
                        <div class="col-md-4">
                            <label class="form-label">Endereço IP</label>
                            <input type="text" class="form-control" name="ip" value="{{ request('ip') }}" placeholder="Ex: 192.168.1.1">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.logs') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Limpar Filtros
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Timeline de Logs -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                @if(!$hasSearch)
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Use os filtros acima para pesquisar os logs do sistema.</p>
                    </div>
                @elseif($logs->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum log encontrado com os filtros selecionados.</p>
                    </div>
                @else
                    <div class="timeline">
                        @foreach($logs as $log)
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <!-- Cabeçalho do Log -->
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong>{{ $log->user->name ?? 'Sistema' }}</strong>
                                            <span class="ms-2">
                                                @switch($log->action)
                                                    @case('LOGIN')
                                                        <span class="badge bg-info">Login</span>
                                                        @break
                                                    @case('LOGOUT')
                                                        <span class="badge bg-secondary">Logout</span>
                                                        @break
                                                    @case('CREATE')
                                                        <span class="badge bg-success">Criação</span>
                                                        @break
                                                    @case('UPDATE')
                                                        <span class="badge bg-warning">Atualização</span>
                                                        @break
                                                    @case('DELETE')
                                                        <span class="badge bg-danger">Exclusão</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-primary">Acesso</span>
                                                @endswitch
                                            </span>
                                        </div>
                                        <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                                    </div>

                                    <!-- Descrição do Log -->
                                    <p class="mb-2">{{ $log->description }}</p>

                                    <!-- Informações Técnicas -->
                                    <div class="d-flex gap-3 mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-code"></i> {{ $log->controller }}/{{ $log->action_name }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-link"></i> {{ $log->route }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-globe"></i> {{ $log->ip_address }}
                                        </small>
                                    </div>

                                    <!-- Detalhes Expandíveis -->
                                    @if($log->request_data || $log->old_values || $log->new_values)
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-link p-0" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#details{{ $log->id }}">
                                                <i class="fas fa-info-circle"></i> Ver detalhes
                                            </button>

                                            <div class="collapse mt-2" id="details{{ $log->id }}">
                                                <div class="card card-body bg-light">
                                                    @if($log->request_data)
                                                        <div class="mb-2">
                                                            <strong>Dados da Requisição:</strong>
                                                            <pre class="mb-0"><code>{{ json_encode(json_decode($log->request_data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                        </div>
                                                    @endif

                                                    @if($log->old_values)
                                                        <div class="mb-2">
                                                            <strong>Valores Anteriores:</strong>
                                                            <pre class="mb-0"><code>{{ json_encode(json_decode($log->old_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                        </div>
                                                    @endif

                                                    @if($log->new_values)
                                                        <div>
                                                            <strong>Novos Valores:</strong>
                                                            <pre class="mb-0"><code>{{ json_encode(json_decode($log->new_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                        </div>
                                                    @endif

                                                    <div class="mt-2">
                                                        <strong>User Agent:</strong>
                                                        <small class="text-muted d-block">{{ $log->user_agent }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            position: relative;
            padding: 20px 30px;
            border-left: 2px solid #e9ecef;
            margin-left: 20px;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: -9px;
            top: 28px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #dc3545;
            border: 2px solid #fff;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        pre {
            background: #fff;
            padding: 10px;
            border-radius: 4px;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
@endsection
