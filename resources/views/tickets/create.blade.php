@extends('user.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <h2 class="fw-bold mb-4">Abrir Novo Chamado</h2>
            <p class="text-muted fs-5 mb-4">Relate seu problema ou solicite suporte preenchendo os detalhes abaixo.</p>

            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="fw-bold mb-0 text-center">Formulário de Criação de Ticket</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('tickets.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="titulo" class="form-label fw-bold">Título <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Ex: Meu computador não liga" required>
                            @error('titulo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-bold">Descrição <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="6" placeholder="Descreva o problema em detalhes..." required>{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-bold">Categoria <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="category_id" name="category_id" required>
                                <option value="" disabled {{ !old('category_id') ? 'selected' : '' }}>Selecione uma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->nome }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type_id" class="form-label fw-bold">Tipo <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="type_id" name="type_id" required disabled>
                                <option value="">Selecione uma categoria primeiro</option>
                            </select>
                            @error('type_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info small d-flex align-items-center mb-4" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                A **Prioridade** será definida automaticamente com base no Tipo/Categoria (padrão ITIL).
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                <i class="fas fa-paper-plane me-2"></i> Criar Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        (function(){
            const cat = document.getElementById('category_id');
            const typ = document.getElementById('type_id');
            const typePreselect = '{{ old('type_id') }}';

            async function loadTypes(categoryId) {
                // Estado de carregamento
                typ.innerHTML = '<option value="">Carregando tipos...</option>';
                typ.disabled = true;

                // Checa se há uma categoria selecionada
                if (!categoryId) {
                    typ.innerHTML = '<option value="">Selecione uma categoria primeiro</option>';
                    return;
                }

                try {
                    // Chamada à API
                    const res = await fetch(`{{ url('/api/categories') }}/${categoryId}/types`, {
                        headers: {'X-Requested-With':'XMLHttpRequest'}
                    });

                    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

                    const data = await res.json();

                    // Preencher o select de Tipos
                    typ.innerHTML = '';
                    if (!data.length) {
                        typ.innerHTML = '<option value="">Nenhum tipo para esta categoria</option>';
                        typ.disabled = true;
                        return;
                    }

                    data.forEach(t => {
                        const opt = document.createElement('option');
                        opt.value = t.id;
                        opt.textContent = t.nome;

                        // Selecionar o tipo se houver um valor antigo (old)
                        if (typePreselect && String(typePreselect) === String(t.id)) {
                            opt.selected = true;
                        }
                        typ.appendChild(opt);
                    });

                    typ.disabled = false;

                } catch (error) {
                    console.error('Erro ao carregar tipos:', error);
                    typ.innerHTML = '<option value="">Erro ao carregar tipos</option>';
                    typ.disabled = true;
                }
            }

            // Listener para mudança de categoria
            cat.addEventListener('change', () => loadTypes(cat.value));

            // Carregar tipos ao iniciar se já houver uma categoria selecionada (útil para revalidação)
            @if(old('category_id'))
                loadTypes('{{ old('category_id') }}');
            @endif
        })();
    </script>
@endpush
