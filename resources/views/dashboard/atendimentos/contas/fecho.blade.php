@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-file-invoice-dollar text-primary"></i> Cobrança Seguradoras</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-hospital') }}">Cobraças</a></li>
                        <li class="breadcrumb-item active">Cobrança</li>
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
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-lock"></i>
                                Cobrança Seguradoras
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="GET">
                                <div class="row">
                                    <div class="col-md-5 col-12">
                                        <label for="mes">Mês</label>
                                        <input type="number" name="mes" class="form-control" placeholder="Mês" value="{{ $mes }}">
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label for="ano">Ano</label>
                                        <input type="number" name="ano" id="ano" class="form-control" placeholder="Ano" value="{{ $ano }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i>
                                            Pesquisar
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <a href="{{ route('fechos-contas.index') }}" class="btn btn-secondary btn-block">
                                            Limpar
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>Seguradora</th>
                                        <th>Nº Contas</th>
                                        <th>Valor Cobrado</th>
                                        <th>Valor Pago</th>
                                        <th>Em Dívida</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dados as $item)
                                    <tr>
                                        <td>
                                            {{$item['seguradora']->nome}}
                                        </td>
                                        <td>
                                            {{$item['quantidade']}}
                                        </td>
                                        <td>
                                            {{number_format($item['total'],2,",",".")}}
                                        </td>
                                        <td>
                                            {{number_format($item['pago'],2,",",".")}}
                                        </td>
                                        <td>
                                            <span class="text-danger font-weight-bold">
                                                {{number_format($item['divida'],2,",",".")}}
                                            </span>
                                        </td>
                                        <td width="220">
                                            <a class="btn btn-info btn-sm" href="{{ route('fechos-contas-seguradora.show', [ $item['seguradora']->id,  $mes, $ano ]) }}">
                                                Ver Contas
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>

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
