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
                        <p> Usuários registrados</p> {{--{{ $userCount }}--}}
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
                        <p> Chamados abertos</p> {{--{{ $openTicketsCount }}--}}
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
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Adicionar Novo Usuário</h5>
                        <a href="" class="btn btn-outline-primary w-100">Criar Usuário</a> {{--{{ route('admin.users.create') }}--}}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Gerenciar Chamados</h5>
                        <a href="" class="btn btn-outline-warning w-100">Ver Chamados</a> {{--{{ route('admin.tickets.index') }}--}}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">Gerenciar Permissões</h5>
                        <a href="{{ route('permissions.index') }}" class="btn btn-outline-success w-100">Ver Permissões</a> {{--{{ route('admin.permissions.index') }}--}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Chamados Recentes -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="fw-bold text-dark">Chamados Recentes</h4>
                <table class="table table-hover shadow-sm">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Assunto</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
{{--                    @foreach($recentTickets as $ticket)--}}
{{--                        <tr>--}}
{{--                            <td>#{{ $ticket->id }}</td>--}}
{{--                            <td>{{ $ticket->assunto }}</td>--}}
{{--                            <td>{{ $ticket->created_at->format('d/m/Y') }}</td>--}}
{{--                            <td>--}}
{{--                                @if($ticket->status == 'Aberto')--}}
{{--                                    <span class="badge bg-warning text-dark">Aberto</span>--}}
{{--                                @elseif($ticket->status == 'Em Andamento')--}}
{{--                                    <span class="badge bg-primary">Em Andamento</span>--}}
{{--                                @else--}}
{{--                                    <span class="badge bg-success">Resolvido</span>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-dark">Ver</a>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
                    </tbody>
                </table>

                @if(null)
                    <p class="text-muted text-center">Nenhum chamado recente.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

