@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Produto no Stock</h1>
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
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('estoques-produtos') }}" method="get">
                            <div class="card-body row">
                                @csrf
                                <div class="col-12 col-md-3">
                                    <label for="loja_id">{{ __('messages.lojas') }}</label>
                                    <div class="input-group">
                                        <select type="text" class="form-control select2" name="loja_id" id="loja_id">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            @foreach ($lojas as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $requests['loja_id'] == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="produto_id">{{ __('messages.produtos') }}</label>
                                    <div class="input-group">
                                        <select type="text" class="form-control select2" name="produto_id" id="produto_id">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $requests['produto_id'] == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="status">Tipos</label>
                                    <div class="input-group">
                                        <select type="text" class="form-control select2" name="status" id="status">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            <option value="activo" {{ $requests['status'] == "activo" ? "selected": "" }}>Activos</option>
                                            <option value="expirado" {{ $requests['status'] == "expirado" ? "selected": "" }}>Expirados</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a class="float-left"> Total de Registro: {{ count($estoques) }}</a>
                            <a href="{{ route('imprimir-estoques-produtos', ['loja_id' => $requests['loja_id'] ?? '', 'status' => $requests['status'] ?? '', 'produto_id' => $requests['produto_id'] ?? '']) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Lote</th>
                                        <th rowspan="2">{{ __('messages.codigo_barras') }} Lote</th>
                                        <th rowspan="2">Estado(Lote)</th>
                                        <th rowspan="2">{{ __('messages.codigo_barras') }}(Produto)</th>
                                        <th rowspan="2">{{ __('messages.designacao') }}</th>
                                        <th colspan="3" class="text-center">Stock</th>
                                        <th rowspan="2" class="text-right">{{ __('messages.preco_custo') }}</th>
                                        <th rowspan="2" class="text-right">{{ __('messages.precc_venda') }}</th>
                                        <th colspan="3" class="text-center">Valor Acumulado</th> {{-- --}}
                                    </tr>
                                    <tr>
                                        <th>Entrada</th>
                                        <th>Saída</th>
                                        <th>Actual</th>

                                        <th>Entrada</th>
                                        <th>Saída</th>
                                        <th>Actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estoques as $item)
                                    <tr>
                                        <td><a href="{{ route('lotes.edit', $item->id) }}">{{ $item->lote }}</a></td>
                                        <td>{{ $item->codigo_barra }}</td>
                                        @if ($item->status == 'activo')
                                        <td class="text-light-success text-uppercase">{{ $item->status }}</td>
                                        @else
                                        <td class="text-light-danger text-uppercase">{{ $item->status }}</td>
                                        @endif
                                        <td>{{ $item->produto->codigo_barra }}</td>
                                        <td><a href="{{ route('produtos.show', $item->produto->id) }}">{{ $item->produto->nome ?? "" }}</a></td>
                                        @php $stock = 0; $stock_entrada = 0; $stock_saida = 0; @endphp
                                        @foreach ($item->registros as $item)
                                        @if ($item->tipo == "E")
                                        @php $stock_entrada += $item->quantidade; @endphp
                                        @endif
                                        @if ($item->tipo == "S")
                                        @php $stock_saida += $item->quantidade; @endphp
                                        @endif
                                        @php $stock += $item->quantidade; @endphp
                                        @endforeach
                                        <td><span class="float-right">{{ $stock_entrada }}</span></td>
                                        <td><span class="float-right">{{ $stock_saida }}</span></td>
                                        <td><span class="float-right">{{ $stock_entrada - $stock_saida }}</span></td>
                                        <td><span class="float-right">{{ number_format($item->produto->preco_custo, '2', ',', '.')  }}</span></td>
                                        <td><span class="float-right">{{ number_format($item->produto->preco_venda, '2', ',', '.')  }}</span></td>
                                        <td><span class="float-right">{{ number_format($item->produto->preco_venda * $stock_entrada, '2', ',', '.')  }}</span></td> {{-- --}}
                                        <td><span class="float-right">{{ number_format($item->produto->preco_venda * $stock_saida, '2', ',', '.')  }}</span></td> {{-- --}}
                                        <td><span class="float-right">{{ number_format($item->produto->preco_venda * ($stock_entrada - $stock_saida), '2', ',', '.')  }}</span></td> {{-- --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

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
