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
            font-size: 13pt;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 10px;
        }

        th,
        td {
            padding: 6px;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        strong {
            font-size: 12px;
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
    <table style="border: 0">
        <tr>
            <td style="border: 0;">
                <img src="{{ $logotipo ?? "" }}" style="height: 80px;width: 80px;margin-bottom: 10px;">
            </td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0">{{ $empresa->empresa->nome }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>NIF: </strong>{{ $empresa->empresa->nif }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Endereço: </strong>{{ $empresa->empresa->morada }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>{{ $empresa->empresa->cidade }} - {{ $empresa->empresa->pais }}</strong></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_inicio') }}</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_final') }}</th>
                <th colspan="2" style="text-transform: uppercase">Loja</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.designacao') }}</th>
            </tr>

            <tr>
                <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $loja ? $loja->nome : 'TODOS' }}</th>
                <th colspan="2">{{ $produto ? $produto->nome : 'TODOS' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="9" style="text-transform: uppercase;padding: 3px"> {{ $titulo }}</th>
            </tr>
            <tr>
                {{-- <th style="border: 1px solid #010101;padding: 2px;width: 30px">ID</th> --}}
                <th style="border: 1px solid #010101;padding: 2px;width: 30px">Codigo</th>
                <th style="border: 1px solid #010101;padding: 2px;width: 100px">{{ __('messages.designacao') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;width: 50px"> {{ __('messages.data') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;width: 100px">Operação</th>
                <th style="border: 1px solid #010101;padding: 2px;width: 100px">Loja</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;width: 100px">{{ __('messages.observacao') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;width: 50px;text-align: right"><span class="float-right">Preço de Movimento</span></th>
                <th style="border: 1px solid #010101;padding: 2px;width: 50px;text-align: right"><span class="float-right">Quant.</span></th>
            </tr>
        </thead>
        @php $entradas = 0; $saidas = 0; @endphp
        <tbody>
            @foreach ($movimentos as $key => $movimento)
            @if ($movimento->tipo == "E")
            @php ++$entradas; @endphp
            @endif
            @if ($movimento->tipo == "S")
            @php ++$saidas; @endphp
            @endif
            <tr>
                <td style="padding: 3px">{{ $movimento->produto->codigo_barra }}</td>
                <td style="padding: 3px">{{ $movimento->produto->nome }}</td>
                <td style="padding: 3px">{{ date_format($movimento->created_at, 'Y-m-d') }}</td>
                <td style="padding: 3px">{{ $movimento->registro }}</td>
                <td style="padding: 3px">{{ $movimento->loja->nome }}</td>
                <td colspan="2">{{ $movimento->observacao }}</td>
                <td style="padding: 3px;text-align: right"><span class="float-right text-light-success">{{ number_format($movimento->preco_unitario, 2, ',', '.') }}</span> </td>
                <td style="padding: 3px;text-align: right"><span class="float-right text-light-success">{{ $movimento->produto->converterDaBase($movimento->quantidade, $movimento->produto->unidade) }} {{ $movimento->produto->unidade->sigla }}</span> </td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="9" style="border: 1px solid #010101;padding: 2px;padding: 5px">ENTRADAS: <span style="float: right">{{ $entradas}}</span> </th>
            </tr>
            <tr>
                <th colspan="9" style="border: 1px solid #010101;padding: 2px;padding: 5px">SAÍDAS: <span style="float: right">{{ $saidas }}</span> </th>
            </tr>
            <tr>
                <th colspan="9" style="border: 1px solid #010101;padding: 2px;padding: 5px">TOTAL REGISTRO: <span style="float: right">{{ count($movimentos) }}</span> </th>
            </tr>
        </tfoot>
    </table>

    <div>
        <p style="position: absolute; bottom: 20px;font-size: 12px">-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS</p>
    </div>
</body>

</html>
