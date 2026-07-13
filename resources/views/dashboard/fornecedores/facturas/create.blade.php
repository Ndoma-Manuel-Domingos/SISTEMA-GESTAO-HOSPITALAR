@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Criar Factura de Compra - Encomenda - {{ $encomenda->factura }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores-facturas-encomendas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active"> {{ __('messages.fornecedores') }} </li>
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
                        <form action="{{ route('fornecedores-facturas-encomendas.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="factura" class="col-form-label text-right">Nº Factura:</label>
                                        <input type="text" class="form-control" id="factura" name="factura" value="{{ $totalEncomendas }}" placeholder="Número da Factura:">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="valor_total_factura_original" class="col-form-label text-right">Valor Total da Factura:</label>
                                        <input type="text" disabled class="form-control" id="valor_total_factura" name="valor_total_factura" value="{{ number_format($encomenda->total ?? 0, 2, ',', '.') }}" placeholder="Valor Total da factura">
                                        <input type="hidden" class="form-control" id="valor_total_factura_original" name="valor_total_factura_original" value="{{ $encomenda->total }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="desconto_" class="col-form-label text-right">{{ __('messages.desconto') }}:</label>
                                        <input disabled type="text" class="form-control" id="desconto_" name="desconto_" value="{{ number_format($encomenda->desconto_valor ?? old('total_a_pagar'), 2, ',', '.') }}" placeholder="Desconto:">
                                        <input type="hidden" value="{{ $encomenda->desconto_valor }}" name="desconto">
                                        <input type="hidden" value="{{ $encomenda->desconto }}" name="desconto_imposto">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="valor_a_pagar" class="col-form-label text-right">Valor a Pagar:</label>
                                        <input type="text" class="form-control" id="valor_a_pagar" name="valor_a_pagar" value="{{ number_format(old('total_a_pagar') ?? $encomenda->total_a_pagar, 2, ',', '.') }}" placeholder="Valor da Factura:">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="data_factura" class="col-form-label text-right">Data Factura</label>
                                        <input type="date" class="form-control" id="data_factura" name="data_factura" value="{{ date('Y-m-d') ?? old('data_factura') ?? "" }}" placeholder="Data factura:">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="data_vencimento" class="col-form-label text-right">Data Vencimento:</label>
                                        <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="{{ date('Y-m-d') ?? old('data_vencimento') ?? "" }}" placeholder="Data Vencimento:">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="observacao" class="col-form-label text-right">{{ __('messages.observacao') }}:</label>
                                        <input type="text" class="form-control" id="observacao" name="observacao" value="{{ old('observacao') ?? ""}}" placeholder="{{ __('messages.observacao') }} ">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="marcar_como" class="col-form-label text-right">Marcar como paga :</label>
                                        <select class="form-control" id="marcar_como" name="marcar_como">
                                            <option value="nao"> {{ __('messages.nao') }} </option>
                                            <option value="sim"> {{ __('messages.sim') }} </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3" id="form_forma_pagamento" style="display: none">
                                    <div class="form-group mb-3">
                                        <label for="forma_pagamento_id" class="col-form-label text-right">Forma de Pagamento</label>
                                        <select class="form-control" id="forma_pagamento_id" name="forma_pagamento_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="NU">NUMERÁRIO</option>
                                            <option value="MB">MULTICAIXA</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3" id="form_caixas" style="display: none">
                                    <div class="form-group mb-3">
                                        <label for="caixa_id" class="col-form-label text-right">Caixas</label>
                                        <select class="form-control" id="caixa_id" name="caixa_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($caixas as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3" id="form_bancos" style="display: none">
                                    <div class="form-group mb-3">
                                        <label for="banco_id" class="col-form-label text-right">Conta Bancária</label>
                                        <select class="form-control" id="banco_id" name="banco_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($bancos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3" id="form_dispesas" style="display: none">
                                    <div class="form-group mb-3">
                                        <label for="dispesa_id" class="col-form-label text-right">Tipo de Dispesa</label>
                                        <select class="form-control" id="dispesa_id" name="dispesa_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($dispesas as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="encomenda_id" value="{{ $encomenda->id }}">

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
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    const select = document.getElementById('marcar_como');
    const forma_pagamento_id = document.getElementById('forma_pagamento_id');

    const form_forma_pagamento = document.getElementById('form_forma_pagamento');
    const form_dispesas = document.getElementById('form_dispesas');
    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');

    select.addEventListener('change', function() {
        if (this.value === 'sim') {
            form_forma_pagamento.style.display = 'block';
            form_dispesas.style.display = 'block';
        } else {
            form_forma_pagamento.style.display = 'none';
            form_dispesas.style.display = 'none';
        }
    });

    forma_pagamento_id.addEventListener('change', function() {
        if (this.value === 'NU') {
            form_caixas.style.display = 'block';
            form_bancos.style.display = 'none';
        } else if (this.value === 'MB') {
            form_bancos.style.display = 'block';
            form_caixas.style.display = 'none';
        } else {
            form_caixas.style.display = 'none';
            form_bancos.style.display = 'none';
        }
    });

    document.getElementById("valor_a_pagar").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número
        let numericValue = parseFloat(value) / 100; // Ajusta casas decimais

        // Formata para exibição no padrão brasileiro (10.000,50)
        e.target.value = numericValue.toLocaleString("pt-BR", {
            minimumFractionDigits: 2
        });
    });


    $(document).ready(function() {
        $('form').on('submit', function(e) {

            e.preventDefault(); // Impede o envio tradicional do formulário

            // formatar valores            
            let input = document.getElementById("valor_a_pagar");
            // Converter de "10.000,50" para "10000.50"
            let rawValue = input.value.replace(/\./g, "").replace(",", ".");
            input.value = parseFloat(rawValue).toFixed(2); // Garantir 2 casas decimais

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

                    window.location.href = response.redirect;
                    // window.location.reload();

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

</script>
@endsection
