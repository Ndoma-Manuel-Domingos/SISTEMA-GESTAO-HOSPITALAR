<!DOCTYPE html>
<html lang="pt">
<head>

    <meta charset="UTF-8">

    <title>Relatório Financeiro</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 30px;
        }

        /* HEADER */

        .header {
            width: 100%;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header table {
            width: 100%;
        }

        .logo {
            width: 90px;
        }

        .titulo {
            text-align: right;
        }

        .titulo h1 {
            color: #0d6efd;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .titulo p {
            color: #777;
            font-size: 12px;
        }

        /* INFO BOX */

        .info {
            margin-top: 20px;
            margin-bottom: 25px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .label {
            font-weight: bold;
            background: #f8f9fa;
            width: 180px;
        }

        /* RESUMO */

        .resumo {
            margin-bottom: 25px;
        }

        .card {
            width: 31%;
            display: inline-block;
            padding: 15px;
            margin-right: 2%;
            border-radius: 8px;
            color: #fff;
        }

        .card:last-child {
            margin-right: 0;
        }

        .bg-primary {
            background: #0d6efd;
        }

        .bg-light-success {
            background: #198754;
        }

        .bg-dark {
            background: #343a40;
        }

        .card-title {
            font-size: 13px;
            margin-bottom: 8px;
        }

        .card-value {
            font-size: 22px;
            font-weight: bold;
        }

        /* TABLE */

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table thead {
            background: #0d6efd;
            color: #fff;
        }

        .table th {
            padding: 12px;
            font-size: 12px;
            text-align: left;
        }

        .table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 11px;
            background: #e9ecef;
        }

        /* FOOTER */

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

    </style>

</head>

<body>

    <!-- HEADER -->

    <div class="header">

        <table>

            <tr>

                <td>

                    <!-- LOGO -->
                    <img src="{{ public_path('logo.png') }}" class="logo">

                </td>

                <td class="titulo">

                    <h1>RELATÓRIO FINANCEIRO</h1>

                    <p>
                        Evolução Financeira das Empresas
                    </p>

                </td>

            </tr>

        </table>

    </div>

    <!-- INFORMAÇÕES -->

    <div class="info">

        <table>

            <tr>

                <td class="label">
                    Data Inicial
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }}
                </td>

                <td class="label">
                    Data Final
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Tipo Relatório
                </td>

                <td>
                    {{ ucfirst($tipo) }}
                </td>

                <td class="label">
                    Emitido em
                </td>

                <td>
                    {{ now()->format('d/m/Y H:i') }}
                </td>

            </tr>

        </table>

    </div>

    <!-- RESUMO -->

    <div class="resumo">

        <div class="card  bg-light-primary"">

            <div class=" card-title">
            TOTAL REGISTROS
        </div>

        <div class="card-value">
            {{ count($dados) }}
        </div>

    </div>

    <div class="card bg-light-success">

        <div class="card-title">
            TOTAL FATURADO
        </div>

        <div class="card-value">

            {{ number_format($dados->sum('total'), 2, ',', '.') }}

        </div>

    </div>

    <div class="card bg-light-dark ">

        <div class="card-title">
            MÉDIA FATURAÇÃO
        </div>

        <div class="card-value">

            {{ number_format($dados->avg('total'), 2, ',', '.') }}

        </div>

    </div>

    </div>

    <!-- TABELA -->

    <table class="table">

        <thead>

            <tr>

                <th width="20%">
                    PERÍODO
                </th>

                <th width="45%">
                    EMPRESA
                </th>

                <th width="35%" class="text-right">
                    TOTAL FATURADO
                </th>

            </tr>

        </thead>

        <tbody>

            @php
            $totalGeral = 0;
            @endphp

            @foreach($dados as $item)

            @php
            $totalGeral += $item->total;
            @endphp

            <tr>

                <td>

                    <span class="badge">
                        {{ $item->periodo }}
                    </span>

                </td>

                <td>

                    {{ strtoupper($item->nome) }}

                </td>

                <td class="text-right">

                    AKZ
                    {{ number_format($item->total, 2, ',', '.') }}

                </td>

            </tr>

            @endforeach

        </tbody>

        <tfoot>

            <tr style="background:#0d6efd; color:#fff; font-weight:bold;">

                <td colspan="2">
                    TOTAL GERAL
                </td>

                <td class="text-right">

                    AKZ
                    {{ number_format($totalGeral, 2, ',', '.') }}

                </td>

            </tr>

        </tfoot>

    </table>

    <!-- FOOTER -->

    <div class="footer">

        Documento gerado automaticamente pelo Sistema Financeiro |
        © {{ date('Y') }}

    </div>

</body>
</html>
