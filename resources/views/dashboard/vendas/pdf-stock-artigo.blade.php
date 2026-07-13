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
            font-size: 13pt;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 12px;
        }

        th,
        td {
            padding: 6px;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        strong {
            font-size: 12px;
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
                <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_inicio') }}</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_final') }}</th>
                <th colspan="2" style="text-transform: uppercase">Loja</th>
                <th colspan="2" style="text-transform: uppercase">Tipo</th>
            </tr>

            <tr>
                <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $loja ? $loja->nome : 'TODOS' }}</th>
                @if ($requests['tipo_preco'] == "PV")
                <th colspan="2">PVP</th>
                @else
                <th colspan="2">PMC</th>
                @endif
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.codigo_barras') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.designacao') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ __('messages.quantidade') }}</th>
                
                @if ($requests['tipo_preco'] == "PV")
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ __('messages.preco_venda') }}</th>
                @else
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Custo</th>
                @endif
                
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ __('messages.imposto') }}</th>
                
                @if ($requests['tipo_preco'] == "PV")
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Desc.</th>
                <th style="border: 1px solid #010101;padding: 2px;text-right">Qtd. Vend.</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">T. Liq. Vendido</th>
                @endif
                
                @if ($requests['tipo_preco'] == "PV")
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">T. Geral</th>
                @else
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Total</th>
                @endif
                
                @if ($requests['tipo_preco'] == "PV")
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Lucro</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Custo</th>
                @endif
            </tr>
        </thead>

        <tbody>

            @php
                $total_liquido_vendido_valor = 0;
                $total_liquido_restante_valor = 0;
                $total_liquido_geral_valor = 0;
                $total_retencao_acumuada = 0;
                $total_liquido_geral = 0;
                $lucro = 0;
                $custo = 0;
            @endphp

            @foreach ($dados as $item)
            <tr>
                <td style="padding: 3px;text-align: left">{{ $item->codigo_barra }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->produto }}</td>
                <td style="padding: 3px;text-align: right"> {{ number_format($item->quantidade_estoque, 2, ',', '.') }} {{ $item->unidade->sigla ?? "" }}</td>
                @if ($requests['tipo_preco'] == "PV")
                <td style="padding: 3px;text-align: right">{{ number_format($item->preco, 2, ',', '.') }}</td>
                @else
                <td style="padding: 3px;text-align: right">{{ number_format($item->preco_custo, 2, ',', '.') }}</td>
                @endif
                
                <td style="padding: 3px;text-align: right">{{ number_format($item->imposto, 2, ',', '.') }}</td>
                
                @if ($requests['tipo_preco'] == "PV")
                <td style="padding: 3px;text-align: right">{{ number_format($item->desconto, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right"> {{ number_format($item->quantidade_vendida, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right"> {{ number_format($item->total_liquido_vendido, 2, ',', '.') }}</td>
                @endif
                
                @if ($requests['tipo_preco'] == "PV")
                <td style="padding: 3px;text-align: right"> {{ number_format($item->preco * $item->quantidade_estoque, 2, ',', '.') }}</td>
                @else
                <td style="padding: 3px;text-align: right"> {{ number_format($item->total_liquido_geral, 2, ',', '.') }}</td>
                @endif
                
                @if ($requests['tipo_preco'] == "PV")
                <td style="padding: 3px;text-align: right"> {{ number_format($item->total_liquido_lucro, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right"> {{ number_format($item->total_liquido_custo, 2, ',', '.') }}</td>
                @endif

                @php
                $total_liquido_vendido_valor += $item->total_liquido_vendido;
                $total_liquido_restante_valor += $item->preco * $item->quantidade_estoque;
                $total_liquido_geral_valor += $item->total_liquido_geral;
                $total_retencao_acumuada += $item->totalRetencaoAcumuada;
                $lucro += $item->total_liquido_lucro;
                $custo += $item->total_liquido_custo;
                $total_liquido_geral += $item->total_liquido_geral;
                @endphp
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">{{ __('messages.total') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
                @if ($requests['tipo_preco'] == "PV")
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
                @endif

                <th style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($total_liquido_vendido_valor, 2, ',', '.') }}</th>
                
                @if ($requests['tipo_preco'] == "PV")
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($total_liquido_restante_valor, 2, ',', '.') }}</th>
                @else
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($total_liquido_geral, 2, ',', '.') }}</th>
                @endif
                
                @if ($requests['tipo_preco'] == "PV")
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($lucro, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($custo, 2, ',', '.') }}</th>
                @endif
            </tr>
        </tfoot>
    </table>

    <div>
        <p style="position: absolute; bottom: 20px;font-size: 12px">-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS</p>
    </div>


</body>

</html>
