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
        <table style="padding: 5px 0;">
            <tr>
                <td style="border: 0;">
                    <img src="{{ $logotipo }}" style="height: 100px;width: 100px">
                </td>
            </tr>
            <tr style="padding: 5px 0;">
                <td style="padding: 5px 0;padding: 20px 0;">{{ $LOJAACTIVAOPERADOR->nome }}</td>
            </tr>
            <tr style="padding: 5px 0;">
                <td style="padding: 5px 0;"><strong>NIF: </strong>{{ $LOJAACTIVAOPERADOR->nif }}</td>
            </tr>
            <tr style="padding: 5px 0;">
                <td style="padding: 5px 0;"><strong>Endereço: </strong>{{ $LOJAACTIVAOPERADOR->morada }}</td>
            </tr>
            <tr style="padding: 5px 0;">
                <td style="padding: 5px 0;"><strong>{{ $empresa_logada->empresa->cidade }} -
                        {{ $empresa_logada->empresa->pais }}</strong></td>
            </tr>
        </table>
    </header>

    <table>
        <thead>
            <tr>
                <th colspan="10" style="padding: 5px 0;text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2" style="text-transform: uppercase;border: 1px solid #010101;padding: 2px;width: 20px">
                    {{ __('messages.data_inicio') }}</th>
                <th colspan="2" style="text-transform: uppercase;border: 1px solid #010101;padding: 2px;width: 20px">
                    {{ __('messages.data_final') }}</th>
            </tr>

            <tr>
                <th colspan="2" style="text-transform: uppercase;border: 1px solid #010101;padding: 2px;width: 20px">
                    {{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th colspan="2" style="text-transform: uppercase;border: 1px solid #010101;padding: 2px;width: 20px">
                    {{ $requests['data_final'] ?? 'TODOS' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="padding: 2px;padding: 3px;text-align: lef;border: 1px solid #010101">ID</th>
                <th style="padding: 2px;padding: 3px;text-align: left;border: 1px solid #010101">{{ __('messages.designacao') }}</th>
                <th style="padding: 2px;padding: 3px;text-align: left;border: 1px solid #010101">{{ __('messages.preco') }}</th>
                <th style="padding: 2px;padding: 3px;text-align: left;border: 1px solid #010101"> {{ __('messages.quantidade') }} </th>
                <th style="padding: 2px;padding: 3px;text-align: right;border: 1px solid #010101">{{ __('messages.total') }}</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($vendas as $item)
            <tr>
                <td>{{ $item->id ?? "" }}</td>
                <td>{{ $item->produto->nome ?? "" }}</td>
                <td style="text-align: right">{{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($item->valor_pagar, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th style="padding: 2px;padding: 3px;text-align: left;border: 1px solid #010101">{{ __('messages.total') }}</th>
                <th colspan="4" style="padding: 2px;padding: 3px;text-align: right;border: 1px solid #010101">
                    {{ number_format($total_venda, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
