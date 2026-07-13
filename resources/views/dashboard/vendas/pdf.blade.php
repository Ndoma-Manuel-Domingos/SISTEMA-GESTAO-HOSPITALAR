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
            font-size: 13pt;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 12px;
        }

        th,
        td {
            padding: 6px;
            font-size: 12px;
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
            <td style="padding: 20px 0;border: 0">{{ $LOJAACTIVAOPERADOR->nome }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>NIF: </strong>{{ $LOJAACTIVAOPERADOR->nif }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Endereço: </strong>{{ $LOJAACTIVAOPERADOR->morada }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>{{ $empresa_logada->empresa->cidade }} - {{ $empresa_logada->empresa->pais }}</strong></td>
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
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.lojas') }}</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_inicio') }}</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_final') }}</th>
                <th colspan="2" style="text-transform: uppercase">Operador</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.caixa') }}</th>
            </tr>

            <tr>
                <th colspan="2">{{ $loja ? $loja->name : 'TODOS' }}</th>
                <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $user ? $user->name : 'TODOS' }}</th>
                <th colspan="2">{{ $caixa ? $caixa->nome : 'TODOS' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        @if (!($empresa_logada->empresa->exibicao_relatorio == 'sintetico'))
        <thead>
            <tr>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;">Loja</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;">Factura</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">V.Entregue</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ __('messages.quantidade') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Desc.</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left">Pagamento</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left">Operador</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left"> {{ __('messages.clientes') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Total</th>
            </tr>
        </thead>
        @endif

        <tbody>
            @php
            $total_vendido_anulado_valor = 0;
            $total_Custo_produto_vendido_anulado = 0;
            $total_ganho_vendas_anulado = 0;
            @endphp
            @foreach ($vendas as $item)
            @if (!($empresa_logada->empresa->exibicao_relatorio == 'sintetico'))
            <tr>
                <td colspan="2">{{ $item->factura->loja->nome ?? "" }}</td>
                <td colspan="2">{{ $item->factura->factura_next ?? "" }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->preco_unitario ?? 0, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->quantidade ?? 0, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->desconto_aplicado_valor ?? 0, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->factura->forma_pagamento($item->pagamento) }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->factura->user->name ?? '' }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->factura->cliente->nome ?? "" }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->valor_pagar ?? 0, 2, ',', '.') }}
                </td>
            </tr>
            @endif

            @if ($item->factura->status_factura == 'anulada')
            @php
            $total_vendido_anulado_valor += $item->valor_pagar;
            $total_Custo_produto_vendido_anulado += $item->custo;
            $total_ganho_vendas_anulado += $item->lucro;
            @endphp
            @endif

            @endforeach
        </tbody>

        <tfoot>

            <tr>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;">PAG. NUMERÁRIOS</th>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_arrecadado_cash ?? 0, 2, ',', '.') }}</th>
            </tr>

            <tr>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;">PAG. MULTICAIXA</th>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_arrecadado_multicaixa ?? 0, 2, ',', '.') }}</th>
            </tr>

            <tr>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;">TOTAL VENDIDO</th>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;text-align: right;">{{ number_format($total_vendido_valor ?? 0, 2, ',', '.') }}</th>
            </tr>

            <tr>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;">TOTAL ANULADO</th>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;text-align: right;">{{ number_format($total_vendido_anulado_valor ?? 0, 2, ',', '.') }}</th>
            </tr>

            <tr>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;">TOTAL CUSTO</th>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;text-align: right;">{{ number_format($total_Custo_produto_vendido - $total_Custo_produto_vendido_anulado, 2, ',', '.') }}</th>
            </tr>

            <tr>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;">TOTAL GANHO</th>
                <th colspan="6" style="border: 1px solid #010101;padding: 2px;text-align: right;">{{ number_format($total_ganho_vendas - $total_ganho_vendas_anulado, 2, ',', '.') }}</th>
            </tr>

            <tr>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left;" colspan="6">TOTAL FINAL:</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;" colspan="6">{{ number_format($total_vendido_valor -  $total_vendido_anulado_valor, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

</body>

</html>
