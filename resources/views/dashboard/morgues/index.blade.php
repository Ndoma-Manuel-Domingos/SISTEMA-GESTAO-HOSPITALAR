@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.morgue') }}</h1>
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
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos'))
                                <a href="{{ route('morgues.create') }}" class="btn btn-light-primary">
                                    <i class="fas fa-plus"></i> {{ __('messages.novo') }}
                                </a>
                                @endif
                            </h3>

                            {{-- <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div> --}}
                        </div>

                        @if ($morgues)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>Obito</th>
                                        <th>Camara</th>
                                        <th>Gaveta</th>
                                        <th>Data Entrada</th>
                                        <th>Hora Entrada</th>
                                        <th>Data Liberação</th>
                                        <th>Hora Liberação</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($morgues as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->status ?? "" }}</td>
                                        <td>{{ $item->obito->documento_declaracao ?? "" }}</td>
                                        <td>{{ $item->camara->nome ?? "" }}</td>
                                        <td>{{ $item->gaveta->nome ?? "" }}</td>
                                        <td>{{ $item->data_entrada_morgue ?? "" }}</td>
                                        <td>{{ $item->hora_entrada_morgue ?? "" }}</td>
                                        <td>{{ $item->data_liberacao ?? "" }}</td>
                                        <td>{{ $item->hora_liberacao ?? "" }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">

                                                    @if (Auth::user()->can('editar todos'))
                                                    <a href="{{ route('morgues.show', $item->id) }}" class="dropdown-item text-light-primary"><i class="fas fa-info"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif

                                                    @if (Auth::user()->can('editar todos'))
                                                    @if ($item->status != 'liberado')
                                                    <a href="{{ route('morgues.liberacao', $item->id) }}" class="dropdown-item text-light-primary"><i class="fas fa-info"></i> Liberação do Corpo</a>
                                                    @endif
                                                    @endif

                                                    @if (Auth::user()->can('editar todos'))
                                                    <a href="{{ route('morgues.edit', $item->id) }}" class="dropdown-item text-light-success"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif

                                                    @if (Auth::user()->can('eliminar todos'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item delete-record text-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                                                    @endif

                                                    <div class="dropdown-divider"></div>
                                                    @if ($item->status == 'liberado')
                                                    <a href="{{ route("morgues.liberacao-imprimir", $item->liberacao->id) }}" target="_blank" class="dropdown-item text-light-danger"><i class="fas fa-file-pdf"></i> Imprimir Ficha Liberação</a>
                                                    @endif
                                                    <a href="{{ route("morgues.imprimir", $item->id) }}" target="_blank" class="dropdown-item text-light-danger"><i class="fas fa-file-pdf"></i> Imprimir Ficha Registro Morgue</a>

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
                    url: `{{ route('morgues.destroy', ':id') }}`.replace(':id'
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
