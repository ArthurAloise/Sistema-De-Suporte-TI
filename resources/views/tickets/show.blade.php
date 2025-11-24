@extends('user.layouts.app')
@section('content')
    @php
        // Variável de conveniência
        $isTechOrAdmin = auth()->check() && (auth()->user()->hasRole('Tecnico') || auth()->user()->hasRole('Admin'));
        $canEdit = auth()->id() === $ticket->usuario_id
                    || auth()->id() === $ticket->tecnico_id
                    || (method_exists(auth()->user(),'hasPermission') && auth()->user()->hasPermission('acessar_admin'));
    @endphp

    {{-- Style para desing do modal de histórico de chamados --}}
    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
            margin-left: 20px;
        }

        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 2px;
            background: #e9ecef; /* Cor da linha vertical da timeline */
        }

        .timeline-item {
            position: relative;
            padding-left: 30px; /* Espaço para o ponto */
            margin-bottom: 30px;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: -8px; /* Posição do ponto na linha */
            top: 8px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #dc3545; /* Ponto Vermelho (Cor principal do sistema) */
            border: 3px solid #fff; /* Borda branca para destacar do fundo */
            z-index: 10;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px; /* Cantos arredondados */
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        /* Ajuste do botão 'Editar' e 'Histórico' no topo */
        .ticket-header-actions {
            position: absolute;
            top: 0;
            right: 0;
            display: flex;
            gap: 10px;
            transform: translateY(-50%);
        }
    /* ==== Ajuste do modal de edição no mobile ==== */
    @media (max-width: 992px) {
        /* Modal ocupa 100% da largura e altura da tela */
        #editTicketModal .modal-dialog {
            margin: 0;
            height: 100%;
            max-width: 100%;
            width: 100%;
        }

        /* Conteúdo em tela cheia, sem cantos arredondados */
        #editTicketModal .modal-content {
            height: 100%;
            display: flex;
            flex-direction: column;
            border-radius: 0;
        }

        /* Só o corpo rola, header e footer fixos */
        #editTicketModal .modal-body {
            flex: 1 1 auto;
            overflow-y: auto;
        }

        /* Botões sempre visíveis no fundo */
        #editTicketModal .modal-footer {
            position: sticky;
            bottom: 0;
            background-color: #ffffff;
            z-index: 2;
        }
    }

    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-lg border-0 rounded-4 position-relative mb-4">

                    <div class="ticket-header-actions me-4">
                        <button type="button" class="btn btn-primary btn-sm rounded-pill shadow-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#historicoModal">
                            <i class="fas fa-history me-1"></i> Histórico do Chamado
                        </button>
                        @if($canEdit)
                            <button type="button" class="btn btn-warning btn-sm rounded-pill shadow-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#editTicketModal">
                                <i class="fas fa-edit me-1"></i> Editar
                            </button>
                        @endif
                    </div>

                    <div class="card-header bg-white pt-4 pb-2 border-0">
                        <h3 class="fw-bolder text-dark mb-0">Chamado <span class="text-primary">#{{ $ticket->id }}</span></h3>
                    </div>

                    <div class="card-body pt-2 pb-4 px-4">

                        <div class="mb-4 p-3 bg-light rounded-3">
                            <div class="row g-2">
                                <div class="col-12"><p class="mb-1"><strong class="text-muted">Título:</strong> <span class="fw-bold">{{ $ticket->titulo }}</span></p></div>
                                <div class="col-12"><p class="mb-1"><strong class="text-muted">Descrição:</strong> {{ $ticket->descricao }}</p></div>
                                <div class="col-md-6"><p class="mb-1"><strong class="text-muted">Categoria:</strong> {{ $ticket->category->nome }}</p></div>
                                <div class="col-md-6"><p class="mb-1"><strong class="text-muted">Tipo:</strong> {{ $ticket->type->nome }}</p></div>
                                <div class="col-md-6"><p class="mb-1"><strong class="text-muted">Prioridade:</strong> <span class="badge bg-{{ strtolower($ticket->prioridade) == 'alta' ? 'danger' : (strtolower($ticket->prioridade) == 'media' ? 'warning' : 'info') }} text-capitalize">{{ ucfirst($ticket->prioridade) }}</span></p></div>
                                <div class="col-md-6"><p class="mb-1"><strong class="text-muted">Aberto por:</strong> {{ $ticket->usuario->name }}</p></div>
                                <div class="col-md-6"><p class="mb-1"><strong class="text-muted">Data de Abertura:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p></div>
                                <div class="col-md-6"><p class="mb-1"><strong class="text-muted">Data do Prazo:</strong> <span class="fw-bold">{{ $ticket->due_at->format('d/m/Y H:i') }}</span></p></div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="mb-0">
                                    <strong class="text-muted">Status:</strong>
                                    @if($ticket->status == 'aberto')
                                        <span class="badge bg-warning text-dark fs-6">Aberto</span>
                                    @elseif($ticket->status == 'andamento')
                                        <span class="badge bg-primary fs-6">Em Andamento</span>
                                    @elseif($ticket->status == 'pendente')
                                        <span class="badge bg-danger fs-6">Pendente</span>
                                    @elseif($ticket->status == 'fechado')
                                        <span class="badge bg-dark fs-6">Fechado</span>
                                    @else
                                        <span class="badge bg-success fs-6">Resolvido</span>
                                    @endif
                                </p>
                            </div>

                            <div class="col-md-6 text-md-end">
                                @if($ticket->tecnico_id)
                                    <p class="mb-0">
                                        <strong class="text-muted">Técnico:</strong>
                                        @if($isTechOrAdmin && auth()->id() === $ticket->tecnico_id)
                                            <span class="badge bg-success">Você ({{ $ticket->tecnico->name }})</span>
                                        @else
                                            <span class="fw-bold">{{ $ticket->tecnico->name }}</span>
                                        @endif
                                    </p>
                                @else
                                    <p class="mb-0"><strong class="text-muted">Técnico:</strong> <span class="text-muted">Nenhum atribuído</span></p>
                                @endif
                            </div>
                        </div>

