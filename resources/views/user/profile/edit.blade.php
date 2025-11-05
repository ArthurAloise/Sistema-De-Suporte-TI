@extends('user.layouts.app')

@section('content')
<style>
    /* Estilo para a área da foto de perfil */
    .profile-picture-container {
        width: 120px;
        height: 120px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        cursor: pointer;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 3px solid #f8f9fa; /* Borda clara */
    }
    .profile-picture-container:hover {
        transform: scale(1.05);
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.5); /* Sombra suave vermelha */
    }
    .profile-picture-placeholder {
        background-color: #e9ecef; color: #6c757d;
    }
    .profile-picture-image {
        width: 100%; height: 100%; object-fit: cover;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="d-flex align-items-center gap-3 mb-4">
                {{-- O botão de voltar leva ao dashboard do usuário --}}
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary border-0" title="Voltar ao Painel">
                    <i class="fas fa-arrow-left fs-4"></i>
                </a>
                <div>
                    {{-- Título movido para cá --}}
                    <h1 class="fw-bolder text-primary mb-0">Editar Perfil</h1>
                    <p class="text-muted fs-6 mb-0">Atualize suas informações pessoais e de contato.</p>
                </div>
            </div>
            {{-- Card do Formulário (removido 'my-5') --}}
            <div class="card shadow-lg border-0">

                {{-- O 'card-header' original foi removido daqui, pois o título está acima --}}

                <div class="card-body p-5">

                    @if(session('success'))
                        <div class="alert alert-success text-center mb-4">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="text-center mb-4">
                            <label for="profile_picture" class="d-inline-block">
                                <div class="profile-picture-container {{ !Auth::user()->profile_picture ? 'profile-picture-placeholder' : '' }}"
                                     onclick="document.getElementById('profile_picture').click();"
                                     title="Clique para alterar a foto">
                                    @if(Auth::user()->profile_picture)
                                        <img src="data:image/jpeg;base64,{{ Auth::user()->profile_picture }}"
                                             alt="Foto de perfil" class="profile-picture-image shadow-sm">
                                    @else
                                        <i class="fas fa-user-circle fa-4x"></i>
                                    @endif
                                </div>
                            </label>
                            <p class="text-muted small mt-2 mb-1">Clique na imagem para alterar</p>
                            <p class="text-danger small mb-0">Formatos permitidos: .png, .jpg, .jpeg, .gif (Até 2MB)</p>
                            <input type="file" name="profile_picture" id="profile_picture" class="form-control d-none" accept=".png, .jpg, .jpeg, .gif">
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nome</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg"
                                   value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg"
                                   value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">Telefone/Celular</label>
                            <input type="tel" name="phone" id="phone" class="form-control form-control-lg"
                                   value="{{ old('phone', preg_replace('/[^0-9]/', '', Auth::user()->phone)) }}">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                <i class="fas fa-save me-2"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    {{-- Scripts do jQuery e Inputmask (mantidos como você corrigiu) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>

    <script>
        $(document).ready(function(){
            // Inicializa a máscara de telefone
            $('#phone').inputmask({
                mask: ['(99) 9999-9999', '(99) 99999-9999'],
                numericInput: false,
                keepStatic: true,
                clearIncomplete: true
            });

            // Pré-visualização de imagem
            $('#profile_picture').on('change', function(e) {
                const [file] = e.target.files;
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const container = $('.profile-picture-container');
                        container.removeClass('profile-picture-placeholder').html(
                            `<img src="${e.target.result}" alt="Pré-visualização" class="profile-picture-image shadow-sm">`
                        );
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush

@endsection
