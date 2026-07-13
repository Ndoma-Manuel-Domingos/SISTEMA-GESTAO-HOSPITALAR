@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        {{ __('messages.listagem') }}
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
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
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar cliente'))
                                <a href="{{ route('clientes.create') }}" class="btn btn-light-primary"><i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                                <a href="{{ route('create_import.clientes') }}" class="btn btn-light-success"><i class="fas fa-file-excel"></i> {{ __('messages.importar_excel') }}</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('pdf-clientes') }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($clientes)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Conta</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th> {{ __('messages.genero') }} </th>
                                        <th>{{ __('messages.data_nascimento') }}</th>
                                        <th> {{ __('messages.bilhete_identidade') }} </th>
                                        <th>Parcero/Parente</th>
                                        <th>{{ __('messages.telefone') }}/{{ __('messages.telemovel') }}</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clientes as $item)
                                    <tr>
                                        <td><a href="{{ route('clientes.show', $item->id) }}">{{ $item->conta }}</a></td>
                                        <td><a href="{{ route('clientes.show', $item->id) }}">{{ $item->nome }}</a></td>
                                        <td>{{ $item->genero ?? '------' }}</td>
                                        <td>{{ $item->data_nascimento ?? '------' }}</td>
                                        <td>{{ $item->nif ?? '------' }}</td>

                                        @if ($item->parent_id)
                                        <td><a href="{{ route('clientes.show', $item->parent_id) }}">{{ $item->parent->nome ?? "" }}</a></td>
                                        @else
                                        <td>---</td>
                                        @endif

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

                                                    @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                                    <a class="dropdown-item" href="{{ route('clientes.create', ['parent_id' => $item->id]) }}"><i class="fas fa-plus text-light-primary"></i> Adicionar Funcionário/Filhos </a>
                                                    @endif
                                                    @endif

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                                    <a class="dropdown-item" href="{{ route('clientes.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif
                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar cliente'))
                                                    <a class="dropdown-item" href="{{ route('clientes.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif

                                                    @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('criar consulta'))
                                                    <a class="dropdown-item" href="{{ route('consultas.create', ['origem' => 'padrao', 'paciente_id' => $item->id]) }}"><i class="fas fa-user-nurse text-light-primary"></i>
                                                        Marcar Consulta</a>
                                                    @endif

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('criar exame'))
                                                    <a class="dropdown-item" href="{{ route('exames.create', ['origem' => 'padrao', 'paciente_id' => $item->id]) }}"><i class="fas fa-user-nurse text-light-primary"></i>
                                                        Marcar Exame</a>
                                                    @endif
                                                    @endif

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                                    <a class="dropdown-item" target="_blank" href="{{ route('ficha-cliente', $item->id) }}"><i class="fas fa-file-pdf"></i>
                                                        Ficha do @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFOR')
                                                        Aluno
                                                        @endif
                                                        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOTL')
                                                        Hospede
                                                        @endif
                                                        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CONS')
                                                        Paciente
                                                        @endif
                                                        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                                                        Paciente
                                                        @endif
                                                        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFAT')
                                                        Cliente
                                                        @endif
                                                    </a>
                                                    @endif

                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar cliente'))
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
                    url: `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId)
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
