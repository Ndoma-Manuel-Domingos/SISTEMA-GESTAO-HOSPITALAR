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
            font-size: 12px;
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
                <th style="padding: 3px;text-transform: uppercase;border: 1px solid #030303"> {{ __('messages.periodo') }} </th>
            </tr>
            <tr>
                <th style="padding: 3px;text-transform: uppercase;border: 1px solid #030303">{{ \Carbon\Carbon::parse($dataInicial)->format('d/m/Y') }} Até {{ \Carbon\Carbon::parse($dataFinal)->format('d/m/Y') }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="padding: 3px;text-align: left;border: 1px solid #030303">{{ __('messages.conta') }}</th>
                <th style="padding: 3px;text-align: left;border: 1px solid #030303">{{ __('messages.produtos') }}</th>
                <th style="padding: 3px;text-align: left;border: 1px solid #030303">{{ __('messages.categoria') }}</th>
                <th style="padding: 3px;text-align: right;border: 1px solid #030303">{{ __('messages.quantidade') }}</th>
                <th style="padding: 3px;text-align: right;border: 1px solid #030303">Valor Investido (AKZ)</th>
                <th style="padding: 3px;text-align: right;border: 1px solid #030303">Lucro Aculumado (AKZ)</th>
                <th style="padding: 3px;text-align: right;border: 1px solid #030303">Valor Total (AKZ)</th>
            </tr>
        </thead>
        <tbody>
            @php
            $total_final = 0;
            $quantidade_final = 0;
            $lucro_total = 0;
            $investimento_total = 0;
            @endphp
            @foreach ($produtos as $item)
            <tr>
                <td style="padding: 3px;text-align: left">{{ $item->produto->conta ?? "" }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->produto->nome ?? "" }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->produto->categoria->categoria ?? "" }}</td>
                <td style="padding: 3px;text-align: right">{{ $item->total_vendido }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->valor_total - $item->lucro_total, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->lucro_total, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->valor_total, 2, ',', '.') }}</td>
            </tr>
            @php
            $lucro_total += $item->lucro_total;
            $total_final += $item->valor_total;
            $quantidade_final += $item->total_vendido;
            $investimento_total += ($item->valor_total - $item->lucro_total);
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="padding: 3px;text-align: left;border: 1px solid #030303">{{ __('messages.total') }}</th>
                <th style="padding: 3px;text-align: right;border: 1px solid #030303">{{ number_format($quantidade_final, 2, ',', '.') }}</th>
                <th style="padding: 3px;text-align: right;border: 1px solid #030303">{{ number_format($investimento_total, 2, ',', '.') }}</th>
                <th style="padding: 3px;text-align: right;border: 1px solid #030303">{{ number_format($lucro_total, 2, ',', '.') }}</th>
                <th style="padding: 3px;text-align: right;border: 1px solid #030303">{{ number_format($total_final, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

</body>

</html>
