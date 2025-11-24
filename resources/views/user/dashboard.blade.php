        @extends('user.layouts.app')

        @section('content')
        <style>
            /* Efeito de transição suave para os cards de atalho */
            .card-link {
                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                text-decoration: none;
                color: inherit; /* Herda a cor do texto para não ficar azul de link */
            }
            .card-link:hover {
                transform: translateY(-5px); /* Eleva o card um pouco */
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; /* Aumenta a sombra */
            }

            /* Animação para chamados com SLA estourado */
/* Destaque visual para chamados com SLA estourado (usuário) */
.sla-overdue {
    background: linear-gradient(90deg, #fef2f2 0%, #ffffff 45%);
    border-left: 4px solid #dc2626;
}

/* Mantém as cores de texto padrão da tabela */
.sla-overdue td {
    color: #212529;
}

/* Chips de SLA (vence em / vencido) */
.sla-chip-sla {
    border-radius: 999px;
    padding: 0.15rem 0.6rem;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.sla-chip-sla i {
    font-size: 0.7rem;
}

/* Chip quando ainda está dentro do prazo */
.sla-chip-warning {
    background: #fef3c7;      /* amarelo bem claro */
    color: #92400e;
}

/* Chip quando SLA já venceu */
.sla-chip-overdue {
    background: #fee2e2;      /* vermelho bem claro */
    color: #b91c1c;
}

        </style>

        <div class="container py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold">Olá, {{ Auth::user()->name }}!</h2>
                    <p class="text-muted fs-5">Bem-vindo ao seu painel. Aqui você pode gerenciar seus chamados de TI.</p>
                </div>
            </div>

            <div class="row g-3 mb-5"> <div class="col-md-4">
                    <a href="{{ route('tickets.create') }}" class="card-link">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-plus-circle text-danger fa-3x"></i> </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1">Abrir Novo Chamado</h5>
                                    <p class="text-muted mb-0 small">Relate um problema e solicite suporte.</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('tickets.index') }}" class="card-link">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-tasks text-primary fa-3x"></i> </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1">Meus Chamados</h5>
                                    <p class="text-muted mb-0 small">Acompanhe o status dos seus chamados.</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('user.profile') }}" class="card-link">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-user-cog text-success fa-3x"></i> </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1">Editar Perfil</h5>
                                    <p class="text-muted mb-0 small">Atualize suas informações e senha.</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h4 class="fw-bold text-dark mb-0">Chamados Recentes</h4>
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
                                            <th>Usuário</th>
                                            <th>Categoria</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ticket as $tickets)
                                            @php
                                                $overdue = in_array($tickets->status, ['aberto','andamento'])
                                                        && $tickets->due_at
                                                        && now()->greaterThan($tickets->due_at);
                                            @endphp
                                            <tr class="{{ $overdue ? 'sla-overdue' : '' }}">
                                                <td class="ps-3">#{{ $tickets->id }}</td>
                                                <td>{{ $tickets->titulo }}</td>
<td>
    {{ $tickets->created_at->format('d/m/Y') }}

    @php
        $isResolved = in_array($tickets->status, ['resolvido', 'fechado']);
    @endphp

    @if($tickets->due_at && !$isResolved)
        @php
            $now = now();
            $due = $tickets->due_at;
            $isOverdueCell = $overdue; // mesma flag usada na <tr>

            // diferença absoluta em segundos
            $diffSeconds = $now->diffInSeconds($due);
            // transforma em texto curto tipo "2d 18h"
            $diffHuman = \Carbon\CarbonInterval::seconds($diffSeconds)
                            ->cascade()
                            ->forHumans(short: true, parts: 2);
        @endphp

        <div class="small mt-1">
            @if($isOverdueCell)
                <span class="sla-chip-sla sla-chip-overdue">
                    <i class="fas fa-exclamation-triangle"></i>
                    SLA vencido há {{ $diffHuman }}
                </span>
            @else
                <span class="sla-chip-sla sla-chip-warning">
                    <i class="far fa-clock"></i>
                    Vence em {{ $diffHuman }}
                </span>
            @endif
        </div>
    @endif
</td>

                                                <td>
                                                    @switch(strtolower($tickets->prioridade))
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
                                                            <span class="badge bg-secondary">{{ $tickets->prioridade }}</span>
                                                    @endswitch
                                                </td>
                                                <td>{{ $tickets->usuario->name }}</td>
                                                <td>{{ $tickets->category->nome }}</td>
                                                <td>
                                                    @if($tickets->status == 'aberto')
                                                        <span class="badge bg-warning text-dark">Aberto</span>
                                                    @elseif($tickets->status == 'andamento')
                                                        <span class="badge bg-primary">Em Andamento</span>
                                                    @elseif($tickets->status == 'pendente')
                                                        <span class="badge bg-secondary">Pendente</span>
                                                    @elseif($tickets->status == 'fechado')
                                                        <span class="badge bg-dark">Fechado</span>
                                                    @else
                                                        <span class="badge bg-success">Resolvido</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('tickets.show', $tickets->id) }}" class="btn btn-sm btn-outline-dark">
                                                        <i class="fas fa-eye me-1"></i>Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    <i class="fas fa-info-circle me-2"></i>Nenhum chamado recente para exibir.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($ticket->hasPages())
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-center">
                                {{ $ticket->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endsection
