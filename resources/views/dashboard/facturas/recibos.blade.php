@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Recibos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Facturas</li>
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
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('facturas.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                            </h3>
                            <a href="{{ route('pdf-recibos') }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 bg-light">
                                    <form action="{{ route('recibos') }}" method="get" class="mt-3">
                                        @csrf
                                        <div class="card-body row">

                                            <div class="col-6">
                                                <div class="input-group mb-3">
                                                    <input type="search" class="form-control" name="factura" value="{{ $requests['factura'] ?? ''  }}" placeholder="{{ __('messages.filtrar') }}...">
                                                </div>
                                                <p class="text-light-danger">
                                                    @error('factura')
                                                    {{ $message }}
                                                    @enderror
                                                </p>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-10">
                                                        <div class="input-group">
                                                            <button type="submit" class="btn btn-light-primary ml-2 text-right"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </form>
                                </div>
                            </div>

                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Factura</th>
                                        <th>Referente</th>
                                        <th> {{ __('messages.clientes') }} </th>
                                        <th>Operador</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>Vencimento</th>
                                        <th class="text-right">Dívida</th>
                                        <th class="text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($facturas)
                                    @foreach ($facturas as $item)
                                    <tr>
                                        <td><a href="{{ route('facturas.show', [$item->id, 'tipo_documentos' => $item->factura]) }}">{{ $item->factura_next}}</a> </td>
                                        <td><a href="{{ route('facturas.show', [$item->facturas->id, 'tipo_documentos' => $item->facturas->factura]) }}">{{ $item->facturas->factura_next ?? ''}}</a> </td>
                                        <td>{{ $item->nome_cliente }}</td>
                                        <td>{{ $item->user->name ?? "" }}</td>
                                        <td>{{ $item->data_emissao }}</td>
                                        <td>{{ $item->data_vencimento }}</td>
                                        <td class="text-right">{{ number_format($item->valor_total, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        <td class="text-right"><a href="{{ route('factura-recibo-recibo', $item->code) }}" target="_blank"><i class="fas fa-print"></i> </a></td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
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
