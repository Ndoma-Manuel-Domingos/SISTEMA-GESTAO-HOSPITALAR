@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFOR')
                            Alunos
                            @endif
                            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOTL')
                            Hospedes
                            @endif
                            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CONS')
                            Pacientes
                            @endif
                            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                            Pacientes
                            @endif
                            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFAT')
                            Clientes
                            @endif
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Dados Pessoais</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-12 table-responsive">
                                        <table class="table text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __('messages.designacao') }}</th>
                                                    <td class="text-right">{{ $cliente->nome ?? '-------------' }}</td>
                                                </tr>
                                                <tr>
                                                    <th> {{ __('messages.genero') }} </th>
                                                    <td class="text-right">{{ $cliente->genero ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('messages.data_nascimento') }}</th>
                                                    <td class="text-right">
                                                        {{ $cliente->data_nascimento ?? '-------------' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-12 col-md-12 table-responsive">
                                        <table class="table text-nowrap">
                                            <tbody>

                                                <tr>
                                                    <th>País</th>
                                                    <td class="text-right">{{ $cliente->pais ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('messages.estado_civil') }}</th>
                                                    <td class="text-right">
                                                        {{ $cliente->estado_civil->nome ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th> {{ __('messages.bilhete_identidade') }} </th>
                                                    <td class="text-right">{{ $cliente->nif ?? '-------------' }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-12 col-md-12 table-responsive">
                                        <table class="table text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <th colspan="2">Seguradora</th>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        <strong>Nº Cart:</strong> {{ $cliente->plano->numero_cartao ?? '-------------' }}
                                                    </td>
                                                    <td class="text-left">
                                                        <strong>Matrícula:</strong> {{ $cliente->plano->matricula ?? '-------------' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        <strong>Data Inicial:</strong> {{ $cliente->plano->data_inicio ?? '-------------' }}
                                                    </td>
                                                    <td class="text-left">
                                                        <strong>Data Fim: </strong> {{ $cliente->plano->data_fim ?? '-------------' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        <strong>Estado:</strong> {{ $cliente->plano->status ?? '-------------' }}
                                                    </td>
                                                    <td class="text-left">
                                                        <strong>Limite:</strong> {{ number_format($cliente->plano->limite ?? 0, 2) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        <strong>Plano:</strong> {{ $cliente->plano->plano->nome ?? '-------------' }}
                                                    </td>
                                                    <td class="text-left">
                                                        <strong>Seguradora:</strong> {{ $cliente->plano->plano->seguradora->nome ?? '-------------' }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>



                                    <div class="col-12 col-md-12 table-responsive">
                                        <table class="table text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <th>Morada</th>
                                                    <th>Províncias</th>
                                                    <th>Município</th>
                                                    <th>Distrito</th>
                                                </tr>
                                                <tr>
                                                    <td>{{ $cliente->morada ?? '-------------' }}
                                                        <br>{{ $cliente->codigo_postal ?? '-------------' }}
                                                    </td>
                                                    <td>{{ $cliente->provincia->nome ?? '-------------' }}</td>
                                                    <td>{{ $cliente->municipio->nome ?? '-------------' }}</td>
                                                    <td>{{ $cliente->distrito->nome ?? '-------------' }}</td>
                                                </tr>
                                                {{-- -------------------------------------------- --}}
                                                <tr>
                                                    <th colspan="4">Contactos</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"> {{ __('messages.telemovel') }} </td>
                                                    <td colspan="2"> {{ __('messages.telemovel') }} </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">{{ $cliente->telefone ?? '-------------' }}</td>
                                                    <td colspan="2">{{ $cliente->telemovel ?? '-------------' }}</td>
                                                </tr>
                                                {{-- -------------------------------------------- --}}
                                                <tr>
                                                    <th colspan="4">Contactos</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"> {{ __('messages.data_nascimento') }}</td>
                                                    <td colspan="2">Website</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">{{ $cliente->email ?? '-------------' }}</td>
                                                    <td colspan="2">{{ $cliente->website ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th colspan="4">{{ __('messages.observacao') }}</th>
                                                </tr>

                                                <tr>
                                                    <td colspan="4">{{ $cliente->observacao ?? '-------------' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-8">
                    @if ($empresa_logada->empresa->tipo_entidade->sigla != 'HOSP')
                    <div class="card shadow-sm border-left-primary" style="border-radius: 10px; background: #f8f9fa;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted"> {{ __('messages.clientes') }} </small>
                                    <h5 class="mb-1">{{ $cliente->nome }}</h5>
                                    <small class="text-muted">Validade: {{ \Carbon\Carbon::parse($cliente->cartao->validade_cartao)->format('d/m/Y') }}</small>
                                </div>
                                <div>
                                    <button id="toggle-saldo" class="btn btn-outline-secondary" title="Mostrar/ocultar saldo">
                                        <i class="fas fa-eye" id="icon-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3">
                                <small class="text-muted">Saldo disponível</small><br>
                                <span id="saldo-real" class="h5 text-light-success font-weight-bold">{{ number_format($cliente->cartao->saldo, 2, ',', '.') }}</span>
                                <span id="saldo-oculto" class="h5 font-weight-bold" style="display: none;">••••••</span>
                            </div>

                            <div class="mt-3 d-flex justify-content-between">
                                <a href="#" class="btn btn-light-danger" data-toggle="modal" data-target="#modalCreditar">Usar Saldo</a>
                                <a href="#" class="btn btn-light-success" data-toggle="modal" data-target="#modalDebitar">Recarregar</a>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <a href="{{ route('clientes-contratos.create') }}" class="btn btn-light-primary"><i class="fas fa-list"></i> Novos Contratos</a>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Contrato</th>
                                                <th>Estado</th>
                                                <th>Data Início</th>
                                                <th>Data Final</th>
                                                <th>Cliente</th>
                                                <th class="text-right">{{ __('messages.accoes') }} </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cliente->contratos as $item)
                                            <tr>
                                                <td><a href="{{ route('clientes-contratos.show', $item->id) }}">{{ $item->codigo_contrato }}</a></td>
                                                <td>{{ $item->status }}</td>
                                                <td>{{ $item->data_inicio }}</td>
                                                <td>{{ $item->data_final }}</td>
                                                <td><a href="{{ route('clientes.show', $item->cliente_id) }}">{{ $item->cliente->conta }} - {{ $item->cliente->nome }}</a></td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                        <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu">
                                                            @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                                            <a class="dropdown-item" href="{{ route('clientes-contratos.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                            @endif
                                                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar cliente'))
                                                            <a class="dropdown-item" href="{{ route('clientes-contratos.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                            @endif
                                                            <div class="dropdown-divider"></div>
                                                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar cliente'))
                                                            <button class="btn btn-light-danger dropdown-item delete-record-contrato" data-id="{{ $item->id ?? "" }}">
                                                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <a href="{{ route('clientes-movimentos-conta', $cliente->id) }}" class="btn btn-light-primary"><i class="fas fa-list"></i> Movimentos da conta Corrente</a>
                                    <a href="{{ route('clientes-liquidar-factura', $cliente->id) }}" class="btn btn-light-primary"><i class="fas fa-file-invoice dollar-sign" title="Liquidar Fatura"></i> Liquidar Facturas</a>
                                    {{-- @if ($empresa_logada->empresa->tipo_entidade->sigla != 'CFOR' && $empresa_logada->empresa->tipo_entidade->sigla != 'HOSP') --}}
                                    <a href="{{ route('clientes-actualizar-conta', $cliente->id) }}" class="btn btn-light-primary"><i class="fas fa-file"></i> Cartão consumo</a>
                                    {{-- @endif --}}
                                    <a href="{{ route('clientes-extrato-conta', $cliente->id) }}" class="btn btn-light-primary"><i class="fas fa-file"></i> Extrato</a>

                                    @if ($empresa_logada->empresa->tipo_entidade->sigla != 'CFOR' && $empresa_logada->empresa->tipo_entidade->sigla != 'HOSP')
                                    <a class="btn btn-light-primary" href="{{ route('compras.clientes', $cliente->id) }}">Todas compras do
                                        @if ($empresa_logada->empresa->tem_perfil('Gestão Hotelaria'))
                                        Hospode
                                        @else
                                        Clientes
                                        @endif
                                    </a>
                                    @endif

                                    @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFOR')
                                    <a href="{{ route('ficha-cliente', $cliente->id) }}" target="blink" class="btn btn-light-danger float-right ml-1">
                                        <i class="fas fa-file-pdf"></i>Ficha do Aluno
                                    </a>
                                    <a href="{{ route('alunos-matriculas-create', $cliente->id) }}" class="btn btn-light-primary float-right ml-1">Nova Matrícula</a>
                                    <a href="{{ route('turma-adicionar-aluno', $cliente->id) }}" class="btn btn-light-primary float-right">Adicionar a Turma</a>
                                    @endif

                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon  bg-light-primary"><i class=" far fa-user"></i></span>
                                                <div class="info-box-content">
                                                    <h4 class="info-box-text">Conta Corrente</h4>
                                                    <h1 class="info-box-number"></h1>
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon  bg-light-primary"><i class=" far fa-envelope"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Saldo Total</span>
                                                    <h5 class="info-box-number">
                                                        {{ number_format($facturasVencidasCorrente + $facturasVencidas, 2, ',', '.') }}
                                                        {{ $empresa_logada->empresa->moeda }}</h5>
                                                    <span class="info-box-text">----------------</span>
                                                </div>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>

                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-light-success"><i class="far fa-flag"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Dívida Corrente</span>
                                                    <h5 class="info-box-number">
                                                        {{ number_format($facturasVencidasCorrente, 2, ',', '.') }}
                                                        {{ $empresa_logada->empresa->moeda }}</h5>
                                                    @if ($facturasVencidasCorrente > 0)
                                                    <span class="info-box-text text-light-success">Existem pagamentos
                                                        pendentes</span>
                                                    @else
                                                    <span class="info-box-text">Não existem pagamentos pendentes</span>
                                                    @endif
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>

                                        <!-- /.col -->
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-light-warning"><i class="far fa-copy"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Dívida Vencida</span>
                                                    <h5 class="info-box-number">
                                                        {{ number_format($facturasVencidas, 2, ',', '.') }}
                                                        {{ $empresa_logada->empresa->moeda }}</h5>
                                                    @if ($facturasVencidas > 0)
                                                    <span class="info-box-text text-light-success">Existem pagamentos fora do
                                                        prazo</span>
                                                    @else
                                                    <span class="info-box-text">Não existem pagamentos fora do prazo</span>
                                                    @endif
                                                </div>
                                                <!-- /.info-box-content -->
                                            </div>
                                            <!-- /.info-box -->
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($empresa_logada->empresa->tipo_entidade->sigla != 'CFOR' && $empresa_logada->empresa->tipo_entidade->sigla != 'HOSP')
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-4 text-center">
                                            <h1><i class="fas fa-shopping-cart"></i></h1>
                                            <h2 class="h4">Compras</h2>
                                        </div>

                                        <div class="col-12 col-md-4 text-right">
                                            <h6>{{ __('messages.total') }}</h6>
                                            <h2 class="h4">{{ number_format($valorTotalCompras, 2, ',', '.') }} <span class="text-light-secondary"> {{ $empresa_logada->empresa->moeda }} </span>
                                            </h2>
                                        </div>

                                        <div class="col-12 col-md-4 text-right">
                                            <h6>Total últimos 30 dias</h6>
                                            <h2 class="h4">{{ number_format($valorTotalCompras, 2, ',', '.') }} <span class="text-light-secondary"> {{ $empresa_logada->empresa->moeda }} </span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFOR')
                        <div class="col-12 col-md-12">
                            @foreach ($matriculas as $item)
                            <div class="card">
                                <div class="card-header">
                                    <h6>
                                        <strong>Matrícula: {{ $item->numero }}</strong>
                                        @if (Auth::user()->can('editar todos') || Auth::user()->can('editar cliente'))
                                        @if ($item->status == 'DESACTIVO')
                                        <a href="#" data-id="{{ $item->id ?? "" }}" class="btn btn-light-success delete-record float-right"> {{ __('messages.activo') }}
                                            < Matrícula</a>
                                                @endif
                                                @if ($item->status == 'ACTIVO')
                                                <a href="#" data-id="{{ $item->id ?? "" }}" class="btn btn-light-danger delete-record float-right"> {{ __('messages.desactivo') }}
                                                    < Matrícula</a>
                                                        @endif
                                                        @endif
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6 table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Curso</th>
                                                        <td class="text-right">{{ $item->curso->nome ?? '-------------' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Sala</th>
                                                        <td class="text-right">{{ $item->sala->nome ?? '-------------' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Operador</th>
                                                        <td class="text-right">
                                                            {{ $item->user->name ?? ('' ?? '-------------') }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-6 table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>

                                                    <tr>
                                                        <th>{{ __('messages.estados') }}</th>
                                                        <td class="text-right">{{ $item->status ?? '-------------' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Turno</th>
                                                        <td class="text-right">{{ $item->turno->nome ?? '-------------' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Ano Lectivo</th>
                                                        <td class="text-right">
                                                            {{ $item->ano_lectivo->nome ?? '-------------' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')

                        @if ($cliente->parent_id && $cliente->parent_id !== null)
                        <div class="col-12 col-md-12">
                            <h5 class="py-3">DADOS DA EMPRESA OU PARENTE</h5>
                            <div class="card">
                                <div class="card-body table-responsive">
                                    <table class="table text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Genero</th>
                                                <th>Documento</th>
                                                <th>Idade</th>
                                                <th>Telefone</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="{{ route('clientes.show', $cliente->parent->id) }}">{{ $cliente->parent->nome }}</a></td>
                                                <td>{{ $cliente->parent->genero }}</td>
                                                <td>{{ $cliente->parent->nif }}</td>
                                                <td>{{ $cliente->parent->idade($cliente->parent->data_nascimento) }} Anos</td>
                                                <td>{{ $cliente->parent->telefone ?? '000-000-000' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if ($cliente->filhos && count($cliente->filhos) !== 0)
                        <div class="col-12 col-md-12">
                            <h5 class="py-3">DADOS DOS PARCEIROS OU FILHOS</h5>
                            <div class="card">
                                <div class="card-body table-responsive">
                                    <table class="table text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nº</th>
                                                <th>Nome</th>
                                                <th>Genero</th>
                                                <th>Documento</th>
                                                <th>Idade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cliente->filhos as $key => $filho)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td><a href="{{ route('clientes.show', $filho->id) }}">{{ $filho->nome }}</a></td>
                                                <td>{{ $filho->genero }}</td>
                                                <td>{{ $filho->nif }}</td>
                                                <td>{{ $filho->idade($filho->data_nascimento) }} Anos</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-12 col-md-12">
                            <h5 class="py-3">DADOS CLINICOS</h5>
                        </div>
                        @foreach ($cliente->atendimentos as $atendimento)
                        <div class="col-12 col-md-12">
                            <div class="card card-primary card-outline card-tabs">
                                @if (Auth::user()->can('listar atendimento'))
                                <div class="card-body">
                                    <h5>IDEFICADOR <a href="{{ route('atendimentos.show', $atendimento->id)  }}">Nº: {{ $atendimento->id }}</a></h5>
                                    <h5>ATENDIMENTO Nº: {{ $atendimento->numero }}</h5>
                                    <h5 class="text-uppercase">{{ __('messages.estados') }}: {{ $atendimento->status }}</h5>
                                    <h5>PRIORIDADE DO ATENDIMENTO: {{ $atendimento->prioridade->nome  }} {{ $atendimento->prioridade->tipo_cor($atendimento->prioridade->cor)  }}</h5>
                                </div>
                                @endif
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                        @if (Auth::user()->can('listar internamento'))
                                        <li class="nav-item">
                                            <a class="nav-link active" id="dados-consulta-tab" data-toggle="pill" href="#dados-consulta" role="tab" aria-controls="dados-consulta" aria-selected="true">DADOS DA INTERNAMENTO</a>
                                        </li>
                                        @endif

                                        @if (Auth::user()->can('listar triagem'))
                                        <li class="nav-item">
                                            <a class="nav-link" id="dados-triagem-tab" data-toggle="pill" href="#dados-triagem" role="tab" aria-controls="dados-triagem" aria-selected="false">DADOS DA TRIAGEM</a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->can('listar internamento'))
                                        <li class="nav-item">
                                            <a class="nav-link" id="lista-consultas-tab" data-toggle="pill" href="#lista-consultas" role="tab" aria-controls="lista-consultas" aria-selected="false">CONSULTAS SOLICITADAS</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="lista-exames-tab" data-toggle="pill" href="#lista-exames" role="tab" aria-controls="lista-exames" aria-selected="false">LISTA DOS EXAMES SOLICITADOS</a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->can('listar evolucao medica'))
                                        <li class="nav-item">
                                            <a class="nav-link" id="lista-evolucao-medica-tab" data-toggle="pill" href="#lista-evolucao-medica" role="tab" aria-controls="lista-evolucao-medica" aria-selected="false">EVOLUÇÃO MÉDICA</a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->can('listar receita medica'))
                                        <li class="nav-item">
                                            <a class="nav-link" id="lista-receitas-tab" data-toggle="pill" href="#lista-receitas" role="tab" aria-controls="lista-receitas" aria-selected="false">RECEITA MÉDICA</a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-three-tabContent">
                                        @if (Auth::user()->can('listar internamento'))
                                        <div class="tab-pane fade show active" id="dados-consulta" role="tabpanel" aria-labelledby="dados-consulta-tab">
                                            <div class="row">
                                                @if ($atendimento->internamento)
                                                <div class="col-12 col-md-6 table-responsive">
                                                    <table class="table text-nowrap">
                                                        <tbody>
                                                            <tr>
                                                                <th>Internamento Nº</th>
                                                                <td class="text-right">{{ $atendimento->internamento->numero ?? ""  }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th> {{ __('messages.data') }} </th>
                                                                <td class="text-right">{{ $atendimento->internamento->data_internacao ?? ""  }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Data Alta</th>
                                                                <td class="text-right">{{ $atendimento->internamento->data_alta ?? ""  }}</td>
                                                            </tr>

                                                            @if ($atendimento->internamento->status ?? "" == 'activo')
                                                            <tr>
                                                                <th>{{ __('messages.estados') }}</th>
                                                                <td class="text-right">{{ $atendimento->internamento->status ?? ""  }}</td>
                                                            </tr>
                                                            @endif

                                                            @if($atendimento->internamento)
                                                            @if ($atendimento->internamento->status == 'alta')
                                                            <tr>
                                                                <th>{{ __('messages.estados') }}</th>
                                                                <td class="text-right">{{ $atendimento->internamento->status ?? ""  }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Resumo da Alta</th>
                                                                <td class="text-right">{{ $atendimento->internamento->resumo_alta  ?? "" }}</td>
                                                            </tr>
                                                            @endif

                                                            @if ($atendimento->internamento->status == 'obito')
                                                            <tr>
                                                                <th>{{ __('messages.estados') }}</th>
                                                                <td class="text-right">{{ $atendimento->internamento->status  ?? "" }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Resumo do Obito</th>
                                                                <td class="text-right">{{ $atendimento->internamento->resumo_obito  ?? "" }}
                                                                </td>
                                                            </tr>
                                                            @endif

                                                            @if ($atendimento->internamento->status == 'transferido')
                                                            <tr>
                                                                <th>{{ __('messages.estados') }}</th>
                                                                <td class="text-right">{{ $atendimento->internamento->status ?? ""  }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Resumo da Transferência</th>
                                                                <td class="text-right">
                                                                    {{ $atendimento->internamento->resumo_transferencia ?? ""  }}
                                                                </td>
                                                            </tr>
                                                            @endif

                                                            @endif


                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="col-12 col-md-6 table-responsive">
                                                    <table class=" table text-nowrap">
                                                        <tbody>
                                                            <tr>
                                                                <th>Equipa Médica</th>
                                                                <td class="text-right">{{ $atendimento->internamento->equipa->nome ?? ""  }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Leito</th>
                                                                <td class="text-right">{{ $atendimento->internamento->leito->nome ?? ""  }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Motivo</th>
                                                                <td class="text-right">{{ $atendimento->internamento->motivo  ?? "" }}</td>
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

                                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                                <div class="col-12 col-md-12 text-center my-5">
                                                    <a class="btn btn-lg btn-light-primary" href="{{ route('internamentos.imprimir', $atendimento->internamento->id) }}" target="_blank">
                                                        <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}
                                                    </a>
                                                </div>
                                                @endif

                                                @else
                                                <div class="col-12 col-md-12 text-center my-5">
                                                    <h3>SEM INTERNAMENTO</h3>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        @if (Auth::user()->can('listar triagem'))
                                        <div class="tab-pane fade" id="dados-triagem" role="tabpanel" aria-labelledby="dados-triagem-tab">
                                            <div class="row">

                                                @if ($atendimento->triagem)
                                                @include('dashboard.atendimentos._views.detalhe-triagem', ['triagem' => $atendimento->triagem])
                                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                                <div class="col-12 col-md-12 text-center">
                                                    <a target="_blank" href="{{ route('triangs.triagens-imprimir', $atendimento->triagem->id) }}" class="h3 py-3 my-5 btn btn-light-primary"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}
                                                        Ficha da Triagem Médica
                                                    </a>
                                                </div>
                                                @endif
                                                @else
                                                <div class="col-12 col-md-12 text-center my-5">
                                                    <h3>SEM TRIAGEM</h3>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        @if (Auth::user()->can('listar internamento'))
                                        <div class="tab-pane fade" id="lista-consultas" role="tabpanel" aria-labelledby="lista-consultas-tab">
                                            <div class="row">
                                                @if (count($atendimento->consultas) !== 0)
                                                <div class="col-12 col-md-12 table-responsive">
                                                    <table class=" table text-nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="6">LISTA DAS CONSULTAS SOLICITADAS</th>
                                                            </tr>
                                                            <tr>
                                                                <th>#</th>
                                                                <th> {{ __('messages.designacao') }} </th>
                                                                <th>{{ __('messages.categoria') }}</th>
                                                                <th class="text-right">______________</th>
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
                                                    <h3>SEM CONSULTAS EXTRAS</h3>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="lista-exames" role="tabpanel" aria-labelledby="lista-exames-tab">
                                            @if (count($atendimento->exames) !== 0)
                                            @foreach ($atendimento->exames as $resultado)
                                            @include('dashboard.exames._views.detalhe-exame', ["dados" => $resultado, "editar" => false])
                                            @endforeach
                                            @else
                                            <div class="row">
                                                <div class="col-12 col-md-12 text-center my-5">
                                                    <h3>SEM EXAMES EXTRAS</h3>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endif

                                        @if (Auth::user()->can('listar evolucao medica'))
                                        <div class="tab-pane fade" id="lista-evolucao-medica" role="tabpanel" aria-labelledby="lista-evolucao-medica-tab">
                                            <div class="row">
                                                @if ($atendimento->internamento)
                                                <div class="col-12 col-md-12 table-responsive">
                                                    <table class=" table text-nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="10">EVOLUÇÃO MÉDICA</th>
                                                            </tr>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Tipo</th>
                                                                <th>Data Evolução</th>
                                                                <th>Data E Hora</th>
                                                                <th>{{ __('messages.observacao') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($atendimento->internamento->evolucao_medica as $item)
                                                            <tr>
                                                                <td>{{ $item->id ?? "" }}</td>
                                                                <td>{{ $item->tipo ?? 'sem registro' }}</td>
                                                                <td>{{ $item->data_evolucao ?? 'sem registro' }}</td>
                                                                <td>{{ $item->created_at ?? 'sem registro' }}</td>
                                                                <td>{{ $item->observacoes ?? 'sem registro' }}</td>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                                <div class="col-12 col-md-12 text-center">
                                                    <a target="_blank" href="{{ route('internamentos.imprimir-evolucao-medica', $atendimento->internamento->id) }}" class=" h3 py-3 my-5 btn btn-light-primary"> <i class="fas fa-file-pdf"></i>
                                                        {{ __('messages.imprimir') }}</a>
                                                </div>
                                                @endif
                                                @else
                                                <div class="col-12 col-md-12 text-center my-5">
                                                    <h3>SEM INTERNAMENTO E SEM EVOLUÇÃO MÉDICA</h3>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        @if (Auth::user()->can('listar receita medica'))
                                        <div class="tab-pane fade" id="lista-receitas" role="tabpanel" aria-labelledby="lista-receitas-tab">
                                            <div class="row">
                                                @foreach ($atendimento->receitas as $receit)
                                                <div class="col-12 col-md-6  table-responsive">
                                                    <table class=" table text-nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="5"><a target="_blank" href="{{ route('consulta-receitas-medico-imprimir', $receit->id) }}">RECEITA Nº {{ $receit->id }}</a></th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="5">Observação {{ $receit->observacoes }}
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Medicamento</th>
                                                                <th>Posologia</th>
                                                                <th>Duracao dias</th>
                                                                <th>{{ __('messages.observacao') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($receit->items as $item)
                                                            <tr>
                                                                <td>#</td>
                                                                <td>{{ $item->medicamento ?? 'sem registro' }}</td>
                                                                <td>{{ $item->posologia ?? 'sem registro' }}</td>
                                                                <td>{{ $item->duracao_dias ?? 'sem registro' }}</td>
                                                                <td>{{ $item->observacoes ?? 'sem registro' }}</td>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <div class="col-12 col-md-12 text-left">
                                                        <a target="_blank" href="{{ route('consulta-receitas-medico-imprimir', $receit->id) }}" class="h3 my-5 btn btn-light-primary"><i class="fas fa-file-pdf"></i>
                                                            {{ __('messages.imprimir') }}</a>
                                                    </div>
                                                </div>
                                                @endforeach
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
                        @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Modal Creditar -->
    <div class="modal fade" id="modalCreditar" tabindex="-1">
        <div class="modal-dialog">
            <form id="formCreditar" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Creditar Saldo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Valor (Kz):</label>
                    <input type="number" name="valor" class="form-control" step="0.01" required>
                    <input type="hidden" name="creditar_cliente_id" id="creditar_cliente_id" class="creditar_cliente_id" value="{{ $cliente->id }}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-light-danger">{{ __('messages.salvar') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Debitar -->
    <div class="modal fade" id="modalDebitar" tabindex="-1">
        <div class="modal-dialog">
            <form id="formDebitar" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Debitar Saldo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Valor (Kz):</label>
                    <input type="number" name="valor" class="form-control" step="0.01" required>
                    <input type="hidden" name="debitar_cliente_id" id="debitar_cliente_id" class="debitar_cliente_id" value="{{ $cliente->id }}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-light-success">{{ __('messages.salvar') }}</button>
                </div>
            </form>
        </div>
    </div>


    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    // Ajax: Creditar
    $('#formCreditar').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('clientes.cartao-creditar') }}"
            , method: "POST"
            , data: $(this).serialize()
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {
                if (response.success) {
                    $('#modalCreditar').modal('hide');
                    $('#saldo-real').text('Kz ' + response.novo_saldo);

                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Saldo creditado com sucesso!', 'success');
                    // alert('Saldo creditado com sucesso!');
                    // window.location.reload();
                }
            }
            , error: function(xhr) {
                // Feche o alerta de carregamento
                Swal.close();

                // Trata erros e exibe mensagens para o usuário
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let messages = '';
                    $.each(errors, function(key, value) {
                        messages += `${value}\n *`; // Exibe os erros
                    });

                    showMessage('Erro de Validação!', messages, 'error');

                } else {

                    showMessage('Erro!', xhr.responseJSON.message, 'error');

                }
            }
        });
    });


    // Ajax: Debitar
    $('#formDebitar').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('clientes.cartao-debitar') }}"
            , method: "POST"
            , data: $(this).serialize()
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {
                if (response.success) {
                    $('#modalDebitar').modal('hide');
                    $('#saldo-real').text('Kz ' + response.novo_saldo);

                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Saldo debitado com sucesso!', 'success');
                    // window.location.reload();

                } else {
                    alert(response.message || 'Erro ao debitar.');
                }
            }
            , error: function(xhr) {
                // Feche o alerta de carregamento
                Swal.close();

                // Trata erros e exibe mensagens para o usuário
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let messages = '';
                    $.each(errors, function(key, value) {
                        messages += `${value}\n *`; // Exibe os erros
                    });

                    showMessage('Erro de Validação!', messages, 'error');

                } else {

                    showMessage('Erro!', xhr.responseJSON.message, 'error');

                }
            }
        });
    });

    $('#toggle-saldo').on('click', function() {
        $('#saldo-real, #saldo-oculto').toggle();
        $('#icon-eye').toggleClass('fa-eye fa-eye-slash');
    });


    $(document).on('click', '.delete-record-contrato', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, excluir!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('clientes-contratos.destroy', ':id') }}`.replace(':id', recordId)
                    , method: 'DELETE'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!'
                            , 'Ocorreu um erro ao excluir o registro. Tente novamente.'
                            , 'error');
                    }
                , });
            }
        });
    });


    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, actualizar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('alunos-matriculas-status', ':id') }}`.replace(':id'
                        , recordId)
                    , method: 'GET'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
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
