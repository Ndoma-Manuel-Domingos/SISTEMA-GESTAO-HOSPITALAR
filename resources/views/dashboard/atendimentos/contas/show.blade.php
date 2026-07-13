@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-cash-register text-success"></i> Pagamento da Conta Hospitalar
                        @if ($conta->status == "PAGA")
                        <span class="text-success">
                            - Esta conta já esta paga
                        </span>
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('atendimentos.show', $conta->atendimento_id) }}">Valtar</a></li>
                        <li class="breadcrumb-item active">Recebimento</li>
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
                {{-- RESUMO --}}
                <div class="col-md-4">
                    <div class="card card-primary card-outline">

                        <div class="card-header">

                            <h3 class="card-title">
                                <i class="fas fa-file-invoice-dollar"></i>
                                Resumo da Conta
                            </h3>

                        </div>

                        <div class="card-body">

                            <table class="table table-sm">

                                <tr>
                                    <th>Paciente</th>
                                    <td>{{ $conta->paciente->nome }}</td>
                                </tr>

                                <tr>
                                    <th>Total Conta</th>
                                    <td>
                                        <strong id="total_conta">
                                            {{ number_format($conta->total,2,",",".") }}
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Paciente</th>
                                    <td id="total_paciente">
                                        {{ number_format($conta->valor_paciente,2,",",".") }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Seguradora</th>
                                    <td id="total_seguradora">
                                        {{ number_format($conta->valor_seguradora,2,",",".") }}
                                    </td>
                                </tr>

                                <tr class="bg-success">

                                    <th>Pago</th>

                                    <td id="valor_pago">
                                        {{ number_format($conta->valor_pago,2,",",".") }}
                                    </td>

                                </tr>

                                <tr class="bg-danger">

                                    <th>Saldo</th>

                                    <td id="saldo">
                                        {{ number_format($conta->saldo,2,",",".") }}
                                    </td>

                                </tr>

                            </table>

                        </div>

                    </div>
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                Situação Financeira
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Paciente</th>
                                        <th>Seguradora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Total</th>
                                        <td id="valorPaciente">
                                            {{ number_format($conta->valor_paciente,2,",",".") }}
                                        </td>
                                        <td id="valorSeguradora">
                                            {{ number_format($conta->valor_seguradora,2,",",".") }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Pago</th>
                                        <td id="valorPagoPaciente">
                                            {{ number_format($conta->valor_pago_paciente,2,",",".") }}
                                        </td>
                                        <td id="valorPagoSeguradora">
                                            {{ number_format($conta->valor_pago_seguradora,2,",",".") }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Saldo</th>
                                        <td class="text-danger font-weight-bold" id="saldoPaciente">
                                            {{ number_format($conta->saldo_paciente,2,",",".") }}
                                        </td>
                                        <td class="text-danger font-weight-bold" id="saldoSeguradora">
                                            {{ number_format($conta->saldo_seguradora,2,",",".") }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('atendimentos.show', $conta->atendimento_id) }}" class="btn btn-light-primary">Ver os itens ou serviços da conta</a>
                        </div>
                    </div>
                </div>

                {{-- FORMULÁRIO --}}
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Novo Pagamento</h3>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('pagamento.conta.store', $conta->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="tipo_pagamento">Quem está pagando?</label>
                                            <select class="form-control" id="tipo_pagamento" name="tipo">
                                                @if($conta->saldo_paciente>0)
                                                <option value="PACIENTE">Paciente</option>
                                                @endif
                                                @if($conta->saldo_seguradora>0)
                                                <option value="SEGURADORA">Seguradora</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="forma_pagamento">Forma Pagamento</label>
                                            <select class="form-control" id="forma_pagamento" name="forma_pagamento">
                                                <option value="">Escolher</option>
                                                @foreach ($forma_pagaments as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->titulo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="saldo_disponivel">Saldo Disponível</label>
                                            <input type="text" name="saldo_disponivel" class="form-control" readonly id="saldo_disponivel">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="valor">Valor a pagar</label>
                                            <input type="text" class="form-control" id="valor" name="valor">
                                            <small class="text-danger" id="erro_valor"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="referencia">Referência</label>
                                    <input type="text" id="referencia" name="referencia" class="form-control">
                                </div>

                                <input type="hidden" id="conta_id" value="{{ $conta->id }}">
                                <input type="hidden" value="" name="payment_token" id="payment_token">
                                <input type="hidden" value="FR" name="tipo_documento" id="tipo_documento">

                                <div class="form-group">
                                    <label for="observacao">Observação</label>
                                    <textarea class="form-control" name="observacao" rows="3" id="observacao"></textarea>
                                </div>


                                <div class="text-right">
                                    <a href="{{ route('caixas.monitoramento-caixas') }}" class="btn btn-light-primary btn-lg float-left">Monitoramento do caixa</a>
                                    @if ($conta->status !== "PAGA")
                                    <button type="submit" class="btn btn-light-success btn-lg" id="btn_pagar">
                                        <i class="fas fa-check-circle"></i>
                                        Confirmar Pagamento
                                    </button>
                                    @endif
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Histórico de Pagamentos
                            </h3>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-hover" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Data</th>
                                        <th>Tipo</th>
                                        <th>Forma</th>
                                        <th>Valor</th>
                                        <th>Referência</th>
                                        <th>Utilizador</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($conta->pagamentos as $pagamento)
                                    <tr>
                                        <td>{{ $pagamento->id }}</td>
                                        <td>{{ $pagamento->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($pagamento->tipo=="PACIENTE")
                                            <span class="badge badge-light-primary">
                                                Paciente
                                            </span>
                                            @else
                                            <span class="badge badge-light-warning">
                                                Seguradora
                                            </span>
                                            @endif
                                        </td>
                                        <td>{{ $pagamento->forma_pagamento }}</td>
                                        <td>
                                            {{ number_format($pagamento->valor,2,",",".") }}
                                        </td>
                                        <td>{{ $pagamento->referencia }}</td>
                                        <td>{{ optional($pagamento->recebido_por)->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')

<script>
    $(document).ready(function() {


        // =========================
        // PAGAMENTO
        // =========================

        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let btn = $(this);
            btn.prop("disabled", true);

            let conta_id = $("#conta_id").val();
            let tipo = $("#tipo_pagamento").val();
            let forma = $("#forma_pagamento").val();
            let valor = parseMoney($("#valor").val());
            let referencia = $("#referencia").val();

            if (valor <= 0) {
                Swal.fire("Erro", "Valor inválido", "error");
                btn.prop("disabled", false);
                return;
            }

            let max = tipo === "PACIENTE" ?
                saldoPaciente :
                saldoSeguradora;

            if (valor > max) {
                Swal.fire("Erro", "Valor acima do permitido", "error");
                btn.prop("disabled", false);
                return;
            }


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

                    Swal.fire("Sucesso", "Pagamento registado", "success");

                    // atualizar saldos
                    if (tipo === "PACIENTE") {
                        saldoPaciente -= valor;
                    } else {
                        saldoSeguradora -= valor;
                    }

                    $("#valor").val("");
                    atualizarSaldo();

                    // atualizar UI
                    $("#valor_pago").text(formatMoney(response.conta.valor_pago));
                    $("#saldo").text(formatMoney(response.conta.saldo));

                    $("#valorPagoPaciente").text(formatMoney(response.conta.valor_pago_paciente));
                    $("#valorPagoSeguradora").text(formatMoney(response.conta.valor_pago_seguradora));

                    $("#saldoPaciente").text(formatMoney(response.conta.saldo_paciente));
                    $("#saldoSeguradora").text(formatMoney(response.conta.saldo_seguradora));

                    btn.prop("disabled", false);

                    if (tipo == "PACIENTE") {
                        let url = null;
                        if (response.factura.factura == "FR") {
                            url = `{{ route('factura-recibo', ':code') }}`.replace(':code', response.factura.code);
                        }

                        if (response.factura.factura == "FT") {
                            url = `{{ route('factura-factura', ':code') }}`.replace(':code', response.factura.code);
                        }

                        if (response.factura.factura == "FP") {
                            url = `{{ route('factura-proforma', ':code') }}`.replace(':code', response.factura.code);
                        }
                        // Redirecionar
                        window.location.href = url;
                    }

                    Swal.fire('Sucesso!', 'Operação realizada com sucesso.', 'success');

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
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });
        });


        // =========================
        // FORMATAR MOEDA PT
        // =========================
        function parseMoney(value) {
            if (!value) return 0;
            value = value.toString()
                .replace(/\s/g, '')
                .replace(/\./g, '')
                .replace(',', '.');
            return parseFloat(value) || 0;
        }

        function formatMoney(value) {
            return new Intl.NumberFormat('pt-PT', {
                minimumFractionDigits: 2
                , maximumFractionDigits: 2
            }).format(value);
        }

        // =========================
        // SALDOS INICIAIS
        // =========================
        let saldoPaciente = parseMoney($("#total_paciente").text());
        let saldoSeguradora = parseMoney($("#total_seguradora").text());

        // =========================
        // GERAR TOKEN (ANTI DUPLICAÇÃO)
        // =========================
        function generateToken() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                let r = Math.random() * 16 | 0
                    , v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        // =========================
        // ATUALIZAR SALDO
        // =========================
        function atualizarSaldo() {

            let tipo = $("#tipo_pagamento").val();

            if (tipo === "PACIENTE") {
                $("#saldo_disponivel").val(formatMoney(saldoPaciente));
                $("#valor").attr("max", saldoPaciente);
            }

            if (tipo === "SEGURADORA") {
                $("#saldo_disponivel").val(formatMoney(saldoSeguradora));
                $("#valor").attr("max", saldoSeguradora);
            }
            $("#payment_token").val(generateToken());
        }

        atualizarSaldo();

        // =========================
        // TROCAR TIPO PAGAMENTO
        // =========================
        $("#tipo_pagamento").on("change", function() {

            $("#valor").val("");
            $("#erro_valor").text("");

            atualizarSaldo();
        });

        // =========================
        // VALIDAR VALOR
        // =========================
        $("#valor").on("input", function() {
            let valor = parseMoney($(this).val());
            let tipo = $("#tipo_pagamento").val();

            let max = tipo === "PACIENTE" ?
                saldoPaciente :
                saldoSeguradora;

            if (valor <= 0) {
                $("#erro_valor").text("Valor inválido");
                $("#btn_pagar").prop("disabled", true);
                return;
            }

            if (valor > max) {
                $("#erro_valor").text("Valor excede o saldo disponível");
                $("#btn_pagar").prop("disabled", true);
            } else {
                $("#erro_valor").text("");
                $("#btn_pagar").prop("disabled", false);
            }
        });


        $("#carregar_tabela").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>

@endsection
