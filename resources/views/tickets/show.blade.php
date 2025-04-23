@extends('user.layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Nº do Chamado #{{ $ticket->id }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Título:</strong> {{ $ticket->titulo }}</p>
                        <p><strong>Descrição:</strong> {{ $ticket->descricao }}</p>
                        <p><strong>Categoria:</strong> {{ $ticket->category->nome }}</p>
                        <p><strong>Tipo:</strong> {{ $ticket->type->nome }}</p>
                        <p><strong>Prioridade:</strong> {{ ucfirst($ticket->prioridade) }}</p>
                        <p><strong>Aberto por:</strong> {{ $ticket->usuario->name }}</p>
                        <p><strong>Data de Abertura:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Status:</strong>
                            @if($ticket->status == 'aberto')
                                <span class="badge bg-warning text-dark">Aberto</span>
                            @elseif($ticket->status == 'andamento')
                                <span class="badge bg-primary">Em Andamento</span>
                            @else
                                <span class="badge bg-success">Resolvido</span>
                            @endif
                        </p>

                        @if($ticket->tecnico_id)
                            <p><strong>Técnico Responsável:</strong> {{ $ticket->tecnico->name }}</p>
                        @else
                            <p><strong>Técnico Responsável:</strong> <span class="text-muted">Nenhum atribuído</span></p>
                        @endif
                    </div>
                </div>

                <!-- Formulário para atribuir técnico -->
                @if(!$ticket->tecnico_id)
                    <div class="card mt-3 shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Atribuir Técnico</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="tecnico_id" class="form-label">Selecionar Técnico:</label>
                                    <select name="tecnico_id" id="tecnico_id" class="form-control" required>
                                        <option value="" selected disabled>Escolha um técnico</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Atribuir Técnico</button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Formulário para marcar como concluído -->
                @if($ticket->status == 'andamento')
                    <div class="card mt-3 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Marcar Chamado como Concluído</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('tickets.markAsCompleted', $ticket->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="descricao_resolucao" class="form-label">Descrição do Procedimento Realizado:</label>
                                    <textarea name="descricao_resolucao" id="descricao_resolucao" class="form-control" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Marcar como Concluído</button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="text-center mt-3">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-dark">Voltar à Lista</a>
                </div>
            </div>
        </div>
    </div>
@endsection
