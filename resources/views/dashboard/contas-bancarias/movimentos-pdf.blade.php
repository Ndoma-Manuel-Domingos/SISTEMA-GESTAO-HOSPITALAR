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
                    <img src="{{ $logotipo ?? "" }}" style="height: 80px;width: 80px;margin-bottom: 10px;">
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
                <th colspan="10" style="text-transform: uppercase"> {{ $titulo ?? '' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="text-transform: uppercase" colspan="2">{{ __('messages.data_inicio') }}: {{ $requests['data_inicio'] ?? 'TODOS' }} </th>
                <th style="text-transform: uppercase" colspan="2">{{ __('messages.data_final') }}: {{ $requests['data_final'] ?? 'TODOS' }} </th>
                <th style="text-transform: uppercase" colspan="2">Operador: {{ $user->name ?? 'TODOS' }}</th>
                <th style="text-transform: uppercase" colspan="2">Conta Bancária: {{ $banco->nome ?? 'TODOS' }}</th>
                <th style="text-transform: uppercase" colspan="1">Moeda: {{ $empresa->moeda }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;">ID</th>
                <th style="border: 1px solid #010101;padding: 2px;">Descrição</th>
                <th style="border: 1px solid #010101;padding: 2px;">Número</th>
                <th style="border: 1px solid #010101;padding: 2px;">Operador</th>
                <th style="border: 1px solid #010101;padding: 2px;">Pagamento</th>
                <th style="border: 1px solid #010101;padding: 2px;">Centro de Custo</th>
                <th style="border: 1px solid #010101;padding: 2px;">Movimento</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Motante</th>
            </tr>
        </thead>

        <tbody>
            @php $credito = $debito = 0; @endphp
            @foreach ($movimentos as $item)
                <tr>
                    <td style="text-align: left;padding: 3px">{{ $item->id ?? '' }}</td>
                    <td style="text-align: left;padding: 3px">{{ $item->nome ?? '' }}</td>
                    <td style="text-align: left;padding: 3px">{{ $item->subconta->numero ?? '' }} {{ $item->subconta->nome ?? '' }}</td>
                    <td style="text-align: left;padding: 3px">{{ $item->user->name ?? '' }}</td>

                    @if ($item->formas == 'C')
                        <td style="text-align: left;padding: 3px">NUMÉRARIO</td>
                    @else
                        <td style="text-align: left;padding: 3px">MULTICAIXA</td>
                    @endif

                    <td style="text-align: left;padding: 3px">{{ $item->centro_custo->nome ?? "" }}</td>
                    @if ($item->movimento == 'E')
                        <td style="text-align: left;color: blue;padding: 3px">Entrada</td>
                        @php $debito += $item->motante; @endphp
                    @else
                        @php $credito += $item->motante; @endphp
                        <td style="text-align: left;color: red;padding: 3px">Saída</td>
                    @endif
                    
                    @if ($item->movimento == 'E')
                    <td style="text-align: right;color: blue;padding: 3px"> {{ number_format($item->motante ?? 0, 2, ',', '.') }}</td>
                    @else
                    <td style="text-align: right;color: red;padding: 3px"> {{ number_format($item->motante ?? 0, 2, ',', '.') }}</td>
                    @endif

                </tr>
            @endforeach
        </tbody>
        <tfoot style="margin-top: 50px">
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">Entradas</td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: right;color: blue"> {{ number_format($debito ?? 0, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">Saídas</td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: right;color: red"> {{ number_format($credito ?? 0, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left"></td>
                <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">SALDO FINAL</td>

                @php
                    $saldo = ($debito ?? 0) - ($credito ?? 0);
                @endphp
                @if ($saldo >= 0)
                    <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: right;color: blue"> {{ number_format($saldo, 2, ',', '.') }}</td>
                @else
                    <td style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: right;color: red"> {{ number_format($saldo, 2, ',', '.') }}</td>
                @endif
            </tr>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left" colspan="8">{{ __('messages.total') }}: {{ count($movimentos) }}</th>
            </tr>
        </tfoot>
    </table>

    <div>
        <p style="position: absolute; bottom: 20px;font-size: 12px">-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS</p>
    </div>
</body>

</html>
