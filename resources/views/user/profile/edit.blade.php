@extends('user.layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="card mx-auto shadow-lg p-4" style="max-width: 500px; border-radius: 15px;">
            <h2 class="text-center text-danger fw-bold mb-4">Editar Perfil</h2>

            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Foto de Perfil -->
                <div class="text-center mb-4">
                    @if(Auth::user()->profile_picture)
                        <img src="data:image/jpeg;base64,{{ Auth::user()->profile_picture }}"
                             alt="Foto de perfil" class="rounded-circle border shadow-sm"
                             width="120" height="120" style="object-fit: cover; cursor: pointer; transition: 0.3s;"
                             onclick="document.getElementById('profile_picture').click();">
                    @else
                        <img src="https://via.placeholder.com/120"
                             alt="Foto de perfil" class="rounded-circle border shadow-sm"
                             width="120" height="120" style="cursor: pointer; transition: 0.3s;"
                             onclick="document.getElementById('profile_picture').click();">
                    @endif
                    <p class="text-muted small mt-2">Clique na imagem para alterar</p>
                        <p class="text-danger text-danger small mt-2">.png | .gif (Até 2mb)</p>

                    <input type="file" name="profile_picture" id="profile_picture" class="form-control d-none">
                </div>

                <!-- Nome -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Nome</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ Auth::user()->name }}" required>
                </div>

                <!-- E-mail -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ Auth::user()->email }}" required>
                </div>

                <!-- Phone -->
                <div class="mb-3">
                    <label for="phone" class="form-label fw-bold">Telefone/Celular</label>
                    <input type="tel" name="phone" id="phone" class="form-control"
                           value="{{ Auth::user()->phone }}" required>
                </div>

                <!-- Botão de Salvar -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-danger btn-lg px-5" style="border-radius: 30px;">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Adicionando jQuery e Inputmask para máscara -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#phone').inputmask('(99) 99999-9999'); // Máscara para telefone
        });
    </script>


@endsection
