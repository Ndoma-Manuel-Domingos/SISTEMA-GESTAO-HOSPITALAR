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
                <th colspan="9" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
        </thead>
    </table>

    <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;">Conta</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.designacao') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;"> {{  __('messages.genero') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;">{{  __('messages.estado_civil') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.data_nascimento') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;"> {{ __('messages.bilhete_identidade') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;">Codigo Postal</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{  __('messages.telefone') }}/{{  __('messages.telemovel') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.estados') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($funcionarios as $item)
                <tr>
                    <td style="text-align: right;padding: 3px">{{ $item->conta }}</td>
                    <td style="text-align: left;padding: 3px">{{ $item->nome }}</td>
                    <td style="text-align: left;padding: 3px">{{ $item->genero ?? '------' }}</td>
                    <td style="text-align: left;padding: 3px">{{ $item->estado_civil->nome ?? '------' }}</td>
                    <td style="text-align: right;padding: 3px">{{ $item->data_nascimento ?? '------' }}</td>
                    <td style="text-align: right;padding: 3px">{{ $item->nif ?? '------' }}</td>
                    <td style="text-align: right;padding: 3px">{{ $item->codigo_postal ?? '------' }}</td>
                    <td style="text-align: right;padding: 3px">{{ $item->telefone ?? '--- --- ---' }} /
                        {{ $item->telemovel ?? '--- --- --- ---' }}</td>
                    @if ($item->status == true)
                        <td style="text-align: right;padding: 3px">{{ __('messages.activo') }} </td>
                    @else
                        <td style="text-align: right;padding: 3px">Inactivo</td>
                    @endif
                </tr>
            @endforeach

            <tr>
                <th colspan="9"
                    style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">{{ __('messages.total') }}: {{ count($funcionarios) }}</th>
            </tr>
        </tbody>
    </table>


    <div>
        <p style="position: absolute; bottom: 20px;font-size: 12px">-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS</p>
    </div>
</body>

</html>
