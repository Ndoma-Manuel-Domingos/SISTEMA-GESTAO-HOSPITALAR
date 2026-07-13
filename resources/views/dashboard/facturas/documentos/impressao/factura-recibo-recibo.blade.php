<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>RECIBO</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        @page {
            margin: 120px 30px 100px 30px;
            /* espaço para header e footer */
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 10px;
            color: #000;
        }

        header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .logo {
            float: left;
            width: 25%;
        }

        .logo img {
            max-width: 100%;
            height: auto;
        }

        .empresa-info {
            float: right;
            width: 70%;
            text-align: right;
        }

        .empresa-info h1 {
            margin: 0;
            font-size: 16pt;
        }

        .clearfix {
            clear: both;
        }

        .dados {
            width: 100%;
            margin: 20px 0;
        }

        .col {
            display: inline-block;
            width: 48%;
            vertical-align: top;
        }

        .col-2 {
            display: inline-block;
            width: 51.333333%;
            vertical-align: top;
        }

        .col p {
            margin: 3px 0;
        }

        .marca-dagua {
            position: fixed;
            top: 50%;
            left: 50%;
            text-transform: uppercase;
            transform: translate(-50%, -50%);
            font-size: 10em;
            color: rgba(0, 0, 0, 0.1);
            z-index: 99999999;
            pointer-events: none;
        }

        .marca-dagua1 {
            position: fixed;
            top: 41%;
            left: 20%;
            text-transform: uppercase;
            transform: translate(-50%, -50%);
            font-size: 3em;
            color: rgba(0, 0, 0, 0.1);
            z-index: 1000;
            pointer-events: none;
        }

        h2.section-title {
            margin-top: 20px;
            font-size: 13pt;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            padding: 5px;
            font-size: 10px;
        }

        table th {
            background: #f2f2f2;
            border: 1px solid #000;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        footer {
            margin-top: 20px;
            font-size: 12px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        main {
            margin-top: 10px;
        }

    </style>
</head>
<body>

    <header>
        <div class="logo">
            <img src="{{ $logotipo }}" alt="Logotipo" style="height: 80px;width: 80px">
        </div>
        <div class="empresa-info">
            <h1 style="font-size: 14px">{{ $LOJAACTIVAOPERADOR->nome }}</h1>
            <p>{{ $LOJAACTIVAOPERADOR->morada }}</p>
            <p>Tel: {{ $LOJAACTIVAOPERADOR->telefone }} | Email: {{ $LOJAACTIVAOPERADOR->email }}</p>
            <p>NIF: {{ $LOJAACTIVAOPERADOR->nif }}</p>
        </div>

        <div class="clearfix"></div>
        <hr>
        <div class="dados">
            <div class="col">
                <h3>Dados do Cliente</h3>
                <p><strong>Nome:</strong> {{ $factura->nome_cliente ?? $factura->cliente->nome }}</p>
                <p><strong>NIF:</strong> {{ $factura->documento_nif ?? $factura->cliente->nif }}</p>
                <p><strong>Endereço:</strong> {{ $factura->cliente->localidade }}</p>
                <p><strong>Email:</strong> {{ $factura->cliente->email }}</p>
            </div>
            <div class="col-2" style="text-align:right">
                {{-- <h3>Factura</h3> --}}

                @if ($factura->convertido_factura == 'Y')
                <p><strong>{{ $factura->factura_next }} conforme {{ $factura->numeracao_proforma }}</strong></p>
                @else
                <p><strong style="font-size: 15px">{{ $factura->factura_next }}</strong></p>
                @endif

                <p style="margin-top: 10px"><strong>Data de Emissão:</strong> {{ $factura->created_at }}</p>
                <p><strong>Moeda:</strong> {{ $empresa_logada->empresa->moeda ?? 'AKZ' }}</p>
                <p><strong>Operador:</strong> {{ $factura->user->name }}</p>

                <p style="margin-top: 5px"><i>{{ $opcao }}</i></p>
            </div>
        </div>
    </header>

    @if ($factura->anulado === 'Y')
    <div class="marca-dagua">Anulada</div>
    @endif

    <main>
        <table>
            <thead>
                <tr>
                    <th style="padding: 2px 0">N.º</th>
                    <th>Data documento</th>
                    <th>Referência Factura</th>
                    <th>Total da Factura</th>
                    <th>Valor Pago Factura</th>
                    <th>Dívida Factura</th>
                    <th>Total Imposto</th>
                    <th>Valor Pago</th>
                    <th>Dívida Recibo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 2px 0">#</td>
                    <td>{{ $factura->data_emissao }}</td>
                    <td>{{ $factura->facturas->factura_next }} </td>
                    <td>{{ number_format($factura->facturas->valor_total, 2, ',', '.') }}</td>
                    <td>{{ number_format($factura->facturas->valor_pago, 2, ',', '.') }}</td>
                    <td>{{ number_format($factura->facturas->valor_divida, 1, ',', '.') }}</td>
                    <td>{{ number_format($factura->total_iva, 2, ',', '.') }}</td>
                    <td>{{ number_format($factura->valor_total, 1, ',', '.') }}</td>
                    <td>{{ number_format($factura->valor_divida, 1, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </main>

    <footer>
        <div class="dados">
            <div class="col">
            </div>
            <div class="col-2" style="text-align:right">
                <p><strong>{{ __('messages.total') }}:</strong> {{ number_format($factura->valor_total, '2', ',', '.') }}</p>
            </div>
        </div>

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

        <p><strong>Observação:</strong> {{ $factura->observacao }}</p>
        <p style="margin-bottom: 20px;text-align: right"><em>{{ $factura->valor_extenso ?? 'sem descrição do valor por extensão' }}</em></p>
        <p style="padding: 3px 0"><i>Os bens/serviços foram colocados à disposição do adquirente na data do documento.</i></p>
        <p style="text-align: center"><strong>{{ $factura->obterCaracteres($factura->hash) }}</strong></p>
        <p style="text-align: center">Obrigado pela preferência!</p>
        <p style="text-align: center">Software de facturação, desenvolvido pela {{ env('APP_NAME') }}</p>
    </footer>

</body>
</html>
