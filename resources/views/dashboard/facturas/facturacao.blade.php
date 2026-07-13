@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.facturacao') }}</h1>
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
                    <form action="{{ route('facturas-facturacao') }}" method="get">
                        @csrf
                        <div class="card">
                            <div class="card-body row">
                                @csrf
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="">Referência da factura</label>
                                    <div class="input-group">
                                        <input type="search" class="form-control" name="factura" placeholder="{{ __('messages.filtrar') }}...">
                                    </div>
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label class="form-label" for="data_inicio">{{ __('messages.data_inicio') }}</label>
                                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') ?? $requests['data_inicio'] }}">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label class="form-label" for="data_final">{{ __('messages.data_final') }}</label>
                                    <input type="date" class="form-control" id="data_final" name="data_final" value="{{ old('data_final') ?? $requests['data_final'] }}">
                                </div>

                                <input type="hidden" value="{{ $_GET['relatorio'] ?? "" }}" name="relatorio" id="relatorio">

                                <div class="col-12 col-md-2 mb-3">
                                    <label class="form-label" for="cliente_id"> {{ __('messages.clientes') }} </label>
                                    <select type="text" class="form-control select2" name="cliente_id" id="cliente_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($clientes as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['cliente_id'] == $item->id ? 'selected' : '' }}>{{ $item->conta }} - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary ml-2 text-right"> <i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('facturas.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }} {{ __('messages.factura') }}</a>
                            </h3>
                            <a href="{{ route('pdf-facturas-facturacao', ['cliente_id' => $requests['cliente_id'], 'data_inicio' =>  $requests['data_inicio'], 'data_final' => $requests['data_final'], 'relatorio' => $_GET['relatorio'] ?? ""]) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Factura</th>
                                        <th> {{ __('messages.clientes') }} </th>
                                        @if ($empresa_logada->empresa->tipo_entidade->sigla === 'HOSP')
                                        <th>Quem Cobrirá?</th>
                                        @endif
                                        <th>Operador</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>Vencimento</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">Valor Total</th>
                                        <th class="text-right">Dívida</th>
                                        <th class="text-right">Valor Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($facturas)
                                    @foreach ($facturas as $item)
                                    <tr>
                                        <td><a href="{{ route('facturas.show', [$item->id, 'tipo_documentos' => $item->factura]) }}">{{ $item->factura_next}}</a> </td>
                                        <td><a href="{{ route('clientes.show', $item->cliente->id) }}">{{ $item->nome_cliente }}</a></td>
                                        @if ($empresa_logada->empresa->tipo_entidade->sigla === 'HOSP')
                                        @if ($item->parent_id != NULL)
                                        <td><a href="{{ route('clientes.show', $item->parent_id) }}">{{ $item->parent->nome ?? "" }}</a></td>
                                        @endif
                                        @if ($item->seguradora_id != NULL)
                                        <td><a href="{{ route('seguradoras.show', $item->seguradora_id) }}">{{ $item->seguradora->nome ?? "" }}</a></td>
                                        @endif
                                        @endif
                                        <td>{{ $item->user->name ?? "" }}</td>
                                        <td>{{ $item->data_emissao }}</td>
                                        <td>{{ $item->data_vencimento }}</td>
                                        <td class="text-uppercase">
                                            @if ($item->status_factura == "por pagar")
                                            <span class=""><i class="fas fa-exclamation-triangle"></i></span> {{ $item->status_factura }}
                                            @else
                                            @if ($item->status_factura == "anulada")
                                            <span class=""><i class="fas fa-cancel"></i></span> {{ $item->status_factura }}
                                            @else
                                            @if ($item->status_factura == "pago")
                                            <span class=""><i class="fas fa-check"></i></span> {{ $item->status_factura }}
                                            @endif
                                            @endif
                                            @endif
                                        </td>
                                        <td class="text-right">{{ number_format($item->valor_total, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        <td class="text-right">{{ number_format($item->valor_divida, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        <td class="text-right">{{ number_format($item->valor_pago, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer"></div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

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
