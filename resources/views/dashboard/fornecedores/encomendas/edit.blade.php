@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-edit"></i> {{ __('messages.editar') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores-encomendas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active"> {{ __('messages.controle') }} </li>
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
                        <form action="{{ route('fornecedores-encomendas.update', $encomenda->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="numero" class="form-label text-right">Nº Encomenda:</label>
                                    <div class="form-group MB-3">
                                        <input type="text" class="form-control" id="numero" name="numero" value="{{ $encomenda->factura }}" placeholder="Número da Encomenda:">
                                    </div>
                                    <input type="hidden" name="encomenda_id" id="encomenda_id" value="{{ $encomenda->id }}">
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="fornecedor_id" class="form-label text-right">{{ __('messages.fornecedores') }}:</label>
                                    <div class="form-group mb-3">
                                        <select class="form-control" id="fornecedor_selecionado" name="fornecedor_selecionado">
                                            @foreach ($fornecedores as $fornecedor)
                                            <option value="{{ $fornecedor->id}}" {{ $fornecedor->id == $encomenda->fornecedor->id ? 'selected': '' }}>{{ $fornecedor->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="loja_id" class="form-label text-right">Loja/Armazém:</label>
                                    <div class="form-group mb-3">
                                        <select class="form-control" id="loja_id" name="loja_id">
                                            @foreach ($lojas as $loja)
                                            <option value="{{ $loja->id}}" {{ $loja->id == $encomenda->loja->id  ? 'selected': '' }}>{{ $loja->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="custo_transporte" class="form-label text-right">Custos de Transporte:</label>
                                    <input type="number" class="form-control" id="custo_transporte" name="custo_transporte" value="{{ $encomenda->custo_transporte ?? old('custo_transporte') }}" placeholder="Custos de Transporte">
                                    <p class="text-light-danger col-sm-3">
                                        @error('custo_transporte')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="custo_manuseamento" class="form-label text-right">Custos de Manuseamento:</label>
                                    <input type="number" class="form-control" id="custo_manuseamento" name="custo_manuseamento" value="{{ $encomenda->custo_manuseamento ?? old('custo_manuseamento') }}" placeholder="Custos de Manuseamento">
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="outros_custos" class="form-label text-right">Outros Custos</label>
                                    <input type="number" class="form-control" id="outros_custos" name="outros_custos" value="{{ $encomenda->outros_custos ?? old('outros_custos') }}" placeholder="Outros Custos direitamente atribuíveis à compra dos bens">
                                    <p class="text-light-danger col-sm-3">
                                        @error('outros_custos')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="data_previsao" class="form-label text-right">Previsão de Entrega:</label>
                                    <div class="form-group mb-3">
                                        <input type="date" class="form-control" id="data_previsao" name="data_previsao" value="{{ $encomenda->previsao_entrega }}" placeholder="">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="observacao" class="form-label text-right">{{ __('messages.observacao') }}:</label>
                                    <div class="form-group mb-3">
                                        <input type="text" class="form-control" id="observacao" name="observacao" value="{{ $encomenda->observacao }}" placeholder="{{ __('messages.observacao') }} ">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="produto" class="form-label text-right">{{ __('messages.produtos') }}:</label>
                                    <div class="form-group mb-3">
                                        <select class="form-control select2" id="produto" name="produto">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($produtos as $item2)
                                            <option value="{{ $item2->id }}">{{ $item2->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($items)
                                <div class="col-12 col-md-12">
                                    <table class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th style="width: 5px"></th>
                                                <th>{{ __('messages.designacao') }}</th>
                                                <th> {{ __('messages.quantidade') }} </th>
                                                <th>Custo</th>
                                                <th>IVA</th>
                                                <th>{{ __('messages.desconto') }}</th>
                                                <th>{{ __('messages.total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $item)
                                            <tr>
                                                <td class="bg-light">
                                                    <a href="{{ route('items-nova-encomenda-remover-sem-fornecedora-ctualizar', $item->id) }}" id="remover_id" class="text-light-danger bg-light-danger p-1 img-circle"><i class="fas fa-close text-white"></i></a>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control produto_id" value="{{ $item->produto->nome ?? '' }}" name="produto_id{{ $item->id ?? "" }}" id="{{ $item->id ?? "" }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control quantidade quantidade{{ $item->id ?? "" }}" value="{{ $item->quantidade ?? 0 }}" data-custo="{{ $item->custo ?? 0 }}" data-total="{{ $item->total }}" name="quantidade{{ $item->id ?? "" }}" id="{{ $item->id ?? "" }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control custo custo{{ $item->id ?? "" }}" value="{{ $item->custo ?? 0 }}" name="custo{{ $item->id ?? "" }}" id="{{ $item->id ?? "" }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control iva" value="{{ $item->iva ?? 0 }}" name="iva{{ $item->id ?? "" }}" id="{{ $item->id ?? "" }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control desonto" value="{{ $item->desconto ?? 0 }}" name="desonto{{ $item->id ?? "" }}" id="{{ $item->id ?? "" }}">
                                                </td>
                                                <td class="totalValor{{ $item->id ?? "" }}" id="{{ $item->id ?? "" }}">
                                                    {{ $item->totalSiva ?? '' }}
                                                </td>
                                                <input type="hidden" name="ids[]" value="{{ $item->id ?? "" }}">
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-success">Actualizar</button>
                            </div>
                        </form>
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
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

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

    $(function() {

        $("#encomenda").on('click', function() {
            var factura = $(this).val();
            carregar_novos_dados(factura, "#carregar_factura_js", "Encomenda");
        });

        function carregar_novos_dados(OqueCarregar, ondeCarregar, text_factura) {
            $(ondeCarregar).html("");
            $(ondeCarregar).html(OqueCarregar);
            $("#text_factura").html("");
            $("#text_factura").html(text_factura);

            $("#botao_submit").html("");
            $("#botao_submit").html("Criar " + text_factura);
        }

        $(".quantidade").on('input', function() {
            var quantidade = parseInt($(this).val());
            var id = $(this).attr('id');

            var total = parseInt($(this).data('total'));
            var custo = parseInt($(this).data('custo'));

            var resultado = quantidade * custo;

            $('.totalValor' + id).html("");
            $('.totalValor' + id).append(resultado);

        });

        $(".custo").on('input', function() {
            var custo = parseInt($(this).val());
            var quantidade = parseInt($('.quantidade').val());

            var id = $(this).attr('id');

            var resultado = quantidade * custo;

            $('.totalValor' + id).html("");
            $('.totalValor' + id).append(resultado);

        });

        $(".desonto").on('input', function() {
            var desconto = parseInt($(this).val());

            var id = $(this).attr('id');
            var quantidade = parseInt($('.quantidade' + id).val());
            var custo = parseInt($('.custo' + id).val());
            var resultado = quantidade * custo;

            if (desconto >= 1 && desconto <= 100) {
                var resultadoDesconto = (resultado) - ((resultado) * (desconto / 100));
                var valorDescontado = (resultado) * (desconto / 100);
                $('.totalValor' + id).html("");
                $('.totalValor' + id).append(resultadoDesconto);
            } else {
                $('.totalValor' + id).html("");
                $('.totalValor' + id).append(resultado);
            }
        });

        $("#produto").on('change', function(e) {
            e.preventDefault();

            // Obter os valores dos campos
            const produtoId = $("#produto").val();
            const encomendaId = $("#encomenda_id").val();

            alert(produtoId)
            alert(encomendaId)
            return

            if (produtoId != "") {
                // Gerar a URL com múltiplos parâmetros
                const url = `{{ route('items-nova-encomenda-sem-fornecedora-editar', [':produto', ':encomenda_id']) }}`
                    .replace(':produto', produtoId)
                    .replace(':encomenda_id', encomendaId);

                // Redirecionar
                window.location.href = url;
            }
        })

    });

</script>
@endsection
