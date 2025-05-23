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
                        <a href="{{ route('tickets.create') }}" class="btn btn-danger w-100">Abrir Chamado</a> {{--{{ route('chamados.create') }}--}}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-tasks text-primary" style="font-size: 40px;"></i>
                        <h5 class="fw-bold mt-3">Meus Chamados</h5>
                        <p class="text-muted">Acompanhe o status dos seus chamados abertos.</p>
                        <a href="{{ route('tickets.index') }}" class="btn btn-primary w-100">Ver Chamados</a> {{--{{ route('chamados.index') }}--}}
                    </div>
                </div>
            </div>

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

        <!-- Lista de Chamados Recentes -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="fw-bold text-dark">Chamados Recentes</h4>
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
                    @foreach($ticket as $tickets)
                        <tr>
                            <td>#{{ $tickets->id }}</td>
                            <td>{{ $tickets->titulo }}</td>
                            <td>{{ $tickets->created_at->format('d/m/Y') }}</td>
                            <td>{{ $tickets->prioridade }}</td>
                            <td>{{ $tickets->usuario->name }}</td>
                            <td>{{ $tickets->category->nome }}</td>
                            <td>{{ $tickets->type->nome }}</td>
                            <td>
                                @if($tickets->status == 'aberto')
                                    <span class="badge bg-warning text-dark">Aberto</span>
                                @elseif($tickets->status == 'andamento')
                                    <span class="badge bg-primary">Em Andamento</span>
                                @else
                                    <span class="badge bg-success">Resolvido</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('tickets.show', $tickets->id) }}" class="btn btn-sm btn-outline-dark">Ver Chamado</a> {{--{{ route('chamados.show', $chamado->id) }}--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if($ticket->count() == 0)
                    <p class="text-muted text-center">Nenhum chamado recente.</p>
                @endif
                <!-- Links de Paginação -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $ticket->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

