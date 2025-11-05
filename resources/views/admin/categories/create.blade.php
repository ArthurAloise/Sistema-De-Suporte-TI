@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                 <div class="d-flex align-items-center gap-3 mb-4">
                    {{-- Botão Voltar --}}
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary border-0" title="Voltar para Lista de Categorias">
                        <i class="fas fa-arrow-left fs-4"></i>
                    </a>
                    {{-- Título --}}
                    <div>
                        <h1 class="fw-bolder text-primary mb-0">Criar Nova Categoria</h1>
                        <p class="text-muted fs-6 mb-0">Defina os detalhes para a nova categoria de chamado.</p>
                    </div>
                </div>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('categories.store') }}" method="POST" novalidate>
                             {{-- Inclui o formulário parcial --}}
                             {{-- Passa um novo objeto Category vazio para o form --}}
                            @include('admin.categories._form', ['category' => new \App\Models\Category()])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
