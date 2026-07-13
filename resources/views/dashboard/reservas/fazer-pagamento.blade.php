@extends('layouts.app')

@section('content')
<style>
    .fc-toolbar h2 {
        text-transform: capitalize;
    }

    .fc-day-header {
        text-transform: capitalize;
    }

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pagamento da Reserva: {{ $reserva->codigo_referencia }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('reservas.index') }}">{{ __('messages.voltar') }}</a></li>
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
                    <form action="{{ route('reservas-fazer-pagamento-store') }}" method="post">
                        @csrf
                        @method('post')
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-3">
                                        <label for="cliente_id" class="form-label"> {{ __('messages.clientes') }} </label>
                                        <div class="input-group mb-3">
                                            <select type="text" disabled class="form-control select2 @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id">
                                                @foreach ($clientes as $item)
                                                <option value="{{ $item->id ?? "" }}" {{ $reserva->cliente_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" id="reserva_id" name="reserva_id" value="{{ $reserva->id }}" class="form-control" readonly>

                                    <div class="col-12 col-md-3">
                                        <label for="data_pagamento" class="form-label">Data Pagamento</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control  @error('data_pagamento') is-invalid @enderror" name="data_pagamento" id="data_pagamento" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="actualizar_check_in" class="form-label">Fazer Check-In?</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control @error('actualizar_check_in') is-invalid @enderror" id="actualizar_check_in" name="actualizar_check_in">
                                                <option value="sim" selected> {{ __('messages.sim') }} </option>
                                                <option value="nao"> {{ __('messages.nao') }} </option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-3" id="form_forma_pagamento">
                                        <label for="forma_pagamento_id" class="form-label text-right">Forma de
                                            Pagamento</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control" id="forma_pagamento_id" name="forma_pagamento_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($forma_pagamentos as $item)
                                                <option value="{{ $item->tipo }}">{{ $item->titulo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3" id="form_caixas" style="display: none">
                                        <label for="caixa_id" class="form-label text-right">Caixas</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control" id="caixa_id" name="caixa_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($caixas as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->conta }} -
                                                    {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3" id="form_bancos" style="display: none">
                                        <label for="banco_id" class="form-label text-right">Contas Bancárias</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control" id="banco_id" name="banco_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($bancos as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->conta }} -
                                                    {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="receita_id" class="form-label text-right">Receita</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control" id="receita_id" name="receita_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($receitas as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="valor_pago" class="form-label">Valor Pago</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control  @error('valor_pago') is-invalid @enderror" name="valor_pago" id="valor_pago" value="{{ number_format($reserva->valor_pago, 2, ',', '.')  ?? old('preco_unitario') }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="valor_a_pagar" class="form-label">Valor A Pagar</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control  @error('valor_a_pagar') is-invalid @enderror" name="valor_a_pagar" id="valor_a_pagar" value="{{ number_format($reserva->valor_divida, 2, ',', '.') ?? old('valor_a_pagar') }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="total_factura" class="form-label">{{ __('messages.total') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control  @error('total_factura') is-invalid @enderror" name="total_factura" id="total_factura" value="{{ number_format($reserva->valor_total, 2, ',', '.') ?? old('total_factura') }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="valor_entregue" class="form-label">Valor Entregue</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control  @error('valor_entregue') is-invalid @enderror" name="valor_entregue" id="valor_entregue" value="{{ number_format($reserva->valor_divida, 2, ',', '.') ?? (old('valor_entregue') ?? 0) }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <label for="observacao" class="form-label">Observação (Opcional)</label>
                                        <div class="input-group mb-3">
                                            <textarea name="observacao" class="form-control" id="observacao" placeholder="Informe uma Observação" cols="30" rows="2">{{ $reserva->observacao ?? (old('observacao') ?? '') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar reserva'))
                                <button type="submit" class="btn btn-light-primary">Confirmar o Pagamento</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection


@section('scripts')
<script>
    const form_forma_pagamento = document.getElementById('form_forma_pagamento');
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

    document.getElementById("valor_entregue").addEventListener("input", function(e) {
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

            let valor_entregue = document.getElementById("valor_entregue");
            let rawValue_entregue = valor_entregue.value.replace(/\./g, "").replace(",", ".");
            valor_entregue.value = parseFloat(rawValue_entregue).toFixed(2);

            let total_factura = document.getElementById("total_factura");
            let rawValue_factura = total_factura.value.replace(/\./g, "").replace(",", ".");
            total_factura.value = parseFloat(rawValue_factura).toFixed(2); // Garantir 2 casas decimais

            let valor_pago = document.getElementById("valor_pago");
            let rawValue_valor_pago = valor_pago.value.replace(/\./g, "").replace(",", ".");
            valor_pago.value = parseFloat(rawValue_valor_pago).toFixed(2); // Garantir 2 casas decimais

            let valor_a_pagar = document.getElementById("valor_a_pagar");
            let rawValue_a_pagar = valor_a_pagar.value.replace(/\./g, "").replace(",", ".");
            valor_a_pagar.value = parseFloat(rawValue_a_pagar).toFixed(2); // Garantir 2 casas decimais


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
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');

                    if (response.factura.factura == "FR") {
                        // Gerar a URL usando o Laravel Blade
                        const url = `{{ route('factura-recibo', ':code') }}`.replace(
                            ':code', response.factura.code);
                        // Redirecionar
                        window.location.href = url;
                    }

                    /*window.location.reload();*/
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
            , });
        });
    });

</script>
@endsection
