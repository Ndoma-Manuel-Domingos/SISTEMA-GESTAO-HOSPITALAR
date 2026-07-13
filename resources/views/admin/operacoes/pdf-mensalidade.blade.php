<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>COMPROVATIVO DE PAGAMENTO DE MENSALIDADE</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #2c3e50;
            padding: 10px;
            font-size: 10px;
        }

        .topo {
            width: 100%;
            margin-bottom: 10px;
        }

        .empresa {
            text-align: center;
        }

        .empresa h1 {
            color: #0d6efd;
            font-size: 15px;
            margin-bottom: 5px;
        }

        .empresa p {
            font-size: 12px;
            color: #777;
            text-transform: uppercase;
        }

        .titulo {
            margin-top: 10px;
            background: #0d6efd;
            color: #FFF;
            padding: 10px;
            text-align: center;
            font-size: 13px;
            border-radius: 5px;
        }

        .bloco {
            border: 1px solid #DDD;
            border-radius: 8px;
            padding: 10px;
            margin-top: 20px;
        }

        .subtitulo {
            font-size: 12px;
            margin-bottom: 10px;
            color: #0d6efd;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            padding: 5px;
            border-bottom: 1px solid #EEE;
        }

        .label {
            width: 35%;
            background: #f8f9fa;
            font-weight: bold;
        }

        .status {
            padding: 8px;
            background: green;
            color: #FFF;
            border-radius: 5px;
            display: inline-block;
            font-size: 10px;
        }

        .pendente {
            background: red;
        }

        .valores {
            margin-top: 25px;
        }

        .total {
            font-size: 10px;
            color: green;
            font-weight: bold;
        }

        .assinaturas {
            margin-top: 30px;
        }

        .linha {
            width: 250px;
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 8px;
            font-size: 10px;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        .watermark {
            position: fixed;
            text-transform: uppercase;
            top: 25%;
            left: 30%;
            opacity: 0.05;
            font-size: 90px;
            transform: rotate(-20deg);
            color: #000;
        }

    </style>

</head>
<body>

    <div class="watermark">
        {{$mensalidade->status}}
    </div>

    <div class="topo">
        <div class="empresa">
            <h1>UEA - União dos empresários angolanos</h1>
            <p>
                Sistema de Gestão Financeira
            </p>
        </div>

        <div class="titulo">
            COMPROVATIVO DE PAGAMENTO DE MENSALIDADE
        </div>

    </div>

    <div class="bloco">

        <div class="subtitulo">
            Dados do Membro
        </div>

        <table>

            <tr>
                <td class="label">Nome do Membro</td>
                <td>{{ $mensalidade->membro->nome ?? 'N/D' }}</td>
            </tr>

            <tr>
                <td class="label">Código</td>
                <td>#{{ $mensalidade->id }}</td>
            </tr>

            <tr>
                <td class="label">Mês</td>
                <td>{{ $mensalidade->mes }}</td>
            </tr>

            <tr>
                <td class="label">Ano</td>
                <td>{{ $mensalidade->ano }}</td>
            </tr>

            <tr>
                <td class="label">Data de Vencimento</td>
                <td>
                    {{ date('d/m/Y', strtotime($mensalidade->data_vencimento)) }}
                </td>
            </tr>

            <tr>
                <td class="label">Data de Pagamento</td>
                <td>
                    {{ date('d/m/Y', strtotime($mensalidade->data_pagamento)) }}
                </td>
            </tr>

            <tr>
                <td class="label">Dias de Atraso</td>
                <td>
                    {{ $mensalidade->dias_atraso }} dias
                </td>
            </tr>

            <tr>
                <td class="label">Estado</td>
                <td>
                    <span class="{{ $mensalidade->status == 'pago' ? 'status' : 'status pendente' }} ">
                        {{ $mensalidade->status }}
                    </span>
                </td>
            </tr>

        </table>

    </div>

    <div class="bloco valores">

        <div class="subtitulo">
            Informações Financeiras
        </div>

        <table>

            <tr>
                <td class="label">Valor Original</td>
                <td>
                    {{ number_format($mensalidade->valor_original,2,',','.') }} Kz
                </td>
            </tr>

            <tr>
                <td class="label">Multa</td>
                <td>
                    {{ number_format($mensalidade->multa,2,',','.') }} Kz
                </td>
            </tr>

            <tr>
                <td class="label">Juros</td>
                <td>
                    {{ number_format($mensalidade->juros,2,',','.') }} Kz
                </td>
            </tr>

            <tr>
                <td class="label">Valor Total</td>
                <td class="total">
                    {{ number_format($mensalidade->valor_total,2,',','.') }} Kz
                </td>
            </tr>

            <tr>
                <td class="label">Valor Pago</td>
                <td>
                    {{ number_format($mensalidade->valor_pago,2,',','.') }} Kz
                </td>
            </tr>

            <tr>
                <td class="label">Saldo Devedor</td>
                <td>
                    {{ number_format($mensalidade->saldo_devedor,2,',','.') }} Kz
                </td>
            </tr>

        </table>

    </div>

    <div class="assinaturas">

        <table width="100%">
            <tr>

                <td>
                    <div class="linha">
                        Assinatura do Operador
                    </div>
                </td>

                <td align="right">
                    <div class="linha">
                        Assinatura do Cliente
                    </div>
                </td>

            </tr>
        </table>

    </div>

    <div class="footer">
        Documento gerado automaticamente pelo sistema em
        {{ date('d/m/Y H:i:s') }}
    </div>

</body>
</html>
