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
        style="background-image: url('/public/images/empresa/{{ $empresa_logada->empresa->logotipo ?? '' }}'); background-attachment: fixed;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: contain;opacity: .1;margin: 140px;">
@endif

@if ($empresa_logada->empresa->marca_d_agua_facturas == false)

    <body>
@endif
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
                <strong> {{ __('messages.email') }}: </strong> {{ $LOJAACTIVAOPERADOR->email }}
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
        <tr>
            <td style="font-size: 17px;text-transform: uppercase;text-align: center"> {{ $titulo }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="font-size: 15px;padding: 17px 0">Atesto, para os devidos fins, que o(a) Sr.(a)
                {{ $consulta->paciente->nome }},
                nascido aos {{ date('d', strtotime($consulta->paciente->data_nascimento)) }} de
                {{ $consulta->descricao_mes(date('M', strtotime($consulta->paciente->data_nascimento))) }}
                de {{ date('Y', strtotime($consulta->paciente->data_nascimento)) }}, foi atendido(a) em consulta médica
                nesta data {{ date('d', strtotime($consulta->data_consulta)) }} de
                {{ $consulta->descricao_mes(date('M', strtotime($consulta->data_consulta))) }}
                de {{ date('Y', strtotime($consulta->data_consulta)) }}, apresentando quadro clínico que requer:</td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding: 5px 0;">( ) Afastamento do trabalho/escola por ______ dias, a contar de
                ___/___/____.</td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding: 5px 0;">( ) Repouso domiciliar por ______ dias.</td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding: 5px 0;">( ) Liberação para atividades normais a partir de ___/___/____.
            </td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding-top: 17px;">Motivo do afastamento:
                ___________________________________________________________________</td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding: 5px 0;">
                ______________________________________________________________________________________</td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding: 5px 0;">
                ______________________________________________________________________________________</td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding-top: 10px;">CID: {{ $consulta->cids->sigla ?? "" }} -
                {{ $consulta->cids->nome ?? "" }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="font-size: 15px;padding-top: 40px;">Local e Data: ____________________, ___ de _______________ de
                ______.</td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding-top: 40px">Assinatura e carimbo do médico:</td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding: 5px 0;">____________________________________________</td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding: 5px 0;">Dr. {{ $consulta->medico->funcionario->nome ?? '' }}</td>
        </tr>
        <tr>
            <td style="font-size: 15px;padding: 5px 0;">Nº Ordem. {{ $consulta->medico->numero_cedula ?? '' }}</td>
        </tr>
    </table>

</main>


</body>

</html>
