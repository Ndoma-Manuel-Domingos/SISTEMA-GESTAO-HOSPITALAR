<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NOTA DE CRÉDITO</title>

    <style type="text/css">
        * {
            padding: 0;
            margin: 0;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
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
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 16px;
        }

        .ticket {
            width: 370px;
            padding: 0;
            /* font-family: monospace; */
            font-size: 8px;
        }

        /* Opcional: também fora da impressão */
        html,
        body {
            margin: 10px;
            padding: 0;
        }

        .marca-dagua {
            position: fixed;
            top: 50%;
            left: 50%;
            text-transform: uppercase;
            transform: translate(-50%, -50%);
            font-size: 9em;
            color: rgba(0, 0, 0, 0.1);
            /* Cor do texto com transparência */
            z-index: 1000;
            /* Z-index alto para ficar acima do conteúdo */
            pointer-events: none;
            /* Evitar que o texto interfira com a interação do usuário */
        }

        .marca-dagua-2 {
            position: fixed;
            top: 50%;
            left: 20%;
            text-transform: uppercase;
            transform: translate(-50%, -50%);
            font-size: 40px;
            color: rgba(0, 0, 0, 0.1);
            /* Cor do texto com transparência */
            z-index: 1000;
            /* Z-index alto para ficar acima do conteúdo */
            pointer-events: none;
            /* Evitar que o texto interfira com a interação do usuário */
        }

    </style>

</head>

