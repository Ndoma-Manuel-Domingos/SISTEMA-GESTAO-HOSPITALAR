@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Liquidar Factura - {{ $factura->factura }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            @if ($factura->status2 == 'nao concluido' && $factura->status == false)
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <table style="border: 1px solid #fcfcfc;width: 100%;">
                                <thead>
                                    <tr>
                                        <td style="border: 1px solid #fcfcfc;padding: 5px">
                                            <h6><strong>{{ number_format($factura->valor_pago ?? '0', 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</strong></h6>
                                            <p>Valor Pagor</p>
                                        </td style="border: 1px solid #fcfcfc;padding: 5px">
                                        <td style="border: 1px solid #fcfcfc;padding: 5px">Nº Factura <br> <a href="{{ route('fornecedores-facturas-encomendas.show', $factura->id) }}">{{ $factura->factura ?? '--' }}</a></td>
                                        <td style="border: 1px solid #fcfcfc;padding: 5px">
                                            Valor Factura <br>
                                            {{ number_format($factura->valor_factura ?? '0', 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}
                                        </td>
                                        <td style="border: 1px solid #fcfcfc;padding: 5px">
                                            Data Factura <br>
                                            {{ $factura->data_factura ?? '--' }}
                                        </td>
                                        <td style="border: 1px solid #fcfcfc;padding: 5px">
                                            Vencimento<br>
                                            {{ $factura->data_vencimento ?? '--' }}
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <form action="{{ route('encomenda-liquidar-factura-compra-store') }}" method="post" class="">
                            <div class="card-body">
                                @csrf
                                <div class="card-body row">
                                    <div class="col-12 col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="numero" class="form-label text-right">Valor a Liquidar:</label>
                                            <input type="text" class="form-control" id="valor_liquidar" name="valor_liquidar" value="{{ $factura->valor_divida ?? old('valor_liquidar') }}" placeholder="Valor a Liquidar:">
                                            <p class="text-light-danger col-sm-3">
                                                @error('valor_liquidar')
                                                {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>

                                    <input type="hidden" name="factura_id" value="{{ $factura->id }}">
                                    <input type="hidden" id="valor_total_pagar" name="valor_total_pagar" value="{{ $factura->valor_pago }}">
                                    <input type="hidden" id="valor_total_factura" name="valor_total_factura" value="{{ $factura->valor_factura }}">

                                    <div class="col-12 col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="numero" class="form-label text-right">Data de Pagamento</label>
                                            <input type="date" class="form-control" id="data_pagamento" name="data_pagamento" value="{{ date('Y-m-d') ?? old('data_pagamento') }}" placeholder="Data factura:">
                                            <p class="text-light-danger col-sm-3">
                                                @error('data_pagamento')
                                                {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="dispesa_id" class="form-label text-right">Tipo de Dispesa</label>
                                            <select class="form-control" id="dispesa_id" name="dispesa_id">
                                                @foreach ($dispesas as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-light-danger col-sm-3">
                                                @error('dispesa_id')
                                                {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="forma_pagamento_id" class="form-label text-right">Forma de Pagamento</label>
                                            <select class="form-control" id="forma_pagamento_id" name="forma_pagamento_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                <option value="NU">NUMERÁRIO</option>
                                                <option value="MB">MULTICAIXA</option>
                                            </select>
                                            <p class="text-light-danger col-sm-3">
                                                @error('forma_pagamento_id')
                                                {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3" id="form_caixas" style="display: none">
                                        <div class="form-group mb-3">
                                            <label for="caixa_id" class="form-label text-right">Caixas</label>
                                            <select class="form-control" id="caixa_id" name="caixa_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($caixas as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-light-danger col-sm-3">
                                                @error('caixa_id')
                                                {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3" id="form_bancos" style="display: none">
                                        <div class="form-group mb-3">
                                            <label for="banco_id" class="form-label text-right">Contas Bancárias</label>
                                            <select class="form-control" id="banco_id" name="banco_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($bancos as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-light-danger col-sm-3">
                                                @error('banco_id')
                                                {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="observacao" class="form-label text-right">{{ __('messages.observacao') }}:</label>
                                            <input type="text" class="form-control" id="observacao" name="observacao" value="{{ old('observacao') }}" placeholder="{{ __('messages.observacao') }} ">
                                            <p class="text-light-danger col-sm-3">
                                                @error('observacao')
                                                {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
            @else
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <p>Não existem Facturas para liquidação. </p>
                            <p>Adicione Facturas relacionadas com esta encomenda para posteriomente liquida-las.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    const forma_pagamento_id = document.getElementById('forma_pagamento_id');

    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');


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

    const valor_total_pagar = document.getElementById('valor_total_pagar');
    const valor_total_factura = document.getElementById('valor_total_factura');

    document.getElementById("valor_liquidar").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número
        let numericValue = parseFloat(value) / 100; // Ajusta casas decimais

        // Soma com o que já foi pago
        const totalPago = parseFloat(valor_total_pagar.value);
        const totalFatura = parseFloat(valor_total_factura.value);


        if (totalPago + numericValue > totalFatura) {
            // Se excedeu, traz de volta para o máximo restante
            numericValue = totalFatura - totalPago;

            // Apenas se ainda for maior que 0
            if (numericValue < 0) {
                numericValue = 0;
            }
        }

        // Formata para exibição no padrão brasileiro (10.000,50)
        e.target.value = numericValue.toLocaleString("pt-BR", {
            minimumFractionDigits: 2
        });
    });


    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            // formatar valores            
            let input = document.getElementById("valor_liquidar");
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

                    //window.location.reload();

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
