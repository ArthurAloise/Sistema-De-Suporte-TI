@extends('admin.layouts.app')

@section('content')
    <h2>Usuários</h2>

    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('users.create') }}" class="btn btn-primary">Criar Novo Usuário</a>
    </div>

    {{-- Filtros --}}
    <form method="GET" class="row g-2 align-items-end mb-3">
        <div class="col-12 col-md-4">
            <label for="q" class="form-label mb-1">Buscar (nome, e-mail ou telefone)</label>
            <input type="text" id="q" name="q" class="form-control" value="{{ $q ?? '' }}" placeholder="Ex.: Maria, maria@exemplo.com">
        </div>

        <div class="col-12 col-md-4">
            <label for="setor_id" class="form-label mb-1">Filtrar por Setor</label>
            <select id="setor_id" name="setor_id" class="form-select">
                <option value="">Todos</option>
                @foreach($setores as $s)
                    <option value="{{ $s->id }}" {{ ($setorId ?? '') == $s->id ? 'selected' : '' }}>
                        [{{ $s->sigla }}] {{ $s->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-2">
            <label for="per_page" class="form-label mb-1">Por página</label>
            <select id="per_page" name="per_page" class="form-select">
                @foreach([10,15,25,50] as $n)
                    <option value="{{ $n }}" {{ (int)($perPage ?? 15) === $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-2 d-flex gap-2">
            <button class="btn btn-outline-secondary w-100" type="submit">Aplicar</button>
            <a class="btn btn-link w-100" href="{{ route('users.index') }}">Limpar</a>
        </div>
    </form>

    {{-- Resumo dos resultados --}}
    <div class="mb-2 text-muted">
        @if($users->total() > 0)
            Mostrando {{ $users->firstItem() }}–{{ $users->lastItem() }} de {{ $users->total() }} usuários
        @else
            Nenhum usuário encontrado.
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th style="width:70px;">#</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Setor</th>
                <th style="width:180px;">Ações</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->name }}</td>
                    <td>
                        @if($user->setor)
                            <span class="badge bg-danger me-1">[{{ $user->setor->sigla }}]</span>
                            <strong>{{ $user->setor->nome }}</strong>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                              onsubmit="return confirm('Excluir este usuário?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Nenhum usuário encontrado com os filtros atuais.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    @if($users->hasPages())
        <div class="mt-3">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    @endif
@endsection
