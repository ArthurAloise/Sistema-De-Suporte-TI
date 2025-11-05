@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

  <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
      <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary border-0" title="Voltar ao Painel Principal">
        <i class="fas fa-arrow-left fs-4"></i>
      </a>
      <div>
        <h1 class="fw-bolder text-primary mb-0">Gerenciar Perfis (Roles)</h1>
        <p class="text-muted fs-6 mb-0">Visualize, crie e edite os perfis de acesso do sistema.</p>
      </div>
    </div>
    <a href="{{ route('roles.create') }}" class="btn btn-success fw-bold shadow-sm mt-2 mt-md-0">
      <i class="fas fa-plus me-1"></i> Criar Novo Perfil
    </a>
  </div>

  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-2">
      <p class="text-muted small mb-1">Listando todos os perfis cadastrados.</p>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-striped align-middle mb-0">
        <thead class="table-light sticky-top shadow-sm">
          <tr>
            <th class="ps-4">Nome</th>
            <th class="text-center" style="width:180px;">Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($roles as $role)
            <tr>
              <td class="ps-4 fw-semibold">{{ $role->name }}</td>
              <td class="text-center">
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm" title="Editar Perfil">
                  <i class="fas fa-edit"></i> Editar
                </a>

                <button type="button" class="btn btn-danger btn-sm delete-role-btn ms-1"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmDeleteModal"
                        data-action="{{ route('roles.destroy', $role->id) }}"
                        data-rolename="{{ $role->name }}"
                        title="Excluir Perfil">
                  <i class="fas fa-trash-alt"></i> Excluir
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="2" class="text-center text-muted py-4">
                <i class="fas fa-info-circle me-2"></i>Nenhum perfil encontrado.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- Modal de confirmação --}}
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
        <p class="fs-5 mb-1">Tem certeza que deseja excluir o perfil <strong id="rolename-to-delete"></strong>?</p>
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
  const confirmDeleteButton = document.getElementById('confirmDeleteButton');
  const roleNameToDeleteSpan = document.getElementById('rolename-to-delete');
  let deleteUrl = '';

  document.querySelectorAll('.delete-role-btn').forEach(button => {
    button.addEventListener('click', function () {
      deleteUrl = this.getAttribute('data-action');
      roleNameToDeleteSpan.textContent = this.getAttribute('data-rolename') || '';
    });
  });

  confirmDeleteButton.addEventListener('click', function () {
    if (!deleteUrl) return;

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
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken || '';
    form.appendChild(csrfInput);

    document.body.appendChild(form);
    confirmDeleteButton.disabled = true;
    confirmDeleteButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...';
    form.submit();
  });

  confirmDeleteModalElement.addEventListener('hidden.bs.modal', function () {
    deleteUrl = '';
    roleNameToDeleteSpan.textContent = '';
    confirmDeleteButton.disabled = false;
    confirmDeleteButton.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Confirmar Exclusão';
  });
});
</script>
@endpush
