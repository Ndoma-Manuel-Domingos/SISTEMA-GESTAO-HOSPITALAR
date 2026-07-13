@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('tarefarios.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Tarifário</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        @if ($tarefario)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> {{ __('messages.designacao') }} </th>
                                        <th>{{ __('messages.valor') }}</th>
                                        <th>Modo Tarifário</th>
                                        <th>Tipo Cobrança</th>
                                        <th>{{ __('messages.estados') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $tarefario->id }}</td>
                                        <td>{{ $tarefario->nome }}</td>
                                        <td>{{ number_format($tarefario->valor ??0 , 2, ',', '.')  }}</td>
                                        <td>{{ $tarefario->modo_tarefario ?? '' }}</td>
                                        <td>{{ $tarefario->tipo_cobranca ?? '' }}</td>
                                        <td>{{ $tarefario->status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix d-flex">
                            <a href="{{ route('tarefarios.edit', $tarefario->id) }}" class="btn btn-light-success mx-1">
                                <i class="fas fa-edit"></i> {{ __('messages.editar') }}
                            </a>
                            <a href="{{ route('tarefarios.associar_tarefario', $tarefario->id) }}" class="btn btn-light-primary mx-1">
                                <i class="fas fa-edit"></i> Associonar à Quartos
                            </a>
                            <button class="btn btn-light-danger btn-sm float-right delete-record-tarifario" data-id="{{ $tarefario->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>
                        </div>
                        @endif
                    </div>
                    <!-- /.card -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Quartos Associados ao Tarifário</h5>
                        </div>
                        @if ($tarefario->tarefarios)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> {{ __('messages.designacao') }} </th>
                                        <th>Tipo</th>
                                        <th>Andar</th>
                                        <th>Ocupação</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th> {{ __('messages.descricao') }} </th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tarefario->tarefarios as $q)
                                    <tr>
                                        <td><a href="{{ route('quartos.show', $q->quarto->id) }}">{{ $q->quarto->id }}</a></td>
                                        <td><a href="{{ route('quartos.show', $q->quarto->id) }}">{{ $q->quarto->nome ?? '' }}</a></td>
                                        <td>{{ $q->quarto->tipo->nome ?? '' }}</td>
                                        <td>{{ $q->quarto->andar->nome ?? '' }}</td>
                                        <td>{{ $q->quarto->solicitar_ocupacao ?? '' }}</td>
                                        <td>{{ $q->quarto->status }}</td>
                                        <td>{{ $q->quarto->descricao }}</td>
                                        <td>
                                            <button class="btn btn-light-danger btn-sm float-right delete-record" data-id="{{ $q->id }}">
                                                <i class="fas fa-trash text-light-danger"></i> Desassociar do Quarto
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.quartoiner-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    $(document).on('click', '.delete-record-tarifario', function(e) {
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
                    url: `{{ route('tarefarios.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });


    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, desassociar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('tarefarios.desassociar_tarefario', ':id') }}`.replace(':id', recordId)
                    , method: 'GET'
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
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
