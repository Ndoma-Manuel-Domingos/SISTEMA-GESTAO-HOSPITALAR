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
                <td style="border: 0">{{ $empresa_logada->empresa->nome }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>NIF: </strong>{{ $empresa_logada->empresa->nif }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>Endereço: </strong>{{ $empresa_logada->empresa->morada }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>{{ $empresa_logada->empresa->cidade }} - {{ $empresa_logada->empresa->pais }}</strong></td>
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
                <th style="border: 1px solid #010101;padding: 2px;">Exame</th>
                <th style="border: 1px solid #010101;padding: 2px;">Ordem</th>
                <th style="border: 1px solid #010101;padding: 2px;">Nome</th>
                <th style="border: 1px solid #010101;padding: 2px;">Tipo</th>
                <th style="border: 1px solid #010101;padding: 2px;">Unidade</th>
                <th style="border: 1px solid #010101;padding: 2px;">Referência</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($exames as $item)
                <tr>
                    <td style="text-align: left;padding: 3px">{{ $item->exame->nome ?? "" }}</td>
                    <td style="text-align: left;padding: 3px">{{ $item->ordem ?? "" }}</td>
                    <td style="text-align: left;padding: 3px">{{ $item->nome ?? "" }}</td>
                    <td style="text-align: left;padding: 3px">{{ $item->tipo }}</td>
                     @if ($item->tipo == "lista")
                    <td style="text-align: left;padding: 3px">{{ $item->opcoes }}</td>
                    @else
                        @if ($item->tipo == "booleano")
                            <td style="text-align: left;padding: 3px">{{ $item->texto_sim }}/{{ $item->texto_nao }}</td>
                        @else
                            <td style="text-align: left;padding: 3px">{{ $item->unidade }}</td>
                        @endif
                    @endif
                    <td style="text-align: left;padding: 3px">{{ $item->valor_referencia  }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="9" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase;text-align: left">{{ __('messages.total') }}: {{ count($exames) }}</th>
            </tr>
        </tbody>
    </table>


    <div>
        <p style="position: absolute; bottom: 20px;font-size: 12px">-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS</p>
    </div>
</body>

</html>
