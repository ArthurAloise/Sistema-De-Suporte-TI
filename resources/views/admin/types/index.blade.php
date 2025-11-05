@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-3">
                {{-- Botão Voltar --}}
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary border-0" title="Voltar ao Painel Principal">
                    <i class="fas fa-arrow-left fs-4"></i>
                </a>
                {{-- Título e Descrição --}}
                <div>
                    <h1 class="fw-bolder text-primary mb-0">Tipos de Chamado</h1>
                    <p class="text-muted fs-6 mb-0">Gerencie os tipos que podem ser associados aos chamados.</p>
                </div>
            </div>
            <a href="{{ route('types.create') }}" class="btn btn-primary fw-bold shadow-sm mt-2 mt-md-0">
                <i class="fas fa-plus me-1"></i> Novo Tipo
            </a>
        </div>

        <div class="card shadow-lg border-0 rounded-4">

            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <form method="GET" class="row g-2 align-items-center mb-3" action="{{ route('types.index') }}">
                    <div class="col flex-grow-1">
                        <label for="q" class="visually-hidden">Buscar</label>
                        <input type="text" id="q" name="q" class="form-control form-control-sm" placeholder="Buscar por nome..." value="{{ $search }}">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                        @if($search)
                            <a href="{{ route('types.index') }}" class="btn btn-outline-secondary btn-sm ms-1" title="Limpar Busca">
                                <i class="fas fa-undo"></i> Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light sticky-top shadow-sm">
                    <tr>
                        <th class="ps-4" style="width:120px;">ID</th>
                        <th>Nome</th>
                        <th>Categoria Padrão</th>
                        <th>Prioridade Padrão</th>
                        <th>SLA (Horas)</th>
                        <th class="text-center" style="width:180px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($types as $type)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">#{{ $type->id }}</td>
                            <td class="fw-semibold">{{ $type->nome }}</td>
                            <td>
                                @if($type->category)
                                    {{ $type->category->nome }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($type->default_priority)
                                    <span class="badge bg-secondary text-capitalize">{{ $type->default_priority }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                             <td>
                                @if($type->sla_hours)
                                    {{ $type->sla_hours }}h
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('types.edit', $type) }}" class="btn btn-warning btn-sm" title="Editar Tipo">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                {{-- Botão que abre o modal de exclusão --}}
                                <button type="button" class="btn btn-danger btn-sm delete-type-btn ms-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal"
                                        data-action="{{ route('types.destroy', $type) }}"
                                        data-typename="{{ $type->nome }}"
                                        title="Excluir Tipo">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4"><i class="fas fa-info-circle me-2"></i>Nenhum tipo encontrado.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if($types->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-center">
                        {{ $types->appends(request()->except('page'))->links('pagination::bootstrap-4') }} {{-- Mantém filtros na paginação --}}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4 border-0">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title fw-bold" id="confirmDeleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Confirmar Exclusão
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="fs-5 mb-1">Tem certeza que deseja excluir o tipo <strong id="typename-to-delete"></strong>?</p>
                    <p class="text-danger small fw-bold">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger fw-bold px-4" id="confirmDeleteButton">
                        <i class="fas fa-trash-alt me-1"></i> Confirmar Exclusão
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmDeleteModalElement = document.getElementById('confirmDeleteModal');
        const confirmDeleteModal = new bootstrap.Modal(confirmDeleteModalElement);
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        const typeNameToDeleteSpan = document.getElementById('typename-to-delete');
        let deleteUrl = '';

        // Configurar o Modal
        document.querySelectorAll('.delete-type-btn').forEach(button => {
            button.addEventListener('click', function () {
                deleteUrl = this.getAttribute('data-action');
                const typeName = this.getAttribute('data-typename');
                typeNameToDeleteSpan.textContent = typeName;
            });
        });

        // Lidar com a confirmação
        confirmDeleteButton.addEventListener('click', function () {
            if (deleteUrl) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                } else {
                    console.error('CSRF token meta tag not found!');
                    alert('Erro de segurança. Token CSRF não encontrado.');
                    return;
                }

                document.body.appendChild(form);
                form.submit();

                confirmDeleteButton.disabled = true;
                confirmDeleteButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...';
            } else {
                console.error('Delete URL not set.');
                alert('Ocorreu um erro ao tentar excluir. URL não definida.');
            }
        });

        // Limpar ao fechar
        confirmDeleteModalElement.addEventListener('hidden.bs.modal', function () {
            deleteUrl = '';
            typeNameToDeleteSpan.textContent = '';
            confirmDeleteButton.disabled = false;
            confirmDeleteButton.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Confirmar Exclusão';
        });
    });
</script>
@endpush
