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

    </style>
</head>

<body>

    <table style="border: 0">
        <tr>
            <td style="padding: 20px 0;border: 0;font-size: 12px">{{ $LOJAACTIVAOPERADOR->nome }}</td>
        </tr>
        <tr>
            <td style="border: 0;font-size: 12px"><strong style="font-size: 12px">NIF: </strong>{{ $LOJAACTIVAOPERADOR->nif }}</td>
        </tr>
        <tr>
            <td style="border: 0;font-size: 12px"><strong style="font-size: 12px">Endereço: </strong>{{ $LOJAACTIVAOPERADOR->morada }}</td>
        </tr>
        <tr>
            <td style="border: 0;font-size: 12px"><strong style="font-size: 12px">{{ $empresa_logada->empresa->cidade }} - {{ $empresa_logada->empresa->pais }}</strong></td>
        </tr>
    </table>

    @if ($empresa_logada->empresa->tipo_factura == 'Normal')
    <table>
        <thead>
            <tr>
                <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase"> {{ $titulo ?? "" }}</th>
            </tr>
            <tr>
                <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase"> {{ __('messages.data') }}: </th>
                <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase"> {{ $registro->data_at ?? "" }}</th>
            </tr>
            <tr>
                <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase"> {{ $registro->tipo_documento ?? "" }}: </th>
                <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase"> {{ $registro->sigla ?? "" }}</th>
            </tr>
            <tr>
                <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase"> TIPO DOCUMENTO </th>
                <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase"> {{ $registro->numero ?? "" }}</th>
            </tr>
            <tr>
                <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase"> REFERÊNCIA Nº: </th>
                <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase"> {{ $registro->codigo ?? "" }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left">ID</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left">{{ __('messages.codigo_barras') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left">{{ __('messages.lotes') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left">{{ __('messages.produtos') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left">{{ __('messages.quantidade') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left">{{ __('messages.preco_custo') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left">{{ __('messages.total') }}</th>
            </tr>
        </thead>

        <tbody>
            @php
                $total = 0;
                $totalQuantidade = 0;
            @endphp
            @foreach ($registro->items as $item)
                @php
                    $stock = $item->produto->converterDaBase($item->quantidade, $item->produto->unidade);
                    $totalQuantidade += $stock;
                    $subtotal = $item->preco_custo * $stock;
                    $total += $subtotal;
                @endphp
            <tr>
                <td style="padding: 3px;text-align: left">{{ $item->produto->id ?? '' }}</td>
                <td style="padding: 3px;text-align: right">{{ $item->produto->codigo_barra ?? '' }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->lote->lote ?? '' }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->produto->nome ?? '' }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format( $stock ?? 0, 2, ',', '.') }} (<small>{{ $item->produto->unidade->sigla ?? '' }}</small>)</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->preco_custo ?? 0, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($subtotal, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="4" style="border: 1px solid #010101;padding: 2px;text-align: left">{{ __('messages.quantidade') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($totalQuantidade, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: left"></th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total, 2, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="7" style="padding-top: 30px">Observação: {{ $registro->observacao ?? "" }}</th>
            </tr>
        </tfoot>

    </table>
    @endif

    @if ($empresa_logada->empresa->tipo_factura == 'Ticket')
    <div style="width: 300px">

        <table>
            <thead>
                <tr>
                    <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase;font-size: 13px"> {{ $titulo ?? "" }}</th>
                </tr>
                <tr>
                    <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase;font-size: 13px"> {{ __('messages.data') }}: </th>
                    <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase;font-size: 13px"> {{ $registro->data_at ?? "" }}</th>
                </tr>
                <tr>
                    <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase;font-size: 13px"> {{ $registro->tipo_documento ?? "" }}: </th>
                    <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase;font-size: 13px"> {{ $registro->sigla ?? "" }}</th>
                </tr>
                <tr>
                    <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase;font-size: 13px"> REGISTRO Nº: </th>
                    <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase;font-size: 13px"> {{ $registro->numero ?? "" }}</th>
                </tr>
                <tr>
                    <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase;font-size: 13px"> REFERÊNCIA Nº: </th>
                    <th style="border-top: 1px solid #010101;padding: 5px 0;text-transform: uppercase;font-size: 13px"> {{ $registro->codigo ?? "" }}</th>
                </tr>
            </thead>
        </table>

        <table style="width: 100%">
            <thead>
                <tr>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: left;font-size: 12px">{{ __('messages.produtos') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: left;font-size: 12px">{{ __('messages.codigo_barras') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: left;font-size: 12px">{{ __('messages.quantidade') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: left;font-size: 12px">{{ __('messages.preco_custo') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: left;font-size: 12px">{{ __('messages.total') }}</th>
                </tr>
            </thead>

            <tbody>
                @php
                $total = 0;
                $totalQuantidade = 0;
                @endphp
                @foreach ($registro->items as $item)    
                    @php
                        $stock = $item->produto->converterDaBase($item->quantidade, $item->produto->unidade);
                        $totalQuantidade += $stock;
                        $subtotal = $item->preco_custo * $stock;
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td colspan="5" style="padding: 3px;text-align: left;font-size: 15px">{{ $item->produto->nome ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px;text-align: left;font-size: 12px"></td>
                        <td style="padding: 3px;text-align: right;font-size: 12px">{{ $item->produto->codigo_barra ?? '' }}</td>
                        <td style="padding: 3px;text-align: right;font-size: 12px">{{ number_format($stock, 1, ',', '.') }} (<small>{{ $item->produto->unidade->sigla ?? '' }}</small>)</td>
                        <td style="padding: 3px;text-align: right;font-size: 12px">{{ number_format($item->preco_custo ?? 0, 2, ',', '.') }}</td>
                        <td style="padding: 3px;text-align: right;font-size: 12px">{{ number_format($subtotal, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: left">{{ __('messages.quantidade') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: left">{{ number_format($totalQuantidade ?? 0, 2, ',', '.') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: left"></th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: left">{{ number_format($total, 2, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="5" style="padding-top: 30px;font-size: 12px">Observação: {{ $registro->observacao ?? "" }}</th>
                </tr>
            </tfoot>

        </table>
    </div>
    @endif

</body>
</html>
