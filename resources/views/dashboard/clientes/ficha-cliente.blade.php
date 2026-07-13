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

@if ($empresa_logada->empresa->marca_d_agua_facturas == true)

    <body
        style="background-image: url('/public/images/empresa/{{ $logotipo }}'); background-attachment: fixed;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: contain;opacity: .1;margin: 140px;">
@endif

@if ($empresa_logada->empresa->marca_d_agua_facturas == false)

    <body>
@endif

<header style="position: absolute;top: 30;right: 30px;left: 30px;">
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

<main style="position: absolute;top: 200px;right: 30px;left: 30px;">
    <table>
        <tr>
            <th style="font-size: 13px;text-transform: uppercase;border-bottom: 2px solid #3f3f3f;padding: 5px;">
                {{ $titulo }}: {{ $aluno->id }} - {{ $aluno->nome }} </th>
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
                    {{ $aluno->genero ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->estado_civil->nome ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->data_nascimento ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->nif ?? '-------------' }}</td>
            </tr>

            <tr>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">País</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nome do Pai</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nome da Mãe</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Seguradora</th>
            </tr>

            <tr>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->pais ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->nome_do_pai ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->nome_da_mae ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->seguradora->nome ?? '-------------' }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <tbody>
            <tr>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" colspan="2">Tipo
                    Pessoal</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" colspan="2">Tipo
                    Funcionário</th>
            </tr>
            <tr>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" colspan="2">
                    {{ $aluno->categoria ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" colspan="2">
                    {{ $aluno->tipo_aluno->nome ?? '-------------' }}</td>
            </tr>

            <tr>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Morada</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Províncias</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Município</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Distrito</th>
            </tr>

            <tr>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->morada ?? '-------------' }} <br>{{ $aluno->codigo_postal ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->provincia->nome ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->municipio->nome ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->distrito->nome ?? '-------------' }}</td>
            </tr>

            <tr>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.telemovel') }} </th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.telemovel') }} </th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.data_nascimento') }}</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Website</th>
            </tr>

            <tr>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->telefone ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->telemovel ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->email ?? '-------------' }}</td>
                <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">
                    {{ $aluno->website ?? '-------------' }}</td>
            </tr>

        </tbody>
    </table>


    @if ($matriculas)
        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFOR')
            <table style="margin: 20px 0">
                <tbody>
                    <tr>
                        <th style="border-bottom: 1px solid #cecece;text-align: left;padding: 5px 0;" colspan="2">
                            DADOS ACADEMICOS</th>
                    </tr>
                    @foreach ($matriculas as $item)
                        <tr>
                            <th style="border-bottom: 1px solid #cecece;text-align: left;padding: 5px 0;">CURSO:
                                {{ $item->curso->nome ?? '' }}</th>
                            <th style="border-bottom: 1px solid #cecece;text-align: left;padding: 5px 0;">TURNO:
                                {{ $item->turno->nome ?? '' }}</th>
                        </tr>
                        <tr>
                            <th style="border-bottom: 1px solid #cecece;text-align: left;padding: 5px 0;">SALA:
                                {{ $item->sala->nome ?? '' }}</th>
                            <th style="border-bottom: 1px solid #cecece;text-align: left;padding: 5px 0;">ANO LECTIVO:
                                {{ $item->ano_lectivo->nome ?? '' }}</th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif

</main>

</body>

</html>
