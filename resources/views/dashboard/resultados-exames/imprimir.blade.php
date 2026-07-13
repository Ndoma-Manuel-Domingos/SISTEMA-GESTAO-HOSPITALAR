<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }}</title>

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
            </tr>
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $LOJAACTIVAOPERADOR->nif }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Telefone: </strong> {{ $LOJAACTIVAOPERADOR->telefone }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong> {{ __('messages.data_nascimento') }}: </strong> {{ $LOJAACTIVAOPERADOR->email }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Website: </strong> {{ $empresa_logada->empresa->website }}
                </td>
            </tr>

        </table>
    </header>

    <main style="position: absolute;top: 240px;right: 30px;left: 30px;">
        <table>
            <thead>
                <tr>
                    <th colspan="4" style="text-transform: uppercase;padding: 5px 0"> {{ $titulo }}</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #010101;text-align: left;padding: 3px">{{ __('messages.data_inicio') }}</th>
                    <th style="border: 1px solid #010101;text-align: left;padding: 3px">{{ __('messages.data_final') }}</th>
                    <th style="border: 1px solid #010101;text-align: left;padding: 3px">{{ __('messages.estados') }}</th>
                    <th style="border: 1px solid #010101;text-align: left;padding: 3px">{{ __('messages.paciente') }}</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #010101;text-align: left;padding: 3px"> {{ $requests['data_inicio'] ?? 'TODAS' }}</th>
                    <th style="border: 1px solid #010101;text-align: left;padding: 3px"> {{ $requests['data_final'] ?? 'TODAS' }}</th>
                    <th style="border: 1px solid #010101;text-align: left;padding: 3px"> {{ $requests['status'] ?? 'TODAS' }}</th>
                    <th style="border: 1px solid #010101;text-align: left;padding: 3px"> {{ $paciente ? $paciente->nome : 'TODAS' }}</th>
                </tr>
            </thead>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="border: 1px solid #010101;padding: 2px;">#</th>
                    <th style="border: 1px solid #010101;padding: 2px;">Codigo</th>
                    <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.paciente') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.idade') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.estados') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;">Duração Semanas</th>
                    <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.data_inicio') }}</th>
                    <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.data_final') }}</th>
                </tr>
            </thead>
            @foreach ($tratamentos as $key => $item)
            <tr>
                <td style="text-align: right;padding: 3px">{{ $key + 1 }}</td>
                <td style="text-align: left;padding: 3px">{{ $item->id ?? "" }}</td>
                <td style="text-align: left;padding: 3px">{{ $item->paciente->nome ?? "" }}</td>
                <td style="text-align: left;padding: 3px">{{ $item->paciente->idade($item->paciente->data_nascimento) }}</td>
                <td style="text-align: left;padding: 3px">{{ $item->status }}</td>
                <td style="text-align: left;padding: 3px">{{ $item->duracao_semanas }}</td>
                <td style="text-align: left;padding: 3px">{{ $item->data_inicio }}</td>
                <td style="text-align: left;padding: 3px">{{ $item->data_final }}</td>
            </tr>
            @endforeach
            <tfoot>
                <tr>
                    <th colspan="6" style="border: 1px solid #010101;text-align: left;padding: 3px">{{ __('messages.total') }}</th>
                    <th colspan="2" style="border: 1px solid #010101;text-align: right;padding: 3px">{{ count($tratamentos) }}</th>
                </tr>
            </tfoot>
        </table>

    </main>

</body>

</html>
