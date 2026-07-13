<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>RECIBO</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        body {
            /* largura típica de impressora térmica */
            padding: 10px;
            margin: 0 auto;
            color: #000;
        }

        header,
        footer {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        header img {
            max-width: 60px;
            max-height: 60px;
            margin-bottom: 5px;
            margin-top: 8px;
        }

        header h1 {
            font-size: 14px;
        }

        .empresa-info,
        .cliente-info {
            margin-bottom: 8px;
            font-size: 11px;
        }

        .cliente-info {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table thead {
            border-bottom: 1px dashed #000;
        }

        table th,
        table td {
            padding: 2px 0;
            font-size: 11px;
        }

        table th {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .totais {
            margin-top: 5px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            font-size: 12px;
        }

        .totais p {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .observacao {
            margin-top: 10px;
            font-size: 10px;
        }

        footer {
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 5px;
            font-size: 10px;
        }

    </style>
</head>
<body>

    <header>
        <img src="{{ $logotipo }}" alt="Logotipo">
        <h1>{{ $LOJAACTIVAOPERADOR->nome }}</h1>
        <p>{{ $LOJAACTIVAOPERADOR->morada }}</p>
        <p>Tel: {{ $LOJAACTIVAOPERADOR->telefone }}</p>
        <p>NIF: {{ $LOJAACTIVAOPERADOR->nif }}</p>
    </header>

    <div class="cliente-info">
        <p><strong>Cliente:</strong> {{ $factura->nome_cliente ?? $factura->cliente->nome }}</p>
        <p style="border-bottom: 1px dashed #000;margin-bottom: 5px;padding-bottom: 4px"><strong>NIF:</strong> {{ $factura->documento_nif ?? $factura->cliente->nif }}</p>

        @if ($factura->convertido_factura == 'Y')
        <p style="font-size: 15px;border-bottom: 1px dashed #000;margin-bottom: 5px;padding-bottom: 4px;text-align: center"><strong>{{ $factura->factura_next }} conforme {{ $factura->numeracao_proforma }}</strong></p>
        @else
        <p style="font-size: 15px;border-bottom: 1px dashed #000;margin-bottom: 5px;padding-bottom: 4px;text-align: center"><strong style="font-size: 15px">{{ $factura->factura_next }}</strong></p>
        @endif

        <p><strong>Data:</strong> {{ $factura->created_at }}</p>
        <p><strong>Operador:</strong> {{ $factura->user->name }}</p>
        <p style="text-align: right"><i><strong>{{ $opcao }}</strong></i></p>
    </div>

    <main>

        <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
            <thead style="border-bottom: 1px dashed #000;x">
                <tr>
                    <th>Data doc.</th>
                    <th>Refe. Fact.</th>
                    <th>Total da Fact.</th>
                    <th>Imposto</th>
                    <th>Pago</th>
                    <th>Dívida</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $factura->data_emissao }} </td>
                    <td>{{ $factura->facturas->factura_next }} </td>
                    <td style="text-align:center">{{ number_format($factura->facturas->valor_total, 2, ',', '.') }} </td>
                    <td style="text-align:center">{{ number_format($factura->total_iva, 2, ',', '.') }} </td>
                    <td style="text-align:center">{{ number_format($factura->facturas->valor_pago, 1, ',', '.') }} </td>
                    <td style="text-align:center">{{ number_format($factura->facturas->valor_divida, 1, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="totais">
            <p><strong><span>Total:</span></strong> <span>{{ number_format($factura->valor_total, 2, ',', '.') }}</span></p>
        </div>

        <div class="observacao">
            <p><strong>Obs:</strong> {{ $factura->observacao ?? '---' }}</p>
            <p><em>{{ $factura->valor_extenso ?? 'Sem descrição por extenso' }}</em></p>
        </div>
    </main>

    <footer>
        @if ($empresa_logada->empresa->tipo_regime_id == "regime_exclusao")
        <p style="text-align: center"><strong style="text-transform: uppercase">Regime de Exclusão</strong></p>
        @else
        @if ($empresa_logada->empresa->tipo_regime_id == "regime_geral")
        <p style="text-align: center"><strong style="text-transform: uppercase">Regime Geral</strong></p>
        @else
        @if ($empresa_logada->empresa->tipo_regime_id == "regime_simplificado")
        <p style="text-align: center"><strong style="text-transform: uppercase">Regime Simplificado</strong></p>
        @else
        <p style="text-align: center"><strong style="text-transform: uppercase">Define um Regime para sua empresa</strong></p>
        @endif
        @endif
        @endif
        <div>
            <img src="data:image/png;base64,{{ $factura->qr_code }}" width="120">
        </div>

        <p><strong>{{ $factura->obterCaracteres($factura->hash) }}</strong></p>
        <p>Obrigado pela preferência!</p>
        <p>Software {{ env('APP_NAME') }}</p>
    </footer>
</body>
</html>
