@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Novo Setor</h3>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('setores.store') }}" method="POST" novalidate>
                    @include('admin.setores._form')
                </form>
            </div>
        </div>
    </div>
@endsection

