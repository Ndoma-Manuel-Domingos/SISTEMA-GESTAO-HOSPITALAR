@if ($LOJAACTIVAOPERADOR->modelo_factura != 'modelo1')
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $factura->factura_next }} - Factura Recibo</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .recibo {
            width: 280px;
            margin: 0 auto;
            border: 1px solid #dcdcdc;
            background: #fff;
        }

        .topo-linha {
            height: 3px;
            background: #0b53ce;
            border-right: 3px solid #d59a20;
        }

        .logo-area {
            text-align: center;
            padding: 0;
        }

        .logo {
            width: 85px;
        }

        .logo-area h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #0b53ce;
        }

        .logo-area p {
            margin: 2px 0;
            font-size: 8px;
        }

        .cabecalho {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .cabecalho td {
            padding: 5px 8px;
        }

        .estado {
            background: #f4a100;
            color: #fff;
            padding: 2px 5px;
            font-size: 8px;
        }

        .factura {
            color: #0b53ce;
            font-weight: bold;
            border-top: 1px solid #ddd;
        }

        .cliente {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .cliente td {
            padding: 4px 8px;
        }

        .titulo {
            color: #999;
            font-size: 8px;
        }

        .itens {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .itens th {
            border-bottom: 1px solid #ccc;
            color: #777;
            font-size: 8px;
            padding: 5px;
        }

        .itens td {
            padding: 7px 5px;
            border-bottom: 1px solid #f0f0f0;
        }

        .totais {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .totais td {
            padding: 3px 8px;
        }

        .pagar {
            width: 100%;
            border-collapse: collapse;
            background: #0b53ce;
            color: #fff;
            margin-top: 10px;
        }

        .pagar td {
            padding: 10px;
            font-weight: bold;
        }

        .pagar td:last-child {
            font-size: 18px;
        }

        .extenso {
            text-align: center;
            color: #d39b24;
            font-size: 8px;
            padding: 8px;
            font-weight: bold;
        }

        .observacao {
            width: 100%;
            border-collapse: collapse;
        }

        .observacao td {
            padding: 5px 8px;
            font-size: 8px;
        }

        .qr-box {
            text-align: center;
            margin-top: 10px;
        }

        .regime {
            margin-top: 10px;
            text-align: center;
            color: #0b53ce;
            font-size: 9px;
            font-weight: bold;
        }

        .legal {
            text-align: center;
            padding: 5px 15px;
            font-size: 7px;
        }

        .obrigado {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }

        .software {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;
            font-size: 7px;
            color: #666;
        }

    </style>
</head>
<body>
    <div class="recibo">
        <div class="topo-linha"></div>
        <div class="logo-area">
            <img src="{{ $logotipo }}" class="logo">
            <h2>{{ $LOJAACTIVAOPERADOR->nome }}</h2>
            <p>{{ $LOJAACTIVAOPERADOR->morada }}</p>
            <p>NIF: {{ $LOJAACTIVAOPERADOR->nif }}</p>
            <p>TEL: {{ $LOJAACTIVAOPERADOR->telefone }}</p>
            <p>E-MAIL: {{ $LOJAACTIVAOPERADOR->email }}</p>
        </div>
        <table class="cabecalho">
            <tr>
                <td>
                    <span class="estado">{{ $opcao }}</span>
                </td>
                <td align="right">
                    2024-09-09
                </td>
            </tr>
            <tr>
                <td colspan="2" class="factura">
                    {{ $factura->factura_next }}
                </td>
            </tr>
        </table>

        <table class="cliente">
            <tr>
                <td>
                    <span class="titulo">CLIENTE</span>
                </td>
                <td align="right">
                    <strong>{{ $factura->nome_cliente ?? $factura->cliente->nome }}</strong>
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right">
                    {{ $factura->documento_nif ?? $factura->cliente->nif }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="titulo">Forma de pagamento</span>
                </td>
                <td align="right">
                    {{ $factura->pagamento == "NU" ? "NUMERÁRIO" : ($factura->pagamento == "OU" ? "DUPLO" : ($factura->pagamento == "TB" ? "TRANSFERÊNCIA BANCARIA" : ($factura->pagamento == "DE" ? "DEPOSITO" : "MULTICAIXA"))) }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="titulo">Data Emissão</span>
                </td>
                <td align="right">
                    {{ $factura->created_at }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="titulo">Operador</span>
                </td>
                <td align="right">
                    {{ $factura->user->name }}
                </td>
            </tr>
        </table>

        <table class="itens">
            <thead>
                <tr>
                    <th align="left">ARTIGO</th>
                    <th>PRE</th>
                    <th>QTD</th>
                    <th align="right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items_facturas as $item)
                <tr>
                    <td>{{ $item->produto->nome ?? "" }}</td>
                    <td align="center">{{ number_format($item->quantidade, 0) }}</td>
                    <td align="center">{{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                    <td align="right">{{ number_format($item->valor_pagar, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totais">
            <tr>
                <td>Total Ilíquido</td>
                <td align="right">{{ number_format($factura->total_incidencia, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Desconto</td>
                <td align="right">{{ number_format($factura->desconto, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Imposto</td>
                <td align="right">{{ number_format($factura->total_iva, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Valor Entregue</td>
                <td align="right">{{ number_format($factura->valor_entregue, '2', ',', '.') }}</td>
            </tr>
        </table>

        <table class="pagar">
            <tr>
                <td>TOTAL PAGO</td>
                <td align="right">{{ number_format($factura->valor_total, '2', ',', '.') }}</td>
            </tr>
        </table>

        <div class="extenso">
            {{ $factura->valor_extenso ?? 'Sem descrição por extenso' }}
        </div>

        <table class="observacao">
            <tr>
                <td colspan="2">
                    Observação:
                    {{ $factura->observacao ?? '---' }}
                </td>
            </tr>
        </table>

        @if ($empresa_logada->empresa->tipo_facturacao != "saft")
        <div class="qr-box">
            <img src="data:image/png;base64,{{ $factura->qr_code }}" width="85">
        </div>
        @endif


        <div class="regime" style="text-transform: uppercase">
            @if ($empresa_logada->empresa->tipo_regime_id == "regime_exclusao")
            Regime de Exclusão
            @else
            @if ($empresa_logada->empresa->tipo_regime_id == "regime_geral")
            Regime Geral
            @else
            @if ($empresa_logada->empresa->tipo_regime_id == "regime_simplificado")
            Regime Simplificado
            @else
            Define um Regime para sua empresa
            @endif
            @endif
            @endif
        </div>
        <div class="legal">
            Os bens foram colocados à disposição do adquirente na data do documento
        </div>
        <div class="legal">
            {{ $factura->obterCaracteres($factura->hash) }}
        </div>
        <div class="obrigado">
            Obrigado pela preferência!
        </div>
        <div class="software">
            Software {{ env('APP_NAME') }}
        </div>
    </div>
</body>
</html>
@else
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>FACTURA RECIBO</title>
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
        <p><strong>Forma de pagamento:</strong> {{ $factura->pagamento == "NU" ? "NUMERÁRIO" : ($factura->pagamento == "OU" ? "DUPLO" : ($factura->pagamento == "TB" ? "TRANSFERÊNCIA BANCARIA" : ($factura->pagamento == "DE" ? "DEPOSITO" : "MULTICAIXA"))) }}</p>
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
            <p><strong>Valor Entregue:</strong> {{ number_format($factura->valor_entregue, '2', ',', '.') }}</p>
            <p><strong>Troco:</strong> {{ number_format($factura->valor_troco, '2', ',', '.') }}</p>
            <p><strong>Total pago:</strong> {{ number_format($factura->valor_total, '2', ',', '.') }}</p>
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

        @if ($empresa_logada->empresa->tipo_facturacao != "saft")
        <div>
            <img src="data:image/png;base64,{{ $factura->qr_code }}" width="120">
        </div>
        @endif

        <p><strong>{{ $factura->obterCaracteres($factura->hash) }}</strong></p>
        <p>Obrigado pela preferência!</p>
        <p>Software {{ env('APP_NAME') }}</p>
    </footer>
</body>
</html>
@endif
