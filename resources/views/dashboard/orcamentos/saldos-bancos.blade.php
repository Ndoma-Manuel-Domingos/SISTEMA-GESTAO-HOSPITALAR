@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Saldos dos Bancos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-financeiro') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Financeiro</li>
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
                        <div class="card-header">
                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('transacoes-financeiras-saldos-bancos-imprimir') }}"><i class="fas fa-file-pdf"></i> IMPRIMIR PDF</a>
                            </div>
                        </div>

                        @if ($saldos_bancos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Conta</th>
                                        <th> {{ __('messages.designacao') }} </th>
                                        <th class="text-right">Credito</th>
                                        <th class="text-right">Debito</th>
                                        <th class="text-right">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php
                                    $cred = 0;
                                    $debi = 0;
                                    @endphp

                                    @foreach ($saldos_bancos as $item)
                                    @php
                                    $cred += $item->despesa;
                                    $debi += $item->receita;
                                    @endphp

                                    <tr>
                                        <td>{{ $item->subconta->numero }}</td>
                                        <td>{{ $item->subconta->nome }}</td>
                                        <td class="text-right text-light-danger">- {{ number_format($item->despesa, 2, ',', '.')  }}</td>
                                        <td class="text-right text-light-success">+ {{ number_format($item->receita, 2, ',', '.')  }}</td>
                                        <td class="text-right text-light-primary"> {{ number_format($item->receita - $item->despesa, 2, ',', '.')  }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>-</th>
                                        <th>-</th>
                                        <th class="text-right text-light-danger">- {{ number_format($cred, 2, ',', '.') }}</th>
                                        <th class="text-right text-light-success">+ {{ number_format($debi, 2, ',', '.') }}</th>
                                        <th class="text-right text-light-primary"> {{ number_format($debi - $cred, 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        @endif
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
