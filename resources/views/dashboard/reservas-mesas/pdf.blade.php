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
    <header>
        <table style="border: 0">
            <tr>
                <td style="border: 0;">
                    <img src="{{ $logotipo }}" style="height: 100px;width: 100px">
                </td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0">{{ $empresa->nome }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>NIF: </strong>{{ $empresa->nif }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>Endereço: </strong>{{ $empresa->morada }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>{{ $empresa->cidade }} - {{ $empresa->pais }}</strong></td>
            </tr>
        </table>
    </header>

    <table>
        <thead>
            <tr>
                <th colspan="11" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
            <tr>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px"> {{ __('messages.clientes') }} </th>
                <th colspan="2" style="border: 1px solid #010101;text-align: left;padding: 3px">
                    {{ $cliente ? $cliente->nome : 'TODOS' }}</th>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">Quarto</th>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">
                    {{ $quarto ? $quarto->nome : 'TODAS' }}</th>
            </tr>
            <tr>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">Estado Pagamento</th>
                <th colspan="2" style="border: 1px solid #010101;text-align: left;padding: 3px">
                    {{ $requests['status_pagamento'] ?? 'TODOS' }}</th>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">Estado Reserva</th>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">
                    {{ $requests['status_reserva'] ?? 'TODAS' }}</th>
            </tr>
            <tr>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">Data de Entrada</th>
                <th colspan="2" style="border: 1px solid #010101;text-align: left;padding: 3px">
                    {{ $requests['data_inicio'] ?? 'TODAS' }}</th>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">Data de Saída</th>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">
                    {{ $requests['data_final'] ?? 'TODAS' }}</th>
            </tr>

            <tr>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">Hora Entrada</th>
                <th colspan="2" style="border: 1px solid #010101;text-align: left;padding: 3px">
                    {{ $requests['hora_entrada'] ?? 'TODAS' }}</th>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">Hora Saída</th>
                <th colspan="3" style="border: 1px solid #010101;text-align: left;padding: 3px">
                    {{ $requests['hora_saida'] ?? 'TODAS' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">#</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">{{ __('messages.nome') }}</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Quarto</th>

                <th class="text-center" colspan="2" style="border: 1px solid #010101;padding: 2px;">Previsão
                    Entrada/Saída</th>
                <th class="text-center" colspan="2" style="border: 1px solid #010101;padding: 2px;">Check IN/OUT</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">{{ __('messages.estados') }}</th>

                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Dias</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Pagamento</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Total Factura</th>
            </tr>

            <tr>
                <th class="text-center" style="border: 1px solid #010101;padding: 2px;">Data/Hora</th>
                <th class="text-center" style="border: 1px solid #010101;padding: 2px;">Data/Hora</th>

                <th class="text-center" style="border: 1px solid #010101;padding: 2px;">Data/Hora</th>
                <th class="text-center" style="border: 1px solid #010101;padding: 2px;">Data/Hora</th>
            </tr>

        </thead>
        <tbody>
            @foreach ($reservas as $item)
            <tr style="background-color: {{ $item->status == 'CANCELADO' ? 'rgba(138, 39, 39, .3)' : '' }}">
                <td style="text-align: right;padding: 3px">{{ $item->id ?? "" }}</td>
                <td style="text-align: right;padding: 3px">{{ $item->cliente->nome }}</td>
                <td style="text-align: right;padding: 3px">{{ $item->quarto->nome }}</td>

                <td style="text-align: right;padding: 3px">{{ $item->data_inicio }} - {{ $item->hora_entrada }}
                </td>
                <td style="text-align: right;padding: 3px">{{ $item->data_final }} - {{ $item->hora_saida }}</td>

                <td style="text-align: right;padding: 3px">{{ $item->data_check_in }} - {{ $item->hora_check_in }}
                </td>
                <td style="text-align: right;padding: 3px">{{ $item->data_check_out }} -
                    {{ $item->hora_check_out }}</td>
                <td style="text-align: right;padding: 3px">{{ $item->status }}</td>

                <td style="text-align: right;padding: 3px">{{ $item->total_dias }}</td>
                @if ($item->pagamento == 'EFECTUADO')
                <td style="text-align: right;padding: 3px">{{ $item->pagamento }}</td>
                @endif
                @if ($item->pagamento == 'NAO EFECTUADO')
                <td style="text-align: right;padding: 3px">{{ $item->pagamento }}</td>
                @endif
                <td style="text-align: right;padding: 3px">{{ number_format($item->valor_total ?? 0, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
            <tr>
                <th colspan="11" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"> TOTAL
                    REGISTRO: {{ count($reservas) }}</th>
            </tr>
        </tbody>
    </table>

</body>

</html>
