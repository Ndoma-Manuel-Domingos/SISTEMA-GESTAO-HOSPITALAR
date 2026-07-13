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
                <th colspan="5" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;">Descrição</th>
                <th style="border: 1px solid #010101;padding: 2px;">Codigo</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right"> Existência</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Valor</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ __('messages.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($produtos as $item)
            @if ($item->produto)
            @php 
                $stock = $item->produto->converterDaBase($item->produto->total_produto_loja_activa(), $item->produto->unidade);
                $subtotal = $item->produto->preco_custo * $stock;
                $total += $subtotal; 
            @endphp
            <tr>
                <td style="padding: 3px;text-align: left">{{ $item->produto->nome ?? "" }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->produto->codigo_barra ?? "" }}</td>
                <td style="padding: 3px;text-align: right"> {{ number_format($stock, 1, ',', '.') }} {{ $item->produto->unidade->sigla }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->produto->preco_custo ?? 0, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($subtotal, 2, ',', '.') }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="border-top: 1px solid #010101;padding: 3px;text-align: left"></td>
                <td style="border-top: 1px solid #010101;padding: 3px;text-align: left"></td>
                <td style="border-top: 1px solid #010101;padding: 3px;text-align: left"></td>
                <td style="border-top: 1px solid #010101;padding: 3px;text-align: left"></td>
                <td style="border-top: 1px solid #010101;padding: 3px;text-align: right;font-size: 10px;">{{ number_format($total ?? 0, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>


</body>

</html>