@if($ticket->resolved_at)
    @php
        $slaDefined   = !is_null($ticket->due_at);
        $resolvidoEm  = $ticket->resolved_at;
        $prazoSLA     = $ticket->due_at;
        $cumpriuSLA   = $slaDefined ? $resolvidoEm->lte($prazoSLA) : null;
        $diffMins     = $slaDefined ? $resolvidoEm->diffInMinutes($prazoSLA) : null;
        $diffHuman    = $diffMins !== null ? \Carbon\CarbonInterval::minutes($diffMins)->cascade()->forHumans(short: true, parts: 2) : null;
    @endphp

    <hr class="my-3">
    <div class="bg-white border rounded-3 p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-flag-checkered me-2"></i>
                Informações de Resolução / SLA
            </h5>
            @if(!is_null($cumpriuSLA))
                <span class="badge {{ $cumpriuSLA ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                    {{ $cumpriuSLA ? 'Cumpriu SLA' : 'Fora do SLA' }}
                </span>
            @endif
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="small text-muted">Resolvido em</div>
                <div class="fw-bold">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="col-md-4">
                <div class="small text-muted">Prazo (SLA)</div>
                <div class="fw-bold">{{ $ticket->due_at ? $ticket->due_at->format('d/m/Y H:i') : '—' }}</div>
            </div>
            <div class="col-md-4">
                <div class="small text-muted">Diferença</div>
                <div class="fw-bold">
                    {{ $diffHuman ? ($cumpriuSLA ? $diffHuman.' de antecedência' : 'atraso de '.$diffHuman) : '—' }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted">Responsável</div>
                <div class="fw-bold">{{ optional($ticket->tecnico)->name ?? optional($ticket->usuario)->name ?? '—' }}</div>
            </div>
            <div class="col-12">
                <div class="small text-muted">Descrição do procedimento</div>
                <div class="fw-bold">{!! nl2br(e($ticket->descricao_resolucao ?? '—')) !!}</div>
            </div>
        </div>
    </div>
@endif


                    </div>
                </div>

                @if($isTechOrAdmin && $ticket->status != 'resolvido' && $ticket->status != 'fechado')

                    {{-- 1. Assumir Ticket --}}
                    @if(!$ticket->tecnico_id || $ticket->tecnico_id !== auth()->id())
                        <div class="card card-body bg-primary text-white shadow-sm mb-3">
                            <h5 class="fw-bold mb-3 d-flex align-items-center"><i class="fas fa-user-check me-2"></i> Assumir Ticket</h5>
                            <form action="{{ route('tickets.assume', $ticket->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-light text-primary fw-bold w-100 py-2">
                                    Assumir Ticket
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- 2. Atribuir/Alterar Técnico (Apenas quem tem permissão, para tickets não resolvidos) --}}
                    @if(auth()->user()->hasPermission('alter_tecnico_responsavel'))
                        <div class="card card-body bg-dark text-white shadow-sm mb-3">
                            <h5 class="fw-bold mb-3 d-flex align-items-center"><i class="fas fa-user-tag me-2"></i> Atribuir/Alterar Técnico</h5>
                            <button type="button" class="btn btn-light text-dark fw-bold w-100 py-2"
                                    data-bs-toggle="modal" data-bs-target="#editTechnicianModal">
                                {{ $ticket->tecnico_id ? 'Alterar Técnico Responsável' : 'Atribuir Técnico' }}
                            </button>
                        </div>
                    @endif

                    {{-- 3. Marcar Pendências --}}
                    @if(auth()->user()->hasPermission('marcar_pendencias') && $ticket->status == 'andamento')
                        <div class="card card-body bg-danger text-white shadow-sm mb-3">
                            <h5 class="fw-bold mb-3 d-flex align-items-center"><i class="fas fa-pause-circle me-2"></i> Marcar Pendência</h5>
                            <form action="{{ route('tickets.markAsPending', $ticket->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="pendencia" id="pendencia" class="form-control" rows="3" placeholder="Descrição da Pendência (Obrigatório)" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-light text-danger fw-bold w-100 py-2"> Registrar Pendências </button>
                            </form>
                        </div>
                    @endif

                    {{-- 4. Marcar como Concluído --}}
                    @if(auth()->user()->hasPermission('concluir_chamado') && in_array($ticket->status, ['andamento', 'pendente']))
                        <div class="card card-body bg-success text-white shadow-sm mb-3">
                            <h5 class="fw-bold mb-3 d-flex align-items-center"><i class="fas fa-check-circle me-2"></i> Concluir Chamado</h5>
                            <form action="{{ route('tickets.markAsCompleted', $ticket->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="descricao_resolucao" id="descricao_resolucao" class="form-control" rows="3" placeholder="Descrição do Procedimento Realizado (Obrigatório)" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-light text-success fw-bold w-100 py-2"> Marcar como Concluído </button>
                            </form>
                        </div>
                    @endif
                @endif

