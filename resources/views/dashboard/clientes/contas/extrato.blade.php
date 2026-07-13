@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Extratos de Conta</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ $cliente->nome }}</li>
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
                            <form action="{{ route('clientes-extrato-conta', $cliente->id) }}" method="get" id="form_pesquisa">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-3 mb-3">
                                        <label class="form-label">Tipo Documento</label>
                                        <select type="text" class="form-control select2" name="tipo_documento">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            <option value="FT">Factura</option>
                                            <option value="FR">Factura Recibo</option>
                                            <option value="PP">Factura Pro-forma</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Loja</label>
                                        <select type="text" class="form-control select2" name="loja_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($lojas as $loj)
                                            <option value="{{ $loj->id }}" {{ old('loja_id') == $requests['loja_id'] ? 'selected': '' }}>{{ $loj->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">{{ __('messages.data_inicio') }}</label>
                                        <input type="date" class="form-control" name="data_inicio" value="{{ old('data_inicio') ?? $requests['data_inicio'] }}">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label">{{ __('messages.data_final') }}</label>
                                        <input type="date" class="form-control" name="data_final" value="{{ old('data_final') ?? $requests['data_final'] }}">
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


            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        {{-- <th></th> --}}
                                        <th>Referência</th>
                                        <th> {{ __('messages.descricao') }} </th>
                                        <th>Forma de Pagamento</th>
                                        <th> {{ __('messages.clientes') }} </th>
                                        <th>Operador</th>
                                        <th>Anulada</th>
                                        <th>Convertida</th>
                                        <th>Retificada</th>
                                        <th>Divida</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th class="text-right">{{ __('messages.total') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($facturas)
                                    @foreach ($facturas as $item)
                                    <tr>
                                        <td> {{ $item->id ?? "" }}</td>
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
                                        <td>{{ $item->pagamento == 'NU' ? 'NUMERÁRIO' : ($item->pagamento == 'MB' ? 'MULTICAIXA' : "DUPLO") }}</td>
                                        <td> {{ $item->cliente->nome }}</td>
                                        <td> {{ $item->user->name ?? "" }}</td>

                                        <td class="text-uppercase"> {{ $item->anulado == "Y" ? 'sim' : 'Não' }} </td>
                                        <td class="text-uppercase"> {{ $item->convertido_factura == "Y" ? 'sim' : 'Não' }} </td>
                                        <td class="text-uppercase"> {{ $item->retificado == "Y" ? 'sim' : 'Não' }} </td>
                                        <td class="text-uppercase"> {{ $item->factura_divida == "Y" ? 'sim' : 'Não' }} </td>
                                        <td class="text-uppercase">{{ date_format($item->created_at, "d/m/Y") }}</td>
                                        <td class="text-uppercase text-right">{{ number_format($item->valor_total, 2, ',', '.') }} </td>
                                        <td class="text-right">
                                            @if ($item->factura == "FT")
                                            <a href="{{ route('factura-factura', $item->code) }}" target="_blank"><i class="fas fa-print"></i></a>
                                            @else
                                            @if ($item->factura == "FR")
                                            <a href="{{ route('factura-recibo', $item->code) }}" target="_blank"><i class="fas fa-print"></i></a>
                                            @else
                                            @if ($item->factura == "PP")
                                            <a href="{{ route('factura-proforma', $item->code) }}" target="_blank"><i class="fas fa-print"></i></a>
                                            @else
                                            @if ($item->factura == "RG")
                                            <a href="{{ route('factura-recibo-recibo', $item->code) }}" target="_blank"><i class="fas fa-print"></i></a>
                                            @else
                                            <a href=" {{ route('factura-nota-credito', $item->code) }}" target="_blank"><i class="fas fa-print"></i></a>
                                            @endif
                                            @endif
                                            @endif
                                            @endif
                                        </td>
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
