@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Atendimento Hospitalar</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('atendimentos.index') }}">Valtar</a></li>
                        <li class="breadcrumb-item active">Atendimento</li>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Dados do Paciente
                            </h3>
                        </div>
                        <div class="card-body">
                            <h4>
                                {{ $atendimento->paciente->nome }}
                            </h4>
                            <p>
                                BI: {{ $atendimento->paciente->nif ?? 'N/A' }} |
                                Telefone: {{ $atendimento->paciente->telefone ?? 'N/A' }}
                            </p>
                            <span class="badge bg-light-primary">
                                Atendimento: {{ $atendimento->tipo->nome }}
                            </span>
                            @if($atendimento->contaHospitalar)
                            <span id="status-conta" class="badge bg-light-{{ $atendimento->contaHospitalar->status == 'PAGA' ? 'success': ( $atendimento->contaHospitalar->status == 'ABERTA' ? 'warning' : 'danger') }}">
                                Estado da conta: {{ $atendimento->contaHospitalar->status ?? "Sem conta activa" }}
                            </span>
                            @endif
                            @if ($atendimento->paciente->plano)
                            <span class="badge bg-light-success float-right">
                                PLANO DE SAÚDE: {{ $atendimento->paciente->plano->plano->nome ?? "N/A" }} - SEGURADORA: {{ $atendimento->paciente->plano->plano->seguradora->nome ?? "N/A" }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            @if($atendimento->contaHospitalar)
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header bg-dark">
                            <h3 class="card-title">
                                Conta Hospitalar (Carrinho)
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="tabela-itens">
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Qtd</th>
                                        <th>Preço</th>
                                        <th>Valor Seguradora</th>
                                        <th>Valor Paciente</th>
                                        <th>Cobertura</th>
                                        <th>Total</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($atendimento->contaHospitalar->itens as $item)
                                    <tr>
                                        <td>{{ $item->servico->nome ?? "" }}</td>

                                        <td class="text-right">{{ $item->quantidade }}</td>
                                        <td class="text-right">{{ number_format($item->preco_unitario,2) }}</td>
                                        <td class="text-right">{{ number_format($item->valor_seguradora,2) }}</td>
                                        <td class="text-right">{{ number_format($item->valor_paciente,2) }}</td>
                                        <td class="text-right">{{ number_format($item->percentual_cobertura,2) }}</td>
                                        <td class="text-right">{{ number_format($item->subtotal,2) }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm btn-remover-item" data-id="{{ $item->id ?? "" }}">
                                                Remover
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="3" class="text-right">SUBTOTAL</th>
                                        <th class="text-right">{{ number_format($atendimento->contaHospitalar->itens->sum('valor_seguradora'),2) }}</th>
                                        <th class="text-right">{{ number_format($atendimento->contaHospitalar->itens->sum('valor_paciente'),2) }}</th>
                                        <th class="text-right"></th>
                                        <th class="text-right">{{ number_format($atendimento->contaHospitalar->itens->sum('subtotal'),2) }}</th>
                                        <th></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            @if (Auth::user()->can('concluir conta hospitalar'))
                            @if ($atendimento->contaHospitalar->status !== "CANCELADA" && $atendimento->contaHospitalar->status !== 'PAGA')
                            <button class="btn btn-light-danger btn-block" id="btn-cancelar-conta">
                                Cancelar Conta
                            </button>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Resumo</h3>
                        </div>
                        <div class="card-body">
                            <p><b>Subtotal:</b> {{ number_format($atendimento->contaHospitalar->subtotal,2) }}</p>
                            <p><b>Pago:</b> {{ number_format($atendimento->contaHospitalar->valor_pago,2) }}</p>
                            <p><b>Saldo:</b>
                                <span class="text-danger">
                                    {{ number_format($atendimento->contaHospitalar->saldo,2) }}
                                </span>
                            </p>
                            <hr>

                            @if ($atendimento->contaHospitalar->status !== "CANCELADA" && $atendimento->contaHospitalar->status !== 'PAGA')
                            @if (Auth::user()->can('adicionar item conta hospitalar'))
                            <button class="btn btn-light-primary btn-block" id="btn-add-item">
                                + Adicionar Item
                            </button>
                            @endif

                            @if (Auth::user()->can('concluir conta hospitalar'))
                            <a href="{{ route('contas-hospitalares.show', $atendimento->contaHospitalar->id) }}" class="btn btn-light-success btn-block">
                                Receber Pagamento
                            </a>
                            @endif
                            @endif

                            @if (Auth::user()->can('concluir conta hospitalar'))
                            @if ($atendimento->contaHospitalar->status == "ABERTA")
                            <button class="btn btn-light-danger btn-block" id="btn-fechar-conta">
                                Fechar Conta
                            </button>
                            @endif
                            @if ($atendimento->contaHospitalar->status == "FECHADA")
                            <button class="btn btn-light-dark btn-block" id="btn-reabrir-conta">
                                Reabrir Conta
                            </button>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-12 col-md-12">
                    <h4>SEM CONTA HOSPITALAR</h4>
                </div>
            </div>
            @endif


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-primary card-outline card-tabs">
                        <div class="card-body">
                            <h5>ATENDIMENTO Nº: {{ $atendimento->numero }}</h5>
                            <h5 class="text-uppercase">{{ __('messages.estados') }}: {{ $atendimento->status }}</h5>
                            <h5>PRIORIDADE DO ATENDIMENTO: {{ $atendimento->prioridade->nome  }} {{ $atendimento->prioridade->tipo_cor($atendimento->prioridade->cor)  }}</h5>
                        </div>
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                <li class="nav-item">
                                    <a class="nav-link active" id="dados-paciente-tab" data-toggle="pill" href="#dados-paciente" role="tab" aria-controls="dados-paciente" aria-selected="false">DADOS DO PACIENTE</a>
                                </li>
                                @endif
                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar triagem'))
                                <li class="nav-item">
                                    <a class="nav-link" id="dados-triagem-tab" data-toggle="pill" href="#dados-triagem" role="tab" aria-controls="dados-triagem" aria-selected="false">DADOS DA TRIAGEM</a>
                                </li>
                                @endif
                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar consulta'))
                                <li class="nav-item">
                                    <a class="nav-link" id="lista-consultas-tab" data-toggle="pill" href="#lista-consultas" role="tab" aria-controls="lista-consultas" aria-selected="false">CONSULTAS SOLICITADAS</a>
                                </li>
                                @endif
                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar exame'))
                                <li class="nav-item">
                                    <a class="nav-link" id="lista-exames-tab" data-toggle="pill" href="#lista-exames" role="tab" aria-controls="lista-exames" aria-selected="false">LISTA DOS EXAMES SOLICITADOS</a>
                                </li>
                                @endif
                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar internamento'))
                                <li class="nav-item">
                                    <a class="nav-link" id="dados-consulta-tab" data-toggle="pill" href="#dados-consulta" role="tab" aria-controls="dados-consulta" aria-selected="true">DADOS DA INTERNAMENTO</a>
                                </li>
                                @endif
                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar tratamento'))
                                <li class="nav-item">
                                    <a class="nav-link" id="dados-planos-tratamentos-tab" data-toggle="pill" href="#dados-planos-tratamentos" role="tab" aria-controls="dados-planos-tratamentos" aria-selected="true">PLANOS DE TRATAMENTOS</a>
                                </li>
                                @endif
                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar receita medica'))
                                <li class="nav-item">
                                    <a class="nav-link" id="dados-receitas-medicas-tab" data-toggle="pill" href="#dados-receitas-medicas" role="tab" aria-controls="dados-receitas-medicas" aria-selected="true">RECEITAS MÉDICA</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                <div class="tab-pane fade show active" id="dados-paciente" role="tabpanel" aria-labelledby="dados-paciente-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 table-responsive">
                                            <table class=" table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Nome Paciente Nº</th>
                                                        <td class="text-right">
                                                            {{ $atendimento->paciente->nome }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.data_nascimento') }}</th>
                                                        <td class="text-right">
                                                            {{ $atendimento->paciente->data_nascimento }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.idade') }}</th>
                                                        <td class="text-right">
                                                            {{ $atendimento->paciente->idade($atendimento->paciente->data_nascimento) }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Identificador</th>
                                                        <td class="text-right">
                                                            <a href="{{ route('clientes.show', $atendimento->paciente->id) }}">{{ $atendimento->paciente->id }}</a>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th> {{ __('messages.telemovel') }} </th>
                                                        <td class="text-right">
                                                            <a href="{{ route('clientes.show', $atendimento->paciente->id) }}">{{ $atendimento->paciente->telefone }}</a>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar tratamento'))
                                <div class="tab-pane fade" id="dados-consulta" role="tabpanel" aria-labelledby="dados-consulta-tab">
                                    <div class="row">
                                        @if ($atendimento->internamento)
                                        <div class="col-12 col-md-6 table-responsive">
                                            <table class=" table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Internamento Nº</th>
                                                        <td class="text-right">{{ $atendimento->internamento->numero ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th> {{ __('messages.data') }} </th>
                                                        <td class="text-right">{{ $atendimento->internamento->data_internacao ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Data Alta</th>
                                                        <td class="text-right">{{ $atendimento->internamento->data_alta ?? "" }}</td>
                                                    </tr>

                                                    @if ($atendimento->internamento->status ?? "" == 'activo')
                                                    <tr>
                                                        <th>{{ __('messages.estados') }}</th>
                                                        <td class="text-right">{{ $atendimento->internamento->status ?? "" }}</td>
                                                    </tr>
                                                    @endif

                                                    @if ($atendimento->internamento->status ?? "" == 'alta')
                                                    <tr>
                                                        <th>{{ __('messages.estados') }}</th>
                                                        <td class="text-right">{{ $atendimento->internamento->status ?? "" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Resumo da Alta</th>
                                                        <td class="text-right">{{ $atendimento->internamento->resumo_alta  ?? ""}}</td>
                                                    </tr>
                                                    @endif

                                                    @if ($atendimento->internamento->status ?? "" == 'obito')
                                                    <tr>
                                                        <th>{{ __('messages.estados') }}</th>
                                                        <td class="text-right">{{ $atendimento->internamento->status  ?? ""}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Resumo do Obito</th>
                                                        <td class="text-right">{{ $atendimento->internamento->resumo_obito  ?? ""}}
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    @if ($atendimento->internamento->status ?? "" == 'transferido')
                                                    <tr>
                                                        <th>{{ __('messages.estados') }}</th>
                                                        <td class="text-right">{{ $atendimento->internamento->status ?? "" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Resumo da Transferência</th>
                                                        <td class="text-right">
                                                            {{ $atendimento->internamento->resumo_transferencia ?? "" }}
                                                        </td>
                                                    </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-6 table-responsive">
                                            <table class=" table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Equipa Médica</th>
                                                        <td class="text-right">{{ $atendimento->internamento->equipa->nome ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Leito</th>
                                                        <td class="text-right">{{ $atendimento->internamento->leito->nome  ?? ""}}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Motivo</th>
                                                        <td class="text-right">{{ $atendimento->internamento->motivo  ?? ""}}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Diagnóstico Inicial</th>
                                                        <td class="text-right">
                                                            {{ $atendimento->internamento->diagnostico_inicial ?? "" }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <div class="col-12 col-md-12 text-center my-5">
                                            <h3>SEM INTERNAMENTO</h3>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar triagem'))
                                <div class="tab-pane fade" id="dados-triagem" role="tabpanel" aria-labelledby="dados-triagem-tab">

                                    @include('dashboard.atendimentos._views.detalhe-triagem', ["triagem" => $atendimento->triagem])

                                    <div class="col-12 col-md-12 text-center">
                                        @if ($atendimento->triagem)
                                        <a target="_blank" href="{{ route('triangs.triagens-imprimir', $atendimento->triagem->id ?? "") }}" class="h3 py-3 my-5 btn btn-light-primary">
                                            <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}
                                            Ficha da Triagem Médica
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar consulta'))
                                <div class="tab-pane fade" id="lista-consultas" role="tabpanel" aria-labelledby="lista-consultas-tab">
                                    <div class="row">
                                        @if (count($atendimento->consultas) !== 0)
                                        <div class="col-12 col-md-12 table-responsive">
                                            <table class=" table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4">LISTA DAS CONSULTAS SOLICITADAS</th>
                                                    </tr>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ __('messages.designacao') }}</th>
                                                        <th>{{ __('messages.categoria') }}</th>
                                                        <th>------------</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($atendimento->consultas as $consulta)
                                                    @include('dashboard.atendimentos._views.detalhes-consultas', ["consulta" => $consulta])
                                                    <tr>
                                                        <td colspan="4">
                                                            <a target="_blank" href="{{ route('consultas-imprimir', $consulta->id) }}" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }} </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <div class="col-12 col-md-12 text-center my-5">
                                            <h3>SEM CONSULTAS EXTRAS FEITAS</h3>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar exame'))
                                <div class="tab-pane fade" id="lista-exames" role="tabpanel" aria-labelledby="lista-exames-tab">

                                    @if (count($atendimento->exames) !== 0)
                                    @foreach ($atendimento->exames as $resultado)
                                    @include('dashboard.exames._views.detalhe-exame', ["dados" => $resultado, "editar" => false])
                                    @endforeach
                                    @else
                                    <div class="col-12 col-md-12 text-center my-5">
                                        <h3>SEM EXAMES SOLICITADOS</h3>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar tratamento'))
                                <div class="tab-pane fade" id="dados-planos-tratamentos" role="tabpanel" aria-labelledby="dados-planos-tratamentos-tab">
                                    <div class="row">
                                        @if ($atendimento->planoTratamento)
                                        <div class="col-12 col-md-6 table-responsive">
                                            <table class=" table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Data Inicio</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->data_inicio ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Data Final</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->data_final ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Tipo de Tratamento</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->tipo ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Titulo</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->titulo ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Duração Semanais</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->duracao_semanas ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Frequência</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->frequencia ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Descrição</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->descricao ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Objectivo</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->objectivo ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Orientações Gerais</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->orientacoes_gerais ?? "" }}</td>
                                                    </tr>


                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-6 table-responsive">
                                            <table class=" table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Estado tratamento</th>
                                                        <td class="text-right;">{{ $atendimento->planoTratamento->status  }}</td>
                                                    </tr>
                                                    @if ($atendimento->planoTratamento->equipa)
                                                    <tr>
                                                        <th>Equipa Responsável</th>
                                                        <td class="text-right"><a href="{{ route('equipas.show', $atendimento->planoTratamento->equipa->id ?? '') }}">{{ $atendimento->planoTratamento->equipa->nome ?? "" }}</a></td>
                                                    </tr>
                                                    @endif


                                                    <tr>
                                                        <th>Observações Finais</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->observacoes_finais ?? "" }}</td>
                                                    </tr>


                                                    @if ($atendimento->planoTratamento->status == "finalizado")
                                                    <tr>
                                                        <th>Data Finalização</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->data_finalizacao ?? "" }}</td>
                                                    </tr>
                                                    @endif

                                                    @if ($atendimento->planoTratamento->status == "suspenso")
                                                    <tr>
                                                        <th>Data Suspensão</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->data_suspesao ?? "" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Motivo Suspensão</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->motivo_suspesao ?? "" }}</td>
                                                    </tr>
                                                    @endif

                                                    @if ($atendimento->planoTratamento->status == "cancelado")
                                                    <tr>
                                                        <th>Data Cancelamento</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->data_cancelamento ?? "" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Motivo Cancelamento</th>
                                                        <td class="text-right">{{ $atendimento->planoTratamento->motivo_cancelamento ?? "" }}</td>
                                                    </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-12 text-center">
                                            @if ($atendimento->planoTratamento)
                                            <a target="_blank" href="{{ route('planos-tratamentos.lancar_imprimir', $atendimento->planoTratamento->id ?? "") }}" class="h3 py-3 my-5 btn btn-light-primary">
                                                <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}
                                                Ficha do Plano de Tratamento
                                            </a>
                                            @endif
                                        </div>

                                        @else
                                        <div class="col-12 col-md-12 text-center my-5">
                                            <h3>SEM NENHUM PLANO DE TRATAMENTO</h3>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar receita medica'))
                                <div class="tab-pane fade" id="dados-receitas-medicas" role="tabpanel" aria-labelledby="dados-receitas-medicas-tab">
                                    <div class="row">
                                        @if ($atendimento->receita)
                                        <div class="col-12 col-md-12 table-responsive">
                                            <table class=" table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4">RECEITA Nº: {{ $atendimento->receita->id }} - {{ $atendimento->receita->observacoes }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Medicamento</th>
                                                        <th>Posologia</th>
                                                        <th>Duração dias</th>
                                                        <th>Observações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($atendimento->receita->items as $item)
                                                    <tr>
                                                        <td>{{ $item->medicamento ?? "" }}</td>
                                                        <td>{{ $item->posologia ?? "" }}</td>
                                                        <td>{{ $item->duracao_dias ?? "" }}</td>
                                                        <td>{{ $item->observacoes ?? "" }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-12 text-center">
                                            @if ($atendimento->receita)
                                            <a target="_blank" href="{{ route('consulta-receitas-medico-imprimir', $atendimento->receita->id ?? "") }}" class="h3 py-3 my-5 btn btn-light-primary">
                                                <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}
                                                Ficha da Receita Médica
                                            </a>
                                            @endif
                                        </div>

                                        @else
                                        <div class="col-12 col-md-12 text-center my-5">
                                            <h3>SEM NENHUMA RECEITA MÉDICA</h3>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    @if($atendimento->contaHospitalar)
    <div class="modal fade" id="modalItem">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Adicionar Item</h4>
                </div>
                <div class="modal-body">
                    <form id="form-item">
                        <input type="hidden" id="conta_id" value="{{ $atendimento->contaHospitalar->id }}">
                        <div class="form-group">
                            <label>Descrição</label>
                            <select name="produto_id" id="produto_id" class="form-control select2">
                                <option>Escolher</option>
                                @foreach($produtos as $item)
                                <option value="{{ $item->id ?? "" }}" data-preco="{{ $item->preco }}" data-nome="{{ $item->nome }}"> {{ $item->nome }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" value="1">
                        </div>
                        <div class="form-group">
                            <label>Preço</label>
                            <input type="number" class="form-control" id="preco">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="salvar-item">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    $('#btn-add-item').click(function() {
        $('#modalItem').modal('show');
    });

    let contaId = $("#conta_id").val();

    const routes = {
        adicionarItem: "{{ route('item.add', ':id') }}"
        , removerItem: "{{ route('item.remove', ':id') }}"
        , fecharConta: "{{ route('fechar.conta', ':id') }}"
        , cancelarConta: "{{ route('cancelar.conta', ':id') }}"
        , reabrirConta: "{{ route('reabrir.conta', ':id') }}"
    , };

    $('#produto_id').on('select2:select', function(e) {

        let selected = $(this).find(':selected');
        let preco = selected.data('preco');

        if (!preco) {
            $('#preco').val('');
            return;
        }

        $('#preco').val(preco);
    });

    $('#salvar-item').click(function() {

        let url = routes.adicionarItem.replace(':id', contaId);

        $.ajax({
            url: url
            , type: "POST"
            , data: {
                produto_id: $('#produto_id').val()
                , quantidade: $('#quantidade').val()
                , preco_unitario: $('#preco').val()
                , _token: '{{ csrf_token() }}'
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(res) {
                // Feche o alerta de carregamento
                Swal.close();

                location.reload();
            }
            , error: function(err) { // Feche o alerta de carregamento
                Swal.close();

                alert(err.responseJSON.message);
            }
        });
    });

    $('.btn-remover-item').click(function() {
        let id = $(this).data('id');
        let url = routes.removerItem.replace(':id', id);
        Swal.fire({
            title: 'Tem certeza?'
            , text: "Este item será removido permanentemente."
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, remover'
            , cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url
                    , type: "DELETE"
                    , data: {
                        _token: '{{ csrf_token() }}'
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function() {
                        // Feche o alerta de carregamento
                        Swal.close();

                        Swal.fire({
                            title: 'Removido!'
                            , text: 'O item foi removido com sucesso.'
                            , icon: 'success'
                            , timer: 1500
                            , showConfirmButton: false
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                    , error: function() {
                        // Feche o alerta de carregamento
                        Swal.close();

                        Swal.fire({
                            title: 'Erro!'
                            , text: 'Não foi possível remover o item.'
                            , icon: 'error'
                        });
                    }
                });
            }
        });
    });

    $('#btn-fechar-conta').click(function() {

        let url = routes.fecharConta.replace(':id', contaId);

        Swal.fire({
            title: 'Tem certeza?'
            , text: "Esta conta será fechada!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, fechar'
            , cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url
                    , type: "POST"
                    , data: {
                        _token: "{{ csrf_token() }}"
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(res) {

                        Swal.fire('Sucesso!', 'Conta encerrada com sucesso.', 'success');
                        Swal.close();

                        window.location.reload();
                    }
                    , error: function(xhr) {
                        // Feche o alerta de carregamento
                        Swal.close();
                        alert(xhr.responseJSON ? xhr.responseJSON.message : 'Erro ao fechar conta');
                    }
                });
            }
        });
    });

    $('#btn-reabrir-conta').click(function() {

        let url = routes.reabrirConta.replace(':id', contaId);

        Swal.fire({
            title: 'Tem certeza?'
            , text: "Esta conta será reaberta!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, reabrir'
            , cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url
                    , type: "POST"
                    , data: {
                        _token: "{{ csrf_token() }}"
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(res) {

                        Swal.fire('Sucesso!', 'Conta reaberta com sucesso.', 'success');
                        Swal.close();

                        window.location.reload();
                    }
                    , error: function(xhr) {
                        // Feche o alerta de carregamento
                        Swal.close();
                        alert(xhr.responseJSON ? xhr.responseJSON.message : 'Erro ao fechar conta');
                    }
                });
            }
        });


    });

    $('#btn-cancelar-conta').click(function() {

        let url = routes.cancelarConta.replace(':id', contaId);

        Swal.fire({
            title: 'Tem certeza?'
            , text: "Esta conta será cancelada!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, cancelar'
            , cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url
                    , type: "POST"
                    , data: {
                        _token: "{{ csrf_token() }}"
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(res) {

                        Swal.fire('Sucesso!', 'Conta cancelada com sucesso.', 'success');
                        Swal.close();

                        window.location.reload();
                    }
                    , error: function(xhr) {
                        // Feche o alerta de carregamento
                        Swal.close();
                        alert(xhr.responseJSON ? xhr.responseJSON.message : 'Erro ao fechar conta');
                    }
                });
            }
        });
    });



    document.querySelectorAll('.linha-principal').forEach(row => {
        row.addEventListener('click', function() {

            let id = this.getAttribute('data-id');
            let dropdownAtual = document.getElementById('drop-' + id);

            // Fecha todos os dropdowns
            document.querySelectorAll('.dropdown-parametros').forEach(drop => {
                if (drop !== dropdownAtual) {
                    drop.style.display = 'none';
                }
            });

            // Toggle do atual
            if (dropdownAtual.style.display === 'table-row') {
                dropdownAtual.style.display = 'none';
            } else {
                dropdownAtual.style.display = 'table-row';
            }
        });
    });

    let imagensAtuais = [];
    let imagemSelecionada = null;
    let parametroId = null;

    $(document).on('click', '.btn-ver-imagens', function() {

        parametroId = $(this).data('paramento');

        let imagens = $(this).attr('data-imagens') || '[]';
        imagens = JSON.parse(imagens);

        if (typeof imagens === 'string') {
            imagens = JSON.parse(imagens);
        }

        imagensAtuais = Array.isArray(imagens) ? imagens : [];

        renderImagens();

        $('#modalImagens').modal('show');
        $('#imagem-grande').hide();
    });

    function renderImagens() {

        let container = $('#lista-imagens');
        container.html('');

        imagensAtuais.forEach((img, index) => {
            container.append(`
                <div class="col-md-3 mb-2">
                    <img src="/${img}"
                        class="img-thumbnail img-click"
                        data-index="${index}"
                        style="cursor:pointer; height:120px; object-fit:cover;">
                </div>
            `);
        });
    }

    $(document).on('click', '.img-click', function() {
        let index = $(this).data('index');
        imagemSelecionada = index;
        let src = imagensAtuais[index];

        $('#imagem-grande').attr('src', '/' + src).fadeIn();

    });

</script>
@endsection
