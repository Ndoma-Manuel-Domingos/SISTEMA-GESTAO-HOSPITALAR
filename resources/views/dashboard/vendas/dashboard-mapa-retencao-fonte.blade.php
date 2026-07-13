@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Mapa de Retenção na Fonte</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
                        <form action="{{ route('mapa_retencao_fonte') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">
                                <div class="col-md-3 col-12">
                                    <label for="status" class="form-label">Tipo de Documentos</label>
                                    <select type="text" class="form-control select2" name="status" id="status">
                                        <option value="">{{ __('messages.escolher') }}</option>
                                        <option value="pago" {{ $requests['status'] == "pago" ? 'selected' : ''}}>Pagos</option>
                                        <option value="por pagar" {{ $requests['status'] == "por pagar" ? 'selected' : ''}}>Por pagar</option>
                                        <option value="anulada" {{ $requests['status'] == "anulada" ? 'selected' : ''}}>Anulados</option>
                                    </select>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" id="data_inicio" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" id="data_final" name="data_final" placeholder="Data final">
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
                            <div class="card-tools">
                                <a href="{{ route('imprimir-pdf-mapa-retencao-fonte-excel', ['status' => $requests['status'] ?? "", 'data_inicio' => $requests['data_inicio'] ?? date("Y-m-d"), 'data_final' => $requests['data_final'] ?? date("Y-m-d")]) }}" target="_blank" class="btn btn-light-success float-right"><i class="fas fa-file-excel"></i> {{ __('messages.exportar_excel') }}</a>
                                <a href="{{ route('imprimir-pdf-mapa-retencao-fonte', ['status' => $requests['status'] ?? "", 'data_inicio' => $requests['data_inicio'] ?? date("Y-m-d"), 'data_final' => $requests['data_final'] ?? date("Y-m-d")]) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>Ref</th>
                                        <th>Codigo</th>
                                        <th>Produto</th>
                                        <th>Categoria</th>
                                        <th class="text-right">Total Documento</th>
                                        <th class="text-right">Total Retido</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach ($vendas as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->produto->codigo_barra ?? "" }}</td>
                                        <td>{{ $item->produto->nome ?? "" }}</td>
                                        <td>{{ $item->produto->categoria->categoria ?? "" }}</td>
                                        <td class="text-right">{{ number_format($item->total_valor_pagar ?? 0, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</span></td>
                                        <td class="text-right">{{ number_format($item->total_retencao_fonte ?? 0, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</span></td>
                                    </tr>
                                    @php $total += $item->total_retencao_fonte; @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-right" colspan="5">TOTAL RETIDO</td>
                                        <td class="text-right">{{ number_format($total ?? 0, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda ?? "AKZ" }}</span></td>
                                    </tr>
                                </tfoot>
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
        }).buttons().container();
    });

</script>
@endsection
