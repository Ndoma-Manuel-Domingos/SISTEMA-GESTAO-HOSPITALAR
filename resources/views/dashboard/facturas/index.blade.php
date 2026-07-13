@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Facturas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Stock</li>
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
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="{{ route('facturas.index') }}" method="get" id="form_pesquisa">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-2 mb-3">
                                        <label class="form-label" for="tipo_documento">Tipo Documento</label>
                                        <select type="text" id="tipo_documento" class="form-control select2" name="tipo_documento">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            <option value="FT" {{ $requests['tipo_documento'] == "FT" ? 'selected' : '' }}>Factura</option>
                                            <option value="FR" {{ $requests['tipo_documento'] == "FR" ? 'selected' : '' }}>Factura Recibo</option>
                                            <option value="RG" {{ $requests['tipo_documento'] == "RG" ? 'selected' : '' }}>Recibos</option>
                                            <option value="PP" {{ $requests['tipo_documento'] == "PP" ? 'selected' : '' }}>Factura Pro-forma</option>
                                            <option value="NC" {{ $requests['tipo_documento'] == "NC" ? 'selected' : '' }}>Notas de Crédito</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-2 mb-3">
                                        <label class="form-label" for="data_inicio">{{ __('messages.data_inicio') }}</label>
                                        <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') ?? $requests['data_inicio'] }}">
                                    </div>

                                    <div class="col-12 col-md-2 mb-3">
                                        <label class="form-label" for="data_final">{{ __('messages.data_final') }}</label>
                                        <input type="date" class="form-control" id="data_final" name="data_final" value="{{ old('data_final') ?? $requests['data_final'] }}">
                                    </div>

                                    <div class="col-12 col-md-2 mb-3">
                                        <label class="form-label" for="cliente_id"> {{ __('messages.clientes') }} </label>
                                        <select type="text" class="form-control select2" name="cliente_id" id="cliente_id">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            @foreach ($clientes as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $requests['cliente_id'] == $item->id ? 'selected' : '' }}>{{ $item->conta }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-2 mb-3">
                                        <label class="form-label" for="user_id">Operadores</label>
                                        <select type="text" class="form-control select2" name="user_id" id="user_id">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            @foreach ($users as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $requests['user_id'] == $item->id ? 'selected' : '' }}>{{ $item->conta }} - {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" form="form_pesquisa" class="btn-sm btn-light-primary ml-2 text-right"> <i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('facturas.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                            </h3>
                            <a href="{{ route('pdf-facturas', ['data_inicio' => $requests['data_inicio'] ?? '', 'data_final' => $requests['data_final'] ?? '', 'cliente_id' => $requests['cliente_id'] ?? '', 'user_id' => $requests['user_id']?? '', 'tipo_documento' => $requests['tipo_documento']?? ''] ) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th> {{ __('messages.descricao') }} </th>
                                        <th>Forma de Pagamento</th>
                                        <th> {{ __('messages.clientes') }} </th>
                                        <th>Operador</th>
                                        <th>Anulada</th>
                                        <th>Convertida</th>
                                        <th>Divida</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th class="text-right">{{ __('messages.total') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($documentos)
                                    @foreach ($documentos as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('facturas.show', [$item->id, 'tipo_documentos' => $item->factura]) }}">
                                                <span class="float-right">
                                                    @if ($item->status_factura == "por pagar")
                                                    <i class="fas fa-exclamation-triangle text-light-warning"></i>
                                                    @else
                                                    @if ($item->status_factura == "anulada")
                                                    <i class="fas fa-cancel text-light-danger"></i>
                                                    @endif
                                                    @endif
                                                </span>
                                                <span>
                                                    <strong id="text_factura" class="text-uppercase">{{ $item->factura_next }}</strong>
                                                </span>
                                            </a>
                                        </td>
                                        <td>{{ $item->forma_pagamento($item->pagamento) }}</td>
                                        <td> {{ $item->nome_cliente }}</td>
                                        <td> {{ $item->user->name ?? "" }}</td>

                                        <td class="text-uppercase"> {{ $item->anulado == "Y" ? 'sim' : 'Não' }} </td>
                                        <td class="text-uppercase"> {{ $item->convertido_factura == "Y" ? 'sim' : 'Não' }} </td>
                                        <td class="text-uppercase"> {{ $item->factura_divida == "Y" ? 'sim' : 'Não' }} </td>
                                        <td class="text-uppercase">{{ $item->data_emissao }}</td>
                                        <td class="text-uppercase text-right">{{ number_format($item->valor_total, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>


                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">

                                                    @if (Auth::user()->can('listar todos'))
                                                    <a class="dropdown-item" href="{{ route('gerar-nota-entrega', $item->code) }}" target="_blank"><i class="fas fa-eye text-light-primary"></i> Gerar Nota de Entrega</a>
                                                    @endif


                                                    @if ($item->factura == "FT")
                                                    <a href="{{ route('factura-factura', $item->code) }}" class="dropdown-item" target="_blank"><i class="fas fa-print"></i> Imprimir</a>
                                                    @else
                                                    @if ($item->factura == "FR")
                                                    <a href="{{ route('factura-recibo', $item->code) }}" class="dropdown-item" target="_blank"><i class="fas fa-print"></i> Imprimir</a>
                                                    @else
                                                    @if ($item->factura == "PP")
                                                    <a href="{{ route('factura-proforma', $item->code) }}" class="dropdown-item" target="_blank"><i class="fas fa-print"></i> Imprimir</a>
                                                    @else
                                                    @if ($item->factura == "RG")
                                                    <a href="{{ route('factura-recibo-recibo', $item->code) }}" class="dropdown-item" target="_blank"><i class="fas fa-print"></i> Imprimir</a>
                                                    @else
                                                    <a href=" {{ route('factura-nota-credito', $item->code) }}" class="dropdown-item" target="_blank"><i class="fas fa-print"></i> Imprimir</a>
                                                    @endif
                                                    @endif
                                                    @endif
                                                    @endif

                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
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
