@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="mb-0">Tipos de Chamado</h3>
            <a href="{{ route('types.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Novo Tipo
            </a>
        </div>

        <form method="GET" class="row g-2 mb-3" action="{{ route('types.index') }}">
            <div class="col-sm-8 col-md-6 col-lg-4">
                <input type="text" name="q" class="form-control" placeholder="Buscar por nome..."
                       value="{{ $search }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                @if($search)
                    <a href="{{ route('types.index') }}" class="btn btn-link">Limpar</a>
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
                            <th style="width:220px;">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($types as $type)
                            <tr>
                                <td>#{{ $type->id }}</td>
                                <td>{{ $type->nome }}</td>
                                <td>
                                    <a href="{{ route('types.edit', $type) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </a>
                                    <form action="{{ route('types.destroy', $type) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este tipo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash me-1"></i> Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Nenhum tipo encontrado.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($types->hasPages())
                <div class="card-footer">
                    {{ $types->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
@endsection
