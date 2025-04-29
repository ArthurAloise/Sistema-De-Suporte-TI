@extends('user.layouts.app')
@section('content')
    {{--Style para desing do modal de histórico de chamados--}}
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
    </style>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#historicoModal">
                        <i class="fas fa-history"></i> Histórico do Chamado
                    </button>

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
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0"><strong>Técnico Responsável:</strong> {{ $ticket->tecnico->name }}</p>
                                @if(auth()->user()->hasPermission('alter_tecnico_responsavel'))
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editTechnicianModal">
                                        Alterar Técnico
                                    </button>
                                @endif
                            </div>
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
                @if(auth()->user()->hasPermission('concluir_chamado'))
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
                @endif
                <div class="text-center mt-3">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-dark">Voltar à Lista</a>
                </div>
            </div>
        </div>
    </div>

    {{--Formulário para alteração do Técnico, caso necessário--}}
    <div class="modal fade" id="editTechnicianModal" tabindex="-1" aria-labelledby="editTechnicianModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTechnicianModalLabel">Alterar Técnico Responsável</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tickets.updateTechnician', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tecnico_id" class="form-label">Selecionar Novo Técnico:</label>
                            <select name="tecnico_id" id="tecnico_id" class="form-control" required>
                                <option value="" selected disabled>Escolha um técnico</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $ticket->tecnico_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{--Histórico de Chamados--}}
    <div class="modal fade" id="historicoModal" tabindex="-1" aria-labelledby="historicoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historicoModalLabel">Histórico do Chamado #{{ $ticket->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="timeline">
                        <!-- Registro da criação do chamado -->
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <strong>Criação do Chamado</strong>
                                    <small>{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <p>Chamado criado por {{ $ticket->usuario->name }}</p>
                            </div>
                        </div>

                        <!-- Histórico de alterações -->
                        @foreach($ticket->histories as $history)
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $history->tipo_acao)) }}</strong>
                                        <small>{{ $history->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p>{{ $history->descricao }}</p>
                                    <small class="text-muted">Ação realizada por: {{ $history->user->name }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
