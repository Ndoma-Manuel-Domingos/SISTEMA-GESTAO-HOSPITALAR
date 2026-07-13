@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Facturas Sem Pagamentos</h1>
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
                <div class="col-md-4 col-12">
                    <div class="info-box">
                        <span class="info-box-icon  bg-light-primary"><i class=" far fa-envelope"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Saldo Total</span>
                            <h5 class="info-box-number">{{ number_format($facturasVencidas + $facturasVencidasCorrente, 2, ',', '.')  }}</h5>
                            @if (($facturasVencidas + $facturasVencidasCorrente) > 0)
                            <span class="info-box-text text-light-success">Existem dívidas</span>
                            @else
                            <span class="info-box-text">Não existem dívidas</span>
                            @endif
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-light-success"><i class="far fa-flag"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Dívida Corrente</span>
                            <h5 class="info-box-number">{{ number_format($facturasVencidasCorrente, 2, ',', '.')  }}</h5>
                            @if ($facturasVencidasCorrente > 0)
                            <span class="info-box-text text-light-success">Existem pagamentos pendentes</span>
                            @else
                            <span class="info-box-text">Não existem pagamentos pendentes</span>
                            @endif

                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-light-warning"><i class="far fa-copy"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Dívida Vencida</span>
                            <h5 class="info-box-number">{{ number_format($facturasVencidas, 2, ',', '.') }}</h5>
                            @if ($facturasVencidas > 0)
                            <span class="info-box-text text-light-success">Existem pagamentos fora do prazo</span>
                            @else
                            <span class="info-box-text">Não existem pagamentos fora do prazo</span>
                            @endif
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('facturas-sem-pagamento') }}" method="get">
                        <div class="card">
                            <div class="card-body row">
                                @csrf
                                <div class="col-12 col-md-3">
                                    <div class="input-group">
                                        <select type="text" class="form-control select2" name="tipo_documento">
                                            <option value="todas">Todas</option>
                                            <option value="dividas_corrente">Dívidas Corrente</option>
                                            <option value="dividas_vencidas">Dívidas Vencidas</option>
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('produto')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="input-group">
                                        <input type="search" class="form-control" name="factura" placeholder="{{ __('messages.filtrar') }}...">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('factura')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary ml-2 text-right"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
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
                                <a href="{{ route('facturas.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                            </h3>
                            @if (isset($_GET['tipo_documento']) || isset($_GET['factura']))
                            <a href="{{ route('pdf-facturas-sem-pagamento', [$_GET['tipo_documento'], $_GET['factura']]) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                            @else
                            <a href="{{ route('pdf-facturas-sem-pagamento') }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                            @endif
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5px"></th>
                                        <th>Factura</th>
                                        <th> {{ __('messages.clientes') }} </th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>Vencimento</th>
                                        <th class="text-right">Dívida</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($facturas)
                                    @foreach ($facturas as $item)
                                    <tr>
                                        <td>
                                            @if (date("Y-m-d") > $item->data_vencimento)
                                            <span class="bg-light-warning p-2"><i class="fas fa-file"></i></span>
                                            @endif
                                            @if (date("Y-m-d") < $item->data_vencimento && date("Y-m-d") > $item->data_emissao)
                                                <span class="bg-light-success p-2"><i class="fas fa-file"></i></span>
                                                @endif
                                        </td>
                                        <td><a href="{{ route('facturas.show', [$item->id, 'tipo_documentos' => $item->factura]) }}">{{ $item->factura_next}}</a> </td>
                                        <td>{{ $item->cliente->nome }}</td>
                                        <td>{{ $item->data_emissao }}</td>
                                        <td>{{ $item->data_vencimento }}</td>
                                        <td class="text-right">{{ number_format($item->valor_total, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
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
