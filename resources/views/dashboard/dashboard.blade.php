@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.controle') }}</h1>
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

            @if($empresa_logada->empresa->tipo_entidade->sigla == 'PADARIA')

            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>Caixa</h3>
                            <p class="text-uppercase">Monitoramento do caixa</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('caixas.monitoramento-caixas') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-danger">
                        <div class="inner">
                            <h3>{{ $productions }}</h3>
                            <p class="text-uppercase">Produção</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('producao.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-warning">
                        <div class="inner">
                            <h3>{{ $today }}</h3>
                            <p class="text-uppercase">Produção de Hoje</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('producao.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-success">
                        <div class="inner">
                            <h3>{{ $produtos_primas }}</h3>
                            <p class="text-uppercase">Matérias-Primas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('produtos.materia_primas') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-danger">
                        <div class="inner">
                            <h3>{{ number_format($totalBread, 2, ',', '.') }}</h3>
                            <p class="text-uppercase">Pães Produzidos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('ingredientes.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  text-white bg-light-primary">
                        <div class="inner">
                            <h3>{{ number_format($producaoHoje) }}</h3>
                            <p class="text-uppercase">Produção Hoje</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('ingredientes.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  text-white bg-light-success">
                        <div class="inner">
                            <h3>{{ number_format($producaoMes) }}</h3>
                            <p class="text-uppercase">Produção Mensal</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('ingredientes.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  text-white bg-light-warning">
                        <div class="inner">
                            <h3>{{ $ordensExecucao }}</h3>
                            <p class="text-uppercase">Ordens em Execução</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('ingredientes.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  text-white bg-light-danger">
                        <div class="inner">
                            <h3>{{ $percentual }}%</h3>
                            <p class="text-uppercase">Eficiência</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('ingredientes.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card" style="height: 400px">
                        <div class="card-header">
                            <h3 class="card-title">Produção Industrial</h3>
                            <select id="tipoGrafico" class="form-control w-25 float-right">
                                <option value="line">Linha</option>
                                <option value="bar">Barras</option>
                                <option value="area">Área</option>
                            </select>
                        </div>
                        <div class="card-body">
                            <canvas id="chartProducao"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'RESO')
            <div class="row">
                @can("monitoramento de mesas")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-primary" title="Mesas">
                        <div class="inner">
                            <h3>{{ $totalMesasOcupadas }}/{{ number_format($totalMesas, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.mesa') }} OCUPADAS</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('salas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @can("monitoramento de mesas")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-light-dark bg-light-warning">
                        <div class="inner">
                            <h3>Monit.</h3>
                            <p class="text-uppercase">PRODUTOS E SERVIÇOS</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('visualizacao-produtos-servicos') }}" class="small-box-footer text-light-dark"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-success">
                        <div class="inner">
                            <h3>Monit.</h3>
                            <p class="text-uppercase">{{ __('messages.monitoramento_mesas') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('mesas.visualizacao-mesas') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @can("listar cartao")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-primary" title="Cartão Consumo">
                        <div class="inner">
                            <h3>::</h3>
                            <p class="text-uppercase">{{ __('messages.cartao_consumo') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('cartoes-consumos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @can("criar vendas")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-warning" title="Começar à Venda">
                        <div class="inner">
                            <h3>::</h3>
                            <p class="text-uppercase">Começar à Venda</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        @if ($empresa_logada->empresa->tipo_pronto_venda == 'Grelha')
                        <a href="{{ route('pronto-venda') }}" class="small-box-footer {{ Route::currentRouteNamed('pronto-venda') ? 'active' : '' }}"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i> </a>
                        @endif

                        @if ($empresa_logada->empresa->tipo_pronto_venda == 'Lista')
                        <a href="{{ route('pos.index') }}" class="small-box-footer {{ Route::currentRouteNamed('pos.index') ? 'active' : '' }}"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i> </a>
                        @endif
                    </div>
                </div>
                @endcan

                @can("controle cuzinha")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-primary" title="CUZINHA">
                        <div class="inner">
                            <h3>::</h3>
                            <p class="text-uppercase">Ir para Cuzinha</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('cuzinha.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Reservas">
                        <div class="inner">
                            <h3>{{ number_format($totalReservas, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.reserva') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('reservas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-success" title="Tarifários">
                        <div class="inner">
                            <h3>{{ number_format($totalTarifarios, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.tarefarios') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('tarefarios.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-success" title="Check In Diário">
                        <div class="inner">
                            <h3>{{ number_format($totalReservasCheckIn, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.check_in_diario') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('reservas.check_in_diario') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-danger" title="Check Out Diário">
                        <div class="inner">
                            <h3>{{ number_format($totalReservasCheckOut, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.check_out_diario') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('reservas.check_out_diario') }}" class="small-box-footer">{{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>



            </div>
            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'REST')

            <div class="row">
                @can("monitoramento de mesas")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-danger" title="Mesas Ocupadas">
                        <div class="inner">
                            <h3>{{ $totalMesasOcupadas }}/{{ number_format($totalMesas, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.mesa') }} OCUPADAS</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('salas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @can("criar vendas")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white  bg-light-warning" title="Escolher Forma de vendas">
                        <div class="inner">
                            <h3>::</h3>
                            <p class="text-uppercase">Escolher Forma de vendas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('painel.escolha') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @can("monitoramento de mesas")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-light-dark  bg-light-warning" title="Monitoramento de Produtos e Serviços">
                        <div class="inner">
                            <h3>Monit.</h3>
                            <p class="text-uppercase">PRODUTOS E SERVIÇOS</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('visualizacao-produtos-servicos') }}" class="small-box-footer text-light-dark"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white  bg-light-success" title="Monitoramento de Mesas">
                        <div class="inner">
                            <h3>Monit.</h3>
                            <p class="text-uppercase">{{ __('messages.monitoramento_mesas') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('mesas.visualizacao-mesas') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @can("listar reserva")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-danger" title="Reservas">
                        <div class="inner">
                            <h3>{{ number_format($totalReservas, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.reserva') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('reservas-mesas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @can("agrupar mesas")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-light-dark  bg-light-warning" title="Grupos de Mesas">
                        <div class="inner">
                            <h3>::</h3>
                            <p class="text-uppercase">{{ __('messages.grupos_mesas') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('grupos.index') }}" class="small-box-footer text-light-dark"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @can("listar cartao")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white  bg-light-success" title="Cartão de Consumo">
                        <div class="inner">
                            <h3>::</h3>
                            <p class="text-uppercase">{{ __('messages.cartao_consumo') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('cartoes-consumos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @can("criar vendas")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-danger" title="Começar à Venda">
                        <div class="inner">
                            <h3>::</h3>
                            <p class="text-uppercase">Começar à Venda</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        @if ($empresa_logada->empresa->tipo_pronto_venda == 'Grelha')
                        <a href="{{ route('pronto-venda') }}" class="small-box-footer {{ Route::currentRouteNamed('pronto-venda') ? 'active' : '' }}"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i> </a>
                        @endif

                        @if ($empresa_logada->empresa->tipo_pronto_venda == 'Lista')
                        <a href="{{ route('pos.index') }}" class="small-box-footer {{ Route::currentRouteNamed('pos.index') ? 'active' : '' }}"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i> </a>
                        @endif
                    </div>
                </div>
                @endcan

                @can("controle cuzinha")
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white  bg-light-primary" title="CUZINHA">
                        <div class="inner">
                            <h3>::</h3>
                            <p class="text-uppercase">Ir para Cuzinha</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('cuzinha.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <h4>📊 {{ __('messages.faturamento_15_dias') }}</h4>
                                </div>
                                <div class="col-12 col-md-7">
                                    <div>
                                        <label for="dataFinal">{{ __('messages.data_final') }}:</label>
                                        <input type="date" id="dataFinal" value="{{ now()->toDateString() }}" class="form-control w-auto d-inline-block">
                                        <button id="filtrar" class="btn btn-light-primary"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                                        <button onclick="imprimirGrafico()" class="btn btn-light-primary"><i class="fas fa-print"></i> {{ __('messages.imprimir') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <canvas id="graficoVendas" width="400" height="150"></canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('grafico.vendas.pdf') }}" class="btn btn-light-danger" target="_blank">
                                <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir_relatorio_detalhado') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <h4>{{ __('messages.comparativo_mes') }}</h4>
                                </div>
                                <div class="col-12 col-md-7">
                                    <button id="btnImprimirGrafico" class="btn btn-light-primary">
                                        <i class="fas fa-print"></i> {{ __('messages.imprimir_grafico') }}
                                    </button>

                                    <button id="btnExportarPDF" class="btn btn-light-danger">
                                        <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir_relatorio_detalhado') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoComparativo" width="400" height="170"></canvas>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div>

            </div>

            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFOR')
            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($total_solicitacao, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.solicitacoes') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('solicitacoes-documentos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }}
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($total_alunos, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ _('messages.aluno') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('clientes.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($total_formadores, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.formadores') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('funcionarios.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($total_anos_lectivos, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.ano_lectivo') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('anos-lectivos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($total_salas, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.sala') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('salas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($total_turnos, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.turno') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('turnos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($total_cursos, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.curso') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('cursos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($total_turmas, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.turma') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('turmas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOTL')
            <div class="row">

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-success" title="Hospedes">
                        <div class="inner">
                            <h3>{{ number_format($totalCliente, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.hospede') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('clientes.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Reservas">
                        <div class="inner">
                            <h3>{{ number_format($totalReservasFeitasHoje, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">Total Reserva de Hoje</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('reservas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-success" title=" Reservas">
                        <div class="inner">
                            <h3>{{ number_format($reservasEmUso, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">Reservas activas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('reservas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Reservas">
                        <div class="inner">
                            <h3>{{ number_format($totalReservas, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.reserva') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('reservas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-warning" title="Quartos">
                        <div class="inner">
                            <h3>{{ number_format($totalQuarto, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.quarto') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('quartos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-success" title="Tarifários">
                        <div class="inner">
                            <h3>{{ number_format($totalTarifarios, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.tarefarios') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('tarefarios.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-success" title="Check In Diário">
                        <div class="inner">
                            <h3>{{ number_format($totalReservasCheckIn, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.check_in_diario') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('reservas.check_in_diario') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-danger" title="Check Out Diário">
                        <div class="inner">
                            <h3>{{ number_format($totalReservasCheckOut, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.check_out_diario') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('reservas.check_out_diario') }}" class="small-box-footer">{{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-warning" title="Restaurante & Bar">
                        <div class="inner">
                            <h3>::</h3>
                            <p class="text-uppercase">{{ __('messages.restaurante_bar') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('painel.escolha') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary"" title=" Pedidos aos Quartos">
                        <div class="inner">
                            <h3>::</h3>
                            <p class="text-uppercase">{{ __('messages.pedidos_aos_quartos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('pronto-venda-quartos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>Monit.</h3>
                            <p class="text-uppercase">{{ __('messages.monitoramento_quartos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('quartos.visualizacao-andares-quartos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CONS')
            <div class="row">

                <div class="col-lg-3 col-md-3 col-12">

                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($total_pendentes, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.agendamento_pendente') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('agendamentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">

                    <div class="small-box bg-light-danger">
                        <div class="inner">
                            <h3>{{ number_format($total_expirados, 0, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.agendamento_expirado') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('agendamentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                <div class="col-lg-3 col-md-3 col-12">

                    <div class="small-box bg-light-warning" title="Quantidade Produtos Em Stock">
                        <div class="inner">
                            <h3>{{ $total_cancelados }}</h3>
                            <p class="text-uppercase">{{ __('messages.agendamento_cancelado') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('agendamentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">

                    <div class="small-box bg-light-success">
                        <div class="inner">
                            <h3>{{ $total_atendidos }}</h3>
                            <p class="text-uppercase">{{ __('messages.agendamento_atendido') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('agendamentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')

            <div class="row">

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-success">
                        <div class=" inner">
                            <h3 id="hoje"></h3>
                            <p class="text-uppercase">Atendimentos Hoje</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('atendimentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-warning">
                        <div class=" inner">
                            <h3 id="mes"></h3>
                            <p class="text-uppercase">Total do Mês</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('atendimentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-danger">
                        <div class=" inner">
                            <h3 id="faltas"></h3>
                            <p class="text-uppercase">Taxa de Faltas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('atendimentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-danger">
                        <div class=" inner">
                            <h3 id="critico"></h3>
                            <p class="text-uppercase">Status Crítico</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('atendimentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>📊 Status dos Atendimentos</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="statusChart"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>📈 Evolução Diária</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="evolucaoChart"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>⏱ Tempo Médio de Atendimento</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="tempoMedioChart"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-primary">
                        <div class=" inner">
                            <h3 id="totalTriagens"></h3>
                            <p class="text-uppercase">Total Triagens</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('triagens.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-warning">
                        <div class=" inner">
                            <h3 id="hojeTriagem"></h3>
                            <p class="text-uppercase">Triagens de Hoje</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('triagens.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-success">
                        <div class=" inner">
                            <h3 id="concluidos"></h3>
                            <p class="text-uppercase">Triagens Concluídos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('triagens.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light-danger">
                        <div class=" inner">
                            <h3 id="atendimento"></h3>
                            <p class="text-uppercase">Em Atendimento</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('triagens.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


            </div>

            <div class="row">

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Triagem por periodo</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="triagensPeriodo"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profissionais</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="profissionais"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Graficos de Queixas</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="queixas"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Graficos por peso (IMC)</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="imc"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card">
                        <<div class="card-header">
                            <h4>Triagem por estados</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="status"></canvas>
                    </div>
                    <div class="card-footer"></div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Triagem por sinais vigitais</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="sinaisVitais"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>

            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'SEGPRIVADA')
            <div class="row">

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($totalCliente ?? 0, 2, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.clientes') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('clientes.index') }}" class="small-box-footer"> {{ __('messages.clientes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($totalClienteContratos ?? 0, 2, ',', '.') }}</h3>
                            <p class="text-uppercase">Contratos de Clientes</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('clientes-contratos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($totalPostos ?? 0, 2, ',', '.') }}</h3>
                            <p class="text-uppercase">Postos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('contratos-postos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format($totalOcorrencias ?? 0, 2, ',', '.') }}</h3>
                            <p class="text-uppercase">Ocorrências</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('ocorrencias.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>Scanner</h3>
                            <p class="text-uppercase">Cartões</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('ocorrencias.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
            @endif

            @if ( $empresa_logada->empresa->tipo_entidade->sigla == 'GEST_EMPRE' || $empresa_logada->empresa->tipo_entidade->sigla == 'CFAT')
            @if ($empresa_logada->empresa->tem_perfil('Gestão Facturação'))
            <div class="row">

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-warning" title="Quantidade Produtos Vendidos">
                        <div class="inner">
                            <h3>{{ number_format($vendas->total_quantidade ?? 0, 2, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.quantidade_produtos_vendidos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('vendas') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">

                    <div class="small-box text-white bg-light-success" title="Valor Acumulado Vendas">
                        <div class="inner">
                            <h3>{{ number_format($vendas->total_vendas ?? 0, 2, ',', '.') }}</h3>
                            <p class="text-uppercase">{{ __('messages.valor_acumulado_vendas') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('vendas') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-danger" title="Quantidade Produtos Em Stock">
                        <div class="inner">
                            <h3>{{ $total_estoque_activo ?? 0 }}</h3>
                            <p class="text-uppercase">{{ __('messages.quantidade_produtos_stock') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('estoques-produtos', ['status' => 'activo']) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">

                    <div class="small-box text-white bg-light-danger" title="Quantidade Produtos Expirados Em Stock">
                        <div class="inner">
                            <h3>{{ $total_estoque_expirado ?? 0 }}</h3>
                            <p class="text-uppercase">{{ __('messages.produtos_expirados_stock') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('estoques-produtos', ['status' => 'expirado']) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white bg-light-danger" title="Quantidade Produtos Em Stock">
                        <div class="inner">
                            <h3>{{ $total_produtos }}</h3>
                            <p class="text-uppercase">{{ __('messages.produtos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('produtos.index', ['tipo' => 'P']) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box text-white  bg-light-success" title="Quantidade Serviços Em Stock">
                        <div class="inner">
                            <h3>{{ $total_servicos }}</h3>
                            <p class="text-uppercase">{{ __('messages.servico')}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('produtos.index', ['tipo' => 'S']) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            @endif
            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla !== 'PADARIA' && $empresa_logada->empresa->tipo_entidade->sigla !== 'HOSP' && $empresa_logada->empresa->tipo_entidade->sigla !== 'REST' && $empresa_logada->empresa->tipo_entidade->sigla !== 'HOTL' && $empresa_logada->empresa->tipo_entidade->sigla !== 'SEGPRIVADA' )
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <h4>{{ __('messages.produtos_mais_vendidos') }}</h4>
                                </div>
                                <div class="col-12 col-md-7">
                                    <div class="float-right">
                                        <button class="btn btn-light-primary" onclick="printGraficoProdutoMaisVendido()"><i class="fas fa-print"></i> {{ __('messages.imprimir_grafico') }}</button>
                                        <a href="{{ route('dashboard.produtos_mais_vendidos.pdf', ['inicio' => now()->subDays(14)->format('Y-m-d'), 'fim' => now()->format('Y-m-d')]) }}" id="btnPdfDetalhado" target="_blank" class="btn btn-light-success">
                                            📥 {{ __('messages.imprimir_relatorio_detalhado') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="">
                                <label>{{ __('messages.periodo') }}:</label>
                                <input type="date" id="inicio" class="form-control d-inline" style="width: 200px;">
                                <input type="date" id="fim" class="form-control d-inline" style="width: 200px;">
                                <button onclick="carregarProdutosMaisVendidos()" class="btn btn-light-primary"> {{ __('messages.filtrar') }}</button>
                            </div>
                            <canvas id="graficoProdutosMaisVendidos" width="400" height="80"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <h4>{{ __('messages.estoque_critico_por_loja') }}</h4>
                                </div>
                                <div class="col-md-8 col-12">
                                    <div class="float-right">
                                        <select id="lojaId" class="form-control d-inline" style="width: 400px;" onchange="carregarEstoqueCriticoPorLoja()">
                                            <option value="" selected>{{ __('messages.escolher') }}</option>
                                            @foreach ($lojas as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-light-primary" onclick="imprimirGraficoEstoque()"><i class="fas fa-print"></i> {{ __('messages.imprimir_grafico') }}</button>
                                        <a href="{{ route('dashboard.estoque_critico_pdf') }}" target="_blank" class="btn btn-light-danger">
                                            🧾 {{ __('messages.imprimir_relatorio_detalhado') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoEstoqueLoja" width="400" height="80"></canvas>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
            @endif

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')

<script>
    async function carregarProducao(tipo = 'line') {
        let response = await fetch('/dashboard/producao-dia');
        let dados = await response.json();

        let labels = dados.map(x => x.data);
        let producao = dados.map(x => x.total);
        let perdas = dados.map(x => x.perda ? x.perda : 0);
        let eficiencia = dados.map(x => x.eficiencia ? x.eficiencia : 0);

        const ctx = document.getElementById('chartProducao').getContext('2d');

        if (chartInstance) {
            chartInstance.destroy();
        }

        // Gradiente moderno
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(54, 162, 235, 0.6)');
        gradient.addColorStop(1, 'rgba(54, 162, 235, 0.05)');

        let datasets = [];

        if (tipo === 'line' || tipo === 'area') {

            datasets.push({
                label: 'Produção'
                , data: producao
                , borderColor: '#36A2EB'
                , backgroundColor: tipo === 'area' ? gradient : 'transparent'
                , fill: tipo === 'area'
                , tension: 0.4
                , borderWidth: 3
                , pointRadius: 4
            });

            datasets.push({
                label: 'Perdas'
                , data: perdas
                , borderColor: '#FF6384'
                , backgroundColor: 'transparent'
                , tension: 0.4
                , borderWidth: 2
            });
        }

        if (tipo === 'bar') {
            datasets.push({
                label: 'Produção'
                , data: producao
                , backgroundColor: '#36A2EB'
            });

            datasets.push({
                label: 'Perdas'
                , data: perdas
                , backgroundColor: '#FF6384'
            });
        }

        chartInstance = new Chart(ctx, {
            type: tipo === 'area' ? 'line' : tipo
            , data: {
                labels: labels
                , datasets: datasets
            }
            , options: {
                responsive: true
                , maintainAspectRatio: false,

                interaction: {
                    mode: 'index'
                    , intersect: false
                },

                plugins: {
                    legend: {
                        position: 'top'
                    },

                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true
                        , grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    }
                    , x: {
                        grid: {
                            display: false
                        }
                    }
                },

                animation: {
                    duration: 1200
                    , easing: 'easeInOutQuart'
                }
            }
        });
    }

    // mudança de tipo de gráfico
    document.getElementById('tipoGrafico').addEventListener('change', function() {
        carregarProducao(this.value);
    });

    carregarProducao();

</script>

<script>
    fetch('/atendimentos-relatorios')
        .then(res => res.json())
        .then(data => {

            // KPIs
            document.getElementById('hoje').innerText = data.total_hoje;
            document.getElementById('mes').innerText = data.total_mes;
            document.getElementById('faltas').innerText = data.taxa_faltas + '%';

            // Status crítico (ex: ausente + internamento)
            let critico = data.por_status
                .filter(i => i.status === 'ausente' || i.status === 'internamento')
                .reduce((a, b) => a + b.total, 0);

            document.getElementById('critico').innerText = critico;

            // 🎨 CORES MAIS BONITAS
            const colors = [
                '#4facfe', '#43e97b', '#fa709a'
                , '#667eea', '#f093fb', '#f5576c'
            ];

            // 📊 STATUS CHART (mais bonito)
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut'
                , data: {
                    labels: data.por_status.map(i => i.status)
                    , datasets: [{
                        data: data.por_status.map(i => i.total)
                        , backgroundColor: colors
                        , borderWidth: 2
                    }]
                }
                , options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // 📈 EVOLUÇÃO MELHORADA
            new Chart(document.getElementById('evolucaoChart'), {
                type: 'line'
                , data: {
                    labels: data.evolucao_diaria.map(i => i.data_at)
                    , datasets: [{
                        label: 'Atendimentos'
                        , data: data.evolucao_diaria.map(i => i.total)
                        , borderColor: '#4facfe'
                        , backgroundColor: 'rgba(79,172,254,0.2)'
                        , fill: true
                        , tension: 0.4
                        , pointBackgroundColor: '#4facfe'
                    }]
                }
                , options: {
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                    , scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const dias = data.tempo_medio_dia.map(i => i.data_at);
            const medias = data.tempo_medio_dia.map(i => i.media);

            new Chart(document.getElementById('tempoMedioChart'), {
                type: 'bar'
                , data: {
                    labels: dias
                    , datasets: [{
                        label: 'Minutos médios'
                        , data: medias
                        , backgroundColor: '#667eea'
                    }]
                }
                , options: {
                    responsive: true
                    , plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

        });

</script>

<script>
    let charts = {};

    function carregarDashboard() {
        fetch('/triagens-relatorios')
            .then(response => response.json())
            .then(data => {

                $('#totalTriagens').text(data.cards.total_triagens);
                $('#hojeTriagem').text(data.cards.hoje);
                $('#concluidos').text(data.cards.concluidos);
                $('#atendimento').text(data.cards.em_atendimento);

                gerarTriagens(data.triagensPeriodo);
                gerarProfissionais(data.profissionais);
                gerarQueixas(data.queixas);
                gerarIMC(data.imc);
                gerarStatus(data.status);
                gerarSinais(data.sinaisVitais);

            });
    }

    carregarDashboard();

    function gerarTriagens(dados) {

        if (charts.triagens)
            charts.triagens.destroy();

        charts.triagens = new Chart(
            document.getElementById('triagensPeriodo'), {
                type: 'line'
                , data: {
                    labels: dados.map(x => x.data)
                    , datasets: [{
                        label: 'Triagens'
                        , data: dados.map(x => x.total)
                        , borderColor: '#0d6efd'
                    }]
                }
            });
    }


    function gerarProfissionais(dados) {

        if (charts.profissionais)
            charts.profissionais.destroy();

        charts.profissionais = new Chart(
            document.getElementById('profissionais'), {
                type: 'bar'
                , data: {
                    labels: dados.map(x => x.profissional ? x.profissional.nome : '')
                    , datasets: [{
                        data: dados.map(x => x.total)
                        , backgroundColor: '#198754'
                    }]
                }
            });
    }

    function gerarQueixas(dados) {

        if (charts.queixas)
            charts.queixas.destroy();

        charts.queixas = new Chart(
            document.getElementById('queixas'), {
                type: 'bar'
                , data: {
                    labels: dados.map(x => x.queixa_principal)
                    , datasets: [{
                        data: dados.map(x => x.total)
                    }]
                }
            });
    }


    function gerarIMC(dados) {

        if (charts.imc)
            charts.imc.destroy();

        charts.imc = new Chart(
            document.getElementById('imc'), {
                type: 'doughnut'
                , data: {
                    labels: dados.map(x => x.imc_classificacao)
                    , datasets: [{
                        data: dados.map(x => x.total)
                    }]
                }
            });
    }


    function gerarStatus(dados) {

        if (charts.status)
            charts.status.destroy();

        charts.status = new Chart(
            document.getElementById('status'), {
                type: 'pie'
                , data: {
                    labels: dados.map(x => x.status)
                    , datasets: [{
                        data: dados.map(x => x.total)
                    }]
                }
            });
    }

    function gerarSinais(dados) {

        if (charts.sinais)
            charts.sinais.destroy();

        charts.sinais = new Chart(
            document.getElementById('sinaisVitais'), {
                type: 'bar'
                , data: {
                    labels: [
                        'Febre'
                        , 'Taquicardia'
                        , 'Hipertensão'
                    ]
                    , datasets: [{
                        data: [
                            dados.febre
                            , dados.taquicardia
                            , dados.hipertensos
                        ]
                    }]
                }
            });
    }

</script>

<script>
    let chartInstance;
    let chartEstoqueLoja;
    let chartProdutos = null;

    function carregarGrafico(dataFinal = null) {
        let url = `{{ route("grafico.vendas") }}`;
        if (dataFinal) {
            url += `?data=${dataFinal}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('graficoVendas').getContext('2d');

                if (chartInstance) chartInstance.destroy();

                chartInstance = new Chart(ctx, {
                    type: 'bar'
                    , data: {
                        labels: data.labels
                        , datasets: [{
                            label: 'Faturamento (Kz)'
                            , data: data.valores
                            , backgroundColor: 'rgba(255, 99, 132, 0.6)'
                            , borderColor: 'rgba(255, 99, 132, 1)'
                            , borderWidth: 1
                            , tension: 0.4
                        }, {
                            type: 'line'
                            , label: 'Tendência'
                            , data: data.valores
                            , borderColor: 'rgba(75, 192, 192, 1)'
                            , backgroundColor: 'rgba(75, 192, 192, 0.6)'
                            , borderWidth: 2
                            , tension: 0.3
                            , fill: false
                            , pointRadius: 10
                            , pointBackgroundColor: 'white'
                            , pointBorderColor: 'rgba(75, 192, 192, 0.6)'
                        }]
                    }
                    , options: {
                        responsive: true
                        , scales: {
                            y: {
                                beginAtZero: true
                                , title: {
                                    display: true
                                    , text: 'Valor (Kz)'
                                }
                            }
                            , x: {
                                title: {
                                    display: true
                                    , text: 'Data'
                                }
                            }
                        }
                    }
                });
            });
    }

    function printGraficoProdutoMaisVendido() {
        const canvas = document.getElementById('graficoProdutosMaisVendidos');
        const win = window.open('', '_blank');
        win.document.write('<html><head><title>Gráfico de Produtos Mais Vendidos</title></head><body>');
        win.document.write('<h3>Produtos Mais Vendidos</h3>');
        win.document.write('<img src="' + canvas.toDataURL() + '"/>');
        win.document.write('</body></html>');
        win.document.close();
        win.print();
    }

    document.addEventListener('DOMContentLoaded', function() {

        carregarGrafico();
        carregarProdutosMaisVendidos();
        carregarEstoqueCriticoPorLoja();

        document.getElementById('filtrar').addEventListener('click', function() {
            const dataFinal = document.getElementById('dataFinal').value;
            carregarGrafico(dataFinal);
        });

        document.getElementById("btnImprimirGrafico").addEventListener("click", function() {
            const canvas = document.getElementById("graficoComparativo");
            const imagem = canvas.toDataURL();
            const win = window.open('', '_blank');

            win.document.write(`
                <html>
                <head>
                    <title>Gráfico de Vendas</title>
                </head>
                <body>
                    <h3 style="text-align:center;">Gráfico de Vendas - Comparativo Mês a Mês</h3>
                    <img src="${imagem}" style="width:100%;max-width:900px;"/>
                </body>
                </html>
            `);
            win.document.close();
            win.print();
        });

        document.getElementById("btnExportarPDF").addEventListener("click", async function() {
            const {
                jsPDF
            } = window.jspdf;
            const canvas = document.getElementById("graficoComparativo");
            html2canvas(canvas.parentElement).then(canvasRendered => {
                const imgData = canvasRendered.toDataURL("image/png");
                const pdf = new jsPDF("landscape", "mm", "a4");

                pdf.setFontSize(16);
                pdf.text("Relatório Gráfico de Vendas - Comparativo Mês a Mês", 10, 15);
                pdf.addImage(imgData, "PNG", 10, 25, 270, 0); // auto height
                pdf.save("relatorio_comparativo_vendas.pdf");
            });
        });

        fetch('{{ route("grafico.comparativo") }}')
            .then(response => response.json())
            .then(dados => {
                const labels = dados.map(item => new Date(0, item.mes - 1).toLocaleString('default', {
                    month: 'short'
                }));
                const faturamento = dados.map(item => item.faturamento);
                const pedidos = dados.map(item => item.pedidos);
                const lucro = dados.map(item => item.lucro);

                const ctx = document.getElementById("graficoComparativo").getContext("2d");

                new Chart(ctx, {
                    type: 'line'
                    , data: {
                        labels: labels
                        , datasets: [{
                                label: 'Faturamento (Kz)'
                                , data: faturamento
                                , borderColor: '#4e73df'
                                , backgroundColor: 'rgba(78, 115, 223, 0.1)'
                                , tension: 0.3
                            , }
                            , {
                                label: 'Pedidos'
                                , data: pedidos
                                , borderColor: '#1cc88a'
                                , backgroundColor: 'rgba(28, 200, 138, 0.1)'
                                , tension: 0.3
                            , }
                            , {
                                label: 'Lucro (Kz)'
                                , data: lucro
                                , borderColor: '#e74a3b'
                                , backgroundColor: 'rgba(231, 74, 59, 0.1)'
                                , tension: 0.3
                            , }
                        ]
                    }
                    , options: {
                        responsive: true
                        , plugins: {
                            legend: {
                                position: 'top'
                            }
                            , title: {
                                display: true
                                , text: 'Comparativo Mensal de Vendas'
                            }
                        }
                        , scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });

        const hoje = new Date().toISOString().split("T")[0];
        const atras = new Date(Date.now() - 14 * 24 * 60 * 60 * 1000).toISOString().split("T")[0];

        document.getElementById("inicio").value = atras;
        document.getElementById("fim").value = hoje;


    });

    function imprimirGrafico() {
        const canvas = document.getElementById('graficoVendas');
        const janela = window.open('', '_blank');

        const imagem = canvas.toDataURL();

        janela.document.write(`
            <html>
            <head><title>Imprimir Gráfico</title></head>
            <body style="text-align: center">
                <h3>Gráfico de Faturamento dos ultimos 15 dias</h3>
                <img src="${imagem}" style="width: 100%; max-width: 800px;"/>
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() {
                            window.close();
                        };
                    };
                <\/script>
            </body>
            </html>
        `);

        janela.document.close();
    }

    function carregarProdutosMaisVendidos() {
        const inicio = document.getElementById("inicio").value;
        const fim = document.getElementById("fim").value;

        fetch(`/dashboard/produtos-mais-vendidos?inicio=${inicio}&fim=${fim}`)
            .then(res => res.json())
            .then(data => {

                const labels = data.map(item => item.produto.nome);
                const quantidadeVendida = data.map(item => item.total_vendido);
                const totalVendido = data.map(item => item.valor_total);
                const lucros = data.map(item => item.lucro_total || 0); // lucro deve vir da API

                const ctx = document.getElementById('graficoProdutosMaisVendidos').getContext('2d');

                // Atualizar ou criar o gráfico
                if (chartProdutos) {
                    chartProdutos.destroy();
                }

                chartProdutos = new Chart(ctx, {
                    type: 'bar', // ou 'doughnut'
                    data: {
                        labels: labels
                        , datasets: [{
                                label: 'Quantidade Vendida'
                                , data: quantidadeVendida
                                , backgroundColor: 'rgba(54, 162, 235, 0.6)'
                                , borderColor: 'rgba(54, 162, 235, 1)'
                                , borderWidth: 1
                            }
                            , {
                                label: 'Total Vendido (Kz)'
                                , data: totalVendido
                                , backgroundColor: 'rgba(255, 159, 64, 0.6)'
                                , borderColor: 'rgba(255, 159, 64, 1)'
                                , borderWidth: 1
                            }
                            , {
                                label: 'Lucro Estimado (Kz)'
                                , data: lucros
                                , backgroundColor: 'rgba(75, 192, 192, 0.6)'
                                , borderColor: 'rgba(75, 192, 192, 1)'
                                , borderWidth: 1
                            }
                        ]
                    }
                    , options: {
                        indexAxis: 'y'
                        , responsive: true
                        , plugins: {
                            legend: {
                                display: true
                                , position: 'top'
                            }
                            , tooltip: {
                                enabled: true
                            , }
                        }
                        , scales: {
                            x: {
                                beginAtZero: true
                            , }
                        }
                    }
                });
            });
    }

    function imprimirGraficoEstoque() {
        const canvas = document.getElementById("graficoEstoqueLoja");
        const win = window.open();
        win.document.write(`<img src="${canvas.toDataURL()}" style="width:100%" />`);
        win.print();
    }

    function carregarEstoqueCriticoPorLoja() {
        const lojaId = document.getElementById('lojaId').value;

        fetch(`{{ route('dashboard.estoque.critico') }}${lojaId ? '?loja_id=' + lojaId : ''}`)
            .then(res => res.json())
            .then(data => {
                const labels = data.map(p => `${p.loja.nome} - ${p.produto.nome}`);
                const saldo = data.map(p => p.saldo_atual);
                const minimo = data.map(p => p.stock_minimo);

                const ctx = document.getElementById("graficoEstoqueLoja").getContext("2d");

                if (window.graficoEstoqueCritico) {
                    window.graficoEstoqueCritico.destroy();
                }

                window.graficoEstoqueCritico = new Chart(ctx, {
                    type: 'bar'
                    , data: {
                        labels: labels
                        , datasets: [{
                                label: 'Saldo Atual'
                                , data: saldo
                                , backgroundColor: 'rgba(255, 99, 132, 0.7)'
                            }
                            , {
                                label: 'Estoque Mínimo'
                                , data: minimo
                                , backgroundColor: 'rgba(54, 162, 235, 0.5)'
                            }
                        ]
                    }
                    , options: {
                        responsive: true
                        , plugins: {
                            tooltip: {
                                callbacks: {
                                    label: ctx => ` ${ctx.dataset.label}: ${ctx.raw}`
                                }
                            }
                            , legend: {
                                display: true
                                , position: 'bottom'
                            }
                        }
                        , scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    }

</script>

@endsection
