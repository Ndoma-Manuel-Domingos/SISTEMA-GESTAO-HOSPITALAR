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

    <table class="table">
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;">#</th>
                <th style="border: 1px solid #010101;padding: 2px;">Factura</th>
                <th style="border: 1px solid #010101;padding: 2px;"> {{ __('messages.clientes') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;"> {{ __('messages.data') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;">Vencimento</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.estados') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Valor Total</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Valor Dívida</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @if ($facturas)
            @foreach ($facturas as $key => $item)
            <tr>
                <td style="padding: 3px;text-align: left">{{ $key + 1 }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->factura_next }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->cliente->nome }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->data_emissao }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->data_vencimento }}</td>
                <td style="padding: 3px;text-align: left"><span class="bg-light-warning p-1"><i class="fas fa-exclamation-triangle"></i></span> {{ $item->status_factura }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->valor_total, 2, ',', '.') }}
                <td style="padding: 3px;text-align: right">{{ number_format($item->valor_divida, 2, ',', '.') }}
                <td style="padding: 3px;text-align: right">{{ number_format($item->valor_pago, 2, ',', '.') }}
                    {{ $empresa_logada->empresa->moeda }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="8" style="border: 1px solid #010101;padding: 2px;">TOTAL DE REGISTRO: </th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ count($facturas) }}</th>
            </tr>
        </tfoot>
    </table>

</body>

</html>
