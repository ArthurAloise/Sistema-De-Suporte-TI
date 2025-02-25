@extends('admin.layouts.app')

@section('content')
    <h2>Criar Nova Permissão</h2>

    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

{{--        <div class="mb-3">--}}
{{--            <label for="description" class="form-label">Descrição</label>--}}
{{--            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}">--}}
{{--            @error('description')--}}
{{--            <div class="invalid-feedback">{{ $message }}</div>--}}
{{--            @enderror--}}
{{--        </div>--}}

        <button type="submit" class="btn btn-success">Criar Permissão</button>
    </form>
@endsection
