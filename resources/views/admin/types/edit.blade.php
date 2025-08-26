@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Editar Tipo</h3>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('types.update', $type) }}" method="POST" novalidate>
                    @method('PUT')
                    @include('admin.types._form')
                </form>
            </div>
        </div>
    </div>
@endsection
