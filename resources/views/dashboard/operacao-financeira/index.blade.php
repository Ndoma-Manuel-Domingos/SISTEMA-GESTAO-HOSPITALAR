@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.transacoes') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        @if (!$empresa_logada->empresa->tem_perfil("Gestão Financeira"))
                        <li class="breadcrumb-item"><a href="{{ route('caixas.operacaoes-financeiras') }}">{{ __('messages.voltar') }}</a></li>
                        @else
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-financeiro') }}">{{ __('messages.voltar') }}</a></li>
                        @endif
                        <li class="breadcrumb-item active">{{ __('messages.financeiro') }}</li>
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
                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('operacaoes-financeiras.index') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="subconta_id" class="form-label">{{ __('messages.conta') }}</label>
                                    <select type="text" class="form-control select2" name="subconta_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($subcontas as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['subconta_id'] == $item->id ? 'selected' : ''}}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="centro_custo_id" class="form-label">{{ __('messages.centro_custos') }}</label>
                                    <select type="text" class="form-control select2" name="centro_custo_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($centro_custos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['centro_custo_id'] == $item->id ? 'selected' : ''}}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="cliente_id" class="form-label">{{ __('messages.clientes') }}</label>
                                    <select type="text" class="form-control select2" name="cliente_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($clientes as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['cliente_id'] == $item->id ? 'selected' : ''}}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="fornecedor_id" class="form-label">{{ __('messages.fornecedores') }}</label>
                                    <select type="text" class="form-control select2" name="fornecedor_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($fornecedores as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['fornecedor_id'] == $item->id ? 'selected' : ''}}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="tipo_movimento" class="form-label">Tipo movimento</label>
                                    <select type="text" class="form-control select2" name="tipo_movimento">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        <option value="R" {{ $requests['tipo_movimento'] == "R" ? 'selected' : ''}}>Receitas</option>
                                        <option value="D" {{ $requests['tipo_movimento'] == "D" ? 'selected' : ''}}>Despesas</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <select type="text" class="form-control select2" name="status">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        <option value="pendente" {{ $requests['status'] == "pendente" ? 'selected' : ''}}>Pendente</option>
                                        <option value="pago" {{ $requests['status'] == "pago" ? 'selected' : ''}}>Pago</option>
                                        <option value="atrasado" {{ $requests['status'] == "atrasado" ? 'selected' : ''}}>Atrasado</option>
                                    </select>
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-light-primary btn-sm ml-2 text-right"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos'))
                                <a class="btn btn-light-success" href="{{ route('operacaoes-financeiras.create', ['tipo' => "receita"]) }}"> + {{ __('messages.receita') }}</a>
                                <a class="btn btn-light-danger" href="{{ route('operacaoes-financeiras.create', ['tipo' => "dispesa"]) }}"> - {{ __('messages.despesa') }}</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('operacaoes-financeiras.exportar', ['data_inicio' => $requests['data_inicio'] ?? "", 'data_final' => $requests['data_final'] ?? "", 'centro_custo_id' => $requests['centro_custo_id'] ?? "", 'status' => $requests['status'] ?? "", 'tipo_movimento' =>  $requests['tipo_movimento'] ?? "", 'cliente_id' =>  $requests['cliente_id'] ?? "", 'fornecedor_id' =>  $requests['fornecedor_id'] ?? "", 'subconta_id' =>  $requests['subconta_id'] ?? ""]) }}"><i class="fas fa-file-pdf"></i> IMPRIMIR PDF</a>
                            </div>
                        </div>

                        @if ($operacoes)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Referência</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>{{ __('messages.centro_custos') }}</th>

                                        @if ($empresa_logada->empresa->tem_perfil("Gestão Contabilidade"))
                                        <th>Subconta</th>
                                        @else
                                        <th>Caixa/Conta Bancária</th>
                                        @endif

                                        <th>{{ __('messages.despesa') }}/{{ __('messages.receita') }}</th>
                                        <th>{{ __('messages.fornecedores') }}/{{ __('messages.clientes') }}</th>
                                        <th class="text-right"> {{ __('messages.data') }} </th>
                                        <th class="text-right">Motante</th>
                                        <th><span class="float-right">{{ __('messages.accoes') }} </span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($operacoes as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->nome ?? "" }}</td>
                                        <td>{{ $item->status ?? "" }}</td>
                                        <td>{{ $item->centro_custo->nome ?? "" }}</td>

                                        <td>{{ $item->subconta->numero ?? "" }} - {{ $item->subconta->nome ?? "" }}</td>
                                        <td>{{ $item->type == "D" ? ($item->dispesa->nome  ?? "") : ($item->receita->nome ?? "") }}</td>
                                        <td>{{ $item->type == "D" ? ($item->fornecedor_id ? $item->fornecedor->nome ?? "" : $item->user->name ?? "") : ($item->cliente_id ? $item->cliente->nome ?? "" : $item->user->name ?? "") }}</td>
                                        <td class="text-right">{{ $item->date_at }}</td>
                                        @if ($item->type == "D")
                                        <td class="text-right text-light-danger">- {{ number_format($item->motante, 2, ',', '.')  }}</td>
                                        @else
                                        <td class="text-right text-light-success">+ {{ number_format($item->motante, 2, ',', '.')  }}</td>
                                        @endif

                                        <td class="text-right">
                                            <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                            <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar dispesa'))
                                                <a class="dropdown-item" href="{{ route('operacaoes-financeiras.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                @endif

                                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar dispesa'))
                                                <a class="dropdown-item" href="{{ route('operacaoes-financeiras.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                @endif

                                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar dispesa'))
                                                <a class="dropdown-item" target="_blank" href="{{ route('operacaoes-financeiras.imprimir', $item->id) }}"><i class="fas fa-print text-light-primary"></i> {{ __('messages.imprimir') }} </a>
                                                @endif

                                                <div class="dropdown-divider"></div>
                                                @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar dispesa'))
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
                    url: `{{ route('operacaoes-financeiras.destroy', ':id') }}`.replace(':id', recordId)
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
