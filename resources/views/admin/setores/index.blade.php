@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="mb-0">Setores</h3>
            <a href="{{ route('setores.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Novo Setor
            </a>
        </div>

        <form method="GET" class="row g-2 mb-3" action="{{ route('setores.index') }}">
            <div class="col-sm-8 col-md-6 col-lg-4">
                <input type="text" name="q" class="form-control" placeholder="Buscar por nome ou sigla..."
                       value="{{ $search }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                @if($search)
                    <a href="{{ route('setores.index') }}" class="btn btn-link">Limpar</a>
                @endif
            </div>
        </form>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                        <tr>
                            <th style="width:120px;">ID</th>
                            <th>Nome</th>
                            <th>Sigla</th>
                            <th style="width:220px;">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($setores as $setor)
                            <tr>
                                <td>#{{ $setor->id }}</td>
                                <td>{{ $setor->nome }}</td>
                                <td><span class="badge bg-secondary">{{ $setor->sigla }}</span></td>
                                <td>
                                    <a href="{{ route('setores.edit', $setor) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </a>
                                    <form action="{{ route('setores.destroy', $setor) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este setor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash me-1"></i> Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Nenhum setor encontrado.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($setores->hasPages())
                <div class="card-footer">
                    {{ $setores->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
@endsection
