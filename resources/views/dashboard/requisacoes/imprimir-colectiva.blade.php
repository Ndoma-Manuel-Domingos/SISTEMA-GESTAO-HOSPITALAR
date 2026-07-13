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
    <table style="border: 0">
        <tr>
            <td style="border: 0;">
                <img src="{{ $logotipo }}" alt="Logotipo da Empresa" style="text-align: center;height: 100px;width: 170px;">
            </td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0;padding: 20px 0">{{ $LOJAACTIVAOPERADOR->nome }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>NIF: </strong>{{ $LOJAACTIVAOPERADOR->nif }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Endereço: </strong>{{ $LOJAACTIVAOPERADOR->morada }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>{{ $empresa_logada->empresa->cidade }} -
                    {{ $empresa_logada->empresa->pais }}</strong></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase">{{ __('messages.data_inicio') }}</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase">{{ __('messages.data_final') }}</th>
                <th colspan="2" style="border: 1px solid #010101;padding: 2px;text-transform: uppercase">{{ __('messages.estados') }}</th>
            </tr>

            <tr>
                <th style="border: 1px solid #010101;padding: 2px;" colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th style="border: 1px solid #010101;padding: 2px;" colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
                <th style="border: 1px solid #010101;padding: 2px;" colspan="2">{{ $requests['tipo_documento'] ?? 'TODOS' }}</th>
            </tr>
        </thead>
    </table>

    <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;">Nº Requisição</th>
                <th style="border: 1px solid #010101;padding: 2px;">Requisitante</th>
                <th style="border: 1px solid #010101;padding: 2px;"> {{ __('messages.data') }} </th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.estados') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Qtd Produtos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requisicoes as $item)
            <tr>
                <td style="padding: 3px;text-align: left">REQ Nº {{ $item->id ?? "" }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->user->name }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->data_emissao }}</td>

                @if ($item->status == 'pendente')
                <td><span style="text-transform: uppercase;color: #166c9e;">{{ $item->status }}</span></td>
                @endif

                @if ($item->status == 'aprovada')
                <td><span style="text-transform: uppercase;color: #609b39;">{{ $item->status }}</span></td>
                @endif

                @if ($item->status == 'rejeitada')
                <td><span style="text-transform: uppercase;color: #991010;">{{ $item->status }}</span></td>
                @endif

                @if ($item->status == 'rascunho')
                <td><span style="text-transform: uppercase;color: #d1a207;">{{ $item->status }}</span></td>
                @endif

                <td style="text-align: right">{{ count($item->items) }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
