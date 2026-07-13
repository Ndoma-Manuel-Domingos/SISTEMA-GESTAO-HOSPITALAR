<!DOCTYPE html>
<html lang="pdf">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }} | {{ $descricao }}</title>

    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
        }

        body {
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 15pt;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 12pt;
        }

        p {
            /* margin-bottom: 20px; */
            line-height: 25px;
            font-size: 12pt;
            text-align: justify;
        }

        strong {
            font-size: 12pt;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 12pt;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 10px;
        }

        th,
        td {
            padding: 6px;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }

        strong {
            font-size: 9px;
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
    </style>
</head>

<body>

    <table style="border: 0">
        <tr>
            <td style="border: 0;">
                <img src="{{ $logotipo }}" alt="Logotipo da Empresa" style="text-align: center;height: 100px;width: 170px;">
            </td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0;padding: 20px 0">{{ $LOJAACTIVAOPERADOR->nome }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>NIF: </strong>{{ $LOJAACTIVAOPERADOR->nif }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Endereço: </strong>{{ $LOJAACTIVAOPERADOR->morada }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>{{ $empresa_logada->empresa->cidade }} -
                    {{ $empresa_logada->empresa->pais }}</strong></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
        </thead>
    </table>


    <table>
        <thead>
            <tr>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_inicio') }}</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_final') }}</th>
                <th colspan="2" style="text-transform: uppercase"> {{ __('messages.clientes') }} </th>
                <th colspan="2" style="text-transform: uppercase">Operador</th>
            </tr>

            <tr>
                <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $cliente ? $cliente->nome : 'TODOS' }}</th>
                <th colspan="2">{{ $user ? $user->nome : 'TODOS' }}</th>
            </tr>
        </thead>
    </table>

    <table>


        @if (!($empresa_logada->empresa->exibicao_relatorio == 'sintetico'))
            <thead>
                <tr>
                    <th style="border: 1px solid #010101;padding: 2px;width: 20px">Nº</th>
                    <th style="border: 1px solid #010101;padding: 2px;">Factura</th>
                    <th style="border: 1px solid #010101;padding: 2px;"> {{ __('messages.clientes') }} </th>
                    <th style="border: 1px solid #010101;padding: 2px;">Operador</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: left">Forma Pagamento</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ __('messages.quantidade') }} </th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right">Incidência</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ __('messages.imposto') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: center"> {{ __('messages.data') }} </th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ __('messages.total') }}</th>
                </tr>
            </thead>
        @endif

        <tbody>
            @php
                $total_pagamentos_numerarios = 0;
                $total_pagamentos_depositos = 0;
                $total_pagamentos_multicaixas = 0;
                $total_pagamentos_transferencias = 0;
                $total_pagamentos_duplas = 0;

                $total_factura = 0;
                $total_recibos = 0;
                $total_factura_recibos = 0;
                $total_factura_proforma = 0;
                $total_nota_credito = 0;
                $total_geral = 0;
            @endphp
            @foreach ($documentos as $key => $item)
                <tr>
                    @if (!($empresa_logada->empresa->exibicao_relatorio == 'sintetico'))
                        <td style="padding: 3px;text-align: left">{{ $key + 1 }}</td>
                        <td style="padding: 3px;text-align: left">{{ $item->factura_next }}</td>
                        <td style="padding: 3px;text-align: left"> {{ $item->nome_cliente }}</td>
                        <td style="padding: 3px;text-align: left"> {{ $item->user->name ?? '' }}</td>
                        <td style="padding: 3px;text-align: left">
                            {{ $item->pagamento == 'NU' ? 'NUMERÁRIO' : ($item->pagamento == 'MB' ? 'MULTICAIXA' : 'DUPLO') }}
                        </td>
                        <td style="padding: 3px;text-align: right">{{ number_format($item->quantidade, 1, ',', '.') }}
                        </td>
                        <td style="padding: 3px;text-align: right">
                            {{ number_format($item->total_incidencia, 2, ',', '.') }}</td>
                        <td style="padding: 3px;text-align: right">{{ number_format($item->total_iva, 2, ',', '.') }}
                        </td>
                        <td style="padding: 3px;text-align: right">{{ date('Y-m-d', strtotime($item->created_at)) }}
                        </td>
                        <td style="padding: 3px;text-align: right">{{ number_format($item->valor_total, 2, ',', '.') }}
                        </td>
                    @endif

                    @if ($item->pagamento == 'NU')
                        @php $total_pagamentos_numerarios += $item->valor_total; @endphp
                    @endif
                    @if ($item->pagamento == 'MB')
                        @php $total_pagamentos_multicaixas += $item->valor_total; @endphp
                    @endif
                    @if ($item->pagamento == 'OU')
                        @php $total_pagamentos_duplas += $item->valor_total; @endphp
                    @endif
                    @if ($item->pagamento == 'TB')
                        @php $total_pagamentos_transferencias += $item->valor_total; @endphp
                    @endif
                    @if ($item->pagamento == 'DE')
                        @php $total_pagamentos_depositos += $item->valor_total; @endphp
                    @endif

                    @if ($item->factura == 'FT')
                        @php $total_factura += $item->valor_total; @endphp
                    @endif

                    @if ($item->factura == 'FR' && $item->anulado == 'N')
                        @php $total_factura_recibos += $item->valor_total; @endphp
                    @endif

                    @if ($item->factura == 'RG')
                        @php $total_recibos += $item->valor_total; @endphp
                    @endif

                    @if ($item->factura == 'NC')
                        @php $total_nota_credito += $item->valor_total; @endphp
                    @endif

                    @if ($item->factura == 'FP' || $item->factura == 'PP' || $item->factura == 'PF')
                        @php $total_factura_proforma += $item->valor_total; @endphp
                    @endif

                    @php $total_geral += $item->valor_total; @endphp
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="8" style="border: 1px solid #010101;padding: 2px;">TOTAL DE REGISTRO: </th>
                <th colspan="3" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ count($documentos) }}</th>
            </tr>

            <tr>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;">PAG. NUMERÁRIOS</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;">PAG. MULTICAIXA</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;">PAG. DEPOSITOS</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;">PAG. DUPLO</th>
                <th colspan="3" style="border: 1px solid #010101;padding: 2px;">PAG. TRANSFERÊNCIAS</th>
            </tr>

            <tr>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_pagamentos_numerarios, 2, ',', '.') }}</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_pagamentos_multicaixas, 2, ',', '.') }}</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_pagamentos_depositos, 2, ',', '.') }}</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_pagamentos_duplas, 2, ',', '.') }}</th>
                <th colspan="3" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_pagamentos_transferencias, 2, ',', '.') }}</th>
            </tr>

            <tr>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;">TOTAL FACTURAS</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;">TOTAL FACTURA RECIBOS</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;">TOTAL FACTURA PROFORMA</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;">TOTAL RECIBOS</th>
                <th colspan="3" style="border: 1px solid #010101;padding: 2px;">TOTAL NOTA DE CREDITO</th>
            </tr>
            <tr>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_factura, 2, ',', '.') }}</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_factura_recibos, 2, ',', '.') }}</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_factura_proforma, 2, ',', '.') }}</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_recibos, 2, ',', '.') }}</th>
                <th colspan="3" style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_nota_credito, 2, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="8" style="border: 1px solid #010101;padding: 2px;">TOTAL GERAL COM FACTURAS ANULADAS: </th>
                <th colspan="3" style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_geral, 2, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="8" style="border: 1px solid #010101;padding: 2px;">TOTAL GERAL: </th>
                <th colspan="3" style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_factura_recibos, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>


</body>

</html>
