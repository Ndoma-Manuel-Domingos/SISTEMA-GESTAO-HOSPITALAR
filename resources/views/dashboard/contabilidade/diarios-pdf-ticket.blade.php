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
            padding: 3px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 12px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 12px;
        }

        p {
            /* margin-bottom: 20px; */
            line-height: 25px;
            font-size: 12px;
            text-align: justify;
        }

        strong {
            font-size: 12px;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 13px;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 13px;
        }

        th,
        td {
            padding: 6px;
            font-size: 12px;
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
                <img src="{{ $logotipo }}" alt="Logotipo da Empresa" style="text-align: center;height: 80px;width: 80px;">
            </td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0;padding: 20px 0;">{{ $LOJAACTIVAOPERADOR->nome }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0;font-size: 13px"><strong style="font-size: 13px">NIF:</strong> {{ $LOJAACTIVAOPERADOR->nif }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0;font-size: 13px"><strong style="font-size: 13px">Endereço:</strong> {{ $LOJAACTIVAOPERADOR->morada }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0;font-size: 13px">{{ $empresa_logada->empresa->cidade }} - {{ $empresa_logada->empresa->pais }}</td>
        </tr>
    </table>
    
    <div>
        <table>
            <thead>
                <tr>
                    <th style="text-transform: uppercase;padding: 5px 0"> {{ $titulo }}</th>
                </tr>
                <tr>
                    <th style="text-transform: uppercase;padding: 2px 0"> DATA ABERTURA: {{ date('Y-m-d') }} </th>
                </tr>
                <tr>
                    <th style="text-transform: uppercase;padding: 2px 0"> DATA FACHO: {{ date('Y-m-d') }} </th>
                </tr>
                <tr>
                    <th style="text-transform: uppercase;padding: 2px 0"> OPERADOR: {{ Auth::user()->name }} </th>
                </tr>
            </thead>
        </table>

        <table>
            @if ($empresa_logada->empresa->exibicao_relatorio != 'sintetico')
            <thead>
                <tr>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right">#</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ __('messages.descricao') }} </th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right">Pagamento</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right">Estado</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ __('messages.total') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($resultadoUnificado as $contador => $item)
                <tr>
                    <td style="padding: 3px;text-align: left">{{ $contador + 1 }}</td>
                    <td style="padding: 3px;text-align: left">{{ $item->factura_next ?? "" }}</td>
                    <td style="padding: 3px;text-align: left">{{ $item->forma_pagamento($item->pagamento) }}</td>
                    <td style="padding: 3px;text-align: left;text-transform: uppercase">{{ $item->status_venda ?? 0 }}</td>
                    <td style="padding: 3px;text-align: right">{{ number_format($item->valor_total ?? 0, 2, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            @endif
            <tfoot>
                <tr>
                    <th colspan="5" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left;font-style: italic;">Resumo de Documentos</th>
                </tr>
                <tr>
                    <td colspan="3" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">Total Documentos Vendidos</td>
                    <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_documentos_vendidos ?? 0, 2, ',', '.') }}</th>
                </tr>
    
                <tr>
                    <th colspan="5" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left;font-style: italic;">Entradas por Tipo</th>
                </tr>
                <tr>
                    <td colspan="3" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">TOTAL Cash</td>
                    <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_arrecadado_cash ?? 0, 2, ',', '.') }} </th>
                </tr>
                <tr>
                    <td colspan="3" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">TOTAL Multicaixa</td>
                    <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_arrecadado_multicaixa ?? 0, 2, ',', '.') }} </th>
                </tr>
                <tr>
                    <td colspan="3" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">TOTAL Pagamento Duplo</td>
                    <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_duplo ?? 0, 2, ',', '.') }} </th>
                </tr>
    
                <tr>
                    <th colspan="5" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left;font-style: italic;">Movimentação de Produtos</th>
                </tr>
                <tr>
                    <td colspan="3" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">Total Produtos Vendidos</td>
                    <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_produtos_vendidos ?? 0, 2, ',', '.') }} </th>
                </tr>
                <tr>
                    <td colspan="3" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">Total Produtos Devolvidos</td>
                    <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_produtos_devolvidos ?? 0, 2, ',', '.') }} </th>
                </tr>
                <tr>
                    <td colspan="3" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">Total Saída (não venda)</td>
                    <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_produtos_saida ?? 0, 2, ',', '.') }} </th>
                </tr>
    
                <tr>
                    <th colspan="5" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left;font-style: italic;">Resumo Financeiro</th>
                </tr>
    
                <tr>
                    <td colspan="3" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">Saldo Final</td>
                    <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ number_format($total_vendido_valor ?? 0, 2, ',', '.') }} </th>
                </tr>
            </tfoot>
        </table>

        <table style="margin-top: 40px">
            <thead>
                <tr>
                    <th style="text-transform: uppercase;border-bottom: 1px solid #000;padding: 10px 0;">Impressor por:</th>
                </tr>
                <tr>
                    <th style="text-transform: uppercase;padding: 10px 0;"> {{ Auth::user()->name }} </th>
                </tr>
            </thead>
        </table>

    </div>
</body>

</html>
