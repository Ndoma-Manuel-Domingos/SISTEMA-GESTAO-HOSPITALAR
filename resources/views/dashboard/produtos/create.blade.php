@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('produtos.index') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('produtos.store') }}" method="post" class="" id="formProdutoValidate" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="nome" class="form-label">{{ __('messages.designacao') }}<span class="text-light-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Informe Produto">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="codigo_barra" class="form-label">{{ __('messages.codigo_barras') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="codigo_barra" name="codigo_barra" value="{{ old('codigo_barra') ?? time() }}" placeholder="Informe Codigo Barra">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="descricao" class="form-label"> {{ __('messages.descricao') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="descricao" name="descricao" value="{{ old('descricao') }}" placeholder="Descrição">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="image" class="form-label">Imagem <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="imagem" id="image" value="{{ old('imagem') }}" placeholder="Imagem">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="variacao_id" class="form-label">{{ __('messages.variacoes') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="variacao_id" id="variacao_id">
                                            @foreach ($empresa->variacoes as $variacao)
                                            <option value="{{ $variacao->id }}" {{ $variacao->nome == '-- Sem Variação --' ? 'selected' : '' }}>
                                                {{ $variacao->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="categoria_id" class="form-label">{{ __('messages.categoria') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="categoria_id" id="categoria_id">
                                            @foreach ($empresa->categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{ $categoria->categoria == '-- Sem Categoria --' ? 'selected' : '' }}>
                                                {{ $categoria->categoria }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="marca_id" class="form-label">{{ __('messages.marcas') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="marca_id" id="marca_id">
                                            @foreach ($empresa->marcas as $marca)
                                            <option value="{{ $marca->id }}" {{ $marca->nome == '-- Sem Marca --' ? 'selected' : '' }}>
                                                {{ $marca->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo" class="form-label">{{ __('messages.tipo_produto') }} <span class="text-light-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="tipo" id="tipo">
                                            <option value="P">{{ __('messages.produtos') }}</option>
                                            <option value="S">{{ __('messages.servico') }}</option>
                                            <option value="O">Outro (portes, adiantamentos, etc.)</option>
                                            <option value="I">Imposto (excepto IVA e IS) ou Encargo Parafiscal</option>
                                            <option value="E">Imposto Especial de Consumo (IABA, ISP e IT)
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="unidade" class="form-label">{{ __('messages.unidade') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="unidade" id="unidade">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($unidades as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->sigla == 'un' ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="peso" class="form-label">{{ __('messages.peso') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="peso" name="peso" value="{{ old('peso') ?? 0 }}" placeholder="Informe o peso">
                                    </div>
                                </div>

                                @if ($empresa_logada->empresa->tipo_entidade->sigla !== 'CFOR')
                                <div class="col-12 col-md-3">
                                    <label for="imposto" class="form-label">{{ __('messages.imposto') }}<span class="text-light-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="imposto" id="imposto" required>
                                            @foreach ($impostos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $empresa_logada->empresa->imposto_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="motivo_isencao" class="form-label">{{ __('messages.motivo_isencao') }} <span class="text-light-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="motivo_isencao" id="motivo_isencao">
                                            @foreach ($motivos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $empresa_logada->empresa->motivo_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <div class="col-12 col-md-3">
                                    <label for="preco_custo" class="form-label">{{ __('messages.preco_custo') }} <span class="text-light-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" required id="preco_custo" name="preco_custo" value="{{ old('preco_custo') ?? 0 }}" placeholder="{{ __('messages.preco_custo') }}">
                                    </div>
                                </div>

                                @if ($empresa_logada->empresa->tipo_entidade->sigla !== 'CFOR')

                                <div class="col-12 col-md-3">
                                    <label for="margem" class="form-label">Margem <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="margem" name="margem" value="{{ old('margem') ?? 0 }}" placeholder="Margem">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="preco_venda" class="form-label">{{ __('messages.preco_venda') }} <span class="text-light-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="preco_venda" name="preco_venda" value="{{ old('preco_venda') }}" placeholder="Preço de Venda do Produto">
                                    </div>
                                    <input type="hidden" name="preco_venda" id="preco_venda_guardado" value="" disabled>
                                </div>

                                @endif

                                <div class="col-12 col-md-3">
                                    <label for="controlo_stock" class="form-label">{{ __('messages.controle_stock') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="controlo_stock" id="controlo_stock">
                                            <option value="Sim"> {{ __('messages.sim') }} </option>
                                            <option value="Não"> {{ __('messages.nao') }} </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo_stock" class="form-label">{{ __('messages.tipo_stock') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="tipo_stock" id="tipo_stock">
                                            <optgroup label="Mercadorias">
                                                <option value="M">M - Mercadorias</option>
                                            </optgroup>
                                            <optgroup label="Matérias-primas, subsidiárias e de consumo">
                                                <option value="P">P - Matérias primas</option>
                                                <option value="P1">P - Matérias Subsidiárias</option>
                                                <option value="P2">P - Matérias primas de consumo</option>
                                            </optgroup>
                                            <optgroup label="Produtos acabados e intermédios">
                                                <option value="A">A - Produtos acabados</option>
                                                <option value="A1">A - Produtos intermédios</option>
                                            </optgroup>
                                            <optgroup label="Sub-produtos, desperdícios, resíduos e refugos">
                                                <option value="S">S - Subprodutos, desperdícios refugos</option>
                                                <option value="S1">S - Desperdícios refugos</option>
                                            </optgroup>
                                            <optgroup label="Produtos e trabalhos em curso">
                                                <option value="T">T - Produtos e trabalhos em curso</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="disponibilidade" class="form-label">{{ __('messages.lojas') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" id="disponibilidade" name="disponibilidade">
                                            @if ($lojas)
                                            <option value="">{{ __('messages.todos') }} </option>
                                            @foreach ($lojas as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="status" class="form-label">{{ __('messages.estados') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="status" id="status">
                                            <option value="activo">{{ __('messages.activo') }} </option>
                                            <option value="desactivo">{{ __('messages.desactivo') }} </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="quantidade_inicial_stock" class="form-label">{{ __('messages.quantidade') }} <span class="text-light-secondary">(opcional)</span></label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" value="0" id="quantidade_inicial_stock" name="quantidade_inicial_stock" value="0" placeholder="Preço">
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar produtos'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
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
    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona todos os campos de entrada na página
        const inputs = document.querySelectorAll('input');

        // Itera sobre cada campo de entrada
        inputs.forEach(input => {
            // Garante que o campo esteja focado quando necessário (opcional)
            input.addEventListener('focus', function() {
                console.log(`Campo ${input.name || input.id} está focado.`);
            });

            // Adiciona evento de keydown para bloquear atalhos específicos
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || (e.ctrlKey && e.key === 'j')) {
                    e.preventDefault(); // Impede o comportamento padrão
                    console.log(`Ação bloqueada no campo ${input.name || input.id}.`);
                }
            });
        });
    });

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

        function valorImposto() {
            var elem = $("#imposto").val();
            if (elem == '1') {
                return 0;
            } else if (elem == '2') {
                return 2;
            } else if (elem == '3') {
                return 5;
            } else if (elem == '4') {
                return 7;
            } else if (elem == '5') {
                return 14;
            } else {
                return 14;
            }
        }

        function calcularPreco(imposto) {
            if ($("#margem").val() > 0) {
                var percentagem = parseInt($("#margem").val()) / 100;

                $("#preco_venda").val(parseInt($("#preco_custo").val()) * (1 + percentagem));
                $("#preco_venda_guardado").val(parseInt($("#preco_custo").val()) * (1 + percentagem));
            }
            $("#preco_venda").val($("#preco_custo").val());
            $("#preco_venda_guardado").val($("#preco_custo").val());

            $("#preco_guardado").val($("#preco_custo").val());
            $("#preco").val($("#preco_custo").val());

        }

        function calcularMargem(imposto) {
            if (imposto == 0) {

                $("#prec  o_venda").val($("#preco_custo").val());
                $("#preco_venda_guardado").val($("#preco_custo").val());

                $("#preco").val($("#preco_custo").val());
                $("#preco_guardado").val($("#preco_custo").val());

                if ($("#margem").val() > 0 && $("#margem").val() < 101) {
                    var percentagem = parseInt($("#margem").val()) / 100;

                    $("#preco").val(parseInt($("#preco_custo").val()) * (1 + percentagem));
                    $("#preco_guardado").val(parseInt($("#preco_custo").val()) * (1 + percentagem));

                    $("#preco_venda").val(parseInt($("#preco_custo").val()) * (1 + percentagem));
                    $("#preco_venda_guardado").val(parseInt($("#preco_custo").val()) * (1 + percentagem));

                } else {
                    $("#margem").val(0)

                    $("#preco_venda").val($("#preco_custo").val());
                    $("#preco_venda_guardado").val($("#preco_custo").val());

                    $("#preco").val($("#preco_custo").val());
                    $("#preco_guardado").val($("#preco_custo").val());
                }

            } else {
                /******************/
                // recuperar preco custo
                var precoCusto = parseInt($("#preco_custo").val());
                var resultPrecoVenda = precoCusto + (precoCusto * (imposto / 100));
                /******************/
                // actualizar preco venda
                var actualizarPrecoVenda = resultPrecoVenda + (resultPrecoVenda * (parseInt($("#margem")
                    .val()) / 100));
                $("#preco_venda").val(actualizarPrecoVenda);
                $("#preco_venda_guardado").val(actualizarPrecoVenda);

                // actualizar preco do produto
                var percentagem = parseInt($("#margem").val()) / 100;
                $("#preco").val(parseInt($("#preco_custo").val()) * (1 + percentagem));
                $("#preco_guardado").val(parseInt($("#preco_custo").val()) * (1 + percentagem));
            }

        }

        $("#preco_custo").on('input', function() {
            calcularPreco(valorImposto());
        });

        $("#margem").on('input', function() {
            calcularMargem(valorImposto());
        })

    });

</script>
@endsection
