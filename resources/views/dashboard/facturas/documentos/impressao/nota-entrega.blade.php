<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Nota de Entrega</title>

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
        }

        .logo {
            width: 120px;
        }

        .company-info {
            text-align: right;
        }

        .document-title {
            background: #0d6efd;
            color: white;
            padding: 12px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th {
            background: #f4f4f4;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .footer {
            margin-top: 50px;
        }

        .signature {
            width: 45%;
            display: inline-block;
            text-align: center;
        }

        .line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .status {
            padding: 5px 10px;
            background: green;
            color: white;
            border-radius: 4px;
            font-size: 11px;
        }

        .notes {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            min-height: 70px;
        }

    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER -->
        <div class="header">

            <table>
                <tr>

                    <td>
                        <img src="{{ $logotipo }}" class="logo">
                    </td>

                    <td class="company-info">

                        <h2>{{ $LOJAACTIVAOPERADOR->nome }}</h2>

                        <p>
                            {{ $LOJAACTIVAOPERADOR->morada }}<br>
                            Tel: {{ $LOJAACTIVAOPERADOR->telefone }} | Email: {{ $LOJAACTIVAOPERADOR->email }} <br>
                            NIF: {{ $LOJAACTIVAOPERADOR->nif }}
                        </p>

                    </td>

                </tr>
            </table>

        </div>

        <!-- TITLE -->
        <div class="document-title">
            NOTA DE ENTREGA
        </div>

        <!-- CLIENT INFO -->
        <table class="info-table">

            <tr>
                <td>
                    <strong>Nº Documento:</strong><br>
                    {{ $factura->factura_next }}
                </td>

                <td>
                    <strong>Data:</strong><br>
                    {{ date('d/m/Y', strtotime($factura->created_at)) }}
                </td>

                <td>
                    <strong>Status:</strong><br>

                    <span class="status">
                        Pendente
                    </span>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <strong>Cliente:</strong><br>
                    {{ $factura->nome_cliente ?? $factura->cliente->nome }}
                </td>

                <td>
                    <strong>Telefone:</strong><br>
                    {{ $factura->cliente->telefone ?? "000-000-000" }}
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <strong>Endereço de Entrega:</strong><br>
                    {{ $factura->cliente->localidade ?? "" }}
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Motorista:</strong><br>
                    Ndoma Manuel Domingos
                </td>

                <td>
                    <strong>Matrícula:</strong><br>
                    LD-23-23 HC
                </td>

                <td>
                    <strong>Peso Total:</strong><br>
                    120 KG
                </td>
            </tr>

        </table>

        <!-- ITEMS -->
        <table class="items-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Peso</th>
                </tr>
            </thead>

            <tbody>

                @foreach($items_facturas as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->produto->nome ?? "" }}</td>

                    <td>{{ number_format($item->quantidade, 1, ',', '.') }}</td>

                    <td>{{ $item->produto->peso }} {{ $item->produto->unidade->sigla }}</td>

                </tr>

                @endforeach

            </tbody>

        </table>

        <!-- NOTES -->
        <div class="notes">

            <strong>Observações:</strong><br><br>

            {{ $factura->observacao }}

        </div>

        <!-- SIGNATURES -->
        <div class="footer">

            <div class="signature">

                <div class="line">
                    Assinatura do Motorista
                </div>

            </div>

            <div class="signature" style="float:right;">

                <div class="line">
                    Assinatura do Cliente
                </div>

            </div>

        </div>

    </div>

</body>
</html>
