@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.orcamentos') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-financeiro') }}">{{ __('messages.voltar') }}</a></li>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos'))
                                <a class="btn btn-light-success" href="{{ route('orcamentos.create') }}"> {{ __('messages.novo') }}</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> IMPRIMIR PDF</a>
                            </div>
                        </div>

                        @if ($orcamentos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>{{ __('messages.data_inicio') }}</th>
                                        <th>{{ __('messages.data_final') }}</th>
                                        <th>Código</th>
                                        <th>Tipo</th>
                                        <th>{{ __('messages.exercicio') }}</th>
                                        <th>{{ __('messages.periodo') }}</th>
                                        <th><span class="float-right">{{ __('messages.accoes') }} </span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orcamentos as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->nome ?? "" }}</td>
                                        <td>{{ $item->status ?? "" }}</td>
                                        <td>{{ $item->data_inicio ?? "" }}</td>
                                        <td>{{ $item->data_final ?? "" }}</td>
                                        <td>{{ $item->codigo ?? "" }}</td>
                                        <td>{{ $item->tipo ?? "" }}</td>
                                        <td>{{ $item->exercicio->nome ?? "" }}</td>
                                        <td>{{ $item->periodo->nome ?? "" }}</td>

                                        <td class="text-right">
                                            <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                            <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('listar todos'))
                                                <a class="dropdown-item" href="{{ route('orcamentos.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                @endif

                                                @if (Auth::user()->can('editar todos'))
                                                <a class="dropdown-item" href="{{ route('orcamentos.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                @endif

                                                {{-- @if (Auth::user()->can('listar todos'))
                                                <a class="dropdown-item" target="_blank" href="{{ route('orcamentos.imprimir', $item->id) }}"><i class="fas fa-print text-light-primary"></i> {{ __('messages.imprimir') }} </a>
                                                @endif --}}

                                                <div class="dropdown-divider"></div>
                                                @if (Auth::user()->can('eliminar todos'))
                                                <button class="btn btn-light-danger dropdown-item delete-record" data-id="{{ $item->id ?? "" }}">
                                                    <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                </button>
                                                @endif
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
                    url: `{{ route('orcamentos.destroy', ':id') }}`.replace(':id', recordId)
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
