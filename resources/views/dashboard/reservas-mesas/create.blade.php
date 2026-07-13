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
                    <h1 class="m-0"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('reservas-mesas.index') }}">{{ __('messages.voltar') }}</a></li>
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
                    <form action="{{ route('reservas-mesas.store') }}" method="post">
                        @csrf
                        @method('post')
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-4">
                                        <label for="cliente_id" class="form-label"> {{ __('messages.clientes') }} </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('clientes.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" class="form-control select2 @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id">
                                                @foreach ($clientes as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-2">
                                        <label for="data_entrada" class="form-label"> {{ __('messages.data') }} </label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control  @error('data_entrada') is-invalid @enderror" name="data_entrada" id="data_entrada" value="{{ old('data_entrada') ?? date('Y-m-d') }}" placeholder="Informe a quarto">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="hora_entrada" class="form-label">Horas</label>
                                        <div class="input-group mb-3">
                                            <input type="time" class="form-control  @error('hora_entrada') is-invalid @enderror" name="hora_entrada" id="hora_entrada" value="{{ old('hora_entrada') ?? date('H:i') }}" placeholder="Hora da Entrada">
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-2">
                                        <label for="total_mesas" class="form-label">Total de Mesas</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control  @error('total_mesas') is-invalid @enderror" name="total_mesas" id="total_mesas" value="{{ old('total_mesas') ?? 0 }}" placeholder="Informe o total de dias">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="exercicio_id" class="form-label"> {{ __('messages.exercicio') }} </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('exercicios.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" class="form-control select2 @error('exercicio_id') is-invalid @enderror" id="exercicio_id" name="exercicio_id">
                                                @foreach ($exercicios as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="periodo_id" class="form-label"> {{ __('messages.periodo') }} </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('periodos.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" class="form-control @error('periodo_id') is-invalid @enderror" id="periodo_id" name="periodo_id">
                                                @foreach ($periodos as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="marcar_como" class="form-label">Fazer o pagamento</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control @error('marcar_como') is-invalid @enderror" id="marcar_como" name="marcar_como">
                                                <option value="nao"> {{ __('messages.nao') }} </option>
                                                <option value="sim"> {{ __('messages.sim') }} </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2" id="form_forma_pagamento" style="display: none">
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

                                    <div class="col-12 col-md-2" id="form_caixas" style="display: none">
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


                                    <div class="col-12 col-md-2" id="form_bancos" style="display: none">
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

                                    <div class="col-12 col-md-2" id="form_receitas" style="display: none">
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

                                    <div class="col-12 col-md-2">
                                        <label for="preco_unitario" class="form-label">Preço Unitário</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control  @error('preco_unitario') is-invalid @enderror" name="preco_unitario" id="preco_unitario" value="{{ old('preco_unitario') ?? 0 }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="total_factura" class="form-label">Total da Factura</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control  @error('total_factura') is-invalid @enderror" name="total_factura" id="total_factura" value="{{ old('total_factura') ?? 0 }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2" id="form_valor_entregue" style="display: none">
                                        <label for="valor_entregue" class="form-label">Valor Entregue</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="number" class="form-control  @error('valor_entregue') is-invalid @enderror" name="valor_entregue" id="valor_entregue" value="{{ old('valor_entregue') ?? 0 }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="criancas" class="form-label">Tem Criança</label>
                                        <div class="input-group mb-3">
                                            <select type="text" class="form-control select2 @error('criancas') is-invalid @enderror" id="criancas" name="criancas">
                                                <option value="0"> {{ __('messages.nao') }} </option>
                                                <option value="1"> {{ __('messages.sim') }} </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="total_pessoas" class="form-label">Nº de Adultos</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control  @error('total_pessoas') is-invalid @enderror" name="total_pessoas" id="total_pessoas" value="{{ old('total_pessoas') ?? 1 }}" placeholder="Informe o total de dias">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="numero_criancas" class="form-label">Nº de Crianças</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control  @error('numero_criancas') is-invalid @enderror" name="numero_criancas" id="numero_criancas" value="{{ old('numero_criancas') ?? 0 }}" placeholder="Informe o número de crianças">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="produto_id" class="form-label">{{ __('messages.produtos') }}</label>
                                        <div class="input-group mb-3">
                                            <select type="text" class="form-control select2 @error('produto_id') is-invalid @enderror" id="produto_id" name="produto_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($produtos as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="mesa_id" class="form-label">Mesas</label>
                                        <div class="input-group mb-3">
                                            <select type="text" class="form-control select2 @error('mesa_id') is-invalid @enderror" multiple id="mesa_id" name="mesa_id[]">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($mesas as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <label for="observacao" class="form-label">Observação (opcional)</label>
                                        <div class="input-group mb-3">
                                            <textarea name="observacao" rows="3" id="observacao" class="form-control @error('observacao') is-invalid @enderror" placeholder="Podes especificar aqui e reserva...">{{ old('observacao') ?? '' }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar reserva'))
                                <button type="submit" class="btn btn-light-primary">Confirmar a Reserva</button>
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
    const total_factura = document.getElementById('total_factura');
    const totalMesas = document.getElementById('total_mesas');

    const select = document.getElementById('marcar_como');
    const forma_pagamento_id = document.getElementById('forma_pagamento_id');

    const total_pessoas = document.getElementById('total_pessoas');
    const valor_entregue = document.getElementById('valor_entregue');
    const preco_unitario = document.getElementById('preco_unitario');

    const form_forma_pagamento = document.getElementById('form_forma_pagamento');
    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');
    const form_receitas = document.getElementById('form_receitas');
    const form_valor_entregue = document.getElementById('form_valor_entregue');

    $('#produto_id').on('change', function() {
        const produto_id = $(this).val();

        if (produto_id) {
            fetch('/recuperar-produto-por-id/' + produto_id)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        preco_unitario.value = data.preco_venda;
                        total_factura.value = data.preco_venda * Number(totalMesas.value || 1);
                        valor_entregue.value = total_factura.value; // ou algum outro cálculo
                    }
                })
                .catch(error => console.error('Erro ao buscar produto:', error));
        }
    });

    $('#total_mesas').on('input', function() {
        const total_mesas = $(this).val();

        if (total_mesas > 0) {
            total_factura.value = preco_unitario.value * Number(totalMesas.value || 1);
            valor_entregue.value = total_factura.value; // ou algum outro cálculo
        }
    });

    function formatarMoeda(valor) {
        return valor.toLocaleString('pt-AO', {
            style: 'currency'
            , currency: 'AOA' // Kwanza (Angola)
        });
    }

    select.addEventListener('change', function() {
        if (this.value === 'sim') {
            form_forma_pagamento.style.display = 'block';
            form_valor_entregue.style.display = 'block';
            form_receitas.style.display = 'block';
        } else {
            form_forma_pagamento.style.display = 'none';
            form_valor_entregue.style.display = 'none';
            form_receitas.style.display = 'none';
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

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            let marcar_como = $("#marcar_como").val();

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
                    if (response.success) {

                        let text = "";
                        if (marcar_como == "nao") {
                            text = "Deseja imprimir a ficha desta reserva?";
                        } else {
                            text = "Deseja imprimir a factura desta reserva?";
                        }

                        Swal.fire({
                            title: 'Reserva realizado com sucesso!'
                            , text: text
                            , icon: 'success'
                            , showCancelButton: true
                            , confirmButtonText: 'Sim, imprimir'
                            , cancelButtonText: 'Não'
                        }).then((result) => {
                            if (result.isConfirmed) {

                                if (marcar_como == "nao") {
                                    window.open(response.pdf_url, '_blank');
                                } else {
                                    window.open(response.pdf_url_factura, '_blank');
                                }

                                window.location.reload();
                            }
                        });
                    } else {
                        showMessage('Erro!', 'Não foi possível fazer a reserva!', 'error');
                    }
                    // Exibe uma mensagem de sucesso
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
