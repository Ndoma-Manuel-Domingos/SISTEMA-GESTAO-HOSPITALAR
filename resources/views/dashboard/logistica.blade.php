@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.compras') }}</h1>
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
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($total_produtos, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">{{ __('messages.produtos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('produtos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary"">
                    <div class=" inner">
                        <h3>{{ number_format($total_fornecedores, 0, ',', '.')  }}</h3>
                        <p class="text-uppercase">{{ __('messages.fornecedores') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('fornecedores.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box bg-light-danger">
                    <div class="inner">
                        <h3>{{ number_format($total_encomendas, 0, ',', '.')  }}</h3>
                        <p class="text-uppercase">{{ __('messages.encomendas') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('fornecedores-encomendas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box bg-light-success">
                    <div class="inner">
                        <h3>{{ number_format($total_requisicoes, 0, ',', '.')  }}</h3>
                        <p class="text-uppercase">{{ __('messages.requisicoes') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('requisacoes.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box bg-light-warning">
                    <div class="inner">
                        <h3>::</h3>
                        <p class="text-uppercase">{{ __('messages.actualizar_stock') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('estoques.create') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box bg-light-success">
                    <div class="inner">
                        <h3>::</h3>
                        <p class="text-uppercase">{{ __('messages.inventario_inicial') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('contabilidade-inventario', ['tipo' => 'produto']) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box bg-light-dark ">
                    <div class="inner">
                        <h3>::</h3>
                        <p class="text-uppercase">{{ __('messages.transferencia') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('transferencia-lojas-armazem') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="m-0 text-center h4">Gráfico de Giro de Produtos</h1>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoGiro" width="800" height="200"></canvas>
                    </div>
                    <div class="card-footer"></div>
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
    fetch('{{ route("dashboard.giro-produtos") }}')
        .then(res => res.json())
        .then(data => {
            const altoGiro = data.altoGiro;
            const baixoGiro = data.baixoGiro;

            const labels = [...altoGiro.map(p => p.nome), ...baixoGiro.map(p => p.nome)];
            const dadosAlto = altoGiro.map(p => p.total_saidas);
            const dadosBaixo = baixoGiro.map(p => p.total_saidas);

            const ctx = document.getElementById('graficoGiro').getContext('2d');
            new Chart(ctx, {
                type: 'bar'
                , data: {
                    labels: labels
                    , datasets: [{
                            label: 'Produtos com Alto Giro'
                            , data: dadosAlto.concat(Array(baixoGiro.length).fill(null))
                            , backgroundColor: 'rgba(54, 162, 235, 0.7)'
                        }
                        , {
                            label: 'Produtos com Baixo Giro'
                            , data: Array(altoGiro.length).fill(null).concat(dadosBaixo)
                            , backgroundColor: 'rgba(255, 99, 132, 0.7)'
                        }
                    ]
                }
                , options: {
                    responsive: true
                    , plugins: {
                        title: {
                            display: true
                            , text: 'Produtos com Alto e Baixo Giro'
                        }
                    }
                    , scales: {
                        y: {
                            beginAtZero: true
                            , title: {
                                display: true
                                , text: 'Quantidade Vendida'
                            }
                        }
                    }
                }
            });
        })
        .catch(err => console.error('Erro ao carregar dados:', err));

</script>

@endsection
