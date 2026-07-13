@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Controle de cotas</h1>
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
                    <div class="small-box bg-light-warning">
                        <div class="inner">
                            <h3>AKZ {{ number_format($valor_acumulado_em_dividas ?? 0,2,',','.') }}</h3>
                            <p>Total acumulado em dívidas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="small-box bg-light-dark">
                        <div class="inner">
                            <h3>AKZ {{ number_format($valor_acumulado_em_pendente ?? 0,2,',','.') }}</h3>
                            <p>Total acumulado em pendenca</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="small-box bg-light-danger">
                        <div class="inner">
                            <h3>{{ $inadimplentes ?? 0 }}</h3>
                            <p>Total Inadimplentes</p>
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

            <div class="row mt-4">
                <div class="col-12 col-md-12">
                    <form method="GET" action="{{  route('empresas-dashboard-financeiro-cotas.index') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="membro" class="form-control" value="{{ $requests['membro'] ?? "" }}" placeholder="Nome do membro">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="status" class="form-control">
                                            <option value="">Todos Status</option>
                                            <option value="pago" {{ $requests['status'] == "pago" ? 'selected' : '' }}>Pago</option>
                                            <option value="parcial" {{ $requests['status'] == "parcial" ? 'selected' : '' }}>Parcial</option>
                                            <option value="vencido" {{ $requests['status'] == "vencido" ? 'selected' : '' }}>Vencido</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="mes" class="form-control">
                                            <option value="">Todos Meses</option>
                                            <option value="1" {{ $requests['mes'] == "1" ? 'selected' : '' }}>Janeiro</option>
                                            <option value="2" {{ $requests['mes'] == "2" ? 'selected' : '' }}>Fevereiro</option>
                                            <option value="3" {{ $requests['mes'] == "3" ? 'selected' : '' }}>Março</option>
                                            <option value="4" {{ $requests['mes'] == "4" ? 'selected' : '' }}>Abril</option>
                                            <option value="5" {{ $requests['mes'] == "5" ? 'selected' : '' }}>Maio</option>
                                            <option value="6" {{ $requests['mes'] == "6" ? 'selected' : '' }}>Junho</option>
                                            <option value="7" {{ $requests['mes'] == "7" ? 'selected' : '' }}>Julho</option>
                                            <option value="8" {{ $requests['mes'] == "8" ? 'selected' : '' }}>Agosto</option>
                                            <option value="9" {{ $requests['mes'] == "9" ? 'selected' : '' }}>Setembro</option>
                                            <option value="10" {{ $requests['mes'] == "10" ? 'selected' : '' }}>Outubro</option>
                                            <option value="11" {{ $requests['mes'] == "11" ? 'selected' : '' }}>Novembro</option>
                                            <option value="12" {{ $requests['mes'] == "12" ? 'selected' : '' }}>Dezembro</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="ano" class="form-control">
                                            <option value="">Todos</option>
                                            @for ($i = 2020; $i < 2030; $i++) <option value="{{ $i }}" {{ $requests['ano'] == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-light-primary">
                                            <i class="fas fa-search"></i>
                                            Filtrar
                                        </button>
                                        <a href="" class="btn btn-light-secondary">
                                            Limpar
                                        </a>
                                        <a target="_blank" href="{{ route('empresas-dashboard-financeiro-cotas.imprimir', [
                                            'membro' => request('membro'),
                                            'status' => request('status'),
                                            'mes' => request('mes'),
                                            'ano' => request('ano')
                                        ]) }}" class="btn btn-light-danger float-right">

                                            <i class="fas fa-file-pdf"></i>
                                            Imprimir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Controle de cotas
                            </h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>Membro</th>
                                        <th>Mês</th>
                                        <th>Valor</th>
                                        <th>Multa</th>
                                        <th>Juros</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th class="text-right">Ação</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($mensalidades as $m)
                                    <tr>
                                        <td><a href="{{ route('membros.show', $m->membro->id) }}">{{ $m->membro->nome }}</a></td>
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
                                        <td class="text-right">
                                            <a class="btn btn-light-primary btn-sm" href="{{ route('mensalidade-cotas.pagar', $m->id) }}">
                                                Pagar
                                            </a>
                                            <a class="btn btn-light-secondary btn-sm" target="_blink" href="{{ route('mensalidade-cotas.comprovativo', $m->id) }}">
                                                Imprimir
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
