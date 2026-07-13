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
            z-index: 1727272;
            /* Z-index alto para ficar acima do conteúdo */
            pointer-events: none;
            /* Evitar que o texto interfira com a interação do usuário */
        }

    </style>
</head>

<body>

    <header style="position: absolute;top: 30;right: 10px;left: 10px;">
        <table>
            <td rowspan="">
                <img src="{{ $logotipo }}" alt="Logotipo da Empresa" style="text-align: center;height: 100px;width: 170px;">
            </td>
            <tr>
                <td style="padding: 5px 0;">
                    <strong>{{ $LOJAACTIVAOPERADOR->nome ?? '' }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $LOJAACTIVAOPERADOR->nif ?? '' }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong> {{ __('messages.data_nascimento') }}: </strong> {{ $empresa_logada->empresa->website ?? '' }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Endereço: </strong> {{ $empresa_logada->empresa->mprada ?? '' }}
                </td>
            </tr>

        </table>
    </header>

    <main style="position: absolute;top: 200px;right: 10px;left: 10px;">
        <table>
            <tr>
                <th style="font-size: 13px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;">Ficha do Registro da Morgue Nº: {{ $morgue->id ?? "" }}</th>
            </tr>
            <tr>
                <th style="font-size: 13px;border-bottom: 2px solid #3f3f3f;padding: 5px 0;padding-top: 10px">{{ __('messages.nome') }}: S<small>r(a)</small> {{ $morgue->obito->paciente->nome ?? "" }} </th>
            </tr>
        </table>
        <table>
            <tbody>
            <tbody>
                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Documento Paciente</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->obito->paciente->nif ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.idade') }}</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->obito->paciente->idade($morgue->obito->paciente->data_nascimento) ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Genero Paciente</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->obito->paciente->genero ?? "---" }}</td>
                </tr>
                <tr>
                    <th style="padding: 10px"></th>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Morgue Nº</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->id ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Data da Entrada a Morgue</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->data_entrada_morgue ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Hora da Entrada a Morgue</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->hora_entrada_morgue ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Data da Liberação</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->data_liberacao ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Hora da Liberação</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->hora_liberacao ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Obito</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->obito->documento_declaracao ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Causa do Obito</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->obito->causa_obito ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Data e Hora do obito</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->obito->data_obito ?? "---" }} {{ $morgue->obito->hora_obito ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Local</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->obito->local_obito ?? "---" }} {{ $morgue->obito->local_obito ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.estados') }}</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->status ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Gaveta</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->gaveta->nome ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Camara</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->camara->nome ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Temperatura armazenamento</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->temperatura_armazenamento ?? "---" }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.observacao') }}</th>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" class="text-right">{{ $morgue->observacoes_iniciais ?? "---" }}</td>
                </tr>


            </tbody>
            </tbody>
        </table>
    </main>

</body>

</html>
