@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="d-flex align-items-center gap-3 mb-4">
                    {{-- Botão Voltar --}}
                    <a href="{{ route('setores.index') }}" class="btn btn-outline-secondary border-0" title="Voltar para Lista de Setores">
                        <i class="fas fa-arrow-left fs-4"></i>
                    </a>
                    {{-- Título --}}
                    <div>
                        <h1 class="fw-bolder text-warning mb-0">Editar Setor</h1>
                        <p class="text-muted fs-6 mb-0">Modifique os dados do setor <span class="fw-bold">{{ $setor->nome }}</span>.</p>
                    </div>
                </div>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('setores.update', $setor) }}" method="POST" novalidate>
                            @method('PUT')
                            {{-- Inclui o formulário parcial --}}
                            @include('admin.setores._form', ['setor' => $setor])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
