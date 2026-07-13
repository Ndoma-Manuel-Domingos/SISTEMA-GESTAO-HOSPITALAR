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
                <td style="text-transform: uppercase;padding: 3px" colspan="2" width="50"><strong>Nº da
                        Encomenda: {{ $encomenda->factura ?? '--' }}</strong></td>
                <td style="text-transform: uppercase;padding: 3px" colspan="2" width="50"><strong>Dados da
                        Entrega</strong></td>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td style="border: 1px solid #010101;padding: 2px;width: 30;text-align: left" width="25">
                    Fornecedor(a):</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right" width="25">
                    {{ $encomenda->fornecedor->nome ?? '--' }}</td>

                <td style="border: 1px solid #010101;padding: 2px;width: 30;text-align: left" width="25">
                    empresa_logada/Armazém:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right" width="25">
                    {{ $encomenda->empresa_logada->nome ?? '--' }}</td>
            </tr>


            <tr>
                <td style="border: 1px solid #010101;padding: 2px;width: 30;text-align: left" width="25">Data da
                    Encomenda:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right" width="25">
                    {{ $encomenda->data_emissao ?? '--' }}</td>

                <td style="border: 1px solid #010101;padding: 2px;width: 30;text-align: left" width="25">Previsão de
                    Entrega:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right" width="25">
                    {{ $encomenda->previsao_entrega ?? '--' }}</td>
            </tr>

            <tr>
                <td style="border: 1px solid #010101;padding: 2px;width: 30;text-align: left" width="25">
                    Utilizador(a):</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right" width="25">
                    {{ $encomenda->user ? $encomenda->user->name : 'Nenhum' }}</td>

                <td style="border: 1px solid #010101;padding: 2px;width: 30;text-align: left" width="25">Estado:
                </td>

                @if ($encomenda->status == 'pendente')
                <td style="border: 1px solid #010101;padding: 2px;text-align: right" width="25">
                    {{ $encomenda->status ?? '--' }}</td>
                @endif

                @if ($encomenda->status == 'entregue')
                <td style="border: 1px solid #010101;padding: 2px;text-align: right" width="25">
                    {{ $encomenda->status ?? '--' }}</td>
                @endif

                @if ($encomenda->status == 'cancelada')
                <td style="border: 1px solid #010101;padding: 2px;text-align: right" width="25">
                    {{ $encomenda->status ?? '--' }}</td>
                @endif

            </tr>

        </tbody>
    </table>

    <table>
        <thead>

            <tr>
                <th style="border: 1px solid #010101;padding: 2px;"></th>
                <th style="border: 1px solid #010101;padding: 2px;"></th>
                <th colspan="4" style="border: 1px solid #010101;padding: 2px;">{{ __('messages.preco_custo') }}</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;"> {{ __('messages.quantidade') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;"></th>
            </tr>

            <tr>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.codigo_barras') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.designacao') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;">IVA</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.desconto') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.preco_venda') }}<small class="text-light-danger">(Sugestão)</small></th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.preco_custo') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;">Encomendado</th>
                <th style="border: 1px solid #010101;padding: 2px;">Recebido</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.total') }}</th>
            </tr>

        </thead>
        <tbody>
            @if ($items)
            @php $subtotal = 0; @endphp
            @foreach ($items as $item)
            <tr>
                <td style="padding: 3px">{{ $item->produto->codigo_barra }}</td>
                <td style="padding: 3px">{{ $item->produto->nome ?? "" }}</td>
                <td style="padding: 3px;text-align: right;">{{ $item->iva }} %</td>
                <td style="padding: 3px;text-align: right;">{{ $item->desconto }} %</td>
                <td style="padding: 3px;text-align: right;"> {{ number_format($item->preco_venda, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                <td style="padding: 3px;text-align: right;">{{ number_format($item->custo, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>

                <td style="padding: 3px;text-align: right;">{{ $item->quantidade ?? 0 }} Uni</td>
                <td style="padding: 3px;text-align: right;">{{ $item->quantidade_recebida ?? 0 }} Uni</td>
                <td style="padding: 3px;text-align: right;">{{ number_format($item->total, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
            </tr>
            @php $subtotal += $item->total; @endphp
            @endforeach
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;" colspan="8">SubTotal:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right;"> <strong>{{ number_format($subtotal, 2, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;" colspan="8">Descontos:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right;"> <strong>{{ number_format($encomenda->desconto_valor, 2, ',', '.') }} <small>({{ $encomenda->desconto }}%)</small></td>
            </tr>
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;" colspan="8">Imposto:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right;"> <strong>{{ number_format($encomenda->imposto, 2, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;" colspan="8">Transporte:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($encomenda->custo_transporte, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;" colspan="8">Manuseamento:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($encomenda->custo_manuseamento, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;" colspan="8">Outros Custos:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($encomenda->outros_custos, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;" colspan="8">{{ __('messages.total') }}:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right;"> <strong>{{ number_format($encomenda->total, 2, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;" colspan="8">Total Pago:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right;"> <strong>{{ number_format($encomenda->tota_pago, 2, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td style="border: 1px solid #010101;padding: 2px;" colspan="8">Total A Pagar:</td>
                <td style="border: 1px solid #010101;padding: 2px;text-align: right;"> <strong>{{ number_format($encomenda->total_a_pagar, 2, ',', '.') }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>


</body>

</html>
