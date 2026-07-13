@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.funcionario') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">{{ __('messages.voltar') }}</a></li>
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
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar funcionario'))
                                <a href="{{ route('funcionarios.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                                <a href="{{ route('create_import.funcionarios') }}" class="btn btn-light-success"><i class="fas fa-file-excel"></i> {{ __('messages.importar_excel') }}</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('pdf-funcionarios') }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($funcionarios)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Número Mec.</th>
                                        <th>Tipo Pessoal</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th> {{ __('messages.genero') }} </th>
                                        <th>{{ __('messages.estado_civil') }}</th>
                                        <th>{{ __('messages.data_nascimento') }}</th>
                                        <th> {{ __('messages.bilhete_identidade') }} </th>
                                        <th>Codigo Postal</th>
                                        <th>{{ __('messages.telefone') }}/{{ __('messages.telemovel') }}</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($funcionarios as $item)
                                    <tr>
                                        <td><a href="{{ route('funcionarios.show', $item->id) }}">{{ $item->numero_mecanografico }} </a></td>
                                        <td>{{ $item->categoria ?? '------' }}</td>
                                        <td><a href="{{ route('funcionarios.show', $item->id) }}">{{ $item->nome }} </a></td>
                                        <td>{{ $item->genero ?? '------' }}</td>
                                        <td>{{ $item->estado_civil->nome ?? '------' }}</td>
                                        <td>{{ $item->data_nascimento ?? '------' }}</td>
                                        <td>{{ $item->nif ?? '------' }}</td>
                                        <td>{{ $item->codigo_postal ?? '------' }}</td>
                                        <td>{{ $item->telefone ?? '--- --- ---' }} / {{ $item->telemovel ?? '--- --- --- ---' }}</td>
                                        @if ($item->status == true)
                                        <td>{{ __('messages.activo') }} </td>
                                        @else
                                        <td>Inactivo</td>
                                        @endif

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar funcionario'))
                                                    <a class="dropdown-item" href="{{ route('scanner-foto-funcionario', $item->id) }}"><i class="fas fa-camera text-light-primary"></i> Scanner Foto </a>
                                                    @endif

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar funcionario'))
                                                    <a class="dropdown-item" href="{{ route('carregar-foto-funcionario', $item->id) }}"><i class="fas fa-image text-light-primary"></i> Carregar Foto </a>
                                                    @endif

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar funcionario'))
                                                    <a class="dropdown-item" href="{{ route('funcionarios.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar funcionario'))
                                                    <a class="dropdown-item" href="{{ route('funcionarios.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif

                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar funcionario'))
                                                    <button class="btn btn-light-danger dropdown-item delete-record" data-id="{{ $item->id ?? "" }}">
                                                        <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                    </button>
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
                    url: `{{ route('funcionarios.destroy', ':id') }}`.replace(':id', recordId)
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
