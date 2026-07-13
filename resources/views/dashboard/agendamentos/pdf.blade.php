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
                <th colspan="6" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_inicio') }}</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_final') }}</th>
                <th style="text-transform: uppercase">{{ __('messages.estados') }}</th>
                <th style="text-transform: uppercase"> {{ __('messages.clientes') }} </th>
            </tr>

            <tr>
                <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
                <th style="text-transform: uppercase">{{ $requests['status'] ?? 'TODOS' }}</th>
                <th>{{ $cliente ? $cliente->nome : 'TODOS' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 90px">Nº</th>
                <th style="text-align: left"> {{ __('messages.clientes') }} </th>
                <th style="text-align: left">Serviço/Produto</th>
                <th style="text-align: right">Hora</th>
                <th style="text-align: right"> {{ __('messages.data') }} </th>
                <th style="text-align: left">{{ __('messages.estados') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($agendas as $item)
            <tr>
                <td>{{ $item->numero }}</td>
                <td>{{ $item->cliente ? $item->cliente->nome : '' }}</td>
                <td>{{ $item->produto ? $item->produto->nome : '' }}</td>
                <td style="text-align: right">{{ $item->hora }}</td>
                <td style="text-align: right">{{ $item->data_at }}</td>
                <td style="text-transform: uppercase">{{ $item->status }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th style="padding: 5px;text-align: left" colspan="6">{{ __('messages.total') }}: {{ count($agendas) }}</th>
            </tr>
        </tfoot>
    </table>

    <div>
        <p style="position: absolute; bottom: 20px;font-size: 12px">-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS</p>
    </div>
</body>

</html>
