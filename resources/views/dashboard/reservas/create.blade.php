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
                    <form action="{{ route('reservas.store') }}" method="post">
                        @csrf
                        @method('post')
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Complete os detalhes da sua reserva</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-3">
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

                                            <div class="col-12 col-md-3">
                                                <label for="tipo_reserva_id" class="form-label">Tipo de Reserva</label>
                                                <div class="input-group mb-3">
                                                    <select type="text" class="form-control select2 @error('tipo_reserva_id') is-invalid @enderror" id="tipo_reserva_id" name="tipo_reserva_id">
                                                        @foreach ($tipo_reservas as $item)
                                                        <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <label for="data_entrada" class="form-label">Data de Entrada</label>
                                                <div class="input-group mb-3">
                                                    <input type="date" class="form-control  @error('data_entrada') is-invalid @enderror" name="data_entrada" id="data_entrada" value="{{ old('data_entrada') ?? date('Y-m-d') }}" placeholder="Informe a quarto">
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <label for="data_saida" class="form-label">Data de Saída</label>
                                                <div class="input-group mb-3">
                                                    <input type="date" class="form-control  @error('data_saida') is-invalid @enderror" name="data_saida" id="data_saida" value="{{ old('data_saida') ?? date('Y-m-d') }}" placeholder="Informe a quarto">
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <label for="hora_entrada" class="form-label">Hora de Entrada</label>
                                                <div class="input-group mb-3">
                                                    <input type="time" class="form-control  @error('hora_entrada') is-invalid @enderror" name="hora_entrada" id="hora_entrada" value="{{ old('hora_entrada') ?? date('H:i') }}" placeholder="Hora da Entrada">
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <label for="hora_saida" class="form-label">Hora de Saída</label>
                                                <div class="input-group mb-3">
                                                    <input type="time" class="form-control  @error('hora_saida') is-invalid @enderror" name="hora_saida" id="hora_saida" value="{{ old('hora_saida') ?? date('H:i') }}" placeholder="Hora da Entrada">
                                                    <input type="hidden" name="data_emissao" value="{{ date('Y-m-d') }}">
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <label for="total_dias_reservado_" class="form-label">Total Dias</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" disabled class="form-control" name="total_dias_reservado_" id="total_dias_reservado_" value="{{ old('total_dias_reservado_') ?? 0 }}" placeholder="Informe da Factura">
                                                    <input type="hidden" value="0" name="total_dias_reservado" id="total_dias_reservado">
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <label for="valor_total_geral_" class="form-label">Valor Total Geral</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" disabled class="form-control" name="valor_total_geral_" id="valor_total_geral_" value="{{ old('valor_total_geral_') ?? 0 }}" placeholder="Informe da Factura">
                                                    <input type="hidden" value="0" name="valor_total_geral" id="valor_total_geral">
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <label for="marcar_como" class="form-label">Fazer o pagamento</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control @error('marcar_como') is-invalid @enderror" id="marcar_como" name="marcar_como">
                                                        <option value="nao"> {{ __('messages.nao') }} </option>
                                                        <option value="sim"> {{ __('messages.sim') }} </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-3" id="form_forma_pagamento" style="display: none">
                                                <label for="forma_pagamento_id" class="form-label text-right">Forma de Pagamento</label>
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

                                            <div class="col-12 col-md-3" id="form_receitas" style="display: none">
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

                                            <div class="col-12 col-md-3" id="form_valor_entregue" style="display: none">
                                                <label for="valor_entregue" class="form-label">Valor Entregue</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control  @error('valor_entregue') is-invalid @enderror" name="valor_entregue" id="valor_entregue" value="{{ old('valor_entregue') ?? 0 }}" placeholder="Informe da Factura">
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <label for="fazer_check" class="form-label">Fazer o check direitamente</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control @error('fazer_check') is-invalid @enderror" id="fazer_check" name="fazer_check">
                                                        <option value="nao"> {{ __('messages.nao') }} </option>
                                                        <option value="sim"> {{ __('messages.sim') }} </option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-12 col-md-3">
                                                <label for="observacao" class="form-label">Observação (opcional)</label>
                                                <div class="input-group mb-3">
                                                    <input name="observacao" id="observacao" class="form-control @error('observacao') is-invalid @enderror" value="{{ old('observacao') ?? '' }}" placeholder="Podes especificar aqui e reserva..." />
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
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <button type="button" class="btn btn-light-primary" onclick="adicionarQuarto()"><i class="fas fa-plus"></i> Adicionar Quarto</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-12 text-center">
                                                <div id="quartosContainer"></div>
                                                <!-- Botão para adicionar -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                    </div>
                                </div>
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
    let index = 0;

    function adicionarQuarto() {

        const data_entrada = document.getElementById('data_entrada').value;
        const data_saida = document.getElementById('data_saida').value;
        const hora_entrada = document.getElementById('hora_entrada').value;
        const hora_saida = document.getElementById('hora_saida').value;

        if (!data_entrada || !data_saida) {
            alert('Informe as datas de entrada e saída antes de adicionar um quarto.');
            return;
        }

        const html = `
        <div class="reserva-item text-left" data-index="${index}">
            <div class="row">
                <div class="col-12 col-md-4">
                    <label class="form-label">Quarto</label>
                    <div class="input-group mb-3">
                        <select name="quartos[${index}][quarto_id]" class="form-control quarto-select" data-index="${index}" required>
                            <option value="">{{ __('messages.escolher') }}</option>
                            @foreach($quartos as $quarto)
                                <option value="{{ $quarto->id }}">{{ $quarto->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Tarifário</label>
                    <div class="input-group mb-3">
                        <select name="quartos[${index}][tarifario_id]" class="form-control tarifario-select" id="tarifario-${index}" data-index="${index}" required>
                            <option value="">Selecione o tarifário</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-md-4">
                    <label class="form-label">Valor Total</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control valor_total_item" name="quartos[${index}][valor_total_item]" value="0" disabled>
                        <div class="input-group-prepend">
                            <button type="button" class=" input-group-text" onclick="remover(this)"><i class="fas fa-trash text-light-danger"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        setTimeout(() => {
            const container = document.querySelector(`#quartosContainer .reserva-item:last-child`);
            const quarto_id = container.querySelector('select[name*="[quarto_id]"]');

            const input_data_entrada = document.getElementById('data_entrada');
            const input_data_saida = document.getElementById('data_saida');
            const input_hora_entrada = document.getElementById('hora_entrada');
            const input_hora_saida = document.getElementById('hora_saida');

            [quarto_id, input_data_entrada, input_data_saida, input_hora_entrada, input_hora_saida].forEach(input => {
                input.addEventListener('change', () => verificarDisponibilidade(quarto_id, input_data_entrada, input_data_saida, input_hora_entrada, input_hora_saida, container));
            });
        }, 100);

        document.getElementById('quartosContainer').insertAdjacentHTML('beforeend', html);

        index++;
    }

    // carregarTarefariosQuartos
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('quarto-select')) {
            const quartoId = e.target.value;
            const index = e.target.dataset.index;

            if (!quartoId) return;

            fetch(`/quartos/${quartoId}/tarifarios`)
                .then(res => res.json())
                .then(data => {
                    const selectTarifario = document.getElementById(`tarifario-${index}`);
                    selectTarifario.innerHTML = '<option value="">Selecione o tarifário</option>';

                    data.forEach(tarifario => {
                        const option = document.createElement('option');
                        option.value = tarifario.tarefario.id;
                        option.text = `${tarifario.tarefario.nome} - ${parseFloat(tarifario.tarefario.preco_venda).toFixed(2)} Kz`;
                        option.setAttribute('data-preco', tarifario.tarefario.preco_venda);
                        selectTarifario.appendChild(option);
                    });

                })
                .catch(() => {
                    alert('Erro ao carregar tarifários.');
                });
        }

        // Quando mudar o TARIFÁRIO → calcular valor total
        if (e.target.classList.contains('tarifario-select')) {

            const index = e.target.dataset.index;
            const preco = parseFloat(e.target.selectedOptions[0].dataset.preco);
            const dias = calcularDiferencaDias();

            const total = dias * preco;
            document.querySelector(`.reserva-item[data-index="${index}"] .valor_total_item`).value = total.toFixed(2);

            calcularValorGeral();
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


    function calcularDiferencaDias() {
        const entrada = new Date(document.getElementById('data_entrada').value);
        const saida = new Date(document.getElementById('data_saida').value);
        const diff = Math.ceil((saida - entrada) / (1000 * 60 * 60 * 24));
        return diff > 0 ? diff : 1;
    }

    function calcularValorGeral() {
        let totalGeral = 0;
        document.querySelectorAll('.valor_total_item').forEach(input => {
            totalGeral += parseFloat(input.value) || 0;
        });
        document.getElementById('valor_total_geral').value = totalGeral.toFixed(2);
        document.getElementById('valor_total_geral_').value = totalGeral.toFixed(2);
        document.getElementById('total_dias_reservado').value = calcularDiferencaDias();
        document.getElementById('total_dias_reservado_').value = calcularDiferencaDias();
    }

    function verificarDisponibilidade(_quarto_id, _data_entrada_quarto, _data_saida_quarto, _hora_inicio_quarto, _hora_fim_quarto, container) {

        /*const quarto_id = _quarto_id.value;
        const data_entrada_quarto = _data_entrada_quarto.value;
        const data_saida_quarto = _data_saida_quarto.value;
        const hora_inicio_quarto = _hora_inicio_quarto.value;
        const hora_fim_quarto = _hora_fim_quarto.value;

        fetch('/verificar-disponibilidade-quartos', {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': document.querySelector("meta[name=csrf-token]").content
                }
                , body: JSON.stringify({
                    quarto_id: quarto_id
                    , data_entrada_quarto
                    , data_saida_quarto
                    , hora_inicio_quarto
                    , hora_fim_quarto
                })
            })
            .then(res => res.json())
            .then(res => {
                if (!res.disponivel) {

                    if (!res.disponivel) {

                        let tabelaHorarios = '';

                        // =====================================
                        // MONTAR TABELA
                        // =====================================

                        if ((res.horarios_disponiveis ? res.horarios_disponiveis.length : 0) > 0) {

                            tabelaHorarios = `
                                <table class="table table-bordered table-striped" style="width:100%; text-align:center;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Hora Início</th>
                                            <th>Hora Fim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;

                            res.horarios_disponiveis.forEach((item, index) => {

                                tabelaHorarios += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.inicio}</td>
                                        <td>${item.fim}</td>
                                    </tr>
                                `;
                            });

                            tabelaHorarios += `
                                    </tbody>
                                </table>
                            `;
                        } else {
                            tabelaHorarios = `
                                <div class="alert alert-warning">
                                    Nenhum horário disponível encontrado.
                                </div>
                            `;
                        }

                        Swal.fire({
                            icon: 'warning'
                            , title: 'Quarto indisponível'
                            , width: 700
                            , html: `
                            <div style="text-align:left;">
                                <p>
                                    O quarto já está reservado para este período.
                                </p>
                                <hr>
                                <h5>
                                    Horários Disponíveis
                                </h5>
                                ${tabelaHorarios}
                            </div>
                            `
                            , confirmButtonText: 'Fechar'
                        });

                        _data_saida_quarto.value = '';
                        _hora_fim_quarto.value = '';
                    }

                }
            });*/
    }

    // Atualiza todos os valores totais dos quartos

    function atualizarValoresTotais() {
        const dias = calcularDiferencaDias(); // função que calcula dias entre data_entrada e data_saida

        document.querySelectorAll('.reserva-item').forEach(item => {
            const selectTarifario = item.querySelector('.tarifario-select');
            const inputTotal = item.querySelector('.valor_total_item');

            if (selectTarifario && inputTotal) {
                const preco = parseFloat(selectTarifario.selectedOptions[0].dataset.preco);
                const total = dias * preco;
                inputTotal.value = total.toFixed(2);
            }
        });

        calcularValorGeral(); // atualiza o total geral
    }

    document.getElementById('data_entrada').addEventListener('change', atualizarValoresTotais);
    document.getElementById('data_saida').addEventListener('change', atualizarValoresTotais);

    function remover(btn) {
        btn.closest('.reserva-item').remove();
        if (document.querySelectorAll('.reserva-item').length === 0) {
            document.getElementById('btnReservar').classList.add('d-none');
        }
    }

    const select = document.getElementById('marcar_como');
    const forma_pagamento_id = document.getElementById('forma_pagamento_id');
    const valor_entregue = document.getElementById('valor_entregue');

    const form_forma_pagamento = document.getElementById('form_forma_pagamento');
    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');
    const form_receitas = document.getElementById('form_receitas');
    const form_valor_entregue = document.getElementById('form_valor_entregue');

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

            // formatar valores            
            let valor_entregue = document.getElementById("valor_entregue");
            // Converter de "10.000,50" para "10000.50"
            let rawValue = valor_entregue.value.replace(/\./g, "").replace(",", ".");
            valor_entregue.value = parseFloat(rawValue).toFixed(2); // Garantir 2 casas decimais


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
                    }
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
