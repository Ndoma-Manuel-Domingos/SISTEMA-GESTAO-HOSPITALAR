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
            padding: 3px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 12px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 12px;
        }

        p {
            /* margin-bottom: 20px; */
            line-height: 25px;
            font-size: 12px;
            text-align: justify;
        }

        strong {
            font-size: 12px;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 13px;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 13px;
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
    <table style="border: 0;">
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
            <td style="border: 0"><strong>{{ $empresa_logada->empresa->cidade }} - {{ $empresa_logada->empresa->pais }}</strong></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="9" style="text-transform: uppercase"> {{ $titulo }} <span style="float: right">Data: {{ date('Y-m-d') }}</span></th>
            </tr>
        </thead>
    </table>

    <table>

        @if (!($empresa_logada->empresa->exibicao_relatorio == 'sintetico'))
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">#</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Nº de Registo</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ __('messages.descricao') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ __('messages.data') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Forma Pagamento</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ __('messages.clientes') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Operador</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Caixa</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ __('messages.total') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($resultadoUnificado as $contador => $item)
            <tr>
                <td style="padding: 3px;text-align: left">{{ $contador + 1 }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->id ?? "" }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->factura_next ?? ""  }}</td>
                <td style="padding: 3px;text-align: left">{{ date('Y-m-d', strtotime($item->created_at)) }} ÁS {{ date('H:i:s', strtotime($item->created_at)) }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->forma_pagamento($item->pagamento) }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->cliente->nome ?? ""  }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->user->name ?? '' }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->caixa->nome ?? '' }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->valor_total ?? 0, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        @endif

        <tfoot>
            <tr>
                <th colspan="5" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">ENTRADA </th>
                <th colspan="4" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_vendido_valor ?? 0, 2, ',', '.') }} </th>
            </tr>
            <tr>
                <th colspan="5" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">TOTAL
                    FACTURAÇÃO NUMERÁRIO</th>
                <th colspan="4" style="border: 1px solid #010101;padding: 2px;text-align: right">
                    {{ number_format($total_arrecadado_cash ?? 0, 2, ',', '.') }} </th>
            </tr>
            <tr>
                <th colspan="5" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">TOTAL
                    FACTURAÇÃO TPA</th>
                <th colspan="4" style="border: 1px solid #010101;padding: 2px;text-align: right">
                    {{ number_format($total_arrecadado_multicaixa ?? 0, 2, ',', '.') }} </th>
            </tr>
            <tr>
                <th colspan="5" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">TOTAL
                    FACTURAÇÃO DUPLO</th>
                <th colspan="4" style="border: 1px solid #010101;padding: 2px;text-align: right">
                    {{ number_format($total_duplo ?? 0, 2, ',', '.') }} </th>
            </tr>
            <tr>
                <th colspan="5" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">SALDO</th>
                <th colspan="4" style="border: 1px solid #010101;padding: 2px;text-align: right">
                    {{ number_format($total_vendido_valor ?? 0, 2, ',', '.') }} </th>
            </tr>
        </tfoot>
    </table>

    <table style="margin-top: 100px">
        <thead>
            <tr>
                <th colspan="9" style="text-transform: uppercase;border-bottom: 1px solid #000;padding: 10px 0;">
                    Impressor por:</th>
            </tr>
            <tr>
                <th style="text-transform: uppercase;padding: 10px 0;"> {{ Auth::user()->name }} </th>
            </tr>
        </thead>
    </table>
</body>
</html>
