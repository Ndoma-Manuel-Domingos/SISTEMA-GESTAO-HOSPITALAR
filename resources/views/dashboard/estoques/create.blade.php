@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.actualizar_stock')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('estoques.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Stock</li>
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
                <div class="col-md-12 col-12">
                    <div class="card">
                        <form action="{{ route('estoques.store') }}" method="post" class="">
                            @csrf
                            <div class="card-header">
                                <a href="{{ route('store_import.produtos') }}" class="btn btn-light-success"><i class="fas fa-file-excel"></i> {{ __('messages.importar_excel') }}</a>
                            </div>

                            <div class="card-body row">
                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">{{ __('messages.lojas') }}</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="loja_id">
                                            @if ($lojas)
                                            @foreach ($lojas as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="operacao" class="form-label">Operação</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control operacao" name="operacao" id="operacao">
                                            <option value="Entrada de Stock">Entrada de Stock</option>
                                            <option value="Saída de Stock">Saída de Stock</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="tipo_documento" class="form-label">Tipo documento</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 tipo_documento" name="tipo_documento" id="tipo_documento">
                                            <optgroup label="ENTRADAS" id="entradas">
                                                <option value="CN" selected>CN - COMPRAS A PRO.PAGA</option>
                                                <option value="CF">CF - COMPRAS A PRAZO</option>
                                                <option value="IO">IO - EXISTÊNCIA INICIAS</option>
                                                <option value="IP">IP - ACERTO INVENTÁRIO</option>
                                            </optgroup>

                                            <optgroup label="SAÍDAS" id="saidas">
                                                <option value="D1">D1 - DEVOLUÇÃO A FRONECEDOR</option>
                                                <option value="L1">L1 - REQUISIÇÕES COM CUSTOS</option>
                                                <option value="L4">L4 - QUEBRA EM ARAMAZÉM</option>
                                                <option value="IN">IN - ACERTO INVENTÁRIO</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="fornecedor_id" class="form-label">{{ __('messages.fornecedores') }}</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 fornecedor_id" name="fornecedor_id" id="fornecedor_id">
                                            @foreach ($fornecedores as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="cliente_id" class="form-label">{{ __('messages.clientes') }}</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 cliente_id" name="cliente_id" id="cliente_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($clientes as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">{{ __('messages.observacao') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="observacao" value="{{ old('observacao') ?? '' }}" placeholder="Observação">
                                    </div>
                                </div>

                                <!-- Produto, Quantidade e Preço -->
                                <div class="col-12 col-md-12">
                                    <div class="row mb-5">
                                        <div class="col-12 col-md-4">
                                            <label>{{ __('messages.designacao') }}</label>
                                            <select id="produtoSelect" class="form-control select2" style="width: 100%">
                                                <option value="">{{ __('messages.escolher') }}</option>
                                                @foreach ($produtos as $produto)
                                                <option value="{{ $produto->id }}" data-nome="{{ $produto->nome }}">{{ $produto->nome }} ---- {{ $produto->codigo_barra }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <input type="hidden" class="form-control" value="0" id="lote_id" name="lote_id">
                                        <input type="hidden" class="form-control" value="" id="lote_name" name="lote_name">


                                        {{-- <div class="col-12 col-md-2">
                                            <label for="lote_id" class="form-label">Lote</label>
                                            <div class="input-group mb-3">
                                                <select type="text" class="form-control select2" id="lote_id" name="lote_id">
                                                </select>
                                            </div>
                                        </div> --}}

                                        <div class="col-12 col-md-2">
                                            <label>Qtd</label>
                                            <input type="number" id="quantidadeInput" value="{{ __('messages.quantidade') }}" class="form-control" min="1">
                                        </div>

                                        <div class="col-12 col-md-1">
                                            <label>Qtd Existente</label>
                                            <input type="number" id="quantidadeActualInput" disabled value="{{ __('messages.quantidade') }}" class="form-control" min="1">
                                        </div>

                                        <div class="col-12 col-md-2">
                                            <label>{{ __('messages.preco_custo') }}</label>
                                            <input type="number" id="precoInput" class="form-control" value="0" step="0.01">
                                        </div>

                                        <div class="col-12 col-md-2">
                                            <label>{{ __('messages.preco_venda') }}</label>
                                            <input type="number" id="precoInputVenda" class="form-control" value="0" step="0.01">
                                        </div>

                                        <div class="col-12 col-md-1 pt-4">
                                            <button type="button" class="btn btn-light-primary mt-2" id="adicionarBtn">Adicionar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <!-- Tabela Temporária -->
                                    <table class="table table-bordered" id="tabelaProdutos">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.designacao') }}</th>
                                                <th>Lote</th>
                                                <th>{{ __('messages.quantidade') }}</th>
                                                <th>{{ __('messages.quantidade') }} actual</th>
                                                <th>{{ __('messages.preco_custo') }}</th>
                                                <th>{{ __('messages.preco_venda') }}</th>
                                                <th>{{ __('messages.total') }}</th>
                                                <th>{{ __('messages.accoes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                    <!-- Área escondida para enviar dados -->
                                    <input type="hidden" name="itens" id="itensInput">
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.row -->
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
    let produtosTemp = [];

    document.getElementById('adicionarBtn').addEventListener('click', function() {
        const produtoSelect = document.getElementById('produtoSelect');
        const loteSelect = document.getElementById('lote_id');
        const loteSelectName = document.getElementById('lote_name');
        const quantidade = document.getElementById('quantidadeInput').value;
        const quantidade_actual = document.getElementById('quantidadeActualInput').value;
        const preco = document.getElementById('precoInput').value;
        const preco_venda = document.getElementById('precoInputVenda').value;

        const produtoId = produtoSelect.value;
        const loteId = loteSelect.value;
        const loteNome = loteSelectName.value;

        const produtoNome = produtoSelect.options[produtoSelect.selectedIndex].dataset.nome;
        // const loteNome = loteSelect.options[loteSelect.selectedIndex].dataset.nome;

        if (!produtoId || !quantidade) {
            alert('Preencha todos os campos!');
            return;
        }

        if (quantidade == 0) {
            alert('A quantidade não pode ser igual a Zero (0)!');
            return;
        }

        produtosTemp.push({
            produto_id: produtoId
            , nome: produtoNome
            , lote_id: loteId
            , lote: loteNome
            , quantidade
            , quantidade_actual
            , preco
            , preco_venda
        });

        renderTabela();
        limparCampos();
    });

    function renderTabela() {
        let total_final = 0;
        const tbody = document.querySelector('#tabelaProdutos tbody');
        tbody.innerHTML = '';

        produtosTemp.forEach((item, index) => {
            let total = item.preco * item.quantidade;
            total_final += total;
            tbody.innerHTML += `
                <tr>
                    <td>${item.nome}</td>
                    <td>${item.lote}</td>
                    <td>${item.quantidade}</td>
                    <td>${item.quantidade_actual}</td>
                    <td>${formatarKwanza(item.preco)}</td>
                    <td>${formatarKwanza(item.preco_venda)}</td>
                    <td>${formatarKwanza(total)}</td>
                    <td><button type="button" class="btn btn-light-danger btn-sm" onclick="removerItem(${index})">Remover</button></td>
                </tr>
            `;
        });

        tbody.innerHTML += `
            <tr>
                <td colspan="6" class="text-end"><b>Total</b></td>
                <td>${formatarKwanza(total_final)}</td>
                <td></td>
            </tr>
        `;

        document.getElementById('itensInput').value = JSON.stringify(produtosTemp);
    }

    function formatarKwanza(valor) {
        return Number(valor).toLocaleString('pt-AO', {
            style: 'currency'
            , currency: 'AOA'
        });
    }

    function removerItem(index) {
        produtosTemp.splice(index, 1);
        renderTabela();
    }

    function limparCampos() {
        document.getElementById('produtoSelect').value = '';
        document.getElementById('lote_id').value = '';
        document.getElementById('lote_name').value = '';
        document.getElementById('quantidadeInput').value = '';
        document.getElementById('quantidadeActualInput').value = '';
        document.getElementById('precoInput').value = '';
        document.getElementById('precoInputVenda').value = '';
    }

    $(document).ready(function() {
        $('form').on('submit', function(e) {
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

                    Swal.fire({
                        title: `${response.message}`
                        , html: `<p><strong>Código:</strong> ${response.registro.numero}</p>Deseja Imprimir Um Documento?`
                        , icon: 'question'
                        , showCancelButton: true
                        , confirmButtonText: 'Sim, desejo imprimir'
                        , cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const url = `{{ route('pdf-registro-movimentos-estoque', ':code') }}`.replace(':code', response.registro.id);
                            window.location.href = url;
                        } else {
                            showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');
                            window.location.reload();
                        }
                    });

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


    $("#produtoSelect").on('change', function() {
        let id = $(this).val();

        $.ajax({
            url: `../../carregar-lotes/${id}`
            , type: 'GET'
            , success: function(data) {
                document.getElementById('precoInput').value = data.produto.preco_custo ? data.produto.preco_custo : 0;
                document.getElementById('precoInputVenda').value = data.produto.preco_venda;
                document.getElementById('quantidadeActualInput').value = data.quantidade_actual;
                document.getElementById('lote_id').value = data.data.id;
                document.getElementById('lote_name').value = `${data.data.lote} - ${data.data.codigo_barra}`;

                //$("#lote_id").html(""); // Limpa o conteúdo atual
                //$("#lote_id").html(data.data); // Insere os novos dados recebidos
            }
            , error: function(xhr, status, error) {
                console.error("Erro ao carregar os lotes:", error);
                alert("Não foi possível carregar os lotes. Tente novamente.");
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const operacaoSelect = document.getElementById("operacao");
        const tipoDocumentoSelect = document.getElementById("tipo_documento");
        const entradasGroup = document.querySelector("#entradas");
        const saidasGroup = document.querySelector("#saidas");

        function updateTipoDocumentoOptions() {
            const operacao = operacaoSelect.value;

            if (operacao === "Entrada de Stock") {
                entradasGroup.disabled = false;
                saidasGroup.disabled = true;
            } else if (operacao === "Saída de Stock") {
                entradasGroup.disabled = true;
                saidasGroup.disabled = false;
            }

            // Remove all options
            const allOptions = tipoDocumentoSelect.querySelectorAll("option");
            allOptions.forEach(option => option.hidden = true);

            // Enable and show only the relevant group
            const visibleGroup = operacao === "Entrada de Stock" ? entradasGroup : saidasGroup;
            const visibleOptions = visibleGroup.querySelectorAll("option");
            visibleOptions.forEach(option => option.hidden = false);

            // Set first visible option as selected
            tipoDocumentoSelect.value = visibleOptions[0].value;

            // If using Select2, trigger update
            if ($(tipoDocumentoSelect).hasClass("select2")) {
                $(tipoDocumentoSelect).trigger('change');
            }
        }

        // Inicializar
        updateTipoDocumentoOptions();

        // Atualizar quando mudar
        operacaoSelect.addEventListener("change", updateTipoDocumentoOptions);
    });

    $(document).ready(function() {
        // Inicializa Select2
        $('#produtoSelect').select2();

        // Captura tecla F2 globalmente
        $(document).on('keydown', function(e) {
            if (e.key === "F2") {
                e.preventDefault();

                // Abre o Select2 do produto
                $('#produtoSelect').select2('open');
            }
        });
    });


    $(document).on("keydown", "#quantidadeInput", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            return;
        }
    });

</script>
@endsection
