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
    <body
        style="background-image: url('/public/images/empresa/{{ $logotipo }}'); background-attachment: fixed;
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
            <th style="font-size: 13px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;">Lista de Pacientes no Quarto: {{ $quarto->nome }}</th>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">#</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.nome') }}</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.genero') }}</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ __('messages.idade') }}</th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ __('messages.bilhete_identidade') }} </th>
                <th style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Leito</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($internamentos as $internamento)
                <tr>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ $internamento->id ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ $internamento->paciente->nome ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ $internamento->paciente->genero ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ $internamento->paciente->idade($internamento->paciente->data_nascimento) }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"> {{ $internamento->paciente->nif ?? '-------------' }}</td>
                    <td style="width: 120px;border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">  {{ $internamento->leito->nome ?? '-------------' }}</td>
                </tr>
            @endforeach
        </tbody>
        
        <tfoot>
            <tr>
                <td style="font-size: 10px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;" colspan="5">{{ __('messages.total') }}</td>
                <td style="font-size: 10px;text-transform: uppercase;border: 1px solid #3f3f3f;padding: 5px;" colspan="1">{{ count($internamentos) }}</td>
            </tr>
        </tfoot>
    </table>

</main>

</body>

</html>
