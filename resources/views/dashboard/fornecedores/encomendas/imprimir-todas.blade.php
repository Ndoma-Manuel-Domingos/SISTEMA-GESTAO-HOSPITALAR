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

    <table>
        <thead>
            <tr>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_inicio') }}</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_final') }}</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.estados') }}</th>
            </tr>

            <tr>
                <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $requests['status'] ?? 'TODOS' }}</th>
            </tr>
        </thead>
    </table>


    <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
        <thead>
            <tr>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: left;">Nº Encomenda</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: left;"> {{ __('messages.fornecedores') }} </th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: left;"> {{ __('messages.data') }} </th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: left;">{{ __('messages.estados') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: center;" colspan="3">Custos</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: center;" colspan="2"> {{ __('messages.quantidade') }} </th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right;">Total S/IVA</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right;">Total C/IVA</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right;">Total A Pagar</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right;">{{ __('messages.total') }}</th>
            </tr>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">Transporte</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">Manuseamento</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">Outros</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">Encomendadas</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">Recebidas</th>

            </tr>
        </thead>
        <tbody>
            @php
                $quantidade = 0;
                $quantidade_recebida = 0;
                $total_sIva = 0;
                $total_cIVa = 0;
                $total_a_pagar = 0;
                $total = 0;
            @endphp

            @foreach ($encomendas as $item)
                <tr>
                    <td style="padding: 3px;text-align: left;">{{ $item->factura }}</td>
                    <td style="padding: 3px;text-align: left;">{{ $item->fornecedor->nome }}</td>
                    <td style="padding: 3px;text-align: left;">{{ $item->data_emissao }}</td>
                    @if ($item->status == 'pendente')
                        <td><span
                                style="padding: 3px;text-align: left;text-transform: uppercase;color: #cf9a08;">{{ $item->status }}</span>
                        </td>
                    @endif

                    @if ($item->status == 'entregue')
                        <td><span
                                style="padding: 3px;text-align: left;text-transform: uppercase;color: #0e83c7;">{{ $item->status }}</span>
                        </td>
                    @endif

                    @if ($item->status == 'cancelada')
                        <td><span
                                style="padding: 3px;text-align: left;text-transform: uppercase;color: #961313;">{{ $item->status }}</span>
                        </td>
                    @endif

                    <td style="padding: 3px;text-align: right;">
                        {{ number_format($item->custo_transporte, 2, ',', '.') }}</td>
                    <td style="padding: 3px;text-align: right;">
                        {{ number_format($item->custo_manuseamento, 2, ',', '.') }}</td>
                    <td style="padding: 3px;text-align: right;">{{ number_format($item->outros_custos, 2, ',', '.') }}
                    </td>

                    <td style="padding: 3px;text-align: right;">{{ number_format($item->quantidade, 2, ',', '.') }}
                    </td>
                    <td style="padding: 3px;text-align: right;">
                        {{ number_format($item->quantidade_recebida, 2, ',', '.') }}</td>

                    <td style="padding: 3px;text-align: right;">{{ number_format($item->total_sIva, 2, ',', '.') }}
                    </td>
                    <td style="padding: 3px;text-align: right;">{{ number_format($item->total_cIVa, 2, ',', '.') }}
                    </td>

                    <td style="padding: 3px;text-align: right;">{{ number_format($item->total_a_pagar, 2, ',', '.') }}</td>
                    <td style="padding: 3px;text-align: right;">{{ number_format($item->total, 2, ',', '.') }}</td>
                    @php

                        $quantidade += $item->quantidade;
                        $quantidade_recebida += $item->quantidade_recebida;
                        $total_sIva += $item->total_sIva;
                        $total_cIVa += $item->total_cIVa;
                        $total_a_pagar += $item->total_a_pagar;
                        $total += $item->total;

                    @endphp
                </tr>
            @endforeach

            <tr>
                <th style="border: 1px solid #010101;padding: 2px;"></th>
                <th style="border: 1px solid #010101;padding: 2px;"></th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;"></th>
                <th style="border: 1px solid #010101;padding: 2px;"></th>
                <th style="border: 1px solid #010101;padding: 2px;"></th>
                <th style="border: 1px solid #010101;padding: 2px;"></th>
                <th style="border: 1px solid #010101;padding: 2px;"></th>

                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">
                    {{ number_format($quantidade, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">
                    {{ number_format($quantidade_recebida, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">
                    {{ number_format($total_sIva, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">
                    {{ number_format($total_cIVa, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">
                    {{ number_format($total_a_pagar, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">
                    {{ number_format($total, 2, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>


</body>

</html>
