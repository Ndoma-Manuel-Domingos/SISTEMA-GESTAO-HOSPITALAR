<!DOCTYPE html>
<html lang="pdf">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }} | {{ $descricao }}</title>

    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
        }

        body {
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 15pt;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 12pt;
        }

        p {
            /* margin-bottom: 20px; */
            line-height: 25px;
            font-size: 12pt;
            text-align: justify;
        }

        strong {
            font-size: 12pt;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 12pt;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 10px;
        }

        th,
        td {
            padding: 6px;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }

        strong {
            font-size: 9px;
        }

        .marca-dagua {
            position: fixed;
            top: 50%;
            left: 50%;
            text-transform: uppercase;
            transform: translate(-50%, -50%);
            font-size: 9em;
            color: rgba(0, 0, 0, 0.1);
            /* Cor do texto com transparência */
            z-index: 1000;
            /* Z-index alto para ficar acima do conteúdo */
            pointer-events: none;
            /* Evitar que o texto interfira com a interação do usuário */
        }
    </style>
</head>

<body>
    <header style="position: absolute;top: 30;right: 30px;left: 30px;">
        <table>
            <tr>
                <td rowspan="">
                    <img src="{{ $logotipo }}" alt="Logotipo da Empresa" style="text-align: center;height: 100px;width: 170px;">
                </td>
                <td style="text-align: right">
                    <span style="margin-bottom: 50px">Pág: 1/1</span> <br> <br>
                    {{ date('d-m-Y', strtotime($factura->created_at)) }} <br> <br>
                    ORIGINAL
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">
                    <strong style="padding: 20px 0;">{{ $LOJAACTIVAOPERADOR->nome }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Endereço:</strong> {{ $LOJAACTIVAOPERADOR->morada }}
                </td>
                <td>DADOS DO FORNECEDOR</td>
            </tr>
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $LOJAACTIVAOPERADOR->nif }}
                </td>
                <td style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                    <strong style="font-size: 9px">{{ $factura->fornecedor->nome }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>{{ __('messages.telefone') }}: </strong> {{ $LOJAACTIVAOPERADOR->telefone ?? '' }}
                </td>
                <td style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>NIF: {{ $factura->fornecedor->nif }}</strong>
                </td>
            </tr>

            <tr>
                <td>

                </td>
                <td style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>Endereço: </strong> {{ $factura->fornecedor->morada ?? 'Endereço' }}
                </td>
            </tr>
            <tr>
                <td>

                </td>
                <td style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>{{ __('messages.telefone') }}: </strong> {{ $factura->fornecedor->telefone }}
                </td>
            </tr>

            <tr>
                <td>
                    <strong> {{ __('messages.email') }}: </strong> {{ $empresa_logada->empresa->website }}
                </td>
                <td
                    style="border-bottom: #eaeaea 1px solid;border-right: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>{{ __('messages.conta_corrente') }} N.º: </strong> {{ $factura->fornecedor->conta }}
                </td>
            </tr>
        </table>
    </header>

    <main style="position: absolute;top: 260px;right: 30px;left: 30px;">
        
        <table>
            <tr>
                <td style="font-size: 13px">
                    <strong>Luanda-Angola</strong> <br>
                    <strong>FACTURA</strong>
                </td>
            </tr>

            <tr style="margin-top: 29px;display: block">
                <td style="font-size: 13px;margin-top: 5px;display: block"> <strong>{{ $factura->factura }}</strong></td>
            </tr>
        </table>
        
        <table>
            <tr>
                <td style="font-size: 9px;padding: 1px 0">Moeda: <strong>{{ $empresa_logada->empresa->moeda ?? 'AKZ' }} </strong>
                </td>
                <td style="font-size: 9px;padding: 1px 0">Data de Emissão: <strong>{{ $factura->data_factura }}</strong></td>
            </tr>
        </table>
        
        @php
            $numero = 0;
        @endphp
        @if (count($factura->pagamentos) != 0)
            <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                <thead style="border-bottom: 1px dashed #000;x">
                    <tr>
                        <th style="padding: 2px 0">N.º</th>
                        <th style="padding: 2px 0">Forma Pagamento</th>
                        <th style="padding: 2px 0">Data Pagamento</th>
                        <th style="text-align: right">{{ __('messages.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($factura->pagamentos as $item)
                        @php
                            $numero++;
                        @endphp
                        <tr>
                            <td style="padding: 2px 0">{{ $numero }}</td>
                            <td style="padding: 2px 0">{{ $item->forma_pagamento->titulo }}</td>
                            <td style="padding: 2px 0">{{ $item->data_pagamento }}</td>
                            <td style="text-align: right">{{ number_format($item->valor_factura, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <table>
            <tr>
                <td>
                    OPERADOR: {{ $factura->user->name }} <br>
                    _______________________
                </td>
            </tr>
        </table>
        <table style="border-top: 2px solid #000000">
            <tbody>
                <tr>
                    <td>COORDENADAS BANCÁRIAS</td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Descontos:</strong>
                        {{ number_format($factura->desconto, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>BANCO: {{ $empresa_logada->empresa->banco }}</td>
                    <td style="text-align: right;padding: 3px 0;"><strong>{{ __('messages.total') }}:</strong>
                        {{ number_format($factura->valor_factura, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>CONTA: {{ $empresa_logada->empresa->conta }}</td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Total Pago:</strong>
                        {{ number_format($factura->valor_pago, 2, ',', '.') }}</td>
                </tr>
    
                <tr>
                    <td>IBAN: {{ $empresa_logada->empresa->iban }}</td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Total A Pagar:</strong>
                        {{ number_format($factura->valor_divida, 2, ',', '.') }}</td>
                </tr>
    
                <tr>
                    <td>BANCO: {{ $empresa_logada->empresa->banco1 }}</td>
                    <td style="text-align: right;padding: 3px 0;"></td>
                </tr>
    
                <tr>
                    <td style="text-align: left;padding: 3px 0;">CONTA: {{ $empresa_logada->empresa->conta1 }}</td>
                    <td></td>
                </tr>
    
                <tr>
                    <td style="text-align: left;padding: 3px 0;">IBAN: {{ $empresa_logada->empresa->iban1 }}</td>
                    <td></td>
                </tr>
    
                <tr>
                    <td style="padding: 3px 0;margin-top: 30px;display: block;font-style: italic">Os bens/serviços foram colocados à disposição do adquirente na data do documento</td>
                </tr>
    
                <tr style="">
                    <td style="padding: 3px 0; text-align: center;border-top: 2px solid #000000" colspan="2">Software
                        de facturação, desenvolvido pela {{ env('APP_NAME') }}</td>
                </tr>
    
            </tbody>
        </table>
    </main>

</body>

</html>
