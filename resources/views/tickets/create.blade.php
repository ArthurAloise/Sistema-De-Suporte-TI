@extends('user.layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Criar Ticket</div>
                    <div class="card-body">
                        <form action="{{ route('tickets.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Categoria</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipo</label>
                                <select class="form-select" id="type_id" name="type_id" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-danger mb-3">
                                A prioridade será definida automaticamente com base no Tipo/Categoria (ITIL).
                            </p>
                            <button type="submit" class="btn btn-primary">Criar Ticket</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
