@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2>Editar Perfil: {{ $role->name }}</h2>
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nome do Perfil</label>
                <input type="text" name="name" id="name" value="{{ $role->name }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <input type="text" name="description" id="description" value="{{ $role->description }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="permissions">Permissões</label><br>
                @foreach($permissions as $permission)
                    <div class="form-check">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                               class="form-check-input"
                               @if(in_array($permission->id, $rolePermissions)) checked @endif>
                        <label class="form-check-label" for="permissions">{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
@endsection
