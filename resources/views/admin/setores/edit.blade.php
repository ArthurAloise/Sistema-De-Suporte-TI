@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Editar Setor</h3>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('setores.update', $setor) }}" method="POST" novalidate>
                    @method('PUT')
                    @include('admin.setores._form')
                </form>
            </div>
        </div>
    </div>
@endsection

