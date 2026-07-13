@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.requisicoes') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-logistica') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('requisacoes.index') }}" method="get" class="mt-3">
                        <div class="card">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label">{{ __('messages.estados') }}</label>
                                        <select type="text" class="form-control select2" name="tipo_documento">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            <option value="pendente" {{ $requests['tipo_documento'] == "pendente" ? 'selected' : '' }}>Pendentes</option>
                                            <option value="rejeitada" {{ $requests['tipo_documento'] == "rejeitada" ? 'selected' : '' }}>Rejeitada</option>
                                            <option value="aprovada" {{ $requests['tipo_documento'] == "aprovada" ? 'selected' : '' }}>Aprovada</option>
                                            <option value="rascunho" {{ $requests['tipo_documento'] == "rascunho" ? 'selected' : '' }}>Rascunho</option>
                                        </select>
                                        <p class="text-light-danger">
                                            @error('tipo_documento')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label">{{ __('messages.data_inicio') }}</label>
                                        <input type="date" class="form-control" name="data_inicio" value="{{ $requests['data_inicio'] ?? '' }}">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label">{{ __('messages.data_final') }}</label>
                                        <input type="date" class="form-control" name="data_final" value="{{ $requests['data_final'] ?? '' }}">
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary ml-2 text-right"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar requisacao'))
                                <a href="{{ route('requisacoes.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('imprimir-requisicao-colectivas', ['tipo_documento' => $requests['tipo_documento'] ?? "", 'data_inicio' => $requests['data_inicio'] ?? "", 'data_final' => $requests['data_final'] ?? "" ]) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nº Requisição</th>
                                        <th>Requisitante</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">Qtd Produtos</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requisicoes as $item)
                                    <tr>
                                        <td><a href="{{ route('requisacoes.show', $item->id) }}">REQ Nº {{ $item->id ?? "" }}</a></td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->data_emissao }}</td>

                                        @if ($item->status == 'pendente')
                                        <td><span class="bg-light-primary p-1 text-uppercase">{{ $item->status }}</span></td>
                                        @endif

                                        @if ($item->status == 'aprovada')
                                        <td><span class="bg-light-success p-1 text-uppercase">{{ $item->status }}</span></td>
                                        @endif

                                        @if ($item->status == 'rejeitada')
                                        <td><span class="bg-light-danger p-1 text-uppercase">{{ $item->status }}</span></td>
                                        @endif

                                        @if ($item->status == 'rascunho')
                                        <td><span class="bg-light-warning p-1 text-uppercase">{{ $item->status }}</span></td>
                                        @endif

                                        <td class="text-right">{{ count($item->items) }}</td>

                                        <td>
                                            <div class="btn-group float-right">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">


                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar requisacao'))
                                                    <a class="dropdown-item" href="{{ route('requisacoes.show', $item->id) }}"> {{ __('messages.mais_detalhes') }}</a>
                                                    @endif

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar requisacao'))
                                                    <a class="dropdown-item" href="{{ route('requisacoes.edit', $item->id) }}">{{ __('messages.actualizar') }}</a>
                                                    @endif


                                                    @if (Auth::user()->can('aprovar requisicao'))
                                                    @if ($item->status != 'aprovada')
                                                    <a class="dropdown-item text-light-success" href="{{ route('requisacoes.aprovada', $item->id) }}">Marcar como Apravada</a>
                                                    @endif
                                                    @endif

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar requisacao'))
                                                    @if ($item->status != 'rascunho')
                                                    <a class="dropdown-item text-light-warning" href="{{ route('requisacoes.rascunho', $item->id) }}">Marcar como Rascunho</a>
                                                    @endif
                                                    @endif

                                                    @if (Auth::user()->can('rejeitar requisicao'))
                                                    @if ($item->status != 'rejeitada')
                                                    <a class="dropdown-item text-light-danger" href="{{ route('requisacoes.rejeitar', $item->id) }}">Marcar como Rejeitado</a>
                                                    @endif
                                                    @endif

                                                    <div class="dropdown-divider"></div>

                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar requisacao'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item delete-record text-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                                                    @endif

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar requisacao'))
                                                    <a class="dropdown-item" target="_blank" href="{{ route('imprimir-requisicao-individual', $item->id) }}"><i class="fas fa-print"></i> {{ __('messages.imprimir') }}</a>
                                                    @endif


                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, excluir!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('requisacoes.destroy', ':id') }}`.replace(':id', recordId)
                    , method: 'DELETE'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });


    $(function() {
        $("#carregar_tabela").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
