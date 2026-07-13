@extends('layouts.vendas')

@section('content')
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header bg-light-dark  mb-5">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 col-md-4">
                    <h1 class="m-0">Ponto de Venda</h1>
                </div><!-- /.col -->
                <div class="col-12 col-md-8">
                    <div class="row">

                        @if (!empty($checkCaixa))
                        <div class="col-12 col-md-2">
                            <a href="#" data-id="{{ $checkCaixa->id }}" class="btn-app bg-light-danger d-block text-center logout_caixa" role="button" data-slide="true" data-widget="control-sidebar" title="SAIR DAS VENDAS">
                                <i class="fas fa-power-off"></i>
                                Fechar Caixa
                            </a>
                        </div>

                        @if (Auth::user()->can('entrada valor no caixa'))
                        <div class="col-lg-2 col-md-3 col-12">
                            <a href="#" class="btn-app bg-light-secondary text-center d-block" onclick="toggleModalEntradaValores()"><i class="fas fa-arrow-down"></i>Entrada de valores no caixa</a>
                        </div>
                        @endif

                        @if (Auth::user()->can('saida valor no caixa'))
                        <div class="col-lg-2 col-md-3 col-12">
                            <a href="#" class="btn-app bg-light-secondary text-center d-block" onclick="toggleModalSaidaValores()"><i class="fas fa-arrow-up"></i>Saída de Valores no caixa</a>
                        </div>
                        @endif

                        @if (Auth::user()->can('movimento no caixa'))
                        <div class="col-lg-2 col-md-3 col-12">
                            <a href="#" onclick="toggleModalListagemVendas()" class="btn-app bg-light-secondary text-center d-block"><i class="fas fa-shopping-basket"></i>{{ __('messages.listagem') }} {{ __('messages.venda') }}</a>
                        </div>
                        @endif
                        @endif

                        @if (!Auth::user()->can('Operador'))
                        <div class="col-lg-2 col-md-3 col-12">
                            <a href="{{ route('dashboard-principal') }}" class="btn-app bg-light-secondary text-center d-block"><i class="fas fa-home"></i> {{ __('messages.controle') }}</a>
                        </div>
                        @endif

                        @if (Auth::user()->can('Operador'))
                        <div class="col-12 col-md-2">
                            <button class="btn-app  bg-light-primary" text-center d-block" data-toggle="modal" data-target="#editarSenhaModal">
                                <i class="fas fa-lock"></i> Alterar Minha Senha
                            </button>
                        </div>
                        @endif


                        <div class="col-12 col-md-2">
                            <a href="#" class="btn-app bg-light-danger text-center d-block finiched-session-application">
                                <i class="fas fa-sign-out-alt"></i>
                                {{ __('messages.terminar_sessao') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="content">
        <div class="container-fluid">
            @if (empty($checkCaixa))
            <div class="row">
                <div class="col-12 col-md-3"></div>
                <div class="col-12 col-md-6">
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
                                                <span class="input-group-text">Kz</span>
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
                                <button type="submit" class="btn btn-md mt-4 d-iniline-block btn-light-primary"><i class="fas fa-box"></i> Abrir Caixa</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3"></div>
            </div>
            @else
            <div class="row">
                <div class="col-12 col-md-9">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <!-- Botão F2 para abrir modal -->
                                        <div class="col-12 col-md-6">
                                            <button id="abrirModal" class="btn btn-light-primary">[F2] Adicionar Produto</button>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="text" name="produto_codigo_barra" id="produto_codigo_barra_original" autofocus class="form-control produto_codigo_barra" placeholder="{{ __('messages.codigo_barras') }}!">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Grelha de carrinho -->
                                    <table class="table table-bordered mt-3" id="carrinhoTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.produtos') }}</th>
                                                <th>{{ __('messages.quantidade') }}</th>
                                                <th>{{ __('messages.preco_custo') }}</th>
                                                <th>{{ __('messages.desconto') }} %</th>
                                                <th>{{ __('messages.imposto') }} %</th>
                                                <th>Subtotal</th>
                                                <th>{{ __('messages.accoes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                </div>

                                <div class="card-footer">
                                    <h6>Clica <span class="text-light-danger">F3</span> para fazer pesquisa dos produtos pelo codigo!</h6>
                                    <h6>Clica <span class="text-light-danger">F6</span> para finalizar a venda!</h6>
                                    <h6>Clica <span class="text-light-danger">TAB</span> para alterar os campos!</h6>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-12 col-md-3">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header bg-light-dark ">
                                    <h4>
                                        <label class="h3">{{ __('messages.total') }}: <span id="total">0</span> KZ</label>
                                        <br>
                                        <label class="h5">Troco: <span id="troco">0</span> KZ</label>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <!-- Resumo -->
                                    <div class="mt-3">

                                        <div class="row">
                                            <div class="col-12 col-md-12 mb-4">
                                                <label class="form-label">Valor Entregue: </label>
                                                <input type="number" id="valor_entregue" class="form-control form-control-lg" />
                                            </div>


                                            <div class="col-md-7 col-12 mb-4">
                                                <input class="form-control form-control-lg" type="text" name="nome_cliente" id="nome_cliente" value="CONSUMIDOR FINAL">
                                            </div>

                                            <div class="col-md-5 col-12 mb-4">
                                                <input class="form-control form-control-lg" type="text" name="documento_nif" id="documento_nif" value="999999999">
                                            </div>

                                            <div class="col-md-12 col-12 mb-4">
                                                <select name="cliente_id" id="cliente_id" class="form-control form-control-lg">
                                                    @foreach ($clientes as $item)
                                                    <option value="{{ $item->id ?? "" }}" data-index={{ $item }} data-descricao={{ $item->nome }} data-nif="{{ $item->nif }}" {{ $item->nome == 'CONSUMIDOR FINAL' ? 'selected' : '' }}> {{ $item->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-12 col-md-7 mb-2">
                                                <label class="form-label">Forma de pagamento: </label>
                                                <select id="forma_pagamento" class="form-control form-control-lg">
                                                    @foreach ($forma_pagmento as $forma)
                                                    <option value="{{ $forma->tipo }}" class="text-uppercase"> {{ $forma->titulo }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <input type="hidden" id="venda_realizado" name="venda_realizado" value="CAIXA">
                                            <input type="hidden" id="tipo_pronto_venda" name="tipo_pronto_venda" value="GRELHA">
                                            <input type="hidden" id="documento" name="documento" value="FR">

                                            <div class="col-md-5 col-12 mb-4">
                                                <label class="form-label">{{ __('messages.desconto') }} %: </label>
                                                <input class="form-control form-control-lg" @if (!Auth::user()->can('atribuir desconto')) disabled @endif type="text" name="desconto" id="desconto" value="0">
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button id="finalizar" class="btn btn-light-success btn-lg mt-3 w-100">Finalizar Venda</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            @endif
        </div>

        <!-- Modal Produtos -->
        <div class="modal fade" id="modalProdutos" tabindex="-1">
            <div class="modal-dialog" style="max-width: 95%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Selecionar Produto</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-12 col-md-6 my-2">
                                <input type="text" id="buscarProduto" class="form-control form-control-lg" placeholder="Pesquisar produto..." autofocus />
                            </div>
                            <div class="col-12 col-md-6 my-2">
                                <input type="number" id="quantidade" class="form-control form-control-lg" placeholder="Qtd" value="1" />
                            </div>
                        </div>
                        <table class="table table-hover" id="tabelaProdutos">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.designacao') }}</th>
                                    <th>Codigo Barra</th>
                                    <th>Existencia Global</th>
                                    <th>Preço</th>
                                </tr>
                            </thead>
                            <tbody id="listaProdutos"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button id="adicionarProduto" class="btn btn-light-primary">Adicionar</button>
                    </div>
                </div>
            </div>
        </div>


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

        <!-- Modal -->
        <div class="modal fade" id="editarSenhaModal" tabindex="-1" aria-labelledby="editarSenhaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formAlterarSenha" method="POST" action="{{ route('privacidade-store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="editarSenhaModalLabel">Alterar Senha</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha Atual</label>
                                <input type="password" name="senha" id="senha" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nova_senha" class="form-label">Nova Senha</label>
                                <input type="password" name="nova_senha" id="nova_senha" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">{{ __('messages.cancelar') }}</button>
                            <button type="submit" class="btn btn-light-success">{{ __('messages.salvar') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <input type="hidden" id="editar_venda_campo" value="{{Auth::user()->can('editar vendas')}}">
</div>
@endsection

@section('scripts')
<script>
    let carrinho = [];
    let produtoSelecionado = null;
    let indexProdutoSelecionado = -1;

    let podeEditarPreco = $('#editar_venda_campo').val();

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

    // F2: abre a modal e carrega os produtos (sem focar ainda)
    $(document).on("keydown", function(e) {
        if (e.key === "F2" && !$('#modalProdutos').hasClass('show')) {
            e.preventDefault();
            $('#modalProdutos').modal('show');
            carregarProdutos(""); // carrega todos
        }
    });

    // F2: abre a modal e carrega os produtos (sem focar ainda)
    $(document).on("keydown", function(e) {
        if (e.key === "F3") {
            e.preventDefault();

            const $inp = $('#produto_codigo_barra_original');
            $inp.val('');
            $inp.trigger('focus');
            $inp.select(); // opcional: já seleciona o texto

            carregarProdutos(""); // carrega todos
        }
    });

    // F2: abre a modal e carrega os produtos (sem focar ainda)
    $(document).on("click", "#abrirModal", function(e) {
        e.preventDefault();
        $('#modalProdutos').modal('show');
        carregarProdutos(""); // carrega todos
    });

    // Quando a modal terminar de abrir, foca no input
    $('#modalProdutos')
        .off('shown.bs.modal') // evita múltiplos binds
        .on('shown.bs.modal', function() {
            const $inp = $('#buscarProduto');
            $inp.val('');
            // pequeno delay ajuda a vencer a animação do Bootstrap
            setTimeout(() => {
                $inp.trigger('focus');
                $inp.select(); // opcional: já seleciona o texto
            }, 10);
        });


    // Função para carregar produtos
    function carregarProdutos(query = "") {
        $.get("/pos/produtos?q=" + query, function(data) {
            let html = "";
            data.forEach((p, i) => {
                html += `<tr class="produto-item"
                    data-index="${i}"
                    data-id="${p.id}"
                    data-nome="${p.nome}"
                    data-taxa="${p.taxa}"
                    data-preco="${p.preco_venda}">
                    <td>${p.nome}</td>
                    <td>${p.codigo_barra ?? ''}</td>
                    <td>${parseFloat(p.total_produto_loja_activa).toFixed(2)}</td>
                    <td>${parseFloat(p.preco_venda).toFixed(2)}</td>
                 </tr>`;
            });
            $("#listaProdutos").html(html);

            // Seleciona o primeiro
            indexProdutoSelecionado = 0;
            $(".produto-item").removeClass("bg-light-primary text-white");
            $(".produto-item").eq(0).addClass("bg-light-primary text-white");
            if (data.length > 0) {
                produtoSelecionado = data[0];
            }
        });
    }

    // Buscar produto enquanto digita
    $("#buscarProduto").on("keyup", function() {
        carregarProdutos($(this).val());
    });


    // Navegação na tabela com setas
    $(document).on("keydown", "#buscarProduto", function(e) {
        let items = $(".produto-item");
        if (items.length === 0) return;


        if (e.key === "ArrowDown") {
            if (indexProdutoSelecionado < items.length - 1) indexProdutoSelecionado++;
        }
        if (e.key === "ArrowUp") {
            if (indexProdutoSelecionado > 0) indexProdutoSelecionado--;
        }

        if (e.key === "Enter") {
            e.preventDefault();
            selecionarProduto(items.eq(indexProdutoSelecionado));
            adicionarAoCarrinho();
            $("#modalProdutos").modal("hide");
            return;
        }

        items.removeClass("bg-light-primary text-white");
        $(items[indexProdutoSelecionado]).addClass("bg-light-primary text-white");
        selecionarProduto(items.eq(indexProdutoSelecionado));
    });

    // Clique com mouse também
    $(document).on("click", ".produto-item", function() {
        selecionarProduto($(this));
        adicionarAoCarrinho();
        $("#modalProdutos").modal("hide");
        return;
    });

    // Botão adicionar
    $("#adicionarProduto").click(function() {
        if (produtoSelecionado) {
            adicionarAoCarrinho();
            $("#modalProdutos").modal("hide");
        }
    });


    // Seleciona produto da linha
    function selecionarProduto(el) {
        produtoSelecionado = {
            id: el.data("id")
            , nome: el.data("nome")
            , preco: el.data("preco")
            , taxa: el.data("taxa")
        };
    }

    // Adicionar no carrinho
    function adicionarAoCarrinho() {
        if (produtoSelecionado) {
            let qtd = parseFloat($("#quantidade").val()) || 1;

            // verifica se já existe no carrinho
            let existente = carrinho.find(item => item.id === produtoSelecionado.id);

            if (existente) {
                // se já existe, apenas atualiza quantidade
                existente.quantidade += qtd; // se quiser apenas substituir, troque por = qtd
                existente.subtotal = existente.preco * existente.quantidade;
            } else {
                // novo item
                let subtotal = produtoSelecionado.preco * qtd;
                let imposto = subtotal * (produtoSelecionado.taxa / 100);
                subtotal += imposto;
                carrinho.push({
                    ...produtoSelecionado
                    , quantidade: qtd
                    , subtotal: subtotal
                    , desconto: 0
                    , imposto: imposto
                    , produto_id: produtoSelecionado.id
                });
            }

            renderCarrinho();
        }
    }


    // Renderiza carrinho
    function renderCarrinho(indexFoco = null) {
        let html = "";
        let total = 0;
        carrinho.forEach((item, index) => {
            total += item.subtotal;
            html += `<tr>
                <td>${item.nome}</td>
                <td><input type="number" value="${item.quantidade}" class="form-control qtdItem" data-index="${index}"></td>
                ${
                    podeEditarPreco
                    ? `<td><input type="number" value="${item.preco}" class="form-control precoItem" data-index="${index}"></td>`
                    : `<td>${item.preco}</td>`
                }
                <td>${item.desconto}</td>
                <td>${item.taxa}</td>
                <td>${item.subtotal.toFixed(2)}</td>
                <td><button class="btn btn-light-danger remover" data-index="${index}">X</button></td>
            </tr>`;
        });
        $("#carrinhoTable tbody").html(html);
        $("#total").text(total.toFixed(2));
        parseFloat($("#valor_entregue").val(total.toFixed(2)));
        calcularTroco();

        // Focar o input de quantidade do item atual (ou o último por padrão)
        setTimeout(() => {
            const indexParaFocar = indexFoco !== null ? indexFoco : carrinho.length - 1;
            const input = $(`.qtdItem[data-index="${indexParaFocar}"]`);
            if (input.length) {
                input.focus().select();
            }
        }, 100);
    }

    // Atualizar preço
    $(document).on("change", "#cliente_id", function() {
        let options = $(this).find(':selected');

        let nome = options.data("descricao");
        let nif = options.data("nif");

        $("#nome_cliente").val(nome)
        $("#documento_nif").val(nif)

    });

    // Atualizar preço
    $(document).on("change", ".precoItem", function() {
        let index = $(this).data("index");
        carrinho[index].preco = parseFloat($(this).val());
        let subtotal = carrinho[index].preco * carrinho[index].quantidade;
        let imposto = subtotal * (carrinho[index].taxa / 100);
        subtotal += imposto;
        carrinho[index].subtotal = subtotal;
        carrinho[index].imposto = imposto;

        renderCarrinho();
    });

    // Atualizar quantidade
    $(document).on("change", ".qtdItem", function() {
        let index = $(this).data("index");
        carrinho[index].quantidade = parseFloat($(this).val());

        let subtotal = carrinho[index].preco * carrinho[index].quantidade;
        let imposto = subtotal * (carrinho[index].taxa / 100);
        subtotal += imposto;
        carrinho[index].subtotal = subtotal;
        carrinho[index].imposto = imposto;

        //carrinho[index].subtotal = carrinho[index].preco * carrinho[index].quantidade;
        renderCarrinho();
    });


    // Remover item
    $(document).on("click", ".remover", function() {
        let index = $(this).data("index");
        carrinho.splice(index, 1);
        renderCarrinho();
    });

    // Calcular troco
    $("#valor_entregue").on("keyup change", function() {
        calcularTroco();
    });

    function calcularTroco() {
        let total = parseFloat($("#total").text());
        let entregue = parseFloat($("#valor_entregue").val()) || 0;
        $("#troco").text((entregue - total).toFixed(2));
    }


    $(document).on("keydown", function(e) {
        if (e.key === "F6") {
            finalizarVenda(e);
        }
    });


    function finalizarVenda(e) {
        e.preventDefault();

        let forma_pagamento = $("#forma_pagamento").val();
        let dados = {
            _token: "{{ csrf_token() }}"
            , pagamento: forma_pagamento
            , valor_entregue: $("#valor_entregue").val()
            , desconto: $("#desconto").val()
            , nome_cliente: $("#nome_cliente").val()
            , documento_nif: $("#documento_nif").val()
            , cliente_id: $("#cliente_id").val()
            , total_pagar: parseFloat($("#total").text().replace(",", ".").trim())
            , troco: parseFloat($("#troco").text().replace(",", ".").trim())
            , venda_realizado: $("#venda_realizado").val()
            , tipo_pronto_venda: $("#tipo_pronto_venda").val()
            , documento: $("#documento").val()
            , carrinho: carrinho
        };

        $.ajax({
            url: "{{ route('carrinho.pagamento') }}"
            , type: "POST"
            , data: dados
            , beforeSend: function() {
                progressBeforeSend();
            }
            , success: function(response) {

                Swal.close();

                carrinho = [];
                renderCarrinho();

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
            }
        });
    }

    // Finalizar venda
    $("#finalizar").click(function(e) {

        finalizarVenda(e);

        // e.preventDefault();

        // let forma_pagamento = $("#forma_pagamento").val();
        // let dados = {
        //     _token: "{{ csrf_token() }}"
        //     , pagamento: forma_pagamento
        //     , valor_entregue: $("#valor_entregue").val()
        //     , desconto: $("#desconto").val()
        //     , nome_cliente: $("#nome_cliente").val()
        //     , documento_nif: $("#documento_nif").val()
        //     , cliente_id: $("#cliente_id").val()
        //     , total_pagar: parseFloat($("#total").text().replace(",", ".").trim())
        //     , troco: parseFloat($("#troco").text().replace(",", ".").trim())
        //     , venda_realizado: $("#venda_realizado").val()
        //     , tipo_pronto_venda: $("#tipo_pronto_venda").val()
        //     , documento: $("#documento").val()
        //     , carrinho: carrinho
        // };

        // $.ajax({
        //     url: "{{ route('carrinho.pagamento') }}"
        //     , type: "POST"
        //     , data: dados
        //     , beforeSend: function() {
        //         progressBeforeSend();
        //     }
        //     , success: function(response) {

        //         Swal.close();

        //         carrinho = [];
        //         renderCarrinho();

        //         const baseUrl = `{{ route('factura-recibo-pos-venda') }}`;
        //         const facturaId = response.data.factura.id; // Este valor pode vir dinamicamente do seu sistema

        //         // Construir a URL completa
        //         const url = `${baseUrl}?factura=${facturaId}`;

        //         // Redirecionar
        //         window.location.href = url;

        //         // Abrir uma nova janela com os dados como parâmetros na URL
        //         //window.location.href = `/dashboard/factura-recibos-pos-venda?factura=${data.data.factura.id}`;
        //         return

        //     }
        //     , error: function(xhr) {

        //         // Feche o alerta de carregamento
        //         Swal.close();
        //         // Trata erros e exibe mensagens para o usuário
        //         if (xhr.status === 422) {
        //             let errors = xhr.responseJSON.errors;
        //             let messages = '';
        //             $.each(errors, function(key, value) {
        //                 messages += `${value}\n`; // Exibe os erros
        //             });
        //             showMessage('Erro de Validação!', messages, 'error');
        //         } else {
        //             showMessage('Erro!', xhr.responseJSON.message, 'error');
        //         }
        //     }
        // });

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

    $(document).ready(function() {
        $('#formAlterarSenha').on('submit', function(e) {
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
                let quantidade = 1;

                fetch('/carrinho/adicionar-codigo-barra-grelha', {
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

                            // Criar um elemento fictício com os atributos data-*
                            const $fakeElement = $('<tr>')
                                .data("id", data.carrinho.id)
                                .data("nome", data.carrinho.nome)
                                .data("preco", parseFloat(data.carrinho.preco).toFixed(2))
                                .data("taxa", data.carrinho.taxa);

                            selecionarProduto($fakeElement);
                            adicionarAoCarrinho();
                            $("#modalProdutos").modal("hide");

                            input.value = ''; // Limpa o campo de entrada
                            input.focus(); // Retorna o foco ao campo

                            return;
                        } else {
                            alert(data.message || 'Erro ao adicionar produto.');
                        }

                    });

            }
        })
    });

</script>
@endsection
