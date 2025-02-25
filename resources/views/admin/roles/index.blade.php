@extends('admin.layouts.app')

@section('content')
    <h2>Gerenciar Perfis (Roles)</h2>

    <a href="{{ route('roles.create') }}" class="btn btn-success mb-3">Criar Novo Perfil</a>

    <table class="table">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>{{ $role->description }}</td>
                <td>
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm">Editar</a>
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
