@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Controle Mensalidades</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">Inicio</li>
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
                <div class="col-lg-3">
                    <div class="small-box bg-light-success">
                        <div class="inner">
                            <h3>AKZ {{ number_format($recebido ?? 0,2,',','.') }}</h3>
                            <p>Total Recebido</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="small-box bg-light-danger">
                        <div class="inner">
                            <h3>{{ $inadimplentes ?? 0 }}</h3>
                            <p>Inadimplentes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-12">
                    <form action="{{ route('financeiro.gerar') }}" method="POST">
                        @csrf
                        <button class="btn btn-light-success btn-block">
                            <i class="fas fa-sync"></i>
                            Gerar Mensalidades Agora
                        </button>
                    </form>
                </div>

                <div class="col-md-6 col-12">
                    <form action="{{ route('financeiro.juros') }}" method="POST">
                        @csrf
                        <button class="btn btn-light-danger btn-block">
                            <i class="fas fa-calculator"></i>
                            Calcular Juros e Multas
                        </button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Controle Mensalidades
                            </h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Mês</th>
                                        <th>Valor</th>
                                        <th>Multa</th>
                                        <th>Juros</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($mensalidades as $m)
                                    <tr>
                                        <td>{{ $m->entidade->nome }}</td>
                                        <td>{{ $m->mes }}/{{ $m->ano }}</td>
                                        <td>{{ number_format($m->valor_original,2,',','.') }}</td>
                                        <td>{{ number_format($m->multa,2,',','.') }}</td>
                                        <td>{{ number_format($m->juros,2,',','.') }}</td>
                                        <td>{{ number_format($m->valor_total,2,',','.') }}</td>
                                        <td>
                                            @if($m->status == 'pago')
                                            <span class="badge badge-light-success">
                                                Pago
                                            </span>
                                            @elseif($m->status == 'parcial')
                                            <span class="badge badge-light-warning">
                                                Parcial
                                            </span>
                                            @else
                                            <span class="badge badge-light-danger">
                                                Vencido
                                            </span>
                                            @endif
                                        </td>
                                        <td>

                                            <a class="btn btn-light-primary btn-sm" href="{{ route('mensalidade.pagar', $m->id) }}">
                                                Pagar
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
