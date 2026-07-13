<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório DRE</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f4f6f9;
            color: #2c3e50;
            padding: 30px;
        }

        .container {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #0d6efd;
        }

        .report-title {
            font-size: 18px;
            margin-top: 5px;
            color: #6c757d;
        }

        .report-date {
            margin-top: 10px;
            font-size: 12px;
            color: #999;
        }

        .summary-cards {
            width: 100%;
            margin-bottom: 30px;
        }

        .card {
            width: 31%;
            display: inline-block;
            vertical-align: top;
            padding: 20px;
            border-radius: 10px;
            color: #fff;
            margin-right: 2%;
        }

        .card:last-child {
            margin-right: 0;
        }

        .card-title {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 20px;
            font-weight: bold;
        }

        .bg-light-success {
            background: #198754;
        }

        .bg-light-danger {
            background: #dc3545;
        }

        .bg-primary {
            background: #0d6efd;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #0d6efd;
            color: #fff;
        }

        th {
            padding: 15px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .text-light-success {
            color: #198754;
            font-weight: bold;
        }

        .text-light-danger {
            color: #dc3545;
            font-weight: bold;
        }

        .text-light-primary {
            color: #0d6efd;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        .signature {
            margin-top: 60px;
            width: 250px;
            border-top: 1px solid #999;
            text-align: center;
            padding-top: 10px;
            font-size: 13px;
        }

    </style>
</head>
<body>

    <div class="container">

        {{-- HEADER --}}
        <div class="header">

            <div class="company-name">
                {{ $empresa->nome ?? "" }}
            </div>

            <div class="report-title">
                Demonstração de Resultado do Exercício (DRE)
            </div>

            <div class="report-date">
                Emitido em: {{ now()->format('d/m/Y H:i') }}
            </div>

        </div>

        {{-- SUMMARY CARDS --}}
        <div class="summary-cards">

            <div class="card bg-light-success">
                <div class="card-title">
                    Receita Bruta
                </div>

                <div class="card-value">
                    AKZ {{ number_format($dre['revenue'], 2, ',', '.') }}
                </div>
            </div>

            <div class="card bg-light-danger">
                <div class="card-title">
                    Despesas Totais
                </div>

                <div class="card-value">
                    AKZ {{ number_format($dre['expenses'], 2, ',', '.') }}
                </div>
            </div>

            <div class="card  bg-light-primary"">
                <div class=" card-title">
                Lucro Líquido
            </div>

            <div class="card-value">
                AKZ {{ number_format($dre['profit'], 2, ',', '.') }}
            </div>
        </div>

        @php if ($dre['margin'] < 5) { $bg='bg-light-danger' ; $texto='Muito perigoso' ; $icon='fa-triangle-exclamation' ; } elseif ($dre['margin']>= 5 && $dre['margin'] < 15) { $bg='bg-light-warning' ; $texto='Baixa' ; $icon='fa-arrow-trend-down' ; } elseif ($dre['margin']>= 15 && $dre['margin'] <= 30) { $bg='bg-light-primary' ; $texto='Boa' ; $icon='fa-chart-line' ; } else { $bg='bg-light-success' ; $texto='Excelente' ; $icon='fa-sack-dollar' ; } @endphp <div class="card {{$bg}}">
        <div class="card-title">
            Margem de Lucro (<small>{{ $texto }}</small>)
        </div>

        <div class="card-value">
            AKZ {{ number_format($dre['margin'], 2, ',', '.') }}%
        </div>
    </div>

    </div>

    {{-- TABLE --}}
    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>Receita Bruta</td>

                    <td class="text-light-success">
                        AKZ {{ number_format($dre['revenue'], 2, ',', '.') }}
                    </td>

                    <td class="text-light-success">
                        Positivo
                    </td>
                </tr>

                <tr>
                    <td>Despesas Operacionais</td>

                    <td class="text-light-danger">
                        AKZ {{ number_format($dre['expenses'], 2, ',', '.') }}
                    </td>

                    <td class="text-light-danger">
                        Saída
                    </td>
                </tr>

                <tr>
                    <td><strong>Lucro Líquido</strong></td>

                    <td class="text-light-primary">
                        <strong>
                            AKZ {{ number_format($dre['profit'], 2, ',', '.') }}
                        </strong>
                    </td>

                    <td class="text-light-primary">
                        Resultado Final
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    {{-- OBSERVAÇÕES --}}
    <div style="margin-top:30px;">

        <h3 style="margin-bottom:10px; color:#0d6efd;">
            Observações Financeiras
        </h3>

        <p style="font-size:14px; line-height:1.7; color:#555;">
            Este relatório apresenta o resumo financeiro da empresa,
            incluindo receitas, despesas e lucro líquido do período analisado.
            Os valores refletem os dados registados no sistema financeiro.
        </p>

    </div>

    {{-- ASSINATURA --}}
    <div class="signature">
        Diretor Financeiro
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Documento gerado automaticamente pelo Sistema Financeiro Empresarial
    </div>

    </div>

</body>
</html>
