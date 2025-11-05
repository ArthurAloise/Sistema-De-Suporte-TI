@extends('user.layouts.app')

@section('content')
<style>
    .card-link {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        text-decoration: none;
        color: inherit;
    }
    .card-link:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .table-dark-header {
        background-color: #343a40 !important; /* Cor de fundo do cabeçalho da tabela */
        color: white;
    }
</style>

<div class="container py-4">
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="fw-bold">Meus Chamados</h2>
            <p class="text-muted fs-5">Acompanhe e gerencie todos os chamados que você abriu ou que foram atribuídos a você.</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <a href="{{ route('tickets.create') }}" class="card-link">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-plus-circle text-danger fa-3x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Abrir Novo Chamado</h5>
                            <p class="text-muted mb-0 small">Relate um problema e solicite suporte.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6">
            <a href="{{ route('user.profile') }}" class="card-link">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-user-cog text-success fa-3x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Editar Perfil</h5>
                            <p class="text-muted mb-0 small">Atualize suas informações e senha.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold text-dark mb-0">Chamados Abertos por Você</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">ID</th>
                                    <th>Título</th>
                                    <th>Data</th>
                                    <th>Prioridade</th>
                                    <th>Categoria</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets_abertos as $ticket)
                                    <tr>
                                        <td class="ps-3">#{{ $ticket->id }}</td>
                                        <td>{{ $ticket->titulo }}</td>
                                        <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @switch(strtolower($ticket->prioridade))
                                                @case('baixa')
                                                    <span class="badge bg-info text-dark">Baixa</span>
                                                    @break
                                                @case('media')
                                                    <span class="badge bg-warning text-dark">Média</span>
                                                    @break
                                                @case('alta')
                                                    <span class="badge bg-danger">Alta</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $ticket->prioridade }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $ticket->category->nome }}</td>
                                        <td>
                                            @if($ticket->status == 'aberto')
                                                <span class="badge bg-warning text-dark">Aberto</span>
                                            @elseif($ticket->status == 'andamento')
                                                <span class="badge bg-primary">Em Andamento</span>
                                            @elseif($ticket->status == 'fechado')
                                                <span class="badge bg-dark">Fechado</span>
                                            @elseif($ticket->status == 'pendente')
                                                <span class="badge bg-secondary">Pendente</span>
                                            @else
                                                <span class="badge bg-success">Resolvido</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>Você não abriu nenhum chamado recentemente.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($tickets_abertos->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $tickets_abertos->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold text-dark mb-0">Chamados Atribuídos a Você</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">ID</th>
                                    <th>Título</th>
                                    <th>Data</th>
                                    <th>Prioridade</th>
                                    <th>Categoria</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets_atribuido as $ticket)
                                    <tr>
                                        <td class="ps-3">#{{ $ticket->id }}</td>
                                        <td>{{ $ticket->titulo }}</td>
                                        <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @switch(strtolower($ticket->prioridade))
                                                @case('baixa')
                                                    <span class="badge bg-info text-dark">Baixa</span>
                                                    @break
                                                @case('media')
                                                    <span class="badge bg-warning text-dark">Média</span>
                                                    @break
                                                @case('alta')
                                                    <span class="badge bg-danger">Alta</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $ticket->prioridade }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $ticket->category->nome }}</td>
                                        <td>
                                            @if($ticket->status == 'aberto')
                                                <span class="badge bg-warning text-dark">Aberto</span>
                                            @elseif($ticket->status == 'andamento')
                                                <span class="badge bg-primary">Em Andamento</span>
                                            @elseif($ticket->status == 'fechado')
                                                <span class="badge bg-dark">Fechado</span>
                                            @elseif($ticket->status == 'pendente')
                                                <span class="badge bg-secondary">Pendente</span>
                                            @else
                                                <span class="badge bg-success">Resolvido</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>Nenhum chamado atribuído a você no momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($tickets_atribuido->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $tickets_atribuido->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
