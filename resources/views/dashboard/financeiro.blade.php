@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.painel_financeiro') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.inicio') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
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
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-success">
                        <div class="inner">
                            <h3>{{ number_format($contasReceberAtraso, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.contas_receber_atraso') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('facturas-facturacao', ['relatorio' => "contas_receber_atraso"]) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-success">
                        <div class="inner">
                            <h3>{{ number_format($contasReceberMes, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.contas_receber_aberto_mes') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('facturas-facturacao', ['relatorio' => "contas_receber_mes"]) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-danger">
                        <div class="inner">
                            <h3>{{ number_format($contasPagarAtraso, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.contas_pagar_atraso') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('fornecedores-facturas-encomendas.index', ['relatorio' => "contas_pagar_atraso"]) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-danger">
                        <div class="inner">
                            <h3>{{ number_format($contasPagarMes, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.contas_pagar_aberto_mes') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('fornecedores-facturas-encomendas.index', ['relatorio' => "contas_pagar_mes"]) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Quantidade Produtos Em Stock">
                        <div class="inner">
                            <h3>{{ number_format($saldoAtual, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.saldo_actual') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Quantidade Produtos Em Stock">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">{{ __('messages.transferencia_caixas_bancos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('transacoes-financeiras-transferencia') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Quantidade Produtos Em Stock">
                        <div class="inner">
                            <h3>{{ number_format($saldos_bancos->receita_caixa - $saldos_bancos->despesa_caixa, 2, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.saldo_bancos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('transacoes-financeiras-saldos-bancos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Quantidade Produtos Em Stock">
                        <div class="inner">
                            <h3>{{ number_format($saldos_caixas->receita_caixa - $saldos_caixas->despesa_caixa, 2, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.saldo_caixas') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('transacoes-financeiras-saldos-caixas') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header flex">
                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <h1 class="m-0 text-center h4">{{ __('messages.visao_financeira') }}</h1>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="input-group">
                                        <select name="filtro_ano" id="filtro_ano" class="form-control">
                                            @for ($i = 10; $i < 50; $i++) @php $year="20" . $i; @endphp <option value="{{ $year }}" {{ $year == date("Y") ? 'selected' : '' }}>{{ $year }}</option> @endfor
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-light-primary">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoAnual" width="800" height="200"></canvas>
                        </div>
                        <div class="card-footer text-center">
                            <h5>{{ __('messages.totais_anuais') }}</h5>
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4"></div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.receita') }}</th>
                                                <th>{{ __('messages.despesa') }}</th>
                                                <th>{{ __('messages.saldo') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="total-receita" style="background-color: rgba(75, 192, 192, 0.6)" class="text-light-success">0.00</td>
                                                <td id="total-despesa" style="background-color: rgba(255, 99, 132, 0.6)" class="text-light-danger">0.00</td>
                                                <td id="total-saldo" style="background-color: rgba(153, 102, 255, 0.6)" class="text-light-dark">0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-center h4">{{ __('messages.receitas_plano_conta') }}</h1>
                        </div>
                        <div class="card-body p-5">
                            <canvas id="graficoReceitas" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-center h4">{{ __('messages.despesas_plano_conta') }}</h1>
                        </div>
                        <div class="card-body p-5">
                            <canvas id="graficoDispesas" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-center h4">Lucro Bruto vs Lucro Líquido</h1>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoLucros" width="800" height="400"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-center h4">{{ __('messages.evolucao_saldos_finais') }}</h1>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoSaldos" width="800" height="200"></canvas>
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
    fetch('{{ route("dashboard.grafico-lucros") }}')
        .then(res => res.json())
        .then(dados => {
            const ctx = document.getElementById('graficoLucros').getContext('2d');

            const labels = dados.map(item => item.mes);
            const lucroBruto = dados.map(item => item.lucro_bruto);
            const lucroLiquido = dados.map(item => item.lucro_liquido);

            new Chart(ctx, {
                type: 'bar'
                , data: {
                    labels: labels
                    , datasets: [{
                            label: 'Lucro Bruto'
                            , backgroundColor: 'rgba(54, 162, 235, 0.7)'
                            , data: lucroBruto
                        }
                        , {
                            label: 'Lucro Líquido'
                            , backgroundColor: 'rgba(255, 99, 132, 0.7)'
                            , data: lucroLiquido
                        }
                    ]
                }
                , options: {
                    responsive: true
                    , plugins: {
                        title: {
                            display: true
                            , text: 'Lucro Bruto vs Lucro Líquido por Mês'
                        }
                    }
                }
            });
        });

</script>

<script>
    const filtro_ano = document.getElementById('filtro_ano').value;
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('graficoAnual').getContext('2d');
        fetch('{{ route("operacaoes-financeiras.grafico-anual") }}')
            .then(response => response.json())
            .then(data => {
                const meses = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
                const receitas = [];
                const despesas = [];
                const saldos = [];

                for (let mes = 1; mes <= 12; mes++) {
                    receitas.push(data.mensal[mes].receita || 0);
                    despesas.push(data.mensal[mes].despesa || 0);
                    saldos.push(data.mensal[mes].saldo || 0);
                }

                // Totais anuais
                const totalReceita = data.totais.receita;
                const totalDespesa = data.totais.despesa;
                const totalSaldo = data.totais.saldo;


                // Exibindo Totais no HTML
                document.getElementById('total-receita').innerText = totalReceita.toFixed(1);
                document.getElementById('total-despesa').innerText = totalDespesa.toFixed(1);
                document.getElementById('total-saldo').innerText = totalSaldo.toFixed(1);

                new Chart(ctx, {
                    type: 'bar'
                    , data: {
                        labels: meses
                        , datasets: [{
                                label: 'Receita'
                                , data: receitas
                                , backgroundColor: 'rgba(75, 192, 192, 0.6)'
                            , }
                            , {
                                label: 'Despesa'
                                , data: despesas
                                , backgroundColor: 'rgba(255, 99, 132, 0.6)'
                            , }
                            , {
                                label: 'Saldo'
                                , data: saldos
                                , backgroundColor: 'rgba(153, 102, 255, 0.6)'
                            , }
                        ]
                    }
                    , options: {
                        responsive: true
                        , plugins: {
                            legend: {
                                position: 'top'
                            , }
                            , tooltip: {
                                mode: 'index'
                                , intersect: false
                            , }
                        }
                        , scales: {
                            x: {
                                stacked: false
                            , }
                            , y: {
                                stacked: false
                                , beginAtZero: true
                            }
                        }
                    }
                });
            });
    });

</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('graficoReceitas').getContext('2d');
        fetch('{{ route("operacaoes-financeiras.grafico-receitas") }}')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.nome);
                const valores = data.map(item => item.total);

                new Chart(ctx, {
                    type: 'pie'
                    , data: {
                        labels: labels
                        , datasets: [{
                            label: 'Receitas'
                            , data: valores
                            , backgroundColor: [
                                'rgba(75, 192, 192, 0.6)'
                                , 'rgba(255, 99, 132, 0.6)'
                                , 'rgba(255, 205, 86, 0.6)'
                                , 'rgba(54, 162, 235, 0.6)'
                                , 'rgba(153, 102, 255, 0.6)'
                                , 'rgba(201, 203, 207, 0.6)'
                            ]
                            , borderColor: [
                                'rgba(75, 192, 192, 1)'
                                , 'rgba(255, 99, 132, 1)'
                                , 'rgba(255, 205, 86, 1)'
                                , 'rgba(54, 162, 235, 1)'
                                , 'rgba(153, 102, 255, 1)'
                                , 'rgba(201, 203, 207, 1)'
                            ]
                            , borderWidth: 1
                        }]
                    }
                    , options: {
                        responsive: true
                        , plugins: {
                            legend: {
                                position: 'top'
                            , }
                            , tooltip: {
                                mode: 'index'
                                , intersect: false
                            , }
                        }
                    }
                });
            });
    });

</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('graficoDispesas').getContext('2d');
        fetch('{{ route("operacaoes-financeiras.grafico-despesas") }}')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.nome);
                const valores = data.map(item => item.total);

                new Chart(ctx, {
                    type: 'pie'
                    , data: {
                        labels: labels
                        , datasets: [{
                            label: 'Dispesas'
                            , data: valores
                            , backgroundColor: [
                                'rgba(75, 192, 192, 0.6)'
                                , 'rgba(255, 99, 132, 0.6)'
                                , 'rgba(255, 205, 86, 0.6)'
                                , 'rgba(54, 162, 235, 0.6)'
                                , 'rgba(153, 102, 255, 0.6)'
                                , 'rgba(201, 203, 207, 0.6)'
                            ]
                            , borderColor: [
                                'rgba(75, 192, 192, 1)'
                                , 'rgba(255, 99, 132, 1)'
                                , 'rgba(255, 205, 86, 1)'
                                , 'rgba(54, 162, 235, 1)'
                                , 'rgba(153, 102, 255, 1)'
                                , 'rgba(201, 203, 207, 1)'
                            ]
                            , borderWidth: 1
                        }]
                    }
                    , options: {
                        responsive: true
                        , plugins: {
                            legend: {
                                position: 'top'
                            , }
                            , tooltip: {
                                mode: 'index'
                                , intersect: false
                            , }
                        }
                    }
                });
            });
    });

</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('graficoSaldos').getContext('2d');
        fetch('{{ route("operacaoes-financeiras.grafico-saldos") }}')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => `Mês ${item.mes}`);
                const saldos = data.map(item => item.saldo);

                new Chart(ctx, {
                    type: 'bar'
                    , data: {
                        labels: labels
                        , datasets: [{
                            label: 'Saldo Mensal'
                            , data: saldos
                            , backgroundColor: 'rgba(54, 162, 235, 0.4)'
                            , borderColor: 'rgba(54, 162, 235, 1)'
                            , borderWidth: 1
                        }]
                    }
                    , options: {
                        responsive: true
                        , scales: {
                            y: {
                                beginAtZero: true
                                , title: {
                                    display: true
                                    , text: 'Saldo (AOA)'
                                }
                            }
                            , x: {
                                title: {
                                    display: true
                                    , text: 'Meses'
                                }
                            }
                        }
                        , plugins: {
                            legend: {
                                display: true
                                , position: 'top'
                            , }
                            , tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Saldo: AOA ${context.raw.toFixed(2)}`;
                                    }
                                }
                            }
                        }
                    }
                });
            });
    });

</script>

@endsection
