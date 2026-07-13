@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $titulo }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Inventário</li>
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
                        <form action="{{ route('contabilidade-inventario', ['tipo' => $isMateriaPrima ? 'materias-primas' : 'produto']) }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="loja_id" class="form-label">{{ __('messages.lojas') }}</label>
                                    <select type="text" class="form-control select2" id="loja_id" name="loja_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($lojas as $loja)
                                        <option value="{{ $loja->id }}" {{ $requests['loja_id'] == $loja->id ? 'selected' : '' }}> {{ $loja->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="categoria_id" class="form-label">{{ __('messages.categoria') }}</label>
                                    <select type="text" class="form-control select2" id="categoria_id" name="categoria_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($empresa->categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ $requests['categoria_id'] == $categoria->id ? 'selected' : '' }}> {{ $categoria->categoria }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="marca_id" class="form-label">{{ __('messages.marcas') }}</label>
                                    <select type="text" class="form-control select2" id="marca_id" name="marca_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($empresa->marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ $requests['marca_id'] == $categoria->id ? 'selected' : '' }}> {{ $marca->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="nome_referencia" class="form-label">{{ __('messages.designacao') }}</label>
                                    <input type="search" class="form-control" id="nome_referencia" name="nome_referencia" placeholder="{{ __('messages.filtrar') }}...">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn-sm btn-light-primary"> <i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('contabilidade-inventario-exportar-pdf', ['tipo' => $isMateriaPrima ? 'materias-primas' : 'produto'], ['loja_id' => $requests['loja_id'], 'categoria_id' => $requests['categoria_id'], 'marca_id' => $requests['marca_id'] ]) }}" target="_blank" class="btn-light-danger btn float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                            <a href="{{ route('contabilidade-inventario-exportar-excel', ['tipo' => $isMateriaPrima ? 'materias-primas' : 'produto'], ['loja_id' => $requests['loja_id'], 'categoria_id' => $requests['categoria_id'], 'marca_id' => $requests['marca_id'] ]) }}" target="_blank" class="btn-light-success btn float-right mx-2"><i class="fas fa-file-excel"></i> {{ __('messages.exportar_pdf') }}</a>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead class="">
                                    <tr>
                                        <th class="text-left">Descrição</th>
                                        <th class="text-left">{{ __('messages.codigo_barras') }}</th>
                                        <th class="text-left">Existência</th>
                                        <th class="text-left">{{ __('messages.valor') }}</th>
                                        <th class="text-right">{{ __('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach ($produtos as $item)
                                    @if ($item->produto)
                                    @php
                                    $stock = $item->produto->converterDaBase($item->produto->total_produto_loja_activa(), $item->produto->unidade);
                                    $subtotal = $item->produto->preco_custo * $stock;
                                    $total += $subtotal;
                                    @endphp
                                    <tr>
                                        <td class="text-left">{{ $item->produto->nome ?? "" }}</td>
                                        <td class="text-left">{{ $item->produto->codigo_barra ?? "" }}</td>
                                        <td class="text-left"> {{ number_format($stock, 1, ',', '.') }} {{ $item->produto->unidade->sigla }}</td>
                                        <td class="text-left">{{ number_format($item->produto->preco_custo ?? 0, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($subtotal, 2, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">{{ number_format($total ?? 0, 2, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
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
