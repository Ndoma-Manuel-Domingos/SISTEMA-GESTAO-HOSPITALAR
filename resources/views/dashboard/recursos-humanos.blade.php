@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.painel_recursos_humanos') }}</h1>
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
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-left h5">{{ __('messages.distribuicao_departamentos') }}</h1>
                        </div>
                        <div class="card-body p-5">
                            <canvas id="graficoFuncionarioPorDepartamentos" width="400" height="120"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-left h5">{{ __('messages.distribuicao_cargos') }}</h1>
                        </div>
                        <div class="card-body p-5">
                            <canvas id="graficoFuncionarioPorCargos" width="400" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-center h5">{{ __('messages.evolucao_taxa_rotatividade') }}</h1>
                        </div>
                        <div class="card-body p-5">
                            <canvas id="graficoAnual" width="800" height="120"></canvas>
                        </div>
                        <div class="card-footer text-center">
                            <h5>{{ __('messages.totais_anuais') }}</h5>
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4"></div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.admitidos') }}</th>
                                                <th>{{ __('messages.demitidos') }}</th>
                                                <th>{{ __('messages.taxas') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="total-admitidos" style="background-color: rgba(75, 192, 192, 0.6)" class="text-light-success">0.00</td>
                                                <td id="total-demitidos" style="background-color: rgba(255, 99, 132, 0.6)" class="text-light-danger">0.00</td>
                                                <td id="total-taxas" style="background-color: rgba(153, 102, 255, 0.6)" class="text-light-dark">0.00</td>
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

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.configuracao_recursos_humanos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('configuracao-recurso-humanos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.controle_presenca') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('controle-presencas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_funcionarios ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.funcionario') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('funcionarios.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.constituir_equipe') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('equipas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_contratos ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.contratos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('contratos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_contratos_renovados ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.renovacao_contratos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('renovacoes-contratos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format(0 ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.fins_contratos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('contratos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format(0 ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.readmissao_contratos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('contratos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">

                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_departamentos ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.departamentos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('departamentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Cargos">
                        <div class="inner">
                            <h3>{{ number_format($total_cargos ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.cargos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('cargos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Processamentos">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.processamentos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('processamentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="mapa de irt e mapa de inss">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">MAPA DE IRT / MAPA DE INSS</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('processamentos.mapas-irt-mapa-inss') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Pagamentos de Processamentos">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.pagamentos_processamentos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('pagamentos-processamentos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Anulação de Processamentos">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.anulacao_processamentos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('anulacao-processamentos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Emissão de Recibos">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.emissao_recibos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('emissao-recibo-processamentos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Marcar Ferias">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.marcar_ferias') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('marcacoes-ferias.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Marcações de Faltas">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.marcacoes_faltas') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('marcacoes-faltas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Marcações de Ausências">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.marcacoes_ausencias') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('marcacoes-ausencias.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Taxas do Imposto de Rendimento de Trabalho">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">{{ __('messages.taxa_irt') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('taxa_irt') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_motivos_saidas ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.motivos_saida_funcionarios') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('motivos-saidas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_motivos_ausencias ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.motivos_ausencia_funcionarios') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('motivos-ausencias.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
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
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('graficoAnual').getContext('2d');
        fetch('{{ route("recurso-humanos.grafico-taxa-rotatividade-anual") }}')
            .then(response => response.json())
            .then(data => {
                const meses = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
                const admitidos = [];
                const demitidos = [];
                const total_funcionarios = [];

                for (let mes = 1; mes <= 12; mes++) {
                    admitidos.push(data.mensal[mes] ? .admitidos || 0);
                    demitidos.push(data.mensal[mes] ? .demitidos || 0);
                    total_funcionarios.push(data.mensal[mes] ? .total_funcionarios || 0);
                }

                // Totais anuais
                const totalAdmitido = data.totais.admitido;
                const totalDemitido = data.totais.demitido;
                const totalSaldo = data.totais.total;


                // Exibindo Totais no HTML
                document.getElementById('total-admitidos').innerText = totalAdmitido.toFixed(1);
                document.getElementById('total-demitidos').innerText = totalDemitido.toFixed(1);
                document.getElementById('total-taxas').innerText = totalSaldo.toFixed(1);

                new Chart(ctx, {
                    type: 'bar'
                    , data: {
                        labels: meses
                        , datasets: [{
                                label: 'Admitidos'
                                , data: admitidos
                                , backgroundColor: 'rgba(75, 192, 192, 0.6)'
                            , }
                            , {
                                label: 'Demitidos'
                                , data: demitidos
                                , backgroundColor: 'rgba(255, 99, 132, 0.6)'
                            , }
                            , {
                                label: 'Totais'
                                , data: total_funcionarios
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
        const ctx = document.getElementById('graficoFuncionarioPorDepartamentos').getContext('2d');

        fetch('{{ route("recurso-humanos.grafico-funcionarios-departamantos") }}')
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
        const ctx = document.getElementById('graficoFuncionarioPorCargos').getContext('2d');

        fetch('{{ route("recurso-humanos.grafico-funcionarios-cargos") }}')
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

@endsection
