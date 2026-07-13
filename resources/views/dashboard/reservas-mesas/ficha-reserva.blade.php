<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }} </title>
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

@if ($empresa_logada->empresa->marca_d_agua_facturas == true)

<body style="background-image: url('/public/images/empresa/{{ $logotipo }}'); background-attachment: fixed;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: contain;opacity: .1;margin: 140px;">
    @endif

    @if ($empresa_logada->empresa->marca_d_agua_facturas == false)

    <body>
        @endif

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
                    <th style="font-size: 13px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;">Ficha da Reserva Nº: {{ $reserva->id }}</th>
                </tr>
                <tr>
                    <th style="font-size: 13px;border-bottom: 2px solid #3f3f3f;padding: 5px 0;padding-top: 10px">{{ __('messages.nome') }}: S<small>r(a)</small> {{ $reserva->cliente->nome }} </th>
                </tr>
            </table>
            <table>
                <tbody>
                    <tr>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{  __('messages.genero') }} </th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.estado_civil') }}</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.data_nascimento') }}</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.bilhete_identidade') }} </th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->genero ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->estado_civil->nome ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->data_nascimento ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->nif ?? '-------------' }}</td>
                    </tr>

                    <tr>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">País</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nome do Pai</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nome da Mãe</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Seguradora</th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->pais ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->nome_do_pai ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->nome_da_mae ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->seguradora->nome ?? '-------------' }}</td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody>

                    <tr>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Morada</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Províncias</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Município</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Distrito</th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->morada ?? '-------------' }}
                            <br>{{ $reserva->cliente->codigo_postal ?? '-------------' }}
                        </td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->provincia->nome ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->municipio->nome ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->distrito->nome ?? '-------------' }}</td>
                    </tr>

                    <tr>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.telemovel') }} </th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.telemovel') }} </th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.data_nascimento') }}</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Website</th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->telefone ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->telemovel ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->email ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $reserva->cliente->website ?? '-------------' }}</td>
                    </tr>

                </tbody>
            </table>


            <table>
                <tr>
                    <th colspan="4" style="font-size: 13px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;">DADOS DA RESERVA</th>
                </tr>
            </table>

            <table>
                <tbody>
                    <tr>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">#</th>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Quarto</th>
                    </tr>
                    @foreach ($reserva->items as $item)
                    <tr>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $item->mesa ? $item->mesa->id : 'indefinido' }}</td>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $item->mesa ? $item->mesa->nome : 'indefinido' }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Data Entrada </th>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Hora Entrada</th>
                    </tr>

                    <tr>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->data_entrada ?? '-------------' }}</td>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->hora_entrada ?? '-------------' }}</td>
                    </tr>


                    <tr>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Nº Adultos </th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Nº Crianças </th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Total de Mesas</th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ __('messages.exercicio') }} </th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->total_pessoas ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->numero_criancas ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->total_mesas ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->exercicio->nome ?? '-------------' }}</td>
                    </tr>

                    <tr>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ __('messages.periodo') }} </th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Estado Pagamento</th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Estado Reserva</th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Estado Check</th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->periodo->nome ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->pagamento ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->status ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->check ?? '-------------' }}</td>
                    </tr>


                    <tr>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Data Check In</th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Data Check Out</th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Hora Check In</th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Hora Check Out</th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->data_check_in ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->hora_check_in ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->data_check_out ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->hora_check_out ?? '-------------' }}</td>
                    </tr>


                    <tr>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Operador check In</th>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Operador check Out</th>
                    </tr>

                    <tr>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->user_in_ckeck->name ?? '-------------' }}</td>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ $reserva->user_out_check->name ?? '-------------' }}</td>
                    </tr>


                    <tr>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px" colspan="4">{{ __('messages.observacao') }}</th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px" colspan="4"> {{ $reserva->observacao ?? '-------------' }}</td>
                    </tr>


                </tbody>
            </table>

        </main>

    </body>

</html>
