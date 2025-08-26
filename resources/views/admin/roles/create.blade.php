@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <h2>Criar Perfil</h2>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nome do Perfil</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
{{--            <div class="form-group">--}}
{{--                <label for="description">Descrição</label>--}}
{{--                <input type="text" name="description" id="description" class="form-control">--}}
{{--            </div>--}}
            <div class="form-group">
                <label for="permissions">Permissões</label><br>
                @foreach($permissions as $permission)
                    <div class="form-check">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input">
                        <label class="form-check-label" for="permissions">{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
@endsection