<div class="text-center mt-4">
    @php
        $prev = url()->previous();
        $backUrl = route('user.dashboard');

        if (\Illuminate\Support\Str::contains($prev, '/admin')) {
            $backUrl = route('admin.dashboard'); // ou route('admin.tickets.index')
        } elseif (\Illuminate\Support\Str::contains($prev, '/user')) {
            $backUrl = route('user.dashboard');
        } elseif (auth()->user()?->hasPermission('acessar_admin')) {
            $backUrl = route('admin.dashboard');
        }
    @endphp

    <a href="{{ $backUrl }}" class="btn btn-outline-dark px-4">
        <i class="fas fa-arrow-left me-1"></i> Voltar à Lista
    </a>
</div>


            </div>
        </div>
    </div>


    {{-- MODALS (Mantidos e estilizados) --}}

    {{-- ===== Modal: Alterar Técnico Responsável ===== --}}
    <div class="modal fade" id="editTechnicianModal" tabindex="-1" aria-labelledby="editTechnicianModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-dark text-white rounded-top-4">
                    <h5 class="modal-title" id="editTechnicianModalLabel"><i class="fas fa-user-tag me-2"></i> Alterar Técnico Responsável</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tickets.updateTechnician', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tecnico_id_edit" class="form-label fw-bold">Selecionar Novo Técnico:</label>
                            <select name="tecnico_id" id="tecnico_id_edit" class="form-select form-select-lg" required>
                                <option value="" selected disabled>Escolha um técnico</option>
                                @foreach($assignableUsers as $u)
                                    <option value="{{ $u->id }}" {{ $ticket->tecnico_id == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Modal: Histórico de Tickets ===== --}}
    <div class="modal fade" id="historicoModal" tabindex="-1" aria-labelledby="historicoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="historicoModalLabel"><i class="fas fa-timeline me-2"></i> Histórico do Chamado #{{ $ticket->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-primary">Criação do Chamado</strong>
                                    <small>{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="mb-0">Chamado criado por <span class="fw-bold">{{ $ticket->usuario->name }}</span></p>
                            </div>
                        </div>

                        @foreach($ticket->histories->sortByDesc('created_at') as $history)
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <strong class="text-dark">{{ ucfirst(str_replace('_', ' ', $history->tipo_acao)) }}</strong>
                                        <small>{{ $history->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    @if($history->descricao)
                                        <p class="mb-2 small text-muted">{!! nl2br(e($history->descricao)) !!}</p>
                                    @endif
                                    <small class="text-primary fw-bold">Ação realizada por: {{ $history->user->name }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Modal: Editar Tickets (Mantido) ===== --}}
    <div class="modal fade" id="editTicketModal" tabindex="-1" aria-labelledby="editTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-4 shadow-lg">
                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-dark rounded-top-4">
                        <h5 class="modal-title fw-bold" id="editTicketModalLabel"><i class="fas fa-edit me-2"></i> Editar Chamado #{{ $ticket->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
<label for="titulo" class="form-label fw-bold">Título</label>
                            <input type="text"
                                    id="titulo" name="titulo"
                                    value="{{ old('titulo', $ticket->titulo) }}"
                                    class="form-control form-control-lg @error('titulo','editTicket') is-invalid @enderror" required>
                            @error('titulo','editTicket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-bold">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="4"
                                      class="form-control @error('descricao','editTicket') is-invalid @enderror" required>{{ old('descricao', $ticket->descricao) }}</textarea>
                            @error('descricao','editTicket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="modal_category_id" class="form-label fw-bold">Categoria</label>
                                <select id="modal_category_id" name="category_id"
                                        class="form-select form-select-lg @error('category_id','editTicket') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('category_id', $ticket->category_id) ? '' : 'selected' }}>
                                        Selecione
                                    </option>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}"
                                            {{ (int)old('category_id', $ticket->category_id) === $c->id ? 'selected' : '' }}>
                                            {{ $c->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id','editTicket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text">Ao alterar Categoria/Tipo, a prioridade e o SLA serão recalculados.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="modal_type_id" class="form-label fw-bold">Tipo</label>
                                <select id="modal_type_id" name="type_id"
                                        class="form-select form-select-lg @error('type_id','editTicket') is-invalid @enderror" required disabled>
                                    <option value="">Selecione uma categoria primeiro</option>
                                </select>
                                @error('type_id','editTicket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3 bg-light p-3 rounded-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Prioridade (atual)</label>
                                <input class="form-control form-control-lg text-capitalize" value="{{ $ticket->prioridade }}" disabled>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Prazo (SLA) atual</label>
                                <input class="form-control form-control-lg"
                                        value="{{ $ticket->due_at ? $ticket->due_at->format('d/m/Y H:i') : '—' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning fw-bold">Salvar alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script de controle do Modal de Edição --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if ($errors->hasBag('editTicket') && $errors->editTicket->any())
                var modal = new bootstrap.Modal(document.getElementById('editTicketModal'));
                modal.show();
                @endif

                // Lógica de carregamento de Tipos para o Modal de Edição
                const modalEl = document.getElementById('editTicketModal');
                if (!modalEl) return;

                const cat = modalEl.querySelector('#modal_category_id');
                const typ = modalEl.querySelector('#modal_type_id');

                function resetTypes(placeholder) {
                    typ.innerHTML = `<option value="">${placeholder || 'Selecione uma categoria primeiro'}</option>`;
                    typ.disabled = true;
                }

                async function loadTypes(categoryId, preselectId) {
                    if (!categoryId) { resetTypes(); return; }

                    typ.innerHTML = '<option value="">Carregando tipos...</option>';
                    typ.disabled = true;

                    try {
                        const res = await fetch(`{{ url('/api/categories') }}/${categoryId}/types`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();

                        typ.innerHTML = '';
                        if (!Array.isArray(data) || data.length === 0) {
                            resetTypes('Nenhum tipo para esta categoria');
                            return;
                        }

                        data.forEach(t => {
                            const opt = document.createElement('option');
                            opt.value = t.id;
                            opt.textContent = t.nome;
                            if (preselectId && String(preselectId) === String(t.id)) opt.selected = true;
                            typ.appendChild(opt);
                        });

                        typ.disabled = false;
                    } catch (e) {
                        resetTypes('Erro ao carregar tipos');
                        console.error(e);
                    }
                }

                // Quando o modal abrir, carregue os tipos da categoria atual/old
                modalEl.addEventListener('shown.bs.modal', function () {
                    const initialCat = cat.value || '{{ old('category_id', $ticket->category_id) }}';
                    const initialType = '{{ old('type_id', $ticket->type_id) }}';
                    loadTypes(initialCat, initialType);
                });

                // Quando a categoria mudar dentro do modal
                cat.addEventListener('change', function () {
                    loadTypes(cat.value, null);
                });
            });
        </script>
    @endpush
@endsection

