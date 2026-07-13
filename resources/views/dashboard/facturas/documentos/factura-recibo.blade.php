<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FACTURA RECIBO</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            box-sizing: border-box;
        }

        #bg-color-primary {
            text-align: center;
            background-color: #e2e2e2 !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            print-color-adjust: exact;
            color: #0f0f0f;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 16px;
            font-size: 16px;
        }

        .ticket {
            width: 370px;
            padding: 0;
            font-size: 8px;
        }

        @media print {
            @page {
                size: auto;
                margin: 3mm;
                /* Remove margem do navegador na impressão */
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                width: 100%;
                height: 100%;
            }

            .recibo {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .ticket {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        /* Opcional: também fora da impressão */
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 14px;
            color: #222;
        }

        .recibo {
            width: 370px;
            margin: 0 auto;
            border: 1px solid #dcdcdc;
            background: #fff;
        }

        .topo-linha {
            height: 3px;
            background: #0b53ce;
            border-right: 3px solid #d59a20;
        }

        .logo-area {
            text-align: center;
        }

        .logo {
            width: 85px;
        }

        .logo-area h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #0b53ce;
        }

        .logo-area p {
            margin: 2px 0;
            font-size: 14px;
        }

        .cabecalho {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .cabecalho td {
            padding: 5px 8px;
        }


        .factura {
            color: #0b53ce;
            font-weight: bold;
            border-top: 1px solid #ddd;
        }

        .cliente {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .cliente td {
            padding: 4px 8px;
        }

        .titulo {
            color: #999;
            font-size: 16px;
        }

        .itens {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .itens th {
            border-bottom: 1px solid #ccc;
            color: #777;
            font-size: 14px;
            padding: 5px;
        }

        .itens td {
            padding: 7px 5px;
            border-bottom: 1px solid #f0f0f0;
        }

        .totais {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .totais td {
            padding: 3px 8px;
        }

        .estado {
            background-color: #f4a100;
            color: #fff;
            padding: 2px 5px;
            font-size: 14px;
        }

        .pagar {
            width: 100%;
            border-collapse: collapse;
            background-color: #0b53ce;
            color: #fff;
            margin-top: 10px;
        }

        .pagar td {
            padding: 10px;
            font-weight: bold;
        }

        .pagar td:last-child {
            font-size: 18px;
        }

        .extenso {
            text-align: center;
            color: #d39b24;
            font-size: 14px;
            padding: 7px;
            font-weight: bold;
        }

        .observacao {
            width: 100%;
            border-collapse: collapse;
        }

        .observacao td {
            padding: 5px 8px;
            font-size: 14px;
        }

        .qr-box {
            text-align: center;
            margin-top: 10px;
        }

        .regime {
            margin-top: 10px;
            text-align: center;
            color: #0b53ce;
            font-size: 9px;
            font-weight: bold;
        }

        .legal {
            text-align: center;
            padding: 5px 15px;
            font-size: 14px;
        }

        .obrigado {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }

        .software {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #666;
        }

        @media print {
            .pagar {
                background-color: #0b53ce !important;
                color: white !important;

                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .estado {
                background-color: #f4a100 !important;
                color: white !important;

                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

    </style>

</head>
<body>

    @if ($LOJAACTIVAOPERADOR->modelo_factura != 'modelo1')

    @for ($i = 1; $i < $empresa_logada->empresa->numero_via_documento; $i++)
        <div class="recibo">
            <div class="topo-linha"></div>
            <div class="logo-area">
                @if (!$empresa_logada->empresa->logotipo == null)
                <img src="{{ $logotipo }}}}" alt="Logotipo da Empresa" style="border-radius: 20px;text-align: center;height: 50px;width: 70px;">
                @else
                <img src="/images/empresa/logo-default.png" alt="Logotipo da Empresa" style="border-radius: 20px;text-align: center;height: 50px;width: 70px;border: 1px solid #000">
                @endif
                <h2>{{ $LOJAACTIVAOPERADOR->nome }}</h2>
                <p>{{ $LOJAACTIVAOPERADOR->morada }}</p>
                <p>NIF: {{ $LOJAACTIVAOPERADOR->nif }}</p>
                <p>TEL: {{ $LOJAACTIVAOPERADOR->telefone }}</p>
                <p>E-MAIL: {{ $LOJAACTIVAOPERADOR->email }}</p>
            </div>
            <table class="cabecalho">
                <tr>
                    <td>
                        <span class="estado">ORGINAL</span>
                    </td>
                    <td align="right">
                        2024-09-09
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="factura">
                        {{ $factura->factura_next }}
                    </td>
                </tr>
            </table>

            <table class="cliente">
                <tr>
                    <td>
                        <span class="titulo">CLIENTE</span>
                    </td>
                    <td align="right">
                        <strong>{{ $factura->nome_cliente ?? $factura->cliente->nome }}</strong>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="right">
                        {{ $factura->documento_nif ?? $factura->cliente->nif }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="titulo">Forma de pagamento</span>
                    </td>
                    <td align="right">
                        {{ $factura->pagamento == "NU" ? "NUMERÁRIO" : ($factura->pagamento == "OU" ? "DUPLO" : ($factura->pagamento == "TB" ? "TRANSFERÊNCIA BANCARIA" : ($factura->pagamento == "DE" ? "DEPOSITO" : "MULTICAIXA"))) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="titulo">Data Emissão</span>
                    </td>
                    <td align="right">
                        {{ $factura->created_at }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="titulo">Operador</span>
                    </td>
                    <td align="right">
                        {{ $factura->user->name }}
                    </td>
                </tr>
            </table>

            <table class="itens">
                <thead>
                    <tr>
                        <th align="left">ARTIGO</th>
                        <th>PRE</th>
                        <th>QTD</th>
                        <th align="right">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items_facturas as $item)
                    <tr>
                        <td>{{ $item->produto->nome ?? "" }}</td>
                        <td align="center">{{ number_format($item->quantidade, 0) }}</td>
                        <td align="center">{{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                        <td align="right">{{ number_format($item->valor_pagar, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="totais">
                <tr>
                    <td>Total Ilíquido</td>
                    <td align="right">{{ number_format($factura->total_incidencia, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Desconto</td>
                    <td align="right">{{ number_format($factura->desconto, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Imposto</td>
                    <td align="right">{{ number_format($factura->total_iva, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Valor Entregue</td>
                    <td align="right">{{ number_format($factura->valor_entregue, '2', ',', '.') }}</td>
                </tr>
            </table>

            <table class="pagar" style="background-color: #0b53ce;">
                <tr>
                    <td>TOTAL PAGO</td>
                    <td align="right">{{ number_format($factura->valor_total, '2', ',', '.') }}</td>
                </tr>
            </table>

            <div class="extenso">
                {{ $factura->valor_extenso ?? 'Sem descrição por extenso' }}
            </div>

            <table class="observacao">
                <tr>
                    <td colspan="2">
                        Observação:
                        {{ $factura->observacao ?? '---' }}
                    </td>
                </tr>
            </table>

            @if ($empresa_logada->empresa->tipo_facturacao != "saft")
            <div class="qr-box">
                <img src="data:image/png;base64,{{ $factura->qr_code }}" width="85">
            </div>
            @endif


            <div class="regime" style="text-transform: uppercase">
                @if ($empresa_logada->empresa->tipo_regime_id == "regime_exclusao")
                Regime de Exclusão
                @else
                @if ($empresa_logada->empresa->tipo_regime_id == "regime_geral")
                Regime Geral
                @else
                @if ($empresa_logada->empresa->tipo_regime_id == "regime_simplificado")
                Regime Simplificado
                @else
                Define um Regime para sua empresa
                @endif
                @endif
                @endif
            </div>
            <div class="legal">
                Os bens foram colocados à disposição do adquirente na data do documento
            </div>
            <div class="legal">
                {{ $factura->obterCaracteres($factura->hash) }}
            </div>
            <div class="obrigado">
                Obrigado pela preferência!
            </div>
            <div class="software">
                Software {{ env('APP_NAME') }}
            </div>
        </div>

        <div class="copy-break"></div>

        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'REST' && $factura->mesa)
        <div class="recibo">
            <table style="width: 100%">
                <thead>
                    <tr>
                        <th colspan="2" style="border-bottom: 2px solid #000;font-size: 25px;text-align: center;padding-top: 30px">
                            PEDIDO DA MESA: <i>{{ $factura->mesa->nome }}</i>
                        </th>
                    </tr>

                    <tr>
                        <th style="border-top: 2px solid #000;border-bottom: 2px solid #000;font-size: 20px;text-align: center;padding: 5px 0 5px 0;">Produto</th>
                        <th style="border-top: 2px solid #000;border-bottom: 2px solid #000;font-size: 20px;text-align: center;padding: 5px 0 5px 0;">{{ __('messages.quantidade') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($items_facturas as $item)
                    <tr>
                        <td style="font-size: 20px; padding: 4px 0;text-transform: uppercase;text-align: center;padding: 5px 0 5px 0;"> {{ $item->produto->nome ?? '' }}</td>
                        <td style="font-size: 20px;text-align: center;padding: 5px 0 5px 0;">{{ number_format($item->quantidade ?? 0, 1, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>


            </table>
        </div>
        @endif
        @endfor

        <div class="copy-break"></div>
        @else

        @for ($i = 1; $i < $empresa_logada->empresa->numero_via_documento; $i++)
            <div class="ticket">

                <header style="width: 100%;padding: 0;">
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: center;border-radius: 20px;">
                                    @if (!$empresa_logada->empresa->logotipo == null)
                                    <img src="{{ $logotipo }}}}" alt="Logotipo da Empresa" style="border-radius: 20px;text-align: center;height: 50px;width: 70px;">
                                    @else
                                    <img src="/images/empresa/logo-default.png" alt="Logotipo da Empresa" style="border-radius: 20px;text-align: center;height: 50px;width: 70px;border: 1px solid #000">
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table>
                        <tbody>
                            <tr>
                                <td style="padding: 5px 0;font-weight: bolder;font-size: 16px;text-align: center">
                                    <strong>{{ $LOJAACTIVAOPERADOR->nome }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bolder;font-size: 16px;text-align: center" colspan="2">
                                    <strong>NIF:</strong> {{ $LOJAACTIVAOPERADOR->nif }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bolder;font-size: 16px;text-align: center" colspan="2">
                                    <strong>Telefone: </strong>
                                    {{ $LOJAACTIVAOPERADOR->telefone ?? "999 999 999" }} | {{ $empresa_logada->empresa->telemovel ?? "999 999 999" }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bolder;font-size: 16px;text-align: center" colspan="2">
                                    <strong>Endereço: </strong>
                                    {{ $LOJAACTIVAOPERADOR->morada ?? "Luanda-Angola" }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </header>

                <main style="width: 100%;padding: 0;">
                    <table style="width: 100%">
                        <tr>
                            <td style="border-bottom: 2px solid #000;font-size: 17px;">
                                <span>FACTURA RECIBO</span> <small style="float: right;font-size: 12px">Nº Diário: {{ $factura->numero_pedido_diario }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td id="bg-color-primary" style="font-size: 20px;padding: 5px 0;text-align: center">{{ $factura->factura_next }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 16px;padding-top: 5px;text-align: center">Cliente: <strong>{{ $factura->nome_cliente }}</strong></td>
                        </tr>
                        <tr>
                            <td style="font-size: 16px;text-align: center">NIF: <strong>{{ $factura->documento_nif ?? '99999999999' }}</strong></td>
                        </tr>
                        @if ($factura->cliente->localidade != NULL)
                        <tr>
                            <td style="font-size: 16px;text-align: center">Endereço: <strong>{{ $factura->cliente->localidade ?? '' }}</strong></td>
                        </tr>
                        @endif
                        <tr>
                            <td style="font-size: 16px;text-align: center">Data e Hora: <strong>{{ $factura->data_emissao }} : {{ date('H:i:s') }}</strong></td>
                        </tr>
                    </table>

                    <table class="table table-stripeds" style="margin-top: -15px;">
                        <thead>
                            <tr>
                                <th style="border-top: 2px solid #000;border-bottom: 2px solid #000;font-size: 16px">Desc.</th>
                                <th style="border-top: 2px solid #000;border-bottom: 2px solid #000;font-size: 16px;text-align: right">{{ __('messages.quantidade') }}</th>
                                <th style="border-top: 2px solid #000;border-bottom: 2px solid #000;border-bottom: 2px solid #000;font-size: 16px;text-align: right">Preço</th>
                                <th style="border-top: 2px solid #000;border-bottom: 2px solid #000;font-size: 16px;text-align: right"> {{ __('messages.imposto') }}</th>
                                <th style="border-top: 2px solid #000;border-bottom: 2px solid #000;font-size: 16px;text-align: right">{{ __('messages.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items_facturas as $item)
                            <tr>
                                <td colspan="5" style="font-size:16px;padding: 4px 0;text-transform: uppercase;"> {{ $item->produto->nome ?? '' }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="font-size: 16px;font-size: 16px;text-align: right">{{ number_format($item->quantidade ?? 0, 1, ',', '.') }}</td>
                                <td style="font-size: 16px;font-size: 16px;text-align: right">{{ number_format($item->preco_unitario ?? 0, 1, ',', '.') }}</td>
                                <td style="font-size: 16px;font-size: 16px;text-align: right">{{ number_format($item->iva_taxa ?? 0, 0, ',', '.') }}%</td>
                                <td style="font-size: 16px;font-size: 16px;text-align: right"> {{ number_format($item->valor_pagar, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table style="margin-top: -10px">
                        <thead>
                            <tr>
                                <th style="border-top: 2px solid #000;font-weight: bolder;font-size:16px;padding: 4px 0" style="font-weight: bolder;">Cod.</th>
                                <th style="border-top: 2px solid #000;font-weight: bolder;font-size:16px;padding: 4px 0" style="font-weight: bolder;">Taxa%</th>
                                <th style="border-top: 2px solid #000;font-weight: bolder;font-size:16px">Incidência</th>
                                <th style="border-top: 2px solid #000;font-weight: bolder;font-size:16px;text-align: right">Valor Imposto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($items_facturas) != 0)

                            @if ($total_incidencia_ise > 0 || $total_iva_ise > 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> M10</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> 0</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> {{ number_format($total_incidencia_ise, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px;text-align: right"> {{ number_format(0, 2, ',', '.') }}</td>
                            </tr>
                            @endif

                            @if ($total_incidencia_out_2 != 0 || $total_iva_out_2 != 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> M10</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> 2</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> {{ number_format($total_incidencia_out_2, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px;text-align: right"> {{ number_format($total_iva_out_2, 2, ',', '.') }}</td>
                            </tr>
                            @endif

                            @if ($total_incidencia_out_5 != 0 || $total_iva_out_5 != 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> M10</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> 5</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> {{ number_format($total_incidencia_out_5, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px;text-align: right"> {{ number_format($total_iva_out_5, 2, ',', '.') }}</td>
                            </tr>
                            @endif

                            @if ($total_incidencia_out != 0 || $total_iva_out != 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> M10</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> 7</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> {{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px;text-align: right"> {{ number_format($total_iva_out, 2, ',', '.') }}</td>
                            </tr>
                            @endif

                            @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> NOR</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> 14</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px"> {{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 16px;text-align: right"> {{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                            </tr>
                            @endif

                            @endif
                        </tbody>

                    </table>
                </main>

                <footer style="width: 100%;padding: 0;">
                    <table style="margin-top: -10px;">
                        <tr>
                            <td style="font-size:16px">
                                OPERADOR: {{ $factura->user->name }}
                            </td>
                        </tr>
                    </table>


                    <table style="border-top: 2px solid #000000;margin-top: -15px;">
                        <tbody>
                            <tr>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px;padding-right: 20px;">
                                    <strong>{{ __('messages.total') }}:</strong>
                                </td>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px">
                                    {{ number_format($factura->valor_total + $factura->desconto, '2', ',', '.') }}AKZ
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px;padding-right: 20px;">
                                    <strong>Desconto:</strong>
                                </td>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px">
                                    {{ number_format($factura->desconto, '2', ',', '.') }}AKZ
                                </td>
                            </tr>
                            @if ($factura->pagamento == 'OU')
                            <tr>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px;padding-right: 20px;">
                                    <strong>Multicaixa:</strong>
                                </td>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px">
                                    {{ number_format($factura->valor_multicaixa, '2', ',', '.') }}AKZ
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px;padding-right: 20px;">
                                    <strong>Numerário:</strong>
                                </td>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px">
                                    {{ number_format($factura->valor_cash, '2', ',', '.') }}AKZ
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px;padding-right: 20px;">
                                    <strong>Valor Entregue:</strong>
                                </td>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px">
                                    {{ number_format($factura->valor_entregue, '2', ',', '.') }}AKZ
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px;padding-right: 20px;">
                                    <strong>Troco:</strong>
                                </td>
                                <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 16px">
                                    {{ $factura->valor_troco <= 0 ? 0 : number_format($factura->valor_troco, '2', ',', '.') }}AKZ
                                </td>
                            </tr>
                            <tr>
                                <td id="bg-color-primary" colspan="2" style="font-size: 15px;padding: 5px 0">OBRIGADO VOLTE SEMPRE!</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center; font-size: 16px">
                                    @if ($empresa_logada->empresa->tipo_regime_id == "regime_exclusao")
                                    Regime de Exclusão
                                    @else
                                    @if ($empresa_logada->empresa->tipo_regime_id == "regime_geral")
                                    Regime Geral
                                    @else
                                    @if ($empresa_logada->empresa->tipo_regime_id == "regime_simplificado")
                                    Regime Simplificado
                                    @else
                                    Define um Regime para sua empresa
                                    @endif
                                    @endif
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0;text-align: left;margin-top: 0;display: block;font-style: italic;font-size:16px" colspan="2">Os bens/serviços foram colocados à disposição do adquirente na data do documento</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0;text-align: left;font-size:16px" colspan="2">{{ $factura->obterCaracteres($factura->hash) }}</td>
                            </tr>
                            <tr style="">
                                <td style="padding: 3px 0; text-align: left;border-top: 2px solid #000000;font-size:16px" colspan="2">{{ $factura->valor_extenso ?? 'sem descrição do valor por extensão' }} Kwanzas</td>
                            </tr>



                        </tbody>
                    </table>

                    @if ($empresa_logada->empresa->tipo_facturacao != "saft")
                    <div class="qr-box">
                        <img src="data:image/png;base64,{{ $factura->qr_code }}" width="85">
                    </div>
                    @endif

                </footer>

            </div>

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'REST' && $factura->mesa)
            <div class="ticket">
                <main style="width: 100%;padding: 0;">
                    <table style="width: 100%">
                        <thead>
                            <tr>
                                <th colspan="2" style="border-bottom: 2px solid #000;font-size: 25px;text-align: center;padding-top: 30px">
                                    PEDIDO DA MESA: <i>{{ $factura->mesa->nome }}</i>
                                </th>
                            </tr>

                            <tr>
                                <th style="border-top: 2px solid #000;border-bottom: 2px solid #000;font-size: 20px;text-align: center;padding: 5px 0 5px 0;">Produto</th>
                                <th style="border-top: 2px solid #000;border-bottom: 2px solid #000;font-size: 20px;text-align: center;padding: 5px 0 5px 0;">{{ __('messages.quantidade') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($items_facturas as $item)
                            <tr>
                                <td style="font-size: 20px; padding: 4px 0;text-transform: uppercase;text-align: center;padding: 5px 0 5px 0;"> {{ $item->produto->nome ?? '' }}</td>
                                <td style="font-size: 20px;text-align: center;padding: 5px 0 5px 0;">{{ number_format($item->quantidade ?? 0, 1, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>


                    </table>

                </main>
            </div>
            @endif
            @endfor

            <div class="copy-break"></div>
            @endif


            @if ($venda_realizado == 'CAIXA' && $empresa_logada->empresa->tipo_pronto_venda == 'Grelha')
            <script>
                window.print();
                setTimeout(() => {
                    window.top.location = "/dashboard/pronto-venda";
                    return;
                }, 2000);

            </script>
            @endif

            @if ($venda_realizado == 'CAIXA' && $empresa_logada->empresa->tipo_pronto_venda == 'Lista')
            <script>
                window.print();
                setTimeout(() => {
                    window.top.location = "/dashboard/pronto-venda-grelha";
                    return;
                }, 2000);

            </script>
            @endif

            @if ($venda_realizado == 'MESA')
            <script>
                window.print();
                setTimeout(() => {
                    window.top.location = "/dashboard/pronto-venda-mesas";
                    return;
                }, 2000);

            </script>
            @endif

            @if ($venda_realizado == 'QUARTO')
            <script>
                window.print();
                setTimeout(() => {
                    window.top.location = "/dashboard/pronto-venda-quartos";
                    return;
                }, 2000);

            </script>
            @endif

            @if ($venda_realizado != 'QUARTO' and $venda_realizado != 'MESA' and $venda_realizado != 'CAIXA')
            <script>
                window.print();
                setTimeout(() => {
                    window.top.location = "/dashboard/pronto-venda";
                    return;
                }, 2000);

            </script>
            @endif

</body>
</html>
