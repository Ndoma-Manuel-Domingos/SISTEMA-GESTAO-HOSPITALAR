<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* HEADER AZUL */
        .header {
            background: #0d6efd;
            color: white;
            padding: 12px;
            display: flex;
            justify-content: space-between;
        }

        .header h2 {
            margin: 0;
        }

        /* BLOCO SEGURADORA */
        .info {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }

        /* TABELA PRINCIPAL */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #0d6efd;
            color: white;
            padding: 8px;
            font-size: 11px;
        }

        td {
            padding: 7px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }

        /* SUBTABELA (contas) */
        .conta-box {
            margin-top: 15px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .conta-title {
            background: #f2f2f2;
            padding: 6px;
            font-weight: bold;
        }

        /* TOTAIS */
        .totais {
            margin-top: 20px;
            width: 300px;
            float: right;
            border: 1px solid #0d6efd;
            padding: 10px;
        }

        .totais div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .total {
            font-size: 15px;
            font-weight: bold;
            color: #0d6efd;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            text-align: center;
            color: #777;
        }

    </style>

</head>
<body>
    <div class="header">

        <div>
            <h2>FACTURA SEGURADORA</h2>
            <small>{{ $factura->numero }}</small>
        </div>

        <div style="text-align:right">
            <strong>{{ $factura->seguradora->nome }}</strong><br>
            Período: {{ $factura->mes }}/{{ $factura->ano }}
        </div>

    </div>

    @foreach($factura->contas as $conta)

    <div class="conta-box">

        <div class="conta-title">
            Conta: {{ $conta->codigo }} |
            Paciente: {{ $conta->paciente->nome }}
        </div>

        <table>

            <thead>
                <tr>
                    <th>Serviço</th>
                    <th>Qtd</th>
                    <th>Valor Seguradora</th>
                </tr>
            </thead>

            <tbody>

                @foreach($conta->itens as $item)

                <tr>

                    <td>{{ $item->descricao }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>
                        {{ number_format($item->valor_seguradora,2,',','.') }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    @endforeach

    <div class="totais">

        <div>
            <span>Subtotal</span>
            <span>{{ number_format($factura->subtotal,2,',','.') }}</span>
        </div>

        <div>
            <span>Pago</span>
            <span>{{ number_format($factura->valor_pago,2,',','.') }}</span>
        </div>

        <div class="total">
            <span>Total em dívida</span>
            <span>{{ number_format($factura->saldo,2,',','.') }}</span>
        </div>

    </div>
    <div class="footer">

        Documento de cobrança de seguradora gerado pelo sistema hospitalar.<br>
        Este documento não constitui factura fiscal certificada pela AGT.

    </div>

</body>
</html>
