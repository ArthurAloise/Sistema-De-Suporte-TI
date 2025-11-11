@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center gap-3"> {{-- Agrupando Voltar e Título --}}

                {{-- Botão Voltar --}}
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary border-0" title="Voltar ao Painel Principal">
                    <i class="fas fa-arrow-left fs-4"></i> {{-- Ícone de seta maior --}}
                </a>

                {{-- Título e Descrição --}}
                <div>
                    <h1 class="fw-bolder text-primary mb-0">Gerenciamento de Usuários</h1> {{-- Removido mb-1 --}}
                    <p class="text-muted fs-6 mb-0">Visualize, crie e edite os usuários do sistema.</p> {{-- Ajustado fs-5 para fs-6 --}}
                </div>
            </div>

            {{-- Botão Criar Novo Usuário --}}
            <a href="{{ route('users.create') }}" class="btn btn-primary fw-bold shadow-sm mt-2 mt-md-0"> {{-- Adicionado margin-top responsivo --}}
                <i class="fas fa-plus me-1"></i> Criar Novo Usuário
            </a>
        </div>

        <div class="card shadow-lg border-0 rounded-4">

            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <form method="GET" class="row g-3 align-items-end mb-3">
                    <div class="col-12 col-md-4">
                        <label for="q" class="form-label mb-1 small fw-bold">Buscar (nome, e-mail ou telefone)</label>
                        <input type="text" id="q" name="q" class="form-control form-control-sm" value="{{ $q ?? '' }}" placeholder="Ex.: Maria, maria@exemplo.com">
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="setor_id" class="form-label mb-1 small fw-bold">Filtrar por Setor</label>
                        <select id="setor_id" name="setor_id" class="form-select form-select-sm">
                            <option value="">Todos os Setores</option>
                            @foreach($setores as $s)
                                <option value="{{ $s->id }}" {{ ($setorId ?? '') == $s->id ? 'selected' : '' }}>
                                    [{{ $s->sigla }}] {{ $s->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label for="per_page" class="form-label mb-1 small fw-bold">Por página</label>
                        <select id="per_page" name="per_page" class="form-select form-select-sm">
                            @foreach([10,15,25,50] as $n)
                                <option value="{{ $n }}" {{ (int)($perPage ?? 15) === $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3 d-flex gap-2">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="fas fa-filter me-1"></i> Aplicar
                        </button>
                        <a class="btn btn-outline-secondary w-100" href="{{ route('users.index') }}">
                            <i class="fas fa-undo me-1"></i> Limpar
                        </a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <div class="small text-muted px-3 mb-2">
                    @if($users->total() > 0)
                        Mostrando {{ $users->firstItem() }}–{{ $users->lastItem() }} de {{ $users->total() }} usuários
                    @else
                        Nenhum usuário encontrado.
                    @endif
                </div>

                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light sticky-top shadow-sm">
                    <tr>
                        <th class="ps-4" style="width:70px;">#</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Perfil</th>
                        <th>Setor</th>
                        <th class="text-center" style="width:180px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $user->id }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-secondary">{{ $user->role->name }}</span></td>
                            <td>
                                @if($user->setor)
                                    <span class="badge bg-primary me-1">[{{ $user->setor->sigla }}]</span>
                                    <span class="fw-bold">{{ $user->setor->nome }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Editar Usuário">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4"><i class="fas fa-info-circle me-2"></i>Nenhum usuário encontrado com os filtros atuais.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if($users->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-center">
                        {{ $users->links('pagination::bootstrap-4') }}
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
                    <p class="fs-5 mb-1">Tem certeza que deseja excluir o usuário <strong id="username-to-delete"></strong>?</p>
                    <p class="text-danger small fw-bold">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    {{-- Este botão acionará a submissão do formulário via JS --}}
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
        // Certifique-se que o Bootstrap 5 está carregado para instanciar o modal
        const confirmDeleteModal = new bootstrap.Modal(confirmDeleteModalElement);

        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        const usernameToDeleteSpan = document.getElementById('username-to-delete');
        let deleteUrl = ''; // Variável para guardar a URL de exclusão

        // 1. Configurar o Modal quando um botão de exclusão é clicado
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function () {
                // Pega a URL de exclusão e o nome do usuário do botão clicado
                deleteUrl = this.getAttribute('data-action');
                const username = this.getAttribute('data-username');

                // Atualiza o nome do usuário no modal
                usernameToDeleteSpan.textContent = username;
            });
        });

        // 2. Lidar com o clique no botão "Confirmar Exclusão" DENTRO do modal
        confirmDeleteButton.addEventListener('click', function () {
            if (deleteUrl) {
                // Cria um formulário na memória
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl; // URL da rota destroy
                form.style.display = 'none'; // Não precisa ser visível

                // Input para o método DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Input para o token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                } else {
                    console.error('CSRF token meta tag not found!');
                    alert('Erro de segurança. Token CSRF não encontrado.'); // Alerta para o usuário
                    return; // Não submete se não houver token
                }

                // Adiciona o formulário ao corpo do documento
                document.body.appendChild(form);

                // Submete o formulário
                form.submit();

                // Opcional: Desabilitar botão para evitar cliques duplos enquanto submete
                confirmDeleteButton.disabled = true;
                confirmDeleteButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...';

            } else {
                console.error('Delete URL not set.');
                alert('Ocorreu um erro ao tentar excluir. URL não definida.');
            }
        });

        // 3. Limpar a URL quando o modal for fechado
        confirmDeleteModalElement.addEventListener('hidden.bs.modal', function () {
            deleteUrl = ''; // Reseta a URL
            usernameToDeleteSpan.textContent = ''; // Limpa o nome
             // Reabilita o botão se ele foi desabilitado
            confirmDeleteButton.disabled = false;
            confirmDeleteButton.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Confirmar Exclusão';
        });

        // Auto-submit para mudança no select 'Por página'
        const perPageSelect = document.getElementById('per_page');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
    });
</script>
@endpush

