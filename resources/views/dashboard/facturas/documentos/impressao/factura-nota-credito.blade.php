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
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .container {
            width: 95%;
            padding: 20px;
        }

        .logo {
            width: 60px;
        }

        h1,
        h2,
        h3,
        h4 {
            margin: 0;
            padding: 0;
        }

        .header {
            width: 100%;
            margin-bottom: 10px;
        }

        .estado {
            background: #f8a300;
            color: #fff;
            padding: 4px 8px;
            font-size: 10px;
        }

        hr {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 10px 0;
        }

        .dados {
            width: 100%;
            margin-bottom: 10px;
        }

        .dados h4 {
            color: #0b53ce;
            font-size: 11px;
        }

        .tabela {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .tabela th {
            background: #0b53ce;
            color: #fff;
            padding: 6px;
            border: 1px solid #0b53ce;
        }

        .tabela td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }

        .tabela td:last-child {
            font-weight: bold;
            color: #0b53ce;
        }

        .iva {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .iva th {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 6px;
        }

        .iva td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .qr {
            width: 70px;
            height: 70px;
            border: 2px solid #ccc;
            text-align: center;
            line-height: 70px;
            margin-bottom: 10px;
        }

        .totais {
            width: 100%;
            border-collapse: collapse;
            background: #0b53ce;
            color: #fff;
        }

        .totais td {
            padding: 2px;
        }

        .pagar {
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }

        .footer h4 {
            color: #0b53ce;
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- CABEÇALHO -->
        <table class="header">
            <tr>
                <td width="60%">
                    <img src="{{ $logotipo }}" class="logo">
                    <h3>{{ $LOJAACTIVAOPERADOR->nome }}</h3>
                    <p>{{ $LOJAACTIVAOPERADOR->morada }}</p>
                    <p>NIF: {{ $LOJAACTIVAOPERADOR->nif }}</p>
                    <p>Tel: {{ $LOJAACTIVAOPERADOR->telefone }}</p>
                    <p>{{ $LOJAACTIVAOPERADOR->email }}</p>
                </td>
                <td width="40%" align="right">
                    <span class="estado">UNICO</span>
                    <h1>{{ $factura->factura_next }} <small style="font-size: 14px">conforme {{ $factura->numeracao_proforma }}</small></h1>
                    <p>Emit. Em: {{ $factura->created_at }}</p>
                </td>
            </tr>
        </table>

        <hr>

        <!-- CLIENTE -->
        <table class="dados">
            <tr>
                <td width="50%">
                    <h4>CLIENT DETAILS</h4>
                    <strong>{{ $factura->nome_cliente ?? $factura->cliente->nome }}</strong>
                    <p>NIF: {{ $factura->documento_nif ?? $factura->cliente->nif }}</p>
                    <p>Endereço: {{ $factura->cliente->localidade }}</p>
                    <p>Email: {{ $factura->cliente->email }}</p>
                </td>
                <td width="50%" align="right">
                    <h4>INFORMAÇÕES DO PAGAMENTO</h4>
                    <p>Forma de pagamento: {{ $factura->pagamento == "NU" ? "NUMERÁRIO" : ($factura->pagamento == "OU" ? "DUPLO" : ($factura->pagamento == "TB" ? "TRANSFERÊNCIA BANCARIA" : ($factura->pagamento == "DE" ? "DEPOSITO" : "MULTICAIXA"))) }}</p>
                    <p>Moeda: {{ $empresa_logada->empresa->moeda ?? 'AKZ' }}</p>
                    <p>Operador: {{ $factura->user->name }}</p>
                    <strong>{{ $opcao }}</strong>
                </td>
            </tr>
        </table>

        <!-- ITENS -->
        <table class="tabela">
            <thead>
                <tr>
                    <th align="left">DESCRIÇÃO</th>
                    <th align="center">QTD</th>
                    <th align="right">PREÇO UNIT.</th>
                    <th align="center">UNI</th>
                    <th align="center">IVA</th>
                    <th align="right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items_facturas as $item)
                <tr>
                    <td>{{ $item->produto->nome ?? "" }}</td>
                    <td align="center">{{ number_format($item->quantidade, 1, ',', '.') }}</td>
                    <td align="right">{{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                    <td align="center">{{ $item->produto->unidade->sigla }}</td>
                    <td align="center">{{ number_format($item->exibir_imposto_iva($item->iva), 1, ',', '.') }}</td>
                    <td align="right">{{ number_format($item->valor_pagar, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- IVA -->
        <table class="iva">
            <tr>
                <th>DESC. TAXA</th>
                <th>TAXA %</th>
                <th>INCIDÊNCIA</th>
                <th>IMPOSTO</th>
                <th>MOT. ISENÇÃO</th>
            </tr>

            @if ($total_incidencia_ise >= 0 || $total_iva_ise >= 0)
            <tr>
                <td>ISENTO</td>
                <td>0</td>
                <td>{{ number_format($total_incidencia_ise, 2, ',', '.') }}</td>
                <td>{{ number_format($total_iva_ise, 2, ',', '.') }}</td>
                <td>M10</td>
            </tr>
            @endif

            @if ($total_incidencia_out_2 != 0 || $total_iva_out_2 != 0)
            <tr>
                <td>OUT</td>
                <td> 2</td>
                <td> {{ number_format($total_incidencia_out_2, 2, ',', '.') }}</td>
                <td> {{ number_format($total_iva_out_2, 2, ',', '.') }}</td>
                <td>M10</td>
            </tr>
            @endif

            @if ($total_incidencia_out_5 != 0 || $total_iva_out_5 != 0)
            <tr>
                <td>RED</td>
                <td> 5</td>
                <td> {{ number_format($total_incidencia_out_5, 2, ',', '.') }}</td>
                <td> {{ number_format($total_iva_out_5, 2, ',', '.') }}</td>
                <td>M10</td>
            </tr>
            @endif

            @if ($total_incidencia_out != 0 || $total_iva_out != 0)
            <tr>
                <td>OUT</td>
                <td>7</td>
                <td>{{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                <td>{{ number_format($total_iva_out, 2, ',', '.') }}</td>
                <td>M10</td>
            </tr>
            @endif

            @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
            <tr>
                <td>IVA</td>
                <td>14</td>
                <td>{{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                <td>{{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                <td>NOR</td>
            </tr>
            @endif

        </table>

        <!-- RODAPÉ SUPERIOR -->
        <table width="100%">
            <tr>
                <td width="55%" valign="top">
                    @if ($empresa_logada->empresa->tipo_facturacao != "saft")
                    <div class="qr">
                        <img src="data:image/png;base64,{{ $factura->qr_code }}" width="85">
                    </div>
                    @endif
                    <h4>MOTIVO ANULAÇÃO</h4>
                    <p>{{ $factura->observacao }}</p>
                </td>

                <td width="45%" valign="top">
                    <table class="totais">
                        <tr>
                            <td>Total Ilíquido</td>
                            <td align="right">{{ number_format($factura->total_incidencia, '2', ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Total Desconto</td>
                            <td align="right">{{ number_format($factura->desconto, '2', ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Total Imposto</td>
                            <td align="right">{{ number_format($factura->total_iva, '2', ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Total Retenção</td>
                            <td align="right">{{ number_format($factura->total_retencao_fonte, '2', ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td align="right">{{ number_format($factura->valor_total, '2', ',', '.') }}</td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>

        <!-- FOOTER -->

        <div class="footer">

            @if ($empresa_logada->empresa->tipo_regime_id == "regime_exclusao")
            <p style="text-transform: uppercase">Regime de Exclusão</p>
            @else
            @if ($empresa_logada->empresa->tipo_regime_id == "regime_geral")
            <p style="text-transform: uppercase">Regime Geral</p>
            @else
            @if ($empresa_logada->empresa->tipo_regime_id == "regime_simplificado")
            <p style="text-transform: uppercase">Regime Simplificado</p>
            @else
            <p style="text-transform: uppercase">Define um Regime para sua empresa</p>
            @endif
            @endif
            @endif

            <p>
                Os bens/serviços foram colocados à disposição do adquirente na data do documento.
            </p>

            <p>{{ $factura->obterCaracteres($factura->hash) }}</p>

            <h4>Obrigado pela preferência!</h4>

            <small>
                SOFTWARE DE FACTURAÇÃO, DESENVOLVIDO PELA {{ env('APP_NAME') }}
            </small>

        </div>

    </div>
</body>
</html>


{{-- <!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>NOTA DE CRÉDITO</title>
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
    @if (count($items_facturas) != 0)
    <table>
        <thead>
            <tr>
                <th style="text-align: left">Descrição</th>
                <th class="text-right">{{ __('messages.quantidade') }}</th>
                <th class="text-right">{{ __('messages.preco') }}</th>
                <th class="text-right">{{ __('messages.unidade') }}</th>
                <th class="text-right">{{ __('messages.desconto') }}</th>
                <th class="text-right">{{ __('messages.imposto') }}</th>
                <th class="text-right">{{ __('messages.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items_facturas as $item)
            <tr>
                <td>{{ $item->produto->nome ?? "" }}</td>
                <td class="text-right">{{ number_format($item->quantidade, 1, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                <td class="text-right">{{ $item->produto->unidade->sigla }}</td>
                <td class="text-right">{{ number_format($item->desconto_aplicado, 1, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->exibir_imposto_iva($item->iva), 1, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->valor_pagar, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th style="text-align: left">Desc</th>
                <th style="text-align: left">Taxa</th>
                <th style="text-align: left">Incidência</th>
                <th style="text-align: left">Imposto</th>
                <th style="text-align: left">Codigo</th>
            </tr>
        </thead>
        <tbody>
            @if ($total_incidencia_ise >= 0 || $total_iva_ise >= 0)
            <tr>
                <td>ISENTO</td>
                <td>0</td>
                <td>{{ number_format($total_incidencia_ise, 2, ',', '.') }}</td>
                <td>{{ number_format($total_iva_ise, 2, ',', '.') }}</td>
                <td>M10</td>
            </tr>
            @endif

            @if ($total_incidencia_out_2 != 0 || $total_iva_out_2 != 0)
            <tr>
                <td>OUT</td>
                <td> 2</td>
                <td> {{ number_format($total_incidencia_out_2, 2, ',', '.') }}</td>
                <td> {{ number_format($total_iva_out_2, 2, ',', '.') }}</td>
                <td>M10</td>
            </tr>
            @endif

            @if ($total_incidencia_out_5 != 0 || $total_iva_out_5 != 0)
            <tr>
                <td>RED</td>
                <td> 5</td>
                <td> {{ number_format($total_incidencia_out_5, 2, ',', '.') }}</td>
                <td> {{ number_format($total_iva_out_5, 2, ',', '.') }}</td>
                <td>M10</td>
            </tr>
            @endif

            @if ($total_incidencia_out != 0 || $total_iva_out != 0)
            <tr>
                <td>OUT</td>
                <td>7</td>
                <td>{{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                <td>{{ number_format($total_iva_out, 2, ',', '.') }}</td>
                <td>M10</td>
            </tr>
            @endif

            @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
            <tr>
                <td>IVA</td>
                <td>14</td>
                <td>{{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                <td>{{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                <td>NOR</td>
            </tr>
            @endif

        </tbody>
    </table>
    @endif
</main>

<footer>
    <div class="dados">
        <div class="col">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: left">BANCO</th>
                        <th style="text-align: left">CONTA</th>
                        <th style="text-align: left">IBAN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $empresa_logada->empresa->banco }}</td>
                        <td>{{ $empresa_logada->empresa->conta }}</td>
                        <td>{{ $empresa_logada->empresa->iban }}</td>
                    </tr>
                    <tr>
                        <td>{{ $empresa_logada->empresa->banco1 }}</td>
                        <td>{{ $empresa_logada->empresa->conta1 }}</td>
                        <td>{{ $empresa_logada->empresa->iban1 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-2" style="text-align:right">
            <p><strong>Total Ilíquido:</strong> {{ number_format($factura->total_incidencia, '2', ',', '.') }}</p>
            <p><strong>Total Desconto:</strong> {{ number_format($factura->desconto, '2', ',', '.') }}</p>
            <p><strong>Total Imposto:</strong> {{ number_format($factura->total_iva, '2', ',', '.') }}</p>
            <p><strong>Total Retenção:</strong> {{ number_format($factura->total_retencao_fonte, '2', ',', '.') }}</p>
            <p><strong>Total á pagar:</strong> {{ number_format($factura->valor_total, '2', ',', '.') }}</p>
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

    <p><strong>Motivo:</strong> {{ $factura->observacao }}</p>
    <p style="margin-bottom: 20px;text-align: right"><em>{{ $factura->valor_extenso ?? 'sem descrição do valor por extensão' }}</em></p>
    <p style="padding: 3px 0"><i>Os bens/serviços foram colocados à disposição do adquirente na data do documento.</i></p>
    <p style="text-align: center"><strong>{{ $factura->obterCaracteres($factura->hash) }}</strong></p>
    <p style="text-align: center">Obrigado pela preferência!</p>
    <p style="text-align: center">Software de facturação, desenvolvido pela {{ env('APP_NAME') }}</p>
</footer>

</body>
</html> --}}
