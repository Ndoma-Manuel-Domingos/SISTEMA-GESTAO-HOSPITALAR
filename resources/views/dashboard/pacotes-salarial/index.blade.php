@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pacotes Salariais</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Pacote</li>
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
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar pacote'))
                                <a href="{{ route('pacotes-salarial.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                                @endif
                            </h3>

                            {{-- <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div> --}}
                        </div>

                        @if ($pacotes)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('messages.cargos') }}</th>
                                        <th>{{ __('messages.categoria') }}</th>
                                        <th>Salário Base</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>
                                            <div class="float-right">{{ __('messages.accoes') }} </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pacotes as $item)
                                    <tr>
                                        <th>{{ $item->id ?? "" }}</th>
                                        <th>{{ $item->cargo->nome ??  "" }}</th>
                                        <th>{{ $item->categoria->nome ??  "" }}</th>
                                        <th>{{ number_format($item->salario_base, 2, ',', '.')  }}</th>
                                        <th>Limite Isenção</th>
                                        <th>Sujeito IRT</th>
                                        <th>Sujeito INSS</th>
                                        <th>{{ $item->status }}</th>
                                        <th class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar pacote'))
                                                    <a class="dropdown-item" href="{{ route('pacotes-salarial.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif
                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar pacote'))
                                                    <a class="dropdown-item" href="{{ route('pacotes-salarial.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar pacote'))
                                                    <button class="btn btn-light-danger dropdown-item delete-record" data-id="{{ $item->id ?? "" }}">
                                                        <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="2">.</th>
                                        <th colspan="7">SUBSÍDIOS</th>
                                    </tr>
                                    @foreach ($item->subsidios_pacotes as $item1)
                                    <tr>
                                        <td colspan="2">.</td>
                                        <td>{{ $item1->subsidio->nome }}</td>
                                        <td>{{ number_format($item1->salario, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item1->limite_isencao, 2, ',', '.') }}</td>
                                        <td>{{ $item1->irt == "Y" ? 'SIM' : 'NÃO' }}</td>
                                        <td>{{ $item1->inss == "Y" ? 'SIM' : 'NÃO' }}</td>
                                        <td>{{ $item1->processamento->nome }}</td>
                                        <td><a href="" class="btn btn-light-danger float-right"><i class="fas fa-trash"></i> Remover</a></td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <th colspan="2">.</th>
                                        <th colspan="7">DESCONTOS</th>
                                    </tr>

                                    @foreach ($item->desconto_pacotes as $item1)
                                    <tr>
                                        <td colspan="2">.</td>
                                        <td>{{ $item1->desconto->nome }}</td>
                                        <td>{{ number_format($item1->salario, 2, ',', '.') }}</td>
                                        <td>{{ $item1->tipo_valor == "P" ? "%": "Kz" }}</td>
                                        <td>{{ $item1->irt == "Y" ? 'SIM' : 'NÃO' }}</td>
                                        <td>{{ $item1->inss == "Y" ? 'SIM' : 'NÃO' }}</td>
                                        <td>{{ $item1->processamento->nome }}</td>
                                        <td><a href="" class="btn btn-light-danger float-right"><i class="fas fa-trash"></i> Remover</a></td>
                                    </tr>
                                    @endforeach

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
                    url: `{{ route('pacotes-salarial.destroy', ':id') }}`.replace(':id', recordId)
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