@if ($empresa_logada->empresa->marca_d_agua_facturas == true)
<body style="background-image: url('/public/images/empresa/{{ $empresa_logada->empresa->logotipo ?? '' }}'); background-attachment: fixed;background-repeat: no-repeat;background-position: center center;background-size: contain;opacity: .1;margin: 140px;">
    @endif

    @if ($empresa_logada->empresa->marca_d_agua_facturas == false)
    <body>
        @endif
        @if ($factura->anulado === 'Y')
        <div class="marca-dagua-2">Anulada</div>
        @endif
        <header style="width: 100%;">
            <table>
                <tbody>
                    <tr>
                        <td style="text-align: center;border-radius: 20px;">
                            <img src="{{ $logotipo }}" alt="Logotipo da Empresa" style="border-radius: 20px;text-align: center;height: 50px;width: 70px;">
                        </td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody>
                    <tr>
                        <td style="padding: 5px 0;font-weight: bolder;font-size: 12px;text-align: center">
                            <strong>{{ $LOJAACTIVAOPERADOR->nome }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bolder;font-size: 12px;text-align: center" colspan="2">
                            <strong>NIF:</strong> {{ $LOJAACTIVAOPERADOR->nif }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bolder;font-size: 12px;text-align: center" colspan="2">
                            <strong>Telefone: </strong>
                            {{ $LOJAACTIVAOPERADOR->telefone ?? "999 999 999" }} | {{ $empresa_logada->empresa->telemovel ?? "999 999 999" }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bolder;font-size: 12px;text-align: center" colspan="2">
                            <strong>Endereço: </strong>
                            {{ $LOJAACTIVAOPERADOR->morada ?? "Luanda-Angola" }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </header>

        <main style="width: 100%;">
            <table style="width: 100%">
                <tr>
                    <td style="border-bottom: 2px solid #000;font-size: 13px;padding: 5px; 0;"><span>NOTA DE CRÉDITO</span></td>
                </tr>
                <tr>
                    <td style="border-bottom: 2px solid #000;font-size: 12px;padding: 5px; 0;"><span>Motivo: {{ $factura->observacao }}</span></td>
                </tr>
                <tr>
                    <td id="bg-color-primary" style="font-size: 15px;padding: 5px 0;text-align: center">{{ $factura->factura_next }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;padding-top: 5px;text-align: center">Cliente: <strong>{{ $factura->nome_cliente ?? $factura->cliente->nome }}</strong></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;text-align: center">NIF: <strong>{{ $factura->documento_nif ?? $factura->cliente->nif }}</strong></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;padding-bottom: 5px;text-align: center">Data e Hora: <strong>{{ $factura->data_emissao }} : {{ date('H:i:s') }}</strong></td>
                </tr>
            </table>

            <table class="table table-stripeds">
                <thead>
                    <tr>
                        <th style="border-top: 2px dotted #000;border-bottom: 2px dotted #000;font-size: 12px;text-align: left">Desc.</th>
                        <th style="border-top: 2px dotted #000;border-bottom: 2px dotted #000;font-size: 12px;text-align: left">{{ __('messages.quantidade') }}</th>
                        <th style="border-top: 2px dotted #000;border-bottom: 2px dotted #000;border-bottom: 2px dotted #000;font-size: 12px;text-align: left">Preço</th>
                        <th style="border-top: 2px dotted #000;border-bottom: 2px dotted #000;font-size: 12px;text-align: left"> IMP</th>
                        <th style="border-top: 2px dotted #000;border-bottom: 2px dotted #000;font-size: 12px;text-align: left">{{ __('messages.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items_facturas as $item)
                    <tr>
                        <td colspan="5" style="font-size:13px;padding: 4px 0"> {{ $item->produto->nome ?? '' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="font-size: 12px;text-align: left">{{ number_format($item->quantidade ?? 0, 1, ',', '.') }}</td>
                        <td style="font-size: 12px;text-align: left">{{ number_format($item->preco_unitario ?? 0, 1, ',', '.') }}</td>
                        <td style="font-size: 12px;text-align: left">{{ number_format($item->iva_taxa ?? 0, 0, ',', '.') }}%</td>
                        <td style="font-size: 12px;text-align: left"> {{ number_format($item->valor_pagar, 2, ',', '.') }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table style="margin-top: 20px">
                <thead>
                    <tr>
                        <th style="font-weight: bolder;font-size:12px;padding: 4px 0">Taxa%</th>
                        <th style="font-weight: bolder;font-size:12px">Incidência</th>
                        <th style="font-weight: bolder;font-size:12px;">Valor Imposto</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($items_facturas) != 0)

                    @if ($total_incidencia_ise > 0 || $total_iva_ise > 0)
                    <tr>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px"> 0</td>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px"> {{ number_format($total_incidencia_ise, 2, ',', '.') }}</td>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px;text-align: right"> {{ number_format(0, 2, ',', '.') }}</td>
                    </tr>
                    @endif

                    @if ($total_incidencia_out_2 != 0 || $total_iva_out_2 != 0)
                    <tr>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px"> 2</td>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px"> {{ number_format($total_incidencia_out_2, 2, ',', '.') }}</td>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px;text-align: right"> {{ number_format($total_iva_out_2, 2, ',', '.') }}</td>
                    </tr>
                    @endif

                    @if ($total_incidencia_out_5 != 0 || $total_iva_out_5 != 0)
                    <tr>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px"> 5</td>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px"> {{ number_format($total_incidencia_out_5, 2, ',', '.') }}</td>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px;text-align: right"> {{ number_format($total_iva_out_5, 2, ',', '.') }}</td>
                    </tr>
                    @endif

                    @if ($total_incidencia_out != 0 || $total_iva_out != 0)
                    <tr>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px"> 7</td>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px"> {{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px;text-align: right"> {{ number_format($total_iva_out, 2, ',', '.') }}</td>
                    </tr>
                    @endif

                    @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
                    <tr>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px"> 14</td>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px"> {{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                        <td style="padding: 2px 0;border-top: 2px solid #000;font-weight: bolder;font-size: 12px;text-align: right"> {{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                    </tr>
                    @endif

                    @endif
                </tbody>

            </table>
        </main>

        <footer style="width: 100%;">
            <div>
                <img src="data:image/png;base64,{{ $factura->qr_code }}" width="120">
            </div>

            <table>
                <tr>
                    <td style="font-weight: bolder;font-size: 12px">
                        OPERADOR: {{ $factura->user->name }} <br>
                        _______________________
                    </td>
                </tr>
            </table>
            <table style="border-top: 2px solid #000000">
                <tbody>
                    <tr>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px;padding-right: 20px;">
                            <strong>{{ __('messages.total') }}:</strong>
                        </td>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px">
                            {{ number_format($factura->valor_total + $factura->desconto, '2', ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px;padding-right: 20px;">
                            <strong>Desconto:</strong>
                        </td>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px">
                            {{ number_format($factura->desconto, '2', ',', '.') }}
                        </td>
                    </tr>

                    @if ($factura->pagamento == 'OU')
                    <tr>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px;padding-right: 20px;">
                            <strong>Multicaixa:</strong>
                        </td>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px">
                            {{ number_format($factura->valor_multicaixa, '2', ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px;padding-right: 20px;">
                            <strong>Numerário:</strong>
                        </td>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px">
                            {{ number_format($factura->valor_cash, '2', ',', '.') }}
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px;padding-right: 20px;">
                            <strong>Valor Entregue:</strong>
                        </td>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px">
                            {{ number_format($factura->valor_entregue, '2', ',', '.') }}
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px;padding-right: 20px;">
                            <strong>Troco:</strong>
                        </td>
                        <td style="text-align: right;padding: 3px 0;font-weight: bolder;font-size: 12px">
                            {{ $factura->valor_troco <= 0 ? 0 : number_format($factura->valor_troco, '2', ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <td id="bg-color-primary" colspan="2" style="font-size: 15px;padding: 5px 0">OBRIGADO VOLTE SEMPRE!</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align: center; font-size: 12px">
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
                        <td style="padding: 3px 0;text-align: left;margin-top: 12px;display: block;font-style: italic;font-weight: bolder;font-size: 12px" colspan="2">
                            Os bens/serviços foram colocados à disposição do adquirente na data do documento
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0;text-align: left;font-weight: bolder;font-size: 12px" colspan="2">{{ $factura->obterCaracteres($factura->hash) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0; text-align: left;border-top: 2px solid #000000;font-weight: bolder;font-size: 12px" colspan="2">{{ $factura->valor_extenso ?? 'sem descrição do valor por extensão' }}
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 3px 0; text-align: left;border-top: 2px solid #000000;font-weight: bolder;font-size: 12px" colspan="2">Software de facturação, desenvolvido pela {{ env('APP_NAME') }}</td>
                    </tr>

                </tbody>
            </table>
        </footer>

    </body>

</html>
