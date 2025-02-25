@extends('user.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Boas-vindas -->
            <div class="col-12">
                <h2 class="fw-bold text-danger">Olá, {{ Auth::user()->name }}!</h2>
                <p class="text-muted">Bem-vindo ao seu painel. Aqui você pode gerenciar seus chamados de TI.</p>
            </div>

            <!-- Cards de Atalhos -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-plus-circle text-danger" style="font-size: 40px;"></i>
                        <h5 class="fw-bold mt-3">Abrir Novo Chamado</h5>
                        <p class="text-muted">Relate um problema e solicite suporte técnico.</p>
                        <a href="" class="btn btn-danger w-100">Abrir Chamado</a> {{--{{ route('chamados.create') }}--}}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-tasks text-primary" style="font-size: 40px;"></i>
                        <h5 class="fw-bold mt-3">Meus Chamados</h5>
                        <p class="text-muted">Acompanhe o status dos seus chamados abertos.</p>
                        <a href="" class="btn btn-primary w-100">Ver Chamados</a> {{--{{ route('chamados.index') }}--}}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-user-cog text-success" style="font-size: 40px;"></i>
                        <h5 class="fw-bold mt-3">Editar Perfil</h5>
                        <p class="text-muted">Atualize suas informações de perfil e senha.</p>
                        <a href="{{ route('user.profile') }}" class="btn btn-success w-100">Editar Perfil</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Chamados Recentes -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="fw-bold text-dark">Chamados Recentes</h4>
                <table class="table table-hover shadow-sm">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Assunto</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
{{--                    @foreach($chamados as $chamado)--}}
{{--                        <tr>--}}
{{--                            <td>#{{ $chamado->id }}</td>--}}
{{--                            <td>{{ $chamado->assunto }}</td>--}}
{{--                            <td>{{ $chamado->created_at->format('d/m/Y') }}</td>--}}
{{--                            <td>--}}
{{--                                @if($chamado->status == 'Aberto')--}}
{{--                                    <span class="badge bg-warning text-dark">Aberto</span>--}}
{{--                                @elseif($chamado->status == 'Em Andamento')--}}
{{--                                    <span class="badge bg-primary">Em Andamento</span>--}}
{{--                                @else--}}
{{--                                    <span class="badge bg-success">Resolvido</span>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <a href="" class="btn btn-sm btn-outline-dark">Ver</a> --}}{{--{{ route('chamados.show', $chamado->id) }}--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
                    </tbody>
                </table>

                @if('success')
                    <p class="text-muted text-center">Nenhum chamado recente.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

