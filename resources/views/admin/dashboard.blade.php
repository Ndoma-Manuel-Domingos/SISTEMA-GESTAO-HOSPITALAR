@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Empresas</h1>
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
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-primary">
                        <div class=" inner">
                            <h3>{{ $entidade_total }}</h3>
                            <p class="text-uppercase">Total Empresas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('empresas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-primary">
                        <div class=" inner">
                            <h3>{{ $anuncios_total }}</h3>
                            <p class="text-uppercase">Anuncíos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('anuncios-admin.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-primary">
                        <div class=" inner">
                            <h3>{{ $membros_total }}</h3>
                            <p class="text-uppercase">Membros</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('membros.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header">
                            <h3>Dashboard Financeiro</h3>
                        </div>

                        <div class="card-body">

                            <div class="row mb-4">

                                <div class="col-lg-2 col-md-3 col-12">
                                    <label>Data Inicial</label>
                                    <input type="date" id="inicio" class="form-control">
                                </div>

                                <div class="col-lg-2 col-md-3 col-12">
                                    <label>Data Final</label>
                                    <input type="date" id="fim" class="form-control">
                                </div>

                                <div class="col-lg-2 col-md-3 col-12">
                                    <label>Período</label>
                                    <select id="tipo" class="form-control">
                                        <option value="anual">Anual</option>
                                        <option value="mensal">Mensal</option>
                                        <option value="trimestral">Trimestral</option>
                                    </select>
                                </div>

                                <div class="col-lg-2 col-md-3 col-12">
                                    <label>Tipo Gráfico</label>
                                    <select id="tipoGrafico" class="form-control">
                                        <option value="line">Linha</option>
                                        <option value="bar">Barra</option>
                                        <option value="pie">Pizza</option>
                                        <option value="doughnut">Rosca</option>
                                        <option value="radar">Radar</option>
                                        <option value="polarArea">Polar</option>
                                    </select>
                                </div>

                                <div class="col-md-1 col-12">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-light-primary btn-block" id="filtrar">
                                        Atualizar
                                    </button>
                                </div>

                                <div class="col-md-1 col-12">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-light-success btn-block" id="imprimirGrafico">
                                        Imprimir gráfico
                                    </button>
                                </div>

                                <div class="col-md-1 col-12">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-light-danger btn-block" id="gerarPDF">
                                        Exportar PDF
                                    </button>
                                </div>

                            </div>

                            <canvas id="graficoFinanceiro"></canvas>

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
    let chart;

    carregarGrafico();

    $('#filtrar').click(function() {
        carregarGrafico();
    });

    function carregarGrafico() {
        $.ajax({

            url: '/empresas-dashboard-financeiro/dados',

            method: 'GET',

            data: {
                inicio: $('#inicio').val()
                , fim: $('#fim').val()
                , tipo: $('#tipo').val()
            },

            success: function(response) {
                montarGrafico(response);
            }

        });
    }

    function montarGrafico(dados) {
        let labels = [];
        let empresas = {};

        dados.forEach(item => {

            if (!labels.includes(item.periodo)) {
                labels.push(item.periodo);
            }

            if (!empresas[item.nome]) {
                empresas[item.nome] = [];
            }
        });

        labels.forEach(periodo => {

            Object.keys(empresas).forEach(nome => {

                let registro = dados.find(x =>
                    x.periodo == periodo &&
                    x.nome == nome
                );

                empresas[nome].push(
                    registro ? registro.total : 0
                );
            });

        });

        let datasets = [];

        let cores = [
            '#007bff'
            , '#28a745'
            , '#dc3545'
            , '#ffc107'
            , '#6610f2'
            , '#17a2b8'
        ];

        let i = 0;

        Object.keys(empresas).forEach(nome => {
            datasets.push({
                label: nome
                , data: empresas[nome]
                , borderColor: cores[i % cores.length]
                , backgroundColor: cores[i % cores.length]
                , fill: false
                , tension: 0.3
            });
            i++;
        });

        if (chart) {
            chart.destroy();
        }

        const ctx = document.getElementById('graficoFinanceiro');

        chart = new Chart(ctx, {
            type: $('#tipoGrafico').val()
            , data: {
                labels: labels
                , datasets: datasets
            }
            , options: {
                responsive: true
                , plugins: {
                    title: {
                        display: true
                        , text: 'Evolução Financeira das Empresas'
                    }
                },

                interaction: {
                    intersect: false
                    , mode: 'index'
                },

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    $('#imprimirGrafico').click(function() {

        let canvas = document.getElementById('graficoFinanceiro');

        let imagem = canvas.toDataURL();

        let janela = window.open('');

        janela.document.write(`
        <html>
            <head>
                <title>Impressão do Gráfico</title>
            </head>
            <body style="text-align:center">
                <h2>Dashboard Financeiro</h2>
                <img src="${imagem}" style="width:100%">
            </body>
        </html>
        `);

        janela.document.close();

        janela.print();
    });

    $('#gerarPDF').click(function() {

        let inicio = $('#inicio').val();
        let fim = $('#fim').val();
        let tipo = $('#tipo').val();

        let url =
            `/empresas-dashboard-financeiro/pdf?inicio=${inicio}&fim=${fim}&tipo=${tipo}`;

        window.open(url, '_blank');
    });

</script>
@endsection
