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
                <th colspan="3" style="text-transform: uppercase;">{{ __('messages.data_inicio') }}</th>
                <th colspan="3" style="text-transform: uppercase;">{{ __('messages.data_final') }}</th>
            </tr>
            <tr>
                <th colspan="3" style="text-transform: uppercase;"> {{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th colspan="3" style="text-transform: uppercase;"> {{ $requests['data_final'] ?? 'TODOS' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;">Ref</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.codigo_barras') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.produtos') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.categoria') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">Total Documento</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">Total Retido</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($vendas as $key => $item)
            <tr>
                <td style="padding: 3px;text-align: left;">{{ $key + 1 }}</td>
                <td style="padding: 3px;text-align: left;">{{ $item->produto->codigo_barra ?? "" }}</td>
                <td style="padding: 3px;text-align: left;">{{ $item->produto->nome ?? "" }}</td>
                <td style="padding: 3px;text-align: left;">{{ $item->produto->categoria->categoria ?? "" }}</td>
                <td style="padding: 3px;text-align: right;">{{ number_format($item->total_valor_pagar ?? 0, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right;">{{ number_format($item->total_retencao_fonte ?? 0, 2, ',', '.') }}</td>
            </tr>
            @php $total += $item->total_retencao_fonte;  @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="border: 1px solid #010101;padding: 3px;text-align: right;" colspan="5">{{ __('messages.total') }}</th>
                <th style="border: 1px solid #010101;padding: 3px;text-align: right;">{{ number_format($total ?? 0, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    <div>
        <p style="position: absolute; bottom: 20px;font-size: 12px">-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS</p>
    </div>
</body>

</html>
