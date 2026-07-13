@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Obitos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-hospital') }}">Home</a></li>
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
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($obitos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Número</th>
                                        <th>Paciente</th>
                                        <th>{{ __('messages.genero') }}</th>
                                        <th>{{ __('messages.idade') }}</th>
                                        <th>Atendimento</th>
                                        <th>Comunicado aos famíliares</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>Hora</th>
                                        <th>Pago</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($obitos as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->documento_declaracao }}</td>
                                        <td>{{ $item->paciente->nome }}</td>
                                        <td>{{ $item->paciente->genero }}</td>
                                        <td>{{ $item->paciente->idade($item->paciente->data_nascimento) }} Anos </td>
                                        <td>{{ $item->atendimento->numero }}</td>
                                        <td>{{ $item->comunicacao_obito == 1 ? "Sim" : "Não" }}</td>

                                        @if ($item->status == 'aguardando_morgue')
                                        <td><span class="badge" style="background-color: #fbcdff;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'morgue')
                                        <td><span class="badge" style="background-color: #FFF3CD;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'liberado')
                                        <td><span class="badge" style="background-color: #D4EDDA;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'entregue')
                                        <td><span class="badge" style="background-color: #d7ebf8;">{{ $item->status }}</span>
                                        </td>
                                        @endif
                                        <td>{{ $item->data_obito }}</td>
                                        <td>{{ $item->hora_obito }}</td>
                                        <td>{{ $item->pago }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">

                                                    @if (Auth::user()->can('editar todos'))
                                                    <a href="{{ route('obitos.show', $item->id) }}" class="dropdown-item text-light-primary"><i class="fas fa-info"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif

                                                    @if (Auth::user()->can('editar todos'))
                                                    <a href="{{ route('obitos.edit', $item->id) }}" class="dropdown-item text-light-success"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    @if (Auth::user()->can('editar todos'))
                                                    @if ($item->comunicacao_obito == 1)
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item comunicar-familiares text-light-danger"><i class="fas fa-times"></i> Tirar o comunicado família</a>
                                                    @else
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item comunicar-familiares text-light-primary"><i class="fas fa-check"></i> Comunicar a família</a>
                                                    @endif
                                                    @endif
                                                    @if (Auth::user()->can('editar todos'))
                                                    @if ($item->status != 'morgue')
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item entregar_o_corpo_a_morgue text-light-primary"><i class="fas fa-check"></i> Entregar o corpo a Morgue</a>
                                                    @endif
                                                    @endif
                                                    @if (Auth::user()->can('editar todos'))
                                                    @if ($item->status != 'entregue')
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item entregar_o_corpo_a_familia text-light-dark"><i class="fas fa-info"></i> Entregar o corpo a família</a>
                                                    @endif
                                                    @endif
                                                    @if (Auth::user()->can('eliminar todos'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item delete-record text-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
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
                        @endif

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
                    url: `{{ route('obitos.destroy', ':id') }}`.replace(':id'
                        , recordId)
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

    $(document).on('click', '.comunicar-familiares', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, comunicar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('obito.comunicar_familiares', ':id') }}`.replace(':id'
                        , recordId)
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

    $(document).on('click', '.entregar_o_corpo_a_morgue', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, Entregar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('obito.entreguar-a-morgue', ':id') }}`.replace(':id'
                        , recordId)
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

    $(document).on('click', '.entregar_o_corpo_a_familia', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, Entregar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('obito.entreguar-a-familiares', ':id') }}`.replace(':id'
                        , recordId)
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
