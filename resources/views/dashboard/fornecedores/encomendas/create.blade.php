@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-plus"></i> {{ __('messages.novo') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores-encomendas.index') }}">{{ __('messages.voltar') }}</a></li>
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
                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('fornecedores-encomendas.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-2">
                                        <label for="numero" class="form-label text-right">Nº Encomenda:</label>
                                        <input type="text" class="form-control" id="numero" name="numero" value="{{ $totalEncomendas }}" placeholder="Número da Encomenda:">
                                        <p class="text-light-danger col-sm-3">
                                            @error('numero')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="fornecedor_id" class="form-label text-right">{{ __('messages.fornecedores') }}:</label>
                                        <select class="form-control select2" id="fornecedor_selecionado" name="fornecedor_selecionado">
                                            @foreach ($fornecedores as $fornecedor)
                                            <option value="{{ $fornecedor->id ?? old('fornecedor_selecionado') }}">{{ $fornecedor->nome }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-light-danger col-sm-3">
                                            @error('fornecedor_selecionado')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="loja_id" class="form-label text-right">Loja/Armazém:</label>
                                        <select class="form-control select2" id="loja_id" name="loja_id">
                                            @foreach ($lojas as $loja)
                                            <option value="{{ $loja->id ?? old('fornecedor_selecionado') }}">{{ $loja->nome }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-light-danger col-sm-3">
                                            @error('loja_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="data_previsao" class="form-label text-right">Previsão de Entrega:</label>
                                        <input type="date" class="form-control" id="data_previsao" name="data_previsao" value="{{ old('data_previsao') ?? date("Y-m-d") }}" placeholder="">
                                        <p class="text-light-danger col-sm-3">
                                            @error('data_previsao')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="custo_transporte" class="form-label text-right">Custos de Transporte:</label>
                                        <input type="text" class="form-control" id="custo_transporte" name="custo_transporte" value="{{ old('custo_transporte') ?? 0 }}" placeholder="Custos de Transporte">
                                        <p class="text-light-danger col-sm-3">
                                            @error('custo_transporte')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="custo_manuseamento" class="form-label text-right">Custos de Manuseamento:</label>
                                        <input type="text" class="form-control" id="custo_manuseamento" name="custo_manuseamento" value="{{ old('custo_manuseamento') ?? 0 }}" placeholder="Custos de Manuseamento">
                                        <p class="text-light-danger col-sm-3">
                                            @error('custo_manuseamento')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="outros_custos" class="form-label text-right">Outros Custos</label>
                                        <input type="text" class="form-control" id="outros_custos" name="outros_custos" value="{{ old('outros_custos') ?? 0 }}" placeholder="Outros Custos direitamente atribuíveis à compra dos bens">
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="desconto_comercial" class="form-label text-right">Desconto Comercial (%)</label>
                                        <div class="input-group">
                                            <input type="number" min="0" step="0.01" max="100" disabled class="form-control" id="desconto_comercial" name="desconto_comercial" value="{{ old('desconto_comercial') ?? 0 }}" placeholder="Desconto global em percentagem EX 5%">
                                            <!-- Checkbox dentro do grupo -->
                                            <div class="input-group-text p-0">
                                                <div class="form-check d-flex align-items-center justify-content-center w-100 h-100 m-0">
                                                    <input style="width: 45px; height: 35px;cursor: pointer" class="form-check-input" type="checkbox" name="toggle_desconto_comercial" id="toggle_desconto_comercial" value="1" {{ old('toggle_desconto_comercial') ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="desconto_financeiro" class="form-label">Desconto Financeiro (%)</label>
                                        <div class="input-group">
                                            <input type="number" disabled class="form-control" value="" step="0.01" id="desconto_financeiro" name="desconto_financeiro" value="{{ old('desconto_financeiro') }}" placeholder="Ex: 5%">
                                            <!-- Checkbox dentro do grupo -->
                                            <div class="input-group-text p-0">
                                                <div class="form-check d-flex align-items-center justify-content-center w-100 h-100 m-0">
                                                    <input style="width: 45px; height: 35px;cursor: pointer" class="form-check-input" type="checkbox" name="toggle_desconto_financeiro" id="toggle_desconto_financeiro" value="1" {{ old('toggle_desconto_financeiro') ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <small class="text-light-secondary">Use este campo apenas se o pagamento for antecipado.</small> --}}
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label for="observacao" class="form-label text-right">{{ __('messages.observacao') }}:</label>
                                        <input type="text" class="form-control" id="observacao" name="observacao" value="{{ old('observacao') ?? "" }}" placeholder="{{ __('messages.observacao') }} ">
                                    </div>

                                    <input type="hidden" name="carrinho_encomenda" id="carrinho_encomenda" value="carrinho_encomenda">

                                    @if ($items)
                                    <div class="col-12 col-md-12 mt-5">

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <button type="button" id="abrirModal" class="btn btn-light-primary">[F2] Adicionar Produto</button>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label class="h4">{{ __('messages.total') }}: <span id="total">0</span></label>
                                            </div>
                                        </div>

                                        <table class="table table-bordered mt-3" id="carrinhoTable">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('messages.produtos') }}</th>
                                                    <th>{{ __('messages.quantidade') }}</th>
                                                    <th>{{ __('messages.preco') }}</th>
                                                    <th>{{ __('messages.desconto') }} %</th>
                                                    <th>{{ __('messages.imposto') }} %</th>
                                                    <th>Subtotal</th>
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    let carrinho = [];
    let produtoSelecionado = null;
    let indexProdutoSelecionado = -1;

    let podeEditarPreco = @json(Auth::user() - > can('editar vendas'));

    // F2: abre a modal e carrega os produtos (sem focar ainda)
    $(document).on("keydown", function(e) {
        if (e.key === "F2" && !$('#modalProdutos').hasClass('show')) {
            e.preventDefault();
            $('#modalProdutos').modal('show');
            carregarProdutos(""); // carrega todos
        }
    });

    // F2: abre a modal e carrega os produtos (sem focar ainda)
    $(document).on("click", "#abrirModal", function(e) {
        e.preventDefault();
        $('#modalProdutos').modal('show');
        carregarProdutos(""); // carrega todos
    });


    function calcularTroco() {
        let total = parseFloat($("#total").text());
        let entregue = parseFloat($("#valor_entregue").val()) || 0;
        $("#troco").text((entregue - total).toFixed(2));
    }

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
                    data-preco="${p.preco_custo}">
                    <td>${p.nome}</td>
                    <td>${p.codigo_barra ?? ''}</td>
                    <td>${parseFloat(p.total_produto_loja_activa).toFixed(2)}</td>
                    <td>${parseFloat(p.preco_custo).toFixed(2)}</td>
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
    function renderCarrinho() {
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
    }

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

    // Remover item
    $(document).on("click", ".remover", function() {
        let index = $(this).data("index");
        carrinho.splice(index, 1);
        renderCarrinho();
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

        renderCarrinho();
    });


    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('toggle_desconto_comercial');
        const checkbox_financeiro = document.getElementById('toggle_desconto_financeiro');
        const input = document.getElementById('desconto_comercial');
        const input_financeiro = document.getElementById('desconto_financeiro');
        checkbox.addEventListener('change', function() {
            input.disabled = !this.checked;
            // opcional: limpa o campo se desmarcar
            if (!this.checked) {
                // input.value = '';
            }
        });
        checkbox_financeiro.addEventListener('change', function() {
            input_financeiro.disabled = !this.checked;
            // opcional: limpa o campo se desmarcar
            if (!this.checked) {
                // input.value = '';
            }
        });
    });

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            // formatar valores
            let custo_transporte = document.getElementById("custo_transporte");
            // Converter de "10.000,50" para "10000.50"
            let rawValue = custo_transporte.value.replace(/\./g, "").replace(",", ".");
            custo_transporte.value = parseFloat(rawValue).toFixed(2); // Garantir 2 casas decimais

            // formatar valores
            let custo_manuseamento = document.getElementById("custo_manuseamento");
            // Converter de "10.000,50" para "10000.50"
            let rawValue1 = custo_manuseamento.value.replace(/\./g, "").replace(",", ".");
            custo_manuseamento.value = parseFloat(rawValue1).toFixed(2); // Garantir 2 casas decimais

            // formatar valores
            let outros_custos = document.getElementById("outros_custos");
            // Converter de "10.000,50" para "10000.50"
            let rawValue2 = outros_custos.value.replace(/\./g, "").replace(",", ".");
            outros_custos.value = parseFloat(rawValue1).toFixed(2); // Garantir 2 casas decimais
            // //==============================================================

            let carrinho_encomenda = document.getElementById("carrinho_encomenda");
            carrinho_encomenda.value = JSON.stringify(carrinho);

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                beforeSend: function() {
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

    // $(function() {
    //     $("#produto").on('change', function(e) {
    //         e.preventDefault();
    //         // Supondo que o valor do produto está em um campo com id 'produto'
    //         const produtoId = $("#produto").val();
    //         if (produtoId != "") {
    //             // Gerar a URL usando o Laravel Blade
    //             const url = `{{ route('items-nova-encomenda-sem-fornecedora-ctualizar', ':produto') }}`.replace(':produto', produtoId);
    //             // Redirecionar
    //             window.location.href = url;
    //         }
    //     })
    //     //Date picker
    //     $('#reservationdate').datetimepicker({
    //         format: 'L'
    //     });
    // });

    document.getElementById("custo_transporte").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número
        let numericValue = parseFloat(value) / 100; // Ajusta casas decimais
        // Formata para exibição no padrão brasileiro (10.000,50)
        e.target.value = numericValue.toLocaleString("pt-BR", {
            minimumFractionDigits: 2
        });
    });

    document.getElementById("custo_manuseamento").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número
        let numericValue = parseFloat(value) / 100; // Ajusta casas decimais

        // Formata para exibição no padrão brasileiro (10.000,50)
        e.target.value = numericValue.toLocaleString("pt-BR", {
            minimumFractionDigits: 2
        });
    });

    document.getElementById("outros_custos").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número
        let numericValue = parseFloat(value) / 100; // Ajusta casas decimais

        // Formata para exibição no padrão brasileiro (10.000,50)
        e.target.value = numericValue.toLocaleString("pt-BR", {
            minimumFractionDigits: 2
        });
    });

</script>
@endsection
