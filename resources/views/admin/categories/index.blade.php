@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="mb-0">Categorias</h3>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nova Categoria
            </a>
        </div>

        <form method="GET" class="row g-2 mb-3" action="{{ route('categories.index') }}">
            <div class="col-sm-8 col-md-6 col-lg-4">
                <input type="text" name="q" class="form-control" placeholder="Buscar por nome..."
                       value="{{ $search }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                @if($search)
                    <a href="{{ route('categories.index') }}" class="btn btn-link">Limpar</a>
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
                        @forelse($categories as $category)
                            <tr>
                                <td>#{{ $category->id }}</td>
                                <td>{{ $category->nome }}</td>
                                <td>
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash me-1"></i> Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Nenhuma categoria encontrada.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($categories->hasPages())
                <div class="card-footer">
                    {{ $categories->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
@endsection
