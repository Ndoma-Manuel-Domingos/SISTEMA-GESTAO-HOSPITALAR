@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.fornecedores') }}</h1>
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

            <div class="card">
                <div class="card-header">
                    <h6>Dívidas a Fornecedores</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text text-right">Saldo</span>
                                    <h5 class="info-box-number text-right text-light-danger">
                                        {{ number_format($saldo, 2, ',', '.') }} {{ $empresa->empresa->moeda }}</h5>
                                    <span class="info-box-text text-right">Valor que deve aos Fornecedores</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text text-right">Dívida Corrente</span>
                                    <h5 class="info-box-number text-right">
                                        {{ number_format($dividaCorrente, 2, ',', '.') }} {{ $empresa->empresa->moeda }}
                                    </h5>
                                    <span class="info-box-text text-right">Não existem pagamentos pendentes</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text text-right">Dívida Vencida</span>
                                    <h5 class="info-box-number text-right">
                                        {{ number_format($dividaVencida, 2, ',', '.') }} {{ $empresa->empresa->moeda }}
                                    </h5>
                                    <span class="info-box-text text-right">Não existem pagamentos fora do prazo</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <!-- /.col -->
                    </div>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar fornecedores'))
                                <a href="{{ route('fornecedores.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                                <a href="{{ route('create_import.fornecedores') }}" class="btn btn-light-success"><i class="fas fa-file-excel"></i> {{ __('messages.importar_excel') }}</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($fornecedores)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Conta</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>{{ __('messages.bilhete_identidade') }}</th>
                                        <th>Tipo Fornecedor</th>
                                        <th>Tipo Pessoa</th>
                                        <th>Codigo Postal</th>
                                        <th>{{ __('messages.telefone') }}/{{ __('messages.telemovel') }}</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fornecedores as $item)
                                    <tr>
                                        <td>{{ $item->conta ?? '------' }}</td>
                                        <td><a href="{{ route('fornecedores.show', $item->id) }}">{{ $item->nome }}
                                            </a></td>
                                        <td>{{ $item->nif ?? '------' }}</td>
                                        <td>{{ $item->tipo_fornecedor ?? '------' }}</td>
                                        <td>{{ $item->tipo_pessoa ?? '------' }}</td>
                                        <td>{{ $item->codigo_postal ?? '------' }}</td>
                                        <td>{{ $item->telefone ?? '--- --- ---' }} /
                                            {{ $item->telemovel ?? '--- --- --- ---' }}</td>
                                        @if ($item->status == true)
                                        <td>{{ __('messages.activo') }} </td>
                                        @else
                                        <td>Inactivo</td>
                                        @endif

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar fornecedores'))
                                                    <a class="dropdown-item" href="{{ route('fornecedores.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif
                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar fornecedores'))
                                                    <a class="dropdown-item" href="{{ route('fornecedores.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar fornecedores'))
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
        // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);

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
                    url: `{{ route('fornecedores.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Erro!'
                            , 'Ocorreu um erro ao excluir o registro. Tente novamente.'
                            , 'error');
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
