@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @if (isset($_GET['relatorio']) && $_GET['relatorio'] == "contas_pagar_atraso")
                    <h1 class="m-0">{{ __('messages.contas_pagar_atraso') }}</h1>
                    @else
                    @if (isset($_GET['relatorio']) && $_GET['relatorio'] == "contas_pagar_mes")
                    <h1 class="m-0">{{ __('messages.contas_pagar_aberto_mes') }}</h1>
                    @else
                    <h1 class="m-0">{{ __('messages.listagem') }}</h1>
                    @endif
                    @endif
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

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('fornecedores-facturas-encomendas.index') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="fornecedor_id" class="form-label">{{ __('messages.fornecedores') }}</label>
                                    <div class="input-group">
                                        <select type="text" class="form-control fornecedor_id select2" id="fornecedor_id" name="fornecedor_id">
                                            <option value="">{{ __('messages.todos') }}</option>
                                            @foreach ($fornecedores as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $requests['fornecedor_id'] == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="status_factura" class="form-label">Tipo Factura</label>
                                    <div class="input-group">
                                        <select type="text" class="form-control status_factura select2" id="status_factura" name="status_factura">
                                            <option value="">{{ __('messages.todos') }}</option>
                                            <option value="false" {{ $requests['status_factura'] == 'false' ? "selected" : "" }}>Por Pagar</option>
                                            <option value="true" {{ $requests['status_factura'] == 'true' ? "selected" : "" }}>Pagas</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control data_inicio" id="data_inicio" name="data_inicio" value="{{ $requests['data_inicio'] ?? old('data_inicio') }}" placeholder="{{ __('messages.designacao') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="data_final" name="data_final" value="{{ $requests['data_final'] ?? old('data_final') }}" placeholder="{{ __('messages.designacao') }}">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{-- <a href="{{ route('fornecedores-facturas-encomendas.create') }}" class="btn btn-light-primary">Adicionar Factura</a> --}}
                            </h3>
                            <a href="{{ route('pdf-facturas-facturacao-fornecedores', ['status_factura' => $requests['status_factura'], 'fornecedor_id' => $requests['fornecedor_id'], 'data_inicio' =>  $requests['data_inicio'], 'data_final' => $requests['data_final'], 'relatorio' => $_GET['relatorio'] ?? ""]) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                        </div>

                        @if ($facturas)
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th> {{ __('messages.fornecedores') }} </th>
                                        <th class="text-center">Nº Factura</th>
                                        <th class="text-center">Data Factura</th>
                                        <th class="text-center">Data Vencimento</th>
                                        <th class="text-center">Valor da Factura</th>
                                        <th class="text-center">Valor Pago</th>
                                        <th class="text-center">Em Dívida</th>
                                        <th class="text-center">{{ __('messages.estados') }}</th>
                                        <th style="width: 10px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($facturas && count($facturas) != 0)
                                    @foreach ($facturas as $factura)
                                    <tr>
                                        <td><a href="{{ route('fornecedores.show', $factura->fornecedor->id) }}">{{ $factura->fornecedor->nome }}</a></td>
                                        <td class="text-center"><a href="{{ route('fornecedores-facturas-encomendas.show', $factura->id) }}">{{ $factura->factura }}</a></td>
                                        <td class="text-center">{{ $factura->data_factura }}</td>
                                        <td class="text-center">{{ $factura->data_vencimento }}</td>
                                        <td class="text-center">{{ number_format($factura->valor_factura, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        <td class="text-center">{{ number_format($factura->valor_pago, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        <td class="text-center">{{ number_format($factura->valor_divida, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        <td class="text-center">
                                            @if ($factura->status2 == "concluido")
                                            <span class="badge bg-light-success">Pago</span>
                                            @else
                                            <span class="badge bg-light-danger">Não Pago</span>
                                            @endif

                                        </td>
                                        <td>

                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="{{ route('fornecedores-facturas-encomendas.show', $factura->id) }}"><i class="fas fa-info text-light-primary"></i> {{ __('messages.mais_detalhes') }}</a>
                                                    <a class="dropdown-item" href="{{ route('encomenda-liquidar-factura-compra', $factura->id) }}"><i class="fas fa-file-invoice dollar-sign" title="Liquidar Fatura"></i> Liquidar Factura</a>
                                                    <a class="dropdown-item" href="{{ route('encomenda-duplicar-factura', $factura->id) }}"><i class="fas fa-copy"></i> Duplicar Factura</a>
                                                    <a class="dropdown-item" target="_blank" href="{{ route('imprimir-facturas-encomenda', $factura->id) }}"><i class="fas fa-file-pdf"></i> Imprimir Factura</a>
                                                    <div class="dropdown-divider"></div>

                                                    <form action="{{ route('fornecedores-facturas-encomendas.destroy', $factura->id ) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Factura?')">
                                                            <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8">Não foram encontrados resultados</td>
                                    </tr>
                                    @endif
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
