@extends('user.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Boas-vindas -->
            <div class="col-12">
                <h2 class="fw-bold text-danger">Olá, {{ Auth::user()->name }}!</h2>
                <p class="text-muted">Bem-vindo ao seu painel. Aqui você pode gerenciar seus chamados de TI.</p>
            </div>

            <!-- Cards de Atalhos -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-plus-circle text-danger" style="font-size: 40px;"></i>
                        <h5 class="fw-bold mt-3">Abrir Novo Chamado</h5>
                        <p class="text-muted">Relate um problema e solicite suporte técnico.</p>
                        <a href="{{ route('tickets.create') }}" class="btn btn-danger w-100">Abrir Chamado</a>
                    </div>
                </div>
            </div>

{{--            <div class="col-md-4">--}}
{{--                <div class="card shadow-sm border-0">--}}
{{--                    <div class="card-body text-center">--}}
{{--                        <i class="fas fa-tasks text-primary" style="font-size: 40px;"></i>--}}
{{--                        <h5 class="fw-bold mt-3">Meus Chamados</h5>--}}
{{--                        <p class="text-muted">Acompanhe o status dos seus chamados abertos e atribuídos a você.</p>--}}
{{--                        <a href="{{ route('tickets.meusChamados') }}" class="btn btn-primary w-100">Ver Chamados</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-user-cog text-success" style="font-size: 40px;"></i>
                        <h5 class="fw-bold mt-3">Editar Perfil</h5>
                        <p class="text-muted">Atualize suas informações de perfil e senha.</p>
                        <a href="{{ route('user.profile') }}" class="btn btn-success w-100">Editar Perfil</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Chamados Abertos -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="fw-bold text-dark">Chamados Abertos por Você</h4>
                <table class="table table-hover shadow-sm">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Data</th>
                        <th>Prioridade</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tickets_abertos as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ $ticket->titulo }}</td>
                            <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                            <td>{{ $ticket->prioridade }}</td>
                            <td>{{ $ticket->category->nome }}</td>
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
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-dark">Ver Chamado</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if($tickets_abertos->isEmpty())
                    <p class="text-muted text-center">Você não abriu nenhum chamado recentemente.</p>
                @endif
            </div>
        </div>

        <!-- Lista de Chamados Atribuídos a Você -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="fw-bold text-dark">Chamados Atribuídos a Você</h4>
                <table class="table table-hover shadow-sm">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Data</th>
                        <th>Prioridade</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tickets_atribuido as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ $ticket->titulo }}</td>
                            <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                            <td>{{ $ticket->prioridade }}</td>
                            <td>{{ $ticket->category->nome }}</td>
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
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-dark">Ver Chamado</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if($tickets_atribuido->isEmpty())
                    <p class="text-muted text-center">Você não tem chamados atribuídos a você.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
