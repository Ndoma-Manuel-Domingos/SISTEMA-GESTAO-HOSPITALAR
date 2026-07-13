@extends('layouts.vendas')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="row mb-2 bg-light-dark  align-items-center">
            <div class="col-md-2 col-12 pt-2">
                <button class="btn-app bg-light-dark  text-center d-block " onclick="document.getElementById('botoes-caixa').classList.toggle('d-none')">
                    {{ __('messages.opcoes') }}
                </button>
            </div>
            <div class="col-12 col-md-10">
                <div id="botoes-caixa" class="content-header row d-none">
                    <div class="col-lg-2 col-md-3 col-12">
                        <a href="{{ route('dashboard-principal') }}" class="btn-app bg-light-secondary text-center d-block"><i class="fas fa-home"></i> {{ __('messages.controle') }}</a>
                    </div>
                    @if (!empty($checkCaixa))
                    <div class="col-lg-2 col-md-3 col-12">
                        <a href="#" class="btn-app bg-light-secondary text-center d-block" onclick="toggleModalEntradaValores()"><i class="fas fa-arrow-down"></i>Entrada de valores no caixa</a>
                    </div>

                    <div class="col-lg-2 col-md-3 col-12">
                        <a href="#" class="btn-app bg-light-secondary text-center d-block" onclick="toggleModalSaidaValores()"><i class="fas fa-arrow-up"></i>Saída de Valores no caixa</a>
                    </div>

                    <div class="col-lg-2 col-md-3 col-12">
                        <a href="#" onclick="toggleModalListagemVendas()" class="btn-app bg-light-secondary text-center d-block"><i class="fas fa-shopping-basket"></i>{{ __('messages.listagem') }} {{ __('messages.venda') }}</a>
                    </div>
                    @endif
                    <div class="col-lg-2 col-md-3 col-12">
                        <a href="#" class="btn-app bg-light-danger text-center d-block finiched-session-application"><i class="fas fa-sign-out-alt"></i> {{ __('messages.terminar_sessao') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col-md-6 -->
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="mb-4">
                        <div class="row">
                            <form action="" method="post" class="col-12 col-md-5">
                                @csrf
                                <div class="col-12 col-md-12">
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <div class="input-group">
                                                <input type="text" name="produto_codigo_barra" autofocus id="produto_codigo_barra_original" class="form-control form-control-lg produto_codigo_barra" placeholder="{{ __('messages.codigo_barras') }}">
                                                <div class="input-group-append"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <form class="col-12 col-md-5">
                                <div class="col-12 col-md-12">
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <div class="input-group">
                                                <input type="search" name="produto" id="produto" class="form-control form-control-lg produto" placeholder="{{ __('messages.filtrar') }}...">
                                                <div class="input-group-append">
                                                    <button type="submit" id="pesquisar_produto" class="btn btn-lg btn-default pesquisar_produto">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="col-12 col-md-4 col-lg-2">
                                <button class="btn-lg btn-light-primary" type="button" data-toggle="modal" data-target="#myModal">{{ __('messages.quantidade') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if (empty($checkCaixa))
                        <div class="col-12 col-md-12">
                            <div class="card p-5 bg-light-dark ">
                                <div class="card-body p-5 text-center">
                                    <h1 class="h3 p-5">{{ __('messages.para_efectuar_operacoes_caixa') }}</h1>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-12 col-md-12">
                            <div class="card card-dark card-outline card-outline-tabs bg-light-dark ">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active text-uppercase text-white" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Geral</a>
                                        </li>
                                        @if ($categorias)
                                        @foreach ($categorias as $categoria)
                                        <li class="nav-item">
                                            <a class="nav-link text-uppercase text-white" id="custom-tabs-four-profile-tab{{ $categoria->id }}" data-toggle="pill" href="#custom-tabs-four-profile{{ $categoria->id }}" role="tab" aria-controls="custom-tabs-four-profile{{ $categoria->id }}" aria-selected="false">{{ $categoria->categoria }}
                                            </a>
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div style="height: 400px;overflow: hidden; overflow-y: scroll">
                                        <div class="tab-content" id="custom-tabs-four-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                                <div class="row" id="carregar_produtos">
                                                    @foreach ($produtos as $item)
                                                    <div class="col-6 col-md-3 col-lg-2">
                                                        <a class="adicionar-carrinho" style="cursor: pointer" data-stock="{{ $item->total_produto_loja_activa() }}" data-id="{{ $item->id ?? "" }}" data-tipo="{{ $item->tipo }}" data-nome="{{ $item->nome }}" data-preco="{{ $item->preco_venda_com_iva }}">
                                                            <div class="card shadow-sm bg-light">
                                                                <!-- /.card-header -->
                                                                <div class="card-body {{ $item->total_produto_loja_activa() <= 0
                                                                    ? 'bg-light-danger'
                                                                    : ($item->total_produto_loja_activa() <= $item->total_produto_minimo_loja_activa() &&
                                                                    $item->total_produto_minimo_loja_activa() > 0
                                                                        ? 'bg-light-warning'
                                                                        : ($item->total_produto_loja_activa() > $item->total_produto_minimo_loja_activa()
                                                                        ? 'bg-light-primary'
                                                                        : '')) }} ">

                                                                    <div class="col-12 col-md-12 col-sm-12">
                                                                        <h6 class="text-uppercase text-white">
                                                                            {{ $item->nome }}</h6>
                                                                        <p class=" text-light-dark">
                                                                            <strong>{{ number_format($item->preco_venda_com_iva, 2, ',', '.') }}
                                                                                <small>Kz</small></strong>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <!-- /.card-body -->
                                                            </div>
                                                        </a>
                                                        <!-- /.card -->
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <!-- /.row -->
                                            </div>

                                            @if ($categorias)
                                            @foreach ($categorias as $categoria)
                                            <div class="tab-pane fade" id="custom-tabs-four-profile{{ $categoria->id }}" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab{{ $categoria->id }}">
                                                <div class="row">
                                                    @foreach ($categoria->produtos as $produto)
                                                    @if ($produto->categoria_id == $categoria->id)
                                                    <div class="col-6 col-md-3 col-lg-2">
                                                        <a class="adicionar-carrinho" style="cursor: pointer" data-stock="{{ $produto->total_produto_loja_activa() }}" data-id="{{ $produto->id }}" data-tipo="{{ $produto->tipo }}" data-nome="{{ $produto->nome }}" data-preco="{{ $produto->preco_venda_com_iva }}">
                                                            <div class="card shadow-sm bg-light">
                                                                <!-- /.card-header -->
                                                                <div class="card-body {{ $produto->total_produto_loja_activa() > $produto->total_produto_minimo_loja_activa() ? 'bg-light-primary' : ($produto->total_produto_loja_activa() <= $produto->total_produto_minimo_loja_activa() && $produto->total_produto_minimo_loja_activa() > 0 ? 'bg-light-warning' : ($produto->total_produto_loja_activa() <= 0 ? 'bg-light-danger' : '')) }} ">
                                                                    <div class="col-12 col-md-12 col-sm-12">
                                                                        <h6 class="pt-3 text-uppercase text-white">
                                                                            {{ $produto->nome }}
                                                                        </h6>
                                                                        <p class="text-light-dark">
                                                                            <strong>{{ number_format($produto->preco_venda_com_iva, 2, ',', '.') }}
                                                                                <small>Kz</small>
                                                                            </strong>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <!-- /.card-body -->
                                                            </div>
                                                        </a>
                                                        <!-- /.card -->
                                                    </div>
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- /.col-md-6 -->
                <div class="col-12 col-md-4 col-lg-3">
                    @if (empty($checkCaixa))
                    <div class="card text-center">
                        <div class="card-header">
                            <img src="{{ asset('dist/img/user.png') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle" style="width: 120px;height: 120px">
                            <h4 class="pt-2">{{ __('messages.fechar_caixa') }}</h4>
                            <p class="text-light-secondary">{{ __('messages.para_efectuar_operacoes_caixa') }}</p>
                        </div>
                        <div class="card-body text-center">
                            <h6 class="text-center"><strong>{{ __('messages.valor') }}</strong></h6>
                            <p class="text-light-secondary">{{ __('messages.introduza_montante_disponivel')}}</p>

                            <form action="{{ route('caixa.abertura_caixa_create') }}" id="abertura_caixa_create_form" method="post" class="px-5">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-12 text-center">
                                        <label for="">{{ __('messages.montante_disponivel_abrir_tpa') }}</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="valor" value="0" placeholder="{{ __('messages.valor') }}">
                                        </div>
                                        @error('valor')
                                        <span>{{ $message }}</span><br>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 text-center">
                                        <label for="">{{ __('messages.escolha_aqui_o_caixa') }}</label>
                                        <div class="input-group text-left">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-cash-register"></i></span>
                                            </div>
                                            <select name="caixa_id" id="" class="form-control @error('caixa_id') is-invalid @enderror">
                                                @foreach ($caixas as $item)
                                                <option value="{{ $item->id ?? "" }}" {{ old('caixa_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->conta }} - {{ $item->nome }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-md mt-4 d-iniline-block btn-light-primary"><i class="fas fa-box"></i> {{ __('messages.activar_caixa') }}</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="card" id="carrinho-itens">
                        <div class="card-header  bg-light-primary">
                            <div class=" row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <p>{{ __('messages.total') }}</p>
                                </div>
                                <div class="col-12 col-md-8 text-right">
                                    <p class="h3" id="total-carrinho">0,00 AKZ</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive mt-3" style="height: 400px;">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th> {{ __('messages.quantidade') }} </th>
                                        <th class="text-right">{{ __('messages.preco') }}</th>
                                        <th style="width: 5px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Os itens do carrinho serão inseridos aqui via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button class="btn-lg btn-light-primary" type="button" data-toggle="modal" id="botao_definir_preco_personalizado" data-target="#myModalPrecoPersonalizado"><i class="fas fa-plus"></i> PREÇO PERSONALIZADO</button>
                            <button class="btn-lg btn-light-danger" type="button" data-toggle="modal" id="botao_remover_preco_personalizado" style="display: none" onclick="removerPrecoPersonalizado()"><i class="fas fa-times"></i> PREÇO PERSONALIZADO</button>

                            <button class="btn-lg btn-light-primary" type="button" data-toggle="modal" id="botao_definir_preco_aplicar_desconto" data-target="#myModalAplicarDesconto"><i class="fas fa-tag"></i> DESCONTO</button>
                            <button class="btn-lg btn-light-danger" type="button" data-toggle="modal" id="botao_remover_preco_aplicar_desconto" style="display: none" onclick="removerPrecoDescontoAplicado()"><i class="fas fa-minus-circle"></i> DESCONTO</button>

                            <span id="precoPersonalizadoTexto" class="float-right h4"></span>
                            <span id="precoDescontoAplicadoTexto" class="float-right h4"></span>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- FORMULARIO FINALIZACAO DE VENDA finalizar-venda-create --}}
                @if (!empty($checkCaixa))
                <div class="col-md-12 col-12">
                    <form action="{{ route('carrinho.pagamento') }}" method="post" id="quickForm">
                        @csrf
                        <div class="card  bg-light-primary">
                            <div class=" card-body row" style="height: auto;">
                                <div class="col-12 col-md-1">
                                    <a href="#" data-id="{{ $checkCaixa->id }}" class="btn btn-light-danger col-12 col-md-12 px-2 py-4 text-center logout_caixa" role="button" data-slide="true" data-widget="control-sidebar" title="SAIR DAS VENDAS">
                                        <span class="h1 text-uppercase">
                                            <i class="fas fa-power-off"></i>
                                        </span><br>
                                        <span class="h6 text-white text-uppercase">
                                            Sair
                                        </span>
                                    </a>
                                </div>

                                <div class="col-12 col-md-8">
                                    <div action="" class="row">
                                        <div class="col-md-12 col-12 mb-4" style="display: none;">
                                            <div class="input-group input-group-lg">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        Kz
                                                    </span>
                                                </div>
                                                <input type="text" name="" class="form-control form-control-lg valor_total_pagar_fixo" disabled value="{{ number_format($total_pagar, 2, ',', '.') }}">
                                                <input type="hidden" name="total_pagar" id="total_pagar" class="form-control form-control-lg total_pagar" value="{{ $total_pagar }}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fas fa-edit"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" id="venda_realizado" name="venda_realizado" value="CAIXA">

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="row">
                                                <div class="col-md-6 col-12 mb-4">
                                                    <input class="form-control form-control-lg" type="text" name="nome_cliente" id="nome_cliente" value="CONSUMIDOR FINAL">
                                                </div>

                                                <div class="col-md-6 col-12 mb-4">
                                                    <input class="form-control form-control-lg" type="text" name="documento_nif" id="documento_nif" value="999999999">
                                                </div>

                                                <div class="col-md-12 col-12 mb-4" style="display: none">
                                                    <div class="input-group input-group-lg">
                                                        <select name="cliente_id" id="cliente_id" class="form-control form-control-lg">
                                                            <option value=""> {{ __('messages.clientes') }} </option>
                                                            @if ($clientes)
                                                            @foreach ($clientes as $item)
                                                            <option value="{{ $item->id ?? "" }}" {{ $item->nome == 'CONSUMIDOR FINAL' ? 'selected' : '' }}>
                                                                {{ $item->nome }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-12 mb-4">
                                                    <div class="input-group input-group-lg">
                                                        <select name="pagamento" id="forma_de_pagamentos" class="form-control form-control-lg">
                                                            @foreach ($forma_pagmento as $forma)
                                                            <option value="{{ $forma->tipo }}" class="text-uppercase">
                                                                {{ $forma->titulo }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="row">
                                                <div class="col-12 col-md-12 mb-4">
                                                    <div class="input-group input-group-lg">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                Kz
                                                            </span>
                                                        </div>
                                                        <input type="text" name="valor_entregue_multicaixa" id="valor_entregue_multicaixa" class="form-control py-3 valor_entregue_multicaixa" disabled height="40" value="0">
                                                        <input type="hidden" name="valor_entregue_multicaixa_input" class="valor_entregue_multicaixa_input" id="valor_entregue_multicaixa_input" value="">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fas fa-credit-card"></i></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-12 mb-4">
                                                    <div class="input-group input-group-lg">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                Kz
                                                            </span>
                                                        </div>
                                                        <input type="text" name="valor_entregue" id="valor_entregue" class="form-control py-3 valor_entregue" height="40" value="{{ $total_pagar }}">
                                                        <input type="hidden" name="valor_entregue_input" class="valor_entregue_input" id="valor_entregue_input" value="">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fas fa-wallet"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="radio" style="display: none;" id="radioPrimary_super_factura_recibo" name="documento" value="FR" checked>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <p class="p-1 text-right">
                                                <span class="h5" id="valor_troco_apresenta">0</span>
                                                <small>{{ $loja->moeda ?? 'KZ' }}</small> <br>
                                                <span class="text-uppercase">Troco</span>
                                            </p>
                                        </div>

                                        <input type="hidden" name="desconto" value="" id="desconto" class="desconto">

                                        <div class="col-12 col-md-12">
                                            <div class="card">
                                                <button type="submit" class="btn btn-dark col-12 col-md-12 px-4 py-2 text-center float-right" id="finalizar-venda">
                                                    <span class="h3 text-white text-uppercase"><i class="fas fa-check"></i> Confirmar venda </span>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Estrutura do Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Cabeçalho do modal -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">{{ __('messages.novo') }} {{ __('messages.quantidade') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Corpo do modal -->
                            <div class="modal-body my-4">
                                <div class="row">
                                    <div class="col-12 col-md-12 mb-3">
                                        <label for="quantidade"> {{ __('messages.quantidade') }} </label>
                                        <input type="text" class="form-control quantidade" value="1" oninput="validateInput(this)" name="quantidade" id="quantidade" placeholder="{{ __('messages.quantidade') }} ...">
                                    </div>

                                    <div class="col-12 col-md-6 mb-3">
                                        <!-- Inputs para os números -->
                                        <label for="input1">Comprimento</label>
                                        <input type="text" class="form-control input1" value="1" id="input1" oninput="validateInput(this)">
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="input2">Altura</label>
                                        <input type="text" class="form-control input2" value="1" id="input2" oninput="validateInput(this)">
                                    </div>

                                    <input type="hidden" value="1" class="result" id="result">
                                </div>
                            </div>
                            <!-- Rodapé do modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                                <button type="button" class="btn btn-light-primary" onclick="calculateMultiplication()">{{ __('messages.salvar') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estrutura do Modal -->
                <div class="modal fade" id="myModalPrecoPersonalizado" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Cabeçalho do modal -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Definir Preço Personalizado</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Corpo do modal -->
                            <div class="modal-body my-4">
                                <div class="row">
                                    <div class="col-12 col-md-12 mb-3">
                                        <label for="precoPersonalizado">Definir Preço Personalizado</label>
                                        <input type="text" class="form-control precoPersonalizado" value="1" oninput="validateInput(this)" name="precoPersonalizado" id="precoPersonalizado" placeholder="Digite o Preço">
                                    </div>
                                </div>
                            </div>
                            <!-- Rodapé do modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                                <button type="button" class="btn btn-light-primary" onclick="salvarPrecoPersonalizado()">{{ __('messages.salvar') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estrutura do Modal -->
                <div class="modal fade" id="myModalAplicarDesconto" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Cabeçalho do modal -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Aplicar Desconto na venda</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Corpo do modal -->
                            <div class="modal-body my-4">
                                <div class="row">
                                    <div class="col-12 col-md-12 mb-3">
                                        <label for="precoDescontoAplicado">Aplicar Desconto na venda <span class="text-light-danger">(informe o valor em percentagem sem simbolo)</span></label>
                                        <input type="text" class="form-control precoDescontoAplicado" value="0" oninput="validateInput(this)" name="precoDescontoAplicado" id="precoDescontoAplicado" placeholder="Digite o Preço">
                                    </div>
                                </div>
                            </div>
                            <!-- Rodapé do modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                                <button type="button" class="btn btn-light-primary" onclick="salvarPrecoDescontoAplicado()">{{ __('messages.salvar') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    @if (!empty($checkCaixa))
    <div class="modal fade" id="modal-lg-listagem-vendas">
        <div class="modal-dialog modal-xxl  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Listagem das vendas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row py-4 mx-4">
                    @if ($operacoes)
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Referência</th>
                                    <th>{{ __('messages.estados') }}</th>
                                    @if ($empresa_logada->empresa->tem_perfil("Gestão Contabilidade"))
                                    <th>Subconta</th>
                                    @else
                                    <th>Caixa/Conta Bancária</th>
                                    @endif
                                    <th>{{ __('messages.despesa') }}/{{ __('messages.receita') }}</th>
                                    <th>{{ __('messages.fornecedores') }}/{{ __('messages.clientes') }}</th>
                                    <th class="text-right"> {{ __('messages.data') }} </th>
                                    <th class="text-right">Motante</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($operacoes as $item)
                                <tr>
                                    <td>{{ $item->nome ?? "" }}</td>
                                    <td>{{ $item->status ?? "" }}</td>
                                    <td>{{ $item->subconta->numero ?? "" }} - {{ $item->subconta->nome ?? "" }}</td>
                                    <td>{{ $item->type == "D" ? ($item->dispesa->nome  ?? "") : ($item->receita->nome ?? "") }}</td>
                                    <td>{{ $item->type == "D" ? (($item->fornecedor_id ?? "") ? ($item->fornecedor->nome ?? "") : ($item->user->name ?? "")) : ($item->cliente_id ? $item->cliente->nome : $item->user->name) }}</td>
                                    <td class="text-right">{{ $item->date_at }}</td>
                                    @if ($item->type == "D")
                                    <td class="text-right text-light-danger">- {{ number_format($item->motante, 2, ',', '.')  }}</td>
                                    @else
                                    <td class="text-right text-light-success">+ {{ number_format($item->motante, 2, ',', '.')  }}</td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="modal-footer justify-content-between">
                    <a class="btn btn-light-danger" target="_blank" href="{{ route('operacaoes-financeiras.exportar', ['data_inicio' => date("Y-m-d"), 'utilizador' => Auth::user()->id]) }}"><i class="fas fa-file-pdf"></i> IMPRIMIR PDF</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <form action="{{ route('caixas.operacaoes-financeiras.entrada-valores') }}" method="post" id="form_entrada_valores">
        @csrf
        <div class="modal fade" id="modal-lg-entrada-valores">
            <div class="modal-dialog modal-xl  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Fazer Entrada de Valores</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">

                        <div class="col-12 col-md-6">
                            <label for="motante_entrada" class="form-label">Motante</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control  @error('motante_entrada') is-invalid @enderror" name="motante_entrada" id="motante_entrada" value="0" placeholder="{{ __('messages.valor') }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="receita_id" class="form-label">{{ __('messages.receita') }}</label>
                            <div class="input-group mb-3">
                                <select style="width: 100%" class="form-control select2 @error('receita_id') is-invalid @enderror" id="receita_id" name="receita_id">
                                    @foreach ($receitas as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="caixa_entrada" value="{{ $checkCaixa->id }}">

                        <div class="col-12 col-md-6">
                            <label for="cliente_id" class="form-label"> {{ __('messages.clientes') }} </label>
                            <div class="input-group mb-3">
                                <select style="width: 100%" class="form-control select2 @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id">
                                    @foreach ($clientes as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="descricao_entrada" class="form-label">Descrição</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control  @error('descricao_entrada') is-invalid @enderror" name="descricao_entrada" id="descricao_entrada" value="Entrada de valores para: " placeholder="Informe o Descrição">
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        {{-- @endif --}}
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->

    <form action="{{ route('caixas.operacaoes-financeiras.saida-valores') }}" method="post" id="form_saida_valores">
        @csrf
        <div class="modal fade" id="modal-lg-saida-valores">
            <div class="modal-dialog modal-xl  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Fazer Saída de Valores</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">

                        <div class="col-12 col-md-6">
                            <label for="motante_saida" class="form-label">Motante</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control  @error('motante_saida') is-invalid @enderror" name="motante_saida" id="motante_saida" value="0" placeholder="{{ __('messages.valor') }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="dispesa_id" class="form-label">{{ __('messages.despesa') }}</label>
                            <div class="input-group mb-3">
                                <select style="width: 100%" class="form-control select2 @error('dispesa_id') is-invalid @enderror" id="dispesa_id" name="dispesa_id">
                                    @foreach ($dispesas as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="caixa_saida" value="{{ $checkCaixa->id }}">

                        <div class="col-12 col-md-6">
                            <label for="fornecedor_id" class="form-label">Fornecedores</label>
                            <div class="input-group mb-3">
                                <select style="width: 100%" class="form-control select2 @error('fornecedor_id') is-invalid @enderror" id="fornecedor_id" name="fornecedor_id">
                                    @foreach ($fornecedores as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome ?? "" }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="descricao_saida" class="form-label">Descrição</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control  @error('descricao_saida') is-invalid @enderror" name="descricao_saida" id="descricao_saida" value="Saída de valores para : " placeholder="Informe o Descrição">
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        {{-- @endif --}}
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->
    @endif

</div>
<!-- /.content-wrapper -->

@if ($empresa_logada->empresa->tipo_venda !== "Normal")
@if (!empty($checkCaixa))
@if (!session()->has('carta_consumo_venda_2022'))
<!-- Modal PIN (fullscreen) -->
<div class="modal fade" id="modalEscolherCartaoConsumo" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-light-dark  text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title w-100 text-center">🔒 Acesso Restrito - PIN do Cartão Consumidor</h5>
            </div>
            <div class="modal-body d-flex flex-column align-items-center justify-content-center">
                <p class="fs-4 mb-4">Digite o PIN do Cartão Consumidor para iniciar a venda</p>
                <input type="password" id="pinInput" class="form-control form-control-lg text-center" placeholder="****" maxlength="6" style="max-width: 300px;">
                <button class="btn btn-light-success mt-4 btn-lg" onclick="validarPin()">Confirmar PIN</button>
                <a href="{{ route("cartoes-consumos.index") }}" class="btn bbtn-light-primary mt-4 btn-lg">Ver Cartões</a>
            </div>
        </div>
    </div>
</div>
@endif
@endif
@endif

@endsection

@section('scripts')

<script>
    // setInterval(controleCaixa, 1000);

    // function controleCaixa()
    // {
    //     fetch('/api/verificar-caixa').then(response => response.json()).then(data => {
    //         if(!data.bloqueado)
    //         {
    //             window.location.href="/tela-bloqueada";
    //         }
    //     });
    // }

    $(document).ready(function() {
        $('#abertura_caixa_create_form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    showMessage('Sucesso!', 'Operação realizada com sucesso.!', 'success');

                    // window.location.href = response.redirect;

                    window.location.reload();
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });
        });
    });

    $(document).ready(function() {
        $('#quickForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {

                    Swal.close();

                    const baseUrl = `{{ route('factura-recibo-pos-venda') }}`;
                    const facturaId = response.data.factura.id; // Este valor pode vir dinamicamente do seu sistema

                    // Construir a URL completa
                    const url = `${baseUrl}?factura=${facturaId}`;

                    // Redirecionar
                    window.location.href = url;

                    // Abrir uma nova janela com os dados como parâmetros na URL
                    //window.location.href = `/dashboard/factura-recibos-pos-venda?factura=${data.data.factura.id}`;
                    return

                }
                , error: function(xhr) {

                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }

                    console.log("Erro detectado!");
                    console.log("Status:", xhr.status);
                    console.log("Resposta:", xhr
                        .responseText); // Mostra a resposta detalhada
                }
            });
        });
    });

</script>

<script>
    let PastaID = null;
    let modalVisible = false;

    const modalElementEntradaValores = document.getElementById('modal-lg-entrada-valores');
    const modalInstanceEntradaValores = new bootstrap.Modal(modalElementEntradaValores);

    const modalElementSaidaValores = document.getElementById('modal-lg-saida-valores');
    const modalInstanceSaidaValores = new bootstrap.Modal(modalElementSaidaValores);

    const modalElementListagemVendas = document.getElementById('modal-lg-listagem-vendas');
    const modalInstanceListagemVendas = new bootstrap.Modal(modalElementListagemVendas);

    function toggleModalEntradaValores() {
        if (modalVisible) {
            modalInstanceEntradaValores.hide();
            modalVisible = false;
        } else {
            modalInstanceEntradaValores.show();
            modalVisible = true;
        }
    }

    function toggleModalSaidaValores() {
        if (modalVisible) {
            modalInstanceSaidaValores.hide();
            modalVisible = false;
        } else {
            modalInstanceSaidaValores.show();
            modalVisible = true;
        }
    }

    function toggleModalListagemVendas() {
        if (modalVisible) {
            modalInstanceListagemVendas.hide();
            modalVisible = false;
        } else {
            modalInstanceListagemVendas.show();
            modalVisible = true;
        }
    }

    document.getElementById("motante_entrada").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número
        let numericValue = parseFloat(value) / 100; // Ajusta casas decimais

        // Formata para exibição no padrão brasileiro (10.000,50)
        e.target.value = numericValue.toLocaleString("pt-BR", {
            minimumFractionDigits: 2
        });
    });

    document.getElementById("motante_saida").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número
        let numericValue = parseFloat(value) / 100; // Ajusta casas decimais

        // Formata para exibição no padrão brasileiro (10.000,50)
        e.target.value = numericValue.toLocaleString("pt-BR", {
            minimumFractionDigits: 2
        });
    });

    $(document).ready(function() {
        $('#form_entrada_valores').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário
            //=======================================================
            // formatar valores
            let motante = document.getElementById("motante_entrada");
            // Converter de "10.000,50" para "10000.50"
            let rawValue = motante.value.replace(/\./g, "").replace(",", ".");
            motante.value = parseFloat(rawValue).toFixed(2); // Garantir 2 casas decimais

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');

                    window.location.reload();

                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }

                }
            , });
        });
    });

    $(document).ready(function() {
        $('#form_saida_valores').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário
            //=======================================================
            // formatar valores
            let motante_banco = document.getElementById("motante_saida");
            // Converter de "10.000,50" para "10000.50"
            let rawValue1 = motante_banco.value.replace(/\./g, "").replace(",", ".");
            motante_banco.value = parseFloat(rawValue1).toFixed(2); // Garantir 2 casas decimais
            // //==============================================================

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');

                    window.location.reload();

                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }

                }
            , });
        });
    });

    var quantidade_final = 1;
    let precoPersonalizado = null;
    let precoDescontoAplicado = null;
    let pinAutorizado = null;

    $(document).on('click', '.logout_caixa', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, desejo sair!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('caixa.fechamento_caixa_create') }}`
                    , method: 'POST'
                    , data: {
                        _token: '{{ csrf_token() }}'
                        , caixa_id: recordId, // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();

                        showMessage('Sucesso!', 'Caixa fechado com sucesso!', 'success');

                        window.location.href = response.redirect;

                    }
                    , error: function(xhr) {
                        Swal.close();

                        if (xhr.responseJSON.success == false) {
                            showMessage('Alerta!', xhr.responseJSON.message, 'warning');
                        }

                        window.location.href = xhr.responseJSON.redirect;

                    }
                , });
            }
        });
    });

    // Abre o modal quando o sistema inicia
    $(document).ready(function() {
        $('#modalEscolherCartaoConsumo').modal('show');
    });

    function validarPin() {
        const pin = $('#pinInput').val();

        $.ajax({
            url: `{{ route('cartoes-consumos.validar-pin') }}`
            , method: 'POST'
            , data: {
                pin: pin
            }
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, // Laravel CSRF
            beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(res) {
                Swal.close();
                if (res.status === 'ok') {
                    pinAutorizado = pin;
                }
                $('#modalEscolherCartaoConsumo').modal('hide');
                showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                window.location.reload();
            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        });
    }

    // Função para validar o input
    function validateInput(input) {
        // Expressão regular para aceitar apenas números e pontos
        input.value = input.value.replace(/[^0-9.]/g, '');

        // Evita múltiplos pontos
        if ((input.value.match(/\./g) || []).length > 1) {
            input.value = input.value.slice(0, -1);
        }
    }

    $(document).on('click', '.fechamento_caixa', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Desejo fazer o fechamento do caixa!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, desejo!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('caixa.fechamento_caixa', ':id') }}`.replace(':id'
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
                        // Exibe uma mensagem+ de sucesso
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

    // Função para multiplicar os valores dos inputs
    function calculateMultiplication() {
        const input1 = document.getElementById("input1").value;
        const input2 = document.getElementById("input2").value;
        const quantidade = document.getElementById("quantidade").value;

        // Converte os valores para float e realiza a multiplicação, se ambos forem válidos
        if (input1 && input2 && quantidade) {
            const num1 = parseFloat(input1);
            const num2 = parseFloat(input2);
            const quant = parseFloat(quantidade);
            const result = num1 * num2 * quant;

            // Exibe o resultado
            document.getElementById("result").value = result;

            $('#myModal').modal('hide');

        } else {
            document.getElementById("result").innerText = "Por favor, insira números válidos nos dois campos.";
        }
    }

    function salvarPrecoDescontoAplicado() {
        const preco = parseFloat(document.getElementById("precoDescontoAplicado").value);
        const desconto = document.getElementById("desconto").value;

        if (!isNaN(preco) && preco > 0) {
            precoDescontoAplicado = preco; // Define o preço personalizado para o próximo produto
            document.getElementById("precoDescontoAplicadoTexto").textContent = `${preco.toFixed(1)} %`;
            document.getElementById("desconto").value = preco;
            $('#myModalAplicarDesconto').modal('hide');
            document.getElementById("botao_definir_preco_aplicar_desconto").style.display = 'none';
            document.getElementById("botao_remover_preco_aplicar_desconto").style.display = 'inline-block';
        } else {
            alert("Por favor, insira um preço válido.");
        }
    }

    function removerPrecoDescontoAplicado() {
        const preco = parseFloat(document.getElementById("precoDescontoAplicado").value);
        if (!isNaN(preco) && preco > 0) {
            precoDescontoAplicado = null; // Define o preço personalizado para o próximo produto
            document.getElementById("desconto").value = 0;
            document.getElementById("precoDescontoAplicadoTexto").textContent = "";
            document.getElementById("botao_definir_preco_aplicar_desconto").style.display = 'inline-block';
            document.getElementById("botao_remover_preco_aplicar_desconto").style.display = 'none';
        } else {
            alert("Por favor, insira um preço válido.");
        }
    }

    function salvarPrecoPersonalizado() {
        const preco = parseFloat(document.getElementById("precoPersonalizado").value);
        if (!isNaN(preco) && preco > 0) {
            precoPersonalizado = preco; // Define o preço personalizado para o próximo produto
            document.getElementById("precoPersonalizadoTexto").textContent = `${preco.toFixed(2)}`;
            $('#myModalPrecoPersonalizado').modal('hide');
            document.getElementById("botao_definir_preco_personalizado").style.display = 'none';
            document.getElementById("botao_remover_preco_personalizado").style.display = 'inline-block';
        } else {
            alert("Por favor, insira um preço válido.");
        }
    }

    function removerPrecoPersonalizado() {
        const preco = parseFloat(document.getElementById("precoPersonalizado").value);
        if (!isNaN(preco) && preco > 0) {
            precoPersonalizado = null; // Define o preço personalizado para o próximo produto
            document.getElementById("precoPersonalizadoTexto").textContent = "";
            document.getElementById("botao_definir_preco_personalizado").style.display = 'inline-block';
            document.getElementById("botao_remover_preco_personalizado").style.display = 'none';
        } else {
            alert("Por favor, insira um preço válido.");
        }
    }

    document.addEventListener('DOMContentLoaded', function() {

        const input = document.getElementById('produto_codigo_barra_original');
        input.focus();

        let ultimaLeitura = 0; // Armazena o timestamp da última leitura

        input.addEventListener('keydown', function(e) {
            // Bloqueia "Enter" ou outros atalhos específicos
            if (e.key === 'Enter' || (e.ctrlKey && e.key === 'j')) {
                e.preventDefault();

                const agora = new Date().getTime();
                if (agora - ultimaLeitura < 500) {
                    // Se a última leitura foi há menos de 500ms, ignore
                    return;
                }

                ultimaLeitura = agora; // Atualiza o timestamp da última leitura

                const produtoId = input.value.trim();
                let quantidade = document.getElementById("result").value;

                fetch('/carrinho/adicionar-codigo-barra', {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                        , body: JSON.stringify({
                            produto_id: input.value
                            , quantidade: quantidade
                        , })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            atualizarCarrinho(data.carrinho, data.total);

                            input.value = ''; // Limpa o campo de entrada
                            input.focus(); // Retorna o foco ao campo

                        } else {
                            alert(data.message || 'Erro ao adicionar produto.');
                        }

                    });

            }
        })

        function limitarTexto(texto, limite = 20) {
            if (!texto) return '';
            return texto.length > limite ?
                texto.substring(0, limite) + '...' :
                texto;
        }

        // Função para atualizar o carrinho na tabela
        function atualizarCarrinho(carrinho, total) {
            let carrinhoItens = document.querySelector('#carrinho-itens tbody');
            carrinhoItens.innerHTML = '';

            // Percorre os itens do carrinho e os insere na tabela
            Object.values(carrinho).forEach(item => {
                let tr = document.createElement('tr');

                tr.innerHTML = `
                    <td><a href="#">${limitarTexto(item.nome, 10)}</a></td>
                    <td><a href="#">${item.quantidade}</a></td>
                    <td class="text-right"><a href="#">${Number(item.valor_pagar).toFixed(2).replace('.', ',')} <small>AKZ</small></a></td>
                    <td><a href="#" class="remover-item" data-id="${item.produto_id}"><i class="fas fa-close text-light-danger"></i> </a></td>
                `;

                carrinhoItens.appendChild(tr);
            });

            // Atualiza o valor total do carrinho
            document.querySelector('#total-carrinho').textContent =
                `${Number(total).toFixed(2).replace('.', ',')} AKZ`;

            document.getElementById('total_pagar').value = `${Number(total).toFixed(2).replace('.', ',')}`;
            document.getElementById('valor_entregue').value = `${Number(total).toFixed(2).replace('.', ',')}`;

            // Adiciona o evento de remoção para os novos elementos
            document.querySelectorAll('.remover-item').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    let produtoId = this.getAttribute('data-id');
                    removerDoCarrinho(produtoId);
                });
            });
        }

        // Função para adicionar um produto ao carrinho
        function adicionarAoCarrinho(produtoId, nome, preco, stock, tipo, quantidade = 1) {

            const precoUsado = precoPersonalizado !== null ? precoPersonalizado : preco;

            let stockk = 0;

            if (tipo == "P") {
                stockk = stock;
            } else {
                stockk = 9999999;
            }

            fetch('/carrinho/adicionar', {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                    , body: JSON.stringify({
                        produto_id: produtoId
                        , nome: nome
                        , preco: precoUsado
                        , quantidade: quantidade
                        , stock: stockk
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // alert(data.message);
                    atualizarCarrinho(data.carrinho, data.total);
                });
        }

        // Função para remover um produto do carrinho
        function removerDoCarrinho(produtoId) {
            fetch('/carrinho/remover', {
                    method: 'DELETE'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                    , body: JSON.stringify({
                        produto_id: produtoId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // alert(data.message);
                    atualizarCarrinho(data.carrinho, data.total);
                });
        }

        // Usar delegação de eventos para capturar cliques em elementos adicionados dinamicamente
        $(document).on("click", "#adicionar-carrinho-exemplo", function(e) {
            e.preventDefault();

            let produtoId = $(this).data('id');
            let nome = $(this).data('nome');
            let preco = $(this).data('preco');
            let stock = $(this).data('stock');
            let tipo = $(this).data('tipo');

            let quantidade = document.getElementById("result").value;

            adicionarAoCarrinho(produtoId, nome, preco, stock, tipo, quantidade);

        });

        // Exemplo de uso: adicionar um produto ao carrinho
        document.querySelectorAll('.adicionar-carrinho').forEach(button => {
            button.addEventListener('click', function() {
                let produtoId = this.getAttribute('data-id');
                let nome = this.getAttribute('data-nome');
                let preco = this.getAttribute('data-preco');
                let stock = this.getAttribute('data-stock');
                let tipo = this.getAttribute('data-tipo');

                if (tipo == "P") {
                    if (stock <= 0) {
                        showMessage('Alerta!'
                            , 'Quantidade no stock insuficiente para realizar qualquer venda.!'
                            , 'warning');
                        return
                    }
                }

                let quantidade = document.getElementById("result").value;

                adicionarAoCarrinho(produtoId, nome, preco, stock, tipo, quantidade);

                document.getElementById("input1").value = 1;
                document.getElementById("input2").value = 1;
                document.getElementById("quantidade").value = 1;
                document.getElementById("result").value = 1;

            });
        });

        $('#forma_de_pagamentos').on('change', function(e) {
            e.preventDefault();

            var forma_pagamento = $('#forma_de_pagamentos').val();
            var valor_entregue_multicaixa = document.getElementById('valor_entregue_multicaixa');
            var valor_entregue = document.getElementById('valor_entregue');

            var valor_total = $('#total_pagar').val();

            if (forma_pagamento == "NU") {
                valor_entregue.disabled = false;
                valor_entregue_multicaixa.disabled = true;

                $('.valor_entregue_multicaixa').val(0);
                $('.valor_entregue').val(valor_total);
            } else if (forma_pagamento == "MB") {
                valor_entregue.disabled = true;
                valor_entregue_multicaixa.disabled = false;

                $('.valor_entregue_multicaixa').val(valor_total);
                $('.valor_entregue').val(0);

            } else if (forma_pagamento == "OU") {
                valor_entregue.disabled = false;
                valor_entregue_multicaixa.disabled = false;

                $('.valor_entregue').val(valor_total);
                $('.valor_entregue_multicaixa').val(0);
            }
        })

        $('.valor_entregue').on('input', function(e) {
            e.preventDefault();

            if ($(this).val() > 0) {
                // valor total a pagar
                var valor_total = $('#total_pagar').val();

                var total = parseInt(valor_total.replace(',', '.'));

                var valor_entregue = parseFloat($(this).val());

                var forma_pagamento = $('#forma_de_pagamentos').val();

                var troco = valor_entregue - total;

                if (forma_pagamento == "NU") {

                    var troco = valor_entregue - total;

                    var f2 = troco.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $("#valor_troco_apresenta").html("")
                    $("#valor_troco_apresenta").append(f2)

                } else if (forma_pagamento == "OU") {

                    var valor_restante = valor_entregue - total;

                    var restante = valor_restante * (-1);

                    var f2 = restante.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $('#valor_entregue_multicaixa').val(0);
                    $('#valor_entregue_multicaixa').val(f2);

                    $('#valor_entregue_multicaixa_input').val(restante);
                    $('#valor_entregue_input').val(valor_entregue);


                    if ((restante + valor_entregue) > total) {
                        var novo_troco = (restante + valor_entregue) - total;

                        var f3 = troco.toLocaleString('pt-br', {
                            minimumFractionDigits: 2
                        });

                        $("#valor_troco_apresenta").html("")
                        $("#valor_troco_apresenta").append(f3)
                    }

                }

            } else {
                console.log("false")
            }
        })

        $('.valor_entregue_multicaixa').on('input', function(e) {
            e.preventDefault();
            if ($(this).val() > 0) {
                // valor total a pagar
                var valor_total = $('#total_pagar').val();

                var total = parseInt(valor_total.replace(',', '.'));

                // var valor_entregue_outra_forma = $('#valor_entregue').val();

                var valor_entregue = parseFloat($(this).val());

                var forma_pagamento = $('#forma_de_pagamentos').val();

                if (forma_pagamento == "MB") {

                    var troco = valor_entregue - total;

                    // var f = troco.toLocaleString('pt-br',{style: 'currency', currency: 'AKZ'});
                    var f2 = troco.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $("#valor_troco_apresenta").html("")
                    $("#valor_troco_apresenta").append(f2)


                } else if (forma_pagamento == "OU") {

                    var valor_restante = valor_entregue - total;

                    var restante = valor_restante * (-1);

                    var f2 = restante.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $('#valor_entregue').val(0);
                    $('#valor_entregue').val(f2);

                    $('#valor_entregue_input').val(restante)
                    $('#valor_entregue_multicaixa_input').val(valor_entregue)

                    if ((restante + valor_entregue) > total) {

                        var novo_troco = (restante + valor_entregue) - total;

                        var f3 = troco.toLocaleString('pt-br', {
                            minimumFractionDigits: 2
                        });

                        $("#valor_troco_apresenta").html("")
                        $("#valor_troco_apresenta").append(f3)
                    }

                }
            } else {
                console.log("false")
            }
        })

        $("#produto").on("input", function(e) {
            e.preventDefault()
            var produto = $("#produto").val()

            $.ajax({
                method: "GET"
                , url: "buscar-produto"
                , data: {
                    produto: produto
                }
                , beforeSend: function() {
                    // $(".ajax_load").fadeIn(200).css("display", "flex");
                }
                , success: function(response) {
                    $("#carregar_produtos").html("")
                    for (let index = 0; index < response.produtos.length; index++) {
                        var btn = "";
                        if (response.produtos[index].estoque.stock <= response.produtos[
                                index].estoque.stock_minimo) {
                            btn = "bg-light-danger";
                        } else {
                            btn = "bg-light-primary";
                        }

                        $('#carregar_produtos').append('<div class="col-6 col-md-3 col-lg-2">\
                                <a id="adicionar-carrinho-exemplo" style="cursor: pointer;" data-stock="100" data-id="' +
                            response.produtos[index].id + '" data-nome="' + response
                            .produtos[index].nome + '" data-preco="' + response
                            .produtos[index].preco_venda_com_iva + '">\
                                    <div class="card shadow-sm bg-light">\
        								<div class="card-body ' + btn + '" ">\
                                            <div class="col-12 col-md-12 col-sm-12">\
                                                <h6 class="pt-3 text-uppercase text-white">' + response.produtos[index]
                            .nome + '</h6>\
                                                <p class="text-light-dark"><strong>' + response.produtos[index].preco_venda_com_iva +
                            '<small>' + response.loja.moeda + '</small></strong></p>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </a>\
                            </div>');
                    }

                }
            })
        })
    });


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
