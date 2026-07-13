@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Nova produção</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('producao.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Tipo de Produto</label>
                                    <select id="bread_type_id" class="form-control select2">
                                        <option value="">Selecionar</option>
                                        @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}" data-peso="{{ $produto->peso }}">{{ $produto->nome }} - {{ $produto->peso }}{{ $produto->unidade->sigla ?? "" }}</option>
                                        @endforeach
                                    </select>
                                </div>
        
                                <div class="col-md-4 mb-3">
                                    <label>Receita</label>
                                    <select id="recipe_id" class="form-control">
                                        <option value="">Selecione a receita</option>
                                    </select>
                                </div>
        
                                <div class="col-md-4 mb-3">
                                    <label>Quantidade Desejada</label>
                                    <input type="number" id="desired_quantity" class="form-control" placeholder="Ex: 1000">
                                </div>
                            </div>
        
                            <div class="card mt-4">
                                <div class="card-header bg-light-primary">
                                    <h5 class="mb-0">Receita Base</h5>
                                </div>
        
                                <div class="card-body p-0">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Matéria Prima</th>
                                                <th>Quantidade</th>
                                                <th>Unidade</th>
                                                <th>Stock Atual</th>
                                            </tr>
                                        </thead>
                                        <tbody id="recipeItemsTable">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
        
                            <div class="text-center mt-4">
                                <button type="button" id="btnCalcular" class="btn btn-light-success btn-lg px-5">
                                    <i class="fas fa-calculator"></i>
                                    Simular Produção
                                </button>
                            </div>
        
                            <div id="resultado" class="mt-5"></div>
        
                        </div>
        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    let productionData = {};

    $('#bread_type_id').change(function() {
        let breadTypeId = $(this).val();
        $('#recipe_id').html(`
        <option>
            Carregando...
        </option>
    `);

    $.get('/produto/' + breadTypeId + '/receitas', function(recipes) {
            let html = `<option value="">Selecionar</option>`;
            recipes.forEach(function(recipe) {
                html += `
                    <option value="${recipe.id}" data-yield="${recipe.rendimento_base}" data-loss="${recipe.porcentagem_perda}" data-peso="${recipe.peso}">${recipe.nome} - Base: ${recipe.rendimento_base} pães </option>
                `;
            });
            $('#recipe_id').html(html);
        });
    });


    $('#btnCalcular').click(function() {
    
        let recipeYield = parseFloat($('#recipe_id').find(':selected').data('yield'));
        let lossPercent = parseFloat($('#recipe_id').find(':selected').data('loss')) || 0;
        let desiredQuantity = parseFloat($('#desired_quantity').val());
        let breadWeight = parseFloat($('#recipe_id').find(':selected').data('peso')) || 0;
    
        if (!recipeYield) {
            Swal.fire('Erro', 'Selecione a receita', 'error');
            return;
        }
    
        if (!desiredQuantity || desiredQuantity <= 0) {
            Swal.fire('Erro', 'Informe a quantidade desejada', 'error');
            return;
        }
    
        if (!breadWeight || breadWeight <= 0) {
            Swal.fire('Erro', 'Peso do pão inválido', 'error');
            return;
        }
    
        // ==================================================
        // FATOR BASE
        // ==================================================
    
        let factor = desiredQuantity / recipeYield;
    
        // ==================================================
        // REGRA DA PERDA
        // ==================================================
        //
        // Se produzir exatamente o rendimento base
        // não existe perda.
        //
        // Caso contrário a perda é compensada
        // aumentando os ingredientes.
        //
        // ==================================================
    
        let adjustedFactor = factor;
        let applyLoss = desiredQuantity !== recipeYield;
    
        if (applyLoss && lossPercent > 0) {
            adjustedFactor = factor / (1 - (lossPercent / 100));
        }
    
        let totalMass = 0;
        let stockError = false;
        let ingredientsHtml = '';
        let productionItems = [];
    
        $('#recipeItemsTable tr').each(function() {
    
            let ingredientId = $(this).data('id');
            let ingredient = $(this).find('td:eq(0)').text();
    
            let grams = parseFloat($(this).data('grams'));
            let stock = parseFloat($(this).data('stock'));
    
            // Quantidade necessária já compensando perda
            let required = grams * adjustedFactor;
    
            totalMass += required;
    
            let hasStock = stock >= required;
    
            if (!hasStock) {
                stockError = true;
            }
    
            productionItems.push({
                ingredient_id: ingredientId,
                quantity_grams: required
            });
    
            ingredientsHtml += `
                <tr>
                    <td>${ingredient}</td>
                    <td>${(required / 1000).toFixed(2)} kg</td>
                    <td>${(stock / 1000).toFixed(2)} kg</td>
                    <td>
                        ${
                            hasStock
                            ? '<span class="badge badge-light-success">OK</span>'
                            : '<span class="badge badge-light-danger">SEM STOCK</span>'
                        }
                    </td>
                </tr>
            `;
        });
    
        // ==================================================
        // CÁLCULO DA PERDA
        // ==================================================
    
        let lossGrams = 0;
    
        if (applyLoss) {
            lossGrams = totalMass * (lossPercent / 100);
        }
    
        let liquidMass = totalMass - lossGrams;
    
        // ==================================================
        // PRODUÇÃO ESTIMADA
        // ==================================================
    
        let estimatedQuantity = Math.floor(liquidMass / breadWeight);
    
        let difference = estimatedQuantity - desiredQuantity;
    
        // ==================================================
        // DADOS PARA SALVAR
        // ==================================================
    
        productionData = {
            produto_id: $('#bread_type_id').val(),
            receita_id: $('#recipe_id').val(),
            quantidade_desejada: desiredQuantity,
            quantidade_estimada: estimatedQuantity,
            quantidade_diferenca: difference,
            fator_escala: factor,
            fator_ajustado: adjustedFactor,
            perda_gramas: lossGrams,
            massa_total_gramas: totalMass,
            items: productionItems
        };
    
        // ==================================================
        // RESULTADO
        // ==================================================
    
        $('#resultado').html(`
    
            <div class="card shadow">
    
                <div class="card-header bg-light-success">
                    <h4 class="mb-0">Pré-Visualização Produção</h4>
                </div>
    
                <div class="card-body">
    
                    <div class="row text-center">
    
                        <div class="col-md-2">
                            <h5>Receita Base</h5>
                            <h2>${recipeYield}</h2>
                        </div>
    
                        <div class="col-md-2">
                            <h5>Desejado</h5>
                            <h2>${desiredQuantity}</h2>
                        </div>
    
                        <div class="col-md-2">
                            <h5>Estimado</h5>
                            <h2 class="text-success">
                                ${estimatedQuantity}
                            </h2>
                        </div>
    
                        <div class="col-md-2">
                            <h5>Diferença</h5>
                            <h2 class="${
                                difference == 0
                                    ? 'text-success'
                                    : 'text-warning'
                            }">
                                ${difference}
                            </h2>
                        </div>
    
                        <div class="col-md-2">
                            <h5>Perda</h5>
                            <h2 class="text-warning">
                                ${(lossGrams / 1000).toFixed(2)} kg
                            </h2>
                        </div>
    
                        <div class="col-md-2">
                            <h5>Escala</h5>
                            <h2>
                                ${adjustedFactor.toFixed(2)}x
                            </h2>
                        </div>
    
                    </div>
    
                    <hr>
    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Ingrediente</th>
                                <th>Necessário</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${ingredientsHtml}
                        </tbody>
                    </table>
    
                    <div class="text-center mt-4">
    
                        ${
                            stockError
                            ?
                            `
                            <button
                                disabled
                                class="btn btn-danger btn-lg">
                                Stock Insuficiente
                            </button>
                            `
                            :
                            `
                            <button
                                type="button"
                                id="btnConfirmar"
                                class="btn btn-primary btn-lg">
                                <i class="fas fa-check"></i>
                                Confirmar Produção
                            </button>
                            `
                        }
    
                    </div>
    
                </div>
    
            </div>
        `);
    });

    $('#recipe_id').change(function() {
        let recipeId = $(this).val();
        $.get('/receitas/' + recipeId + '/items', function(items) {
            let html = '';
            items.forEach(function(item) {
                html += `
                    <tr
                        data-grams="${item.quantidade_gramas}"
                        data-stock="${item.ingrediente.total_produto_loja_activa}"
                        data-id="${item.ingrediente.id}">
                        <td>${item.ingrediente.nome}</td>
                        <td>${Number(item.quantidade).toFixed(2)}</td>
                        <td>${item.unidade ? item.unidade.sigla : ''}</td>
                        <td>${(item.ingrediente.total_produto_loja_activa / 1000).toFixed(2)} ${item.ingrediente.unidade ? item.ingrediente.unidade.sigla : ''}</td>
                    </tr>
                `;
            });
            $('#recipeItemsTable').html(html);
        });
    });

    $(document).on('click', '#btnConfirmar', function() {

        $.ajax({
            url: "{{ route('producao.store') }}"
            , method: "POST"
            , data: {
                _token: "{{ csrf_token() }}"
                , ...productionData
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();

                showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');
                $('#resultado').html('');
                $('#desired_quantity').val('');
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
    });

</script>

@endsection
