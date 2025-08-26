@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Título e Saudação -->
        <div class="row">
            <div class="col-12">
                <h1 class="fw-bold text-danger">Bem-vindo ao Painel Administrativo</h1>
                <p class="text-muted">Gerencie usuários, permissões e outros aspectos do sistema.</p>
            </div>
        </div>

        <!-- Cards de Visão Geral -->
        <div class="row">
            <!-- Número de Usuários -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-users" style="font-size: 40px;"></i>
                        <h5 class="fw-bold mt-3">Usuários</h5>
                        <p> Usuários registrados: {{ $userCount }}</p> {{--{{ $userCount }}--}}
                        <a href="{{ route('users.index') }}" class="btn btn-light w-100">Gerenciar</a>
                    </div>
                </div>
            </div>

            <!-- Número de Chamados -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 bg-warning text-dark">
                    <div class="card-body text-center">
                        <i class="fas fa-clipboard-list" style="font-size: 40px;"></i>
                        <h5 class="fw-bold mt-3">Chamados</h5>
                        <p> Chamados abertos: {{ $openTicketsCount }}</p> {{--{{ $openTicketsCount }}--}}
                        <a href="" class="btn btn-light w-100">Gerenciar</a> {{--{{ route('admin.tickets.index') }}--}}
                    </div>
                </div>
            </div>

            <!-- Relatórios -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line" style="font-size: 40px;"></i>
                        <h5 class="fw-bold mt-3">Relatórios</h5>
                        <p>Acesse os relatórios de sistema</p>
                        <a href="" class="btn btn-light w-100">Ver Relatórios</a> {{--{{ route('admin.reports.index') }}--}}
                    </div>
                </div>
            </div>

            <!-- Perfis -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 bg-dark text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-shield-alt" style="font-size: 40px;"></i>
                        <h5 class="fw-bold mt-3">Perfis</h5>
                        <p>Gerencie Perfis de usuários</p>
                        <a href="{{ route('roles.index') }}" class="btn btn-light w-100">Ver Perfis</a> {{--{{ route('admin.permissions.index') }}--}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="row mt-4">
            <div class="col-md-2">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Tipos</h5>
                        <a href="{{ route('types.index') }}" class="btn btn-outline-primary w-100">Ver Tipos</a>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Categorias</h5>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-warning w-100">Ver Categorias</a> {{--{{ route('admin.users.create') }}--}}
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Setores</h5>
                        <a href="{{ route('setores.index') }}" class="btn btn-outline-dark w-100">Ver Setores</a> {{--{{ route('admin.users.create') }}--}}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Logs</h5>
                        <a href="{{ route('admin.logs') }}" class="btn btn-outline-danger w-100">Ver Logs do Sistema</a> {{--{{ route('admin.tickets.index') }}--}}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Permissões</h5>
                        <a href="{{ route('permissions.index') }}" class="btn btn-outline-success w-100">Ver Permissões</a> {{--{{ route('admin.permissions.index') }}--}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Chamados Recentes -->
        <div class="row mt-4">
            <div class="col-12">
                <!-- Filtro de Chamados -->
                <div class="card shadow-sm border-0">
                    <h4 class="fw-bold text-dark text-center">Chamados Recentes</h4>
                    <div class="card-body">
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 align-items-end">
                            <!-- ID do Chamado -->
                            <div class="col-md-4">
                                <label for="ticket_id" class="form-label">ID do Chamado</label>
                                <input type="number"
                                       class="form-control"
                                       id="ticket_id"
                                       name="ticket_id"
                                       value="{{ request('ticket_id') }}"
                                       placeholder="Digite o ID">
                            </div>

                            <!-- Data -->
                            <div class="col-md-4">
                                <label for="date" class="form-label">Data</label>
                                <input type="date"
                                       class="form-control"
                                       id="date"
                                       name="date"
                                       value="{{ request('date') }}">
                            </div>

                            <!-- Botões -->
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search"></i> Pesquisar
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Limpar
                                </a>
                            </div>
                        </form>
                    </div>
                    <table class="table table-hover shadow-sm">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Titulo</th>
                            <th>Data</th>
                            <th>Prioridade</th>
                            <th>Usuário</th>
                            <th>Categoria</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>#{{ $ticket->id }}</td>
                                <td>{{ $ticket->titulo }}</td>
                                <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                                <td>{{ $ticket->prioridade }}</td>
                                <td>{{ $ticket->usuario->name }}</td>
                                <td>{{ $ticket->category->nome }}</td>
                                <td>{{ $ticket->type->nome }}</td>
                                <td>
                                    @if($ticket->status == 'aberto')
                                        <span class="badge bg-warning text-dark">Aberto</span>
                                    @elseif($ticket->status == 'andamento')
                                        <span class="badge bg-primary">Em Andamento</span>
                                    @else
                                        <span class="badge bg-success">Resolvido</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-dark">Ver Chamado</a> {{--{{ route('chamados.show', $chamado->id) }}--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @if($tickets->count() == 0)
                        <p class="text-muted text-center">Nenhum chamado recente.</p>
                    @endif
                    <!-- Links de Paginação -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $tickets->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

