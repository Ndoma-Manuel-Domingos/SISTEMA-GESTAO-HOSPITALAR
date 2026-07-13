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

        .page-break {
            page-break-after: always;
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

        @foreach ($atendimento->exames as $exame)
        <table>
            <tr>
                <th style="font-size: 13px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;">Ficha do Exame Nº: {{ $exame->id }}</th>
            </tr>
            <tr>
                <th style="font-size: 13px;border-bottom: 2px solid #3f3f3f;padding: 5px 0;padding-top: 10px">{{ __('messages.nome') }}: S<small>r(a)</small> {{ $exame->paciente->nome }} </th>
            </tr>
        </table>
        <table>
            <tbody>
                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.genero') }} </th>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.estado_civil') }}</th>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.data_nascimento') }}</th>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.bilhete_identidade') }} </th>
                </tr>

                <tr>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->genero ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->estado_civil->nome ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->data_nascimento ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->nif ?? '-------------' }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">País</th>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nome do Pai</th>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nome da Mãe</th>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Seguradora</th>
                </tr>

                <tr>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->pais ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->nome_do_pai ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->nome_da_mae ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->seguradora->nome ?? '-------------' }}</td>
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
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->morada ?? '-------------' }} <br>{{ $exame->paciente->codigo_postal ?? '-------------' }}
                    </td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->provincia->nome ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->municipio->nome ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->distrito->nome ?? '-------------' }}</td>
                </tr>

                <tr>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.telemovel') }} </th>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.telemovel') }} </th>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.data_nascimento') }}</th>
                    <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Website</th>
                </tr>

                <tr>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->telefone ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->telemovel ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->email ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $exame->paciente->website ?? '-------------' }}</td>
                </tr>

            </tbody>
        </table>

        <table>
            <tr>
                <th style="font-size: 13px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;">DADOS DO EXAME</th>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">#</th>
                    <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ __('messages.designacao') }} </th>
                    <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ __('messages.categoria') }}</th>
                    <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ __('messages.valor') }}</th>
                    <th style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ __('messages.valor') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($exame->items as $item)
                <tr>
                    <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ $item->id ?? "" }}</td>
                    <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ $item->produto->nome ?? "" }}</td>
                    <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ $item->produto ? $item->produto->categoria->categoria : "" }}</td>
                    <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ number_format($item->valor, 2, ',', '.') }}</td>
                    <td style="width: 120px;border: 1px solid #727272;padding: 5px;margin: 5px">{{ number_format($item->valor, 2, ',', '.') }}</td>
                </tr>

                <tr>
                    <td colspan="5">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Código</th>
                                    <th style="text-align: center">Parâmetro</th>
                                    <th style="text-align: center">Resultado</th>
                                    <th style="text-align: center">Unidade</th>
                                    <th style="text-align: center">Referência</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->paramentos_exames as $param_exame)
                                @if ($param_exame->paramentro->tipo === "lista" || $param_exame->paramentro->tipo === "data" || $param_exame->paramentro->tipo === "numero" || $param_exame->paramentro->tipo === "booleano" || $param_exame->paramentro->tipo === "textarea" || $param_exame->paramentro->tipo === "texto")
                                <tr>
                                    <td style="text-align: center">{{ $param_exame->paramentro->codigo ?? '----------' }}</td>
                                    <td style="text-align: center">{{ $param_exame->paramentro->nome ?? '----------' }}</td>

                                    @if ($param_exame->paramentro->tipo === "textarea" || $param_exame->paramentro->tipo === "texto")
                                    <td style="text-align: center">{{ $param_exame->valor }}</td>
                                    @endif

                                    @if ($param_exame->paramentro->tipo == "lista" || $param_exame->paramentro->tipo === "numero" || $param_exame->paramentro->tipo === "booleano")
                                    <td style="text-align: center">{{ $param_exame->valor }}</td>
                                    @endif

                                    @if ($param_exame->paramentro->tipo === "data")
                                    <td style="text-align: center">{{ $param_exame->valor }}</td>
                                    @endif

                                    @if ($param_exame->paramentro->tipo == "lista")
                                    <td style="text-align: center">{{ $param_exame->paramentro->opcoes ?? '----------' }}</td>
                                    @else
                                    @if ($param_exame->paramentro->tipo == "booleano")
                                    <td style="text-align: center">
                                        {{ $param_exame->paramentro->texto_sim ?? '----------' }}
                                        /
                                        {{ $param_exame->paramentro->texto_nao ?? '----------' }}
                                    </td>
                                    @else
                                    <td style="text-align: center">{{ $param_exame->paramentro->unidade ?? '----------' }}</td>
                                    @endif
                                    @endif
                                    <td style="text-align: center">{{ $param_exame->paramentro->valor_referencia ?? '----------' }}</td>
                                </tr>
                                @endif
                                @endforeach

                                @foreach ($item->paramentos_exames_imagem as $param_exame_imagem)
                                @if ($param_exame_imagem->paramentro->tipo === "imagem")
                                <tr>
                                    <td style="text-align: center">{{ $param_exame_imagem->paramentro->codigo ?? '----------' }}</td>
                                    <td style="text-align: center">{{ $param_exame_imagem->paramentro->nome ?? '----------' }}</td>
                                    <td style="text-align: center">{{ $param_exame_imagem->descricao }}</td>
                                    @if ($param_exame_imagem->paramentro->tipo == "lista")
                                    <td style="text-align: center">{{ $param_exame_imagem->paramentro->opcoes ?? '----------' }}</td>
                                    @else
                                    @if ($param_exame_imagem->paramentro->tipo == "booleano")
                                    <td style="text-align: center">
                                        {{ $param_exame_imagem->paramentro->texto_sim ?? '----------' }}
                                        /
                                        {{ $param_exame_imagem->paramentro->texto_nao ?? '----------' }}
                                    </td>
                                    @else
                                    <td style="text-align: center">{{ $param_exame_imagem->paramentro->unidade ?? '----------' }}</td>
                                    @endif
                                    @endif
                                    <td style="text-align: center">{{ $param_exame_imagem->paramentro->valor_referencia ?? '----------' }}</td>
                                </tr>
                                @endif
                                @endforeach

                                @foreach ($item->paramentos_exames_imagem as $param_exame_imagem)
                                @if ($param_exame_imagem->paramentro->tipo === "imagem")
                                <tr>
                                    <td colspan="5">
                                        <strong>{{ $param_exame_imagem->paramentro->nome }}</strong>
                                        @php
                                        $ficheiros = json_decode($param_exame_imagem->ficheiros, true) ?? [];
                                        @endphp
                                        @foreach ($ficheiros as $ficheiro)
                                        <div style="margin:10px 0;">
                                            <img src="{{ public_path('/resultados/exames/' . $ficheiro) }}" style="max-width:250px; max-height:250px;">
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if (!$loop->last)
        <div class="page-break"></div>
        @endif

        @endforeach

    </main>

</body>

</html>
