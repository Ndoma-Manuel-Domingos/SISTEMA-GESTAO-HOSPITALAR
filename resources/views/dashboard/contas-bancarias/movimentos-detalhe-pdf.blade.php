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
    <header>
        <table style="border: 0">
            <tr>
                <td style="border: 0;">
                    <img src="{{ $logotipo ?? "" }}" style="height: 80px;width: 80px;margin-bottom: 10px;">
                </td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0">{{ $empresa->nome }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>NIF: </strong>{{ $empresa->nif }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>Endereço: </strong>{{ $empresa->morada }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>{{ $empresa->cidade }} - {{ $empresa->pais }}</strong></td>
            </tr>
        </table>
    </header>


    <table>
        <thead>
            <tr>
                <th colspan="10" style="text-transform: uppercase"> {{ $titulo ?? '' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="text-transform: uppercase">Operador</th>
                <th style="text-transform: uppercase">{{ $movimento->user->name ?? '' }}</th>
            </tr>
            <tr>
                <th style="text-transform: uppercase">Conta Bancária</th>
                <th style="text-transform: uppercase">{{ $movimento->banco->nome ?? '' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <tbody>

            <tr>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Data Abertura</th>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Data Fecho</th>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Hora Abertura</th>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Hora Fecho</th>
            </tr>
            <tr>
                <th style="text-align: right;text-transform: uppercase">{{ $movimento->data_abertura ?? '' }}</th>
                <th style="text-align: right;text-transform: uppercase">{{ $movimento->data_fecho ?? '' }}</th>
                <th style="text-align: right;text-transform: uppercase">{{ $movimento->hora_abertura ?? '' }}</th>
                <th style="text-align: right;text-transform: uppercase">{{ $movimento->hora_fecho ?? '' }}</th>
            </tr>

            <tr>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Valor de Abertura</th>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Valor de Entrada</th>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Valor de Saída</th>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7"></th>
            </tr>
            <tr>
                <th style="text-align: right">{{ number_format($movimento->valor_abertura ?? 0, 2, ',', '.') }}</th>
                <th style="text-align: right">{{ number_format($movimento->valor_entrada ?? 0, 2, ',', '.') }}</th>
                <th style="text-align: right">{{ number_format($movimento->valor_saida ?? 0, 2, ',', '.') }}</th>

                <th style="text-align: right"></th>
            </tr>

            {{-- ------------------------------------------------------------------------------------------------------------------ --}}

            <tr>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Valor de Abertura</th>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">Valor Multicaixa</th>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">{{ __('messages.estados') }}</th>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">-----</th>
            </tr>

            <tr>
                <th style="text-align: right">{{ number_format($movimento->valor_abertura ?? 0, 2, ',', '.') }}</th>
                <th style="text-align: right">{{ number_format($movimento->valor_multicaixa ?? 0, 2, ',', '.') }}</th>
                @if ($movimento->status == false)
                <th style="text-align: right">FECHADO</th>
                @else
                <th style="text-align: right">ABERTO</th>
                @endif
                <th style="text-align: right">{{ number_format(0, 2, ',', '.') }}</th>
            </tr>

            {{-- ------------------------------------------------------------------------------------------------------------------ --}}

            <tr>
                <th style="text-align: left;text-transform: uppercase"></th>
                <th style="text-align: left;text-transform: uppercase"></th>
                <th style="text-align: left;text-transform: uppercase"></th>
                <th style="text-align: right;text-transform: uppercase;background-color: #f7f7f7">{{ __('messages.total') }}</th>
            </tr>

            <tr>
                <th class="text-light-success" style="text-align: left"></th>
                <th class="text-light-success" style="text-align: left"></th>
                <th class="text-light-success" style="text-align: left"></th>

                @if (($movimento->valor_valor_fecho ?? 0) < 0) <th style="text-align: right">{{ number_format($movimento->valor_valor_fecho ?? 0, 2, ',', '.') }}
                    </th>
                    @endif
                    @if (($movimento->valor_valor_fecho ?? 0) == 0)
                    <th style="text-align: right">{{ number_format($movimento->valor_valor_fecho ?? 0, 2, ',', '.') }}
                    </th>
                    @endif
                    @if (($movimento->valor_valor_fecho ?? 0) > 0)
                    <th style="text-align: right">{{ number_format($movimento->valor_valor_fecho ?? 0, 2, ',', '.') }}
                    </th>
                    @endif
            </tr>

        </tbody>
    </table>

    <div>
        <p style="position: absolute; bottom: 20px;font-size: 12px">-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS</p>
    </div>
</body>

</html>
