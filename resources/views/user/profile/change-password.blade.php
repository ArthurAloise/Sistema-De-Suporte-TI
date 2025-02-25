@extends('user.layouts.app')
@section('content')
    <div class="container">
        <h2>Alterar Senha</h2>

        <form action="{{ route('user.change-password.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="current_password">Senha Atual</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="new_password">Nova Senha</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Confirmar Nova Senha</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Alterar Senha</button>
        </form>
    </div>
@endsection
