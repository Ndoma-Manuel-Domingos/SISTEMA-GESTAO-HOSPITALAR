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
            <td style="border: 0;">
                <img src="{{ $logotipo }}" alt="Logotipo da Empresa" style="text-align: center;height: 100px;width: 170px;">
            </td>
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
                <th colspan="9" style="padding: 2px;text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
            <tr>
                <th style="padding: 2px;border: 1px solid #010101;" colspan="5">{{ __('messages.lojas') }}</th>
                <th style="padding: 2px;border: 1px solid #010101;text-align: left;text-transform: uppercase" colspan="4">{{ $loja ? $loja->nome : 'TODOS' }}</th>
            </tr>
            <tr>
                <th style="padding: 2px;border: 1px solid #010101;" colspan="5">{{ __('messages.estados') }}</th>
                <th style="padding: 2px;border: 1px solid #010101;text-align: left;text-transform: uppercase" colspan="4">{{ $requests['status'] ?? 'TODOS' }}</th>
            </tr>
            <tr>
                <th style="padding: 2px;border: 1px solid #010101;" colspan="5">{{ __('messages.produtos') }}</th>
                <th style="padding: 2px;border: 1px solid #010101;text-align: left;text-transform: uppercase" colspan="4">{{ $produto ? $produto->nome : 'TODOS' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;" rowspan="2">Lote</th>
                <th style="border: 1px solid #010101;padding: 2px;" rowspan="2">{{ __('messages.codigo_barras') }}(Lote)</th>
                <th style="border: 1px solid #010101;padding: 2px;" rowspan="2">Estado(Lote)</th>
                <th style="border: 1px solid #010101;padding: 2px;" rowspan="2">{{ __('messages.codigo_barras') }} (Produto)</th>
                <th style="border: 1px solid #010101;padding: 2px;" rowspan="2">{{ __('messages.designacao') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: center;" colspan="3">Stock</th>
                <th style="border: 1px solid #010101;padding: 2px;" rowspan="2" class="text-right">{{ __('messages.preco_custo') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;" rowspan="2" class="text-right">{{ __('messages.preco_venda') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: center;" colspan="3">Valor Acumulado
                </th> {{-- --}}
            </tr>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;">Entrada</th>
                <th style="border: 1px solid #010101;padding: 2px;">Saída</th>
                <th style="border: 1px solid #010101;padding: 2px;">Actual</th>

                <th style="border: 1px solid #010101;padding: 2px;">Entrada</th>
                <th style="border: 1px solid #010101;padding: 2px;">Saída</th>
                <th style="border: 1px solid #010101;padding: 2px;">Actual</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($estoques as $item)
            <tr>
                <td style="text-align: right;padding: 3px">{{ $item->lote }}</td>
                <td style="text-align: right;padding: 3px">{{ $item->codigo_barra }}</td>
                @if ($item->status == 'activo')
                <td style="text-align: right;padding: 3px" class="text-light-success text-uppercase">
                    {{ $item->status }}</td>
                @else
                <td style="text-align: right;padding: 3px" class="text-light-danger text-uppercase">
                    {{ $item->status }}</td>
                @endif
                <td style="text-align: right;padding: 3px">{{ $item->produto->codigo_barra }}</td>
                <td style="text-align: right;padding: 3px">{{ $item->produto->nome ?? "" }}</td>
                @php
                $stock = 0;
                $stock_entrada = 0;
                $stock_saida = 0;
                @endphp
                @foreach ($item->registros as $item)
                @if ($item->tipo == 'E')
                @php $stock_entrada += $item->quantidade; @endphp
                @endif
                @if ($item->tipo == 'S')
                @php $stock_saida += $item->quantidade; @endphp
                @endif
                @php $stock += $item->quantidade; @endphp
                @endforeach
                <td style="text-align: right;padding: 3px">{{ $stock_entrada }}</td>
                <td style="text-align: right;padding: 3px">{{ $stock_saida }}</td>
                <td style="text-align: right;padding: 3px">{{ $stock_entrada - $stock_saida }}</td>
                <td style="text-align: right;padding: 3px">
                    {{ number_format($item->produto->preco_custo, '2', ',', '.') }}</td>
                <td style="text-align: right;padding: 3px">
                    {{ number_format($item->produto->preco_venda, '2', ',', '.') }}</td>
                <td style="text-align: right;padding: 3px">
                    {{ number_format($item->produto->preco_venda * $stock_entrada, '2', ',', '.') }}</td>
                {{-- --}}
                <td style="text-align: right;padding: 3px">
                    {{ number_format($item->produto->preco_venda * $stock_saida, '2', ',', '.') }}</td>
                {{-- --}}
                <td style="text-align: right;padding: 3px">
                    {{ number_format($item->produto->preco_venda * ($stock_entrada - $stock_saida), '2', ',', '.') }}
                </td> {{-- --}}
            </tr>
            @endforeach
        </tbody>

    </table>

</body>

</html>
