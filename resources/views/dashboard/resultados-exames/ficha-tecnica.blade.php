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

    <div style="width: 100%;position: relative;height: 1050px;">

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
                    <th style="font-size: 13px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;">Ficha de
                        Tratamento Nº: {{ $tratamento->id }}</th>
                </tr>
                <tr>
                    <th style="font-size: 13px;border-bottom: 2px solid #3f3f3f;padding: 5px 0;padding-top: 10px">Nome
                        Completo:
                        S<small>r(a)</small> {{ $tratamento->paciente->nome }} </th>
                </tr>
            </table>
            <table>
                <tbody>
                    <tr>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.genero') }} </th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.estado_civil') }}</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.data_nascimento') }}
                        </th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.bilhete_identidade') }} </th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->genero ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->estado_civil->nome ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->data_nascimento ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->nif ?? '-------------' }}</td>
                    </tr>

                    <tr>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">País</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nome do Pai</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nome da Mãe</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Seguradora</th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->pais ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->nome_do_pai ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->nome_da_mae ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->seguradora->nome ?? '-------------' }}</td>
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
                            {{ $tratamento->paciente->morada ?? '-------------' }}
                            <br>{{ $tratamento->paciente->codigo_postal ?? '-------------' }}
                        </td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->provincia->nome ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->municipio->nome ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->distrito->nome ?? '-------------' }}</td>
                    </tr>

                    <tr>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.telemovel') }} </th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.telemovel') }} </th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.data_nascimento') }}</th>
                        <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Website</th>
                    </tr>

                    <tr>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->telefone ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->telemovel ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->email ?? '-------------' }}</td>
                        <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                            {{ $tratamento->paciente->website ?? '-------------' }}</td>
                    </tr>

                </tbody>
            </table>


            <table>
                <tr>
                    <th style="font-size: 13px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;">DADOS DO TRATAMENTO</th>
                </tr>
            </table>


            <table>
                <tbody>
                    <tr>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Titulo </th>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ __('messages.descricao') }} </th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">Tipo</th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ __('messages.estados') }}</th>
                    </tr>

                    <tr>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->titulo ?? '-------------' }}</td>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->descricao ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->tipo ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->status ?? '-------------' }}</td>
                    </tr>

                    <tr>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> Frequência</th>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> Duração Semanas</th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ __('messages.data_inicio') }}</th>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ __('messages.data_final') }}</th>
                    </tr>


                    <tr>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->frequencia ?? '-------------' }}</td>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->duracao_semanas ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->data_inicio ?? '-------------' }}</td>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->data_final ?? '-------------' }}</td>
                    </tr>

                    @if ($tratamento->status == 'finalizado')
                    <tr>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> Data Finalização</th>
                        <th colspan="4" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ __('messages.observacao') }}</th>
                    </tr>

                    <tr>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->data_finalizacao ?? '-------------' }}</td>
                        <td colspan="4" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->observacoes_finais ?? '-------------' }}</td>
                    </tr>
                    @endif

                    @if ($tratamento->status == 'suspenso')
                    <tr>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> Data Suspensão</th>
                        <th colspan="4" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> Motivo Suspensão</th>
                    </tr>

                    <tr>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->data_suspesao ?? '-------------' }}</td>
                        <td colspan="4" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->motivo_suspesao ?? '-------------' }}</td>
                    </tr>
                    @endif

                    @if ($tratamento->status == 'cancelado')
                    <tr>
                        <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> Data Cancelamento</th>
                        <th colspan="4" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> Motivo Cancelamento</th>
                    </tr>

                    <tr>
                        <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->data_cancelamento ?? '-------------' }}</td>
                        <td colspan="4" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                            {{ $tratamento->motivo_cancelamento ?? '-------------' }}</td>
                    </tr>
                    @endif


                    <tr>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px" colspan="6">
                            Objectivo
                        </th>
                    </tr>
                    <tr>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px" colspan="6">
                            {{ $tratamento->objectivo ?? '-------------' }}</td>
                    </tr>

                    <tr>
                        <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px" colspan="6">
                            Orientações Gerais
                        </th>
                    </tr>
                    <tr>
                        <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px" colspan="6">
                            {{ $tratamento->orientacoes_gerais ?? '-------------' }}</td>
                    </tr>

                </tbody>
            </table>

        </main>
    </div>

    <div>
        <table>
            <tr>
                <th style="font-size: 13px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;">SESSÕES DE TRATAMENTO</th>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ __('messages.data') }} </th>
                    <th colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px"> {{ __('messages.estados') }} </th>
                    <th colspan="3" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ __('messages.observacao') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tratamento->sessoes_tratamento as $item)
                <tr>
                    <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                        {{ $item->data_at ?? '-------------' }}
                    </td>
                    <td colspan="2" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                        {{ $item->status ?? '-------------' }}
                    </td>
                    <td colspan="3" style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">
                        {{ $item->observacoes ?? '-------------' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
