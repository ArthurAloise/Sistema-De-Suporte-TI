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
                                <label for="category_id" class="form-label">Categoria</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" disabled selected>Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>{{ $category->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="type_id" class="form-label">Tipo</label>
                                <select class="form-select" id="type_id" name="type_id" required disabled>
                                    <option value="">Selecione uma categoria primeiro</option>
                                </select>
                            </div>
{{--                            <div class="mb-3">--}}
{{--                                <label for="category" class="form-label">Categoria</label>--}}
{{--                                <select class="form-select" id="category_id" name="category_id" required>--}}
{{--                                    @foreach($categories as $category)--}}
{{--                                        <option value="{{ $category->id }}">{{ $category->nome }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="mb-3">--}}
{{--                                <label for="type" class="form-label">Tipo</label>--}}
{{--                                <select class="form-select" id="type_id" name="type_id" required>--}}
{{--                                    @foreach($types as $type)--}}
{{--                                        <option value="{{ $type->id }}">{{ $type->nome }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
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
@push('scripts')
    <script>
        (function(){
            const cat = document.getElementById('category_id');
            const typ = document.getElementById('type_id');

            async function loadTypes(categoryId, preselect = null) {
                typ.innerHTML = '<option value="">Carregando tipos...</option>';
                typ.disabled = true;

                const res = await fetch(`{{ url('/api/categories') }}/${categoryId}/types`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
                const data = await res.json();

                typ.innerHTML = '';
                if (!data.length) {
                    typ.innerHTML = '<option value="">Nenhum tipo para esta categoria</option>';
                    typ.disabled = true;
                    return;
                }

                data.forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t.id; opt.textContent = t.nome;
                    if (preselect && String(preselect) === String(t.id)) opt.selected = true;
                    typ.appendChild(opt);
                });
                typ.disabled = false;
            }

            // on change
            cat.addEventListener('change', () => loadTypes(cat.value, null));

            // restaurar seleção após erro de validação
            @if(old('category_id'))
            loadTypes('{{ old('category_id') }}', '{{ old('type_id') }}');
            @endif
        })();
    </script>
@endpush
