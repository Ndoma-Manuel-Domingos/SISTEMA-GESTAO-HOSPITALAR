<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>FACTURA</title>
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
        <p style="font-size: 15px;border-bottom: 1px dashed #000;margin-bottom: 5px;padding-bottom: 4px;text-align: center"><strong style="font-size: 15px">Factura Nº:</strong> {{ $factura->factura_next }}</p>
        <p><strong>Data:</strong> {{ $factura->created_at }}</p>
        <p><strong>Operador:</strong> {{ $factura->user->name }}</p>
        <p style="text-align: right"><i><strong>{{ $opcao }}</strong></i></p>
    </div>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Desc</th>
                    <th class="text-right">{{ __('messages.quantidade') }}</th>
                    <th class="text-right">{{ __('messages.preco') }}</th>
                    <th class="text-right">{{ __('messages.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items_facturas as $item)
                <tr>
                    <td colspan="4" style="font-size: 14px">{{ $item->produto->nome ?? "" }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: right">{{ number_format($item->quantidade, 0) }}</td>
                    <td style="text-align: right">{{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($item->valor_pagar, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totais">
            <p><strong><span>Total Ilíquido:</span></strong> <span>{{ number_format($factura->total_incidencia, 2, ',', '.') }}</span></p>
            <p><strong><span>Desconto:</span></strong> <span>{{ number_format($factura->desconto, 2, ',', '.') }}</span></p>
            <p><strong><span>Impostos:</span></strong> <span>{{ number_format($factura->total_iva, 2, ',', '.') }}</span></p>
            <p><strong><span>Total a Pagar:</span></strong> <span>{{ number_format($factura->valor_total, 2, ',', '.') }}</span></p>
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
