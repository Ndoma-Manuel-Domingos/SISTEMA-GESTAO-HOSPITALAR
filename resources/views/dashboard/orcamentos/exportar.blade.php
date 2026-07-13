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
                <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="text-transform: uppercase">Conta</th>
                <th style="text-transform: uppercase"> {{ __('messages.fornecedores') }} </th>
                <th style="text-transform: uppercase"> {{ __('messages.clientes') }} </th>
                <th style="text-transform: uppercase">{{ __('messages.estados') }}</th>
                <th style="text-transform: uppercase">Tipo Movimento</th>
                <th style="text-transform: uppercase">{{ __('messages.centro_custos') }}</th>
                <th style="text-transform: uppercase">{{ __('messages.data_inicio') }}</th>
                <th style="text-transform: uppercase">{{ __('messages.data_final') }}</th>
            </tr>

            <tr>
                <th>{{ $subconta ? $subconta->nome : 'TODOS' }}</th>
                <th>{{ $fornecedor ? $fornecedor->nome : 'TODOS' }}</th>
                <th>{{ $cliente ? $cliente->nome : 'TODOS' }}</th>
                <th>{{ $requests['status'] ?? 'TODOS' }}</th>
                <th>{{ $requests['tipo_movimento'] ?? 'TODOS' }}</th>
                <th>{{ $centroCusto ? $centroCusto->nome : 'TODOS' }}</th>
                <th>{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th>{{ $requests['data_final'] ?? 'TODOS' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        @if (!($empresa_logada->empresa->exibicao_relatorio == 'sintetico'))
            <thead>
                <tr>
                    <th style="border: 1px solid #010101;padding: 2px;">#</th>
                    <th style="border: 1px solid #010101;padding: 2px;">Referência</th>
                    <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.estados') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.centro_custos') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;">Dispesa/Receita</th>
                    <th style="border: 1px solid #010101;padding: 2px;">Fornecedor/Cliente</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right"> {{ __('messages.data') }} </th>
                    <th style="border: 1px solid #010101;padding: 2px;">Pagamento/Recebimento</th>
                    <th style="border: 1px solid #010101;padding: 2px;text-align: right">Motante</th>
                </tr>
            </thead>
        @endif

        <tbody>
            @php
                $totalReceita = 0;
                $totalDespesa = 0;
                $totalSaldo = 0;
            @endphp
            @foreach ($operacoes as $key => $item)

                @if (!($empresa_logada->empresa->exibicao_relatorio == 'sintetico'))
                    <tr>
                        <td style="padding: 3px">{{ $key + 1 }}</td>
                        <td style="padding: 3px">{{ $item->nome ?? '' }}</td>
                        <td style="padding: 3px">{{ $item->status ?? '' }}</td>
                        <td style="padding: 3px">{{ $item->centro_custo->nome ?? '' }}</td>
                        <td style="padding: 3px">
                            {{ $item->type == 'D' ? $item->dispesa->nome ?? '' : $item->receita->nome ?? '' }}</td>

                        <td style="padding: 3px">
                            {{ $item->type == 'D' ? ($item->fornecedor_id ? $item->fornecedor->nome ?? '' : $item->user->name ?? '') : ($item->cliente_id ? $item->cliente->nome : $item->user->name) }}
                        </td>
                        <td style="text-align: right">{{ $item->date_at }}</td>

                        @if ($item->formas == 'C')
                            <td style="padding: 3px">{{ $item->subconta->nome ?? '' }}</td>
                        @else
                            @if ($item->formas == 'B')
                                <td style="padding: 3px">{{ $item->subconta->nome ?? '' }}</td>
                            @else
                                <td style="padding: 3px">Outras</td>
                            @endif
                        @endif

                        @if ($item->type == 'D')
                            <td style="padding: 3px;color: red;text-align: right">
                                -{{ number_format($item->motante, 2, ',', '.') }}</td>
                        @else
                            <td style="padding: 3px;color: green;text-align: right">
                                +{{ number_format($item->motante, 2, ',', '.') }}</td>
                        @endif

                    </tr>
                @endif

                @if ($item->type == 'D')
                    @php
                        $totalDespesa += $item->motante;
                    @endphp
                @else
                    @php
                        $totalReceita += $item->motante;
                    @endphp
                @endif

            @endforeach
            <tr>
                <td colspan="7"
                    style="border: 1px solid #010101;padding: 2px;text-align: right;color: white;text-align: right;background-color: rgba(15, 121, 15, 0.5)">
                    <strong>TOTAL RECEITAS</strong></td>
                <td colspan="1"
                    style="border: 1px solid #010101;padding: 2px;text-align: right;color: white;text-align: right;background-color: rgba(15, 121, 15, 0.5)">
                    <strong>{{ number_format($totalReceita, 2, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="7"
                    style="border: 1px solid #010101;padding: 2px;text-align: right;color: white;text-align: right;background-color: rgba(214, 49, 19, 0.5)">
                    <strong>TOTAL DESPESAS</strong></td>
                <td colspan="1"
                    style="border: 1px solid #010101;padding: 2px;text-align: right;color: white;text-align: right;background-color: rgba(214, 49, 19, 0.5)">
                    <strong>{{ number_format($totalDespesa, 2, ',', '.') }}</strong></td>
            </tr>
            @php $totalSaldo = $totalReceita - $totalDespesa ; @endphp
            <tr>
                <td colspan="7"
                    style="border: 1px solid #010101;padding: 2px;text-align: right;color: white;text-align: right;background-color: #2c2c2c">
                    <strong>SALDO FINAL</strong></td>
                <td colspan="1"
                    style="border: 1px solid #010101;padding: 2px;text-align: right;color: white;text-align: right;background-color: #2c2c2c">
                    <strong>{{ number_format($totalSaldo, 2, ',', '.') }}</strong></td>
            </tr>
        </tbody>

        <tfoot>
        </tfoot>
    </table>

</body>

</html>
