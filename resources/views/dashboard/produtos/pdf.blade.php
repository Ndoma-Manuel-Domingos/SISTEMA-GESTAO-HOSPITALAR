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
                <td style="border: 0">{{ $empresa->nome }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>NIF: </strong>{{ $empresa->nif }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>Endereço: </strong>{{ $empresa->morada }}</td>
            </tr>
            <tr style="border: 0">
                <td style="border: 0"><strong>{{ $empresa->cidade }} - {{ $empresa->pais }}</strong></td>
            </tr>
        </table>
    </header>

    <table>
        <thead>
            <tr>
                <th colspan="7" style="text-transform: uppercase;padding: 2px;"> {{ $titulo }}</th>
            </tr>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;width: 100px">Codigo Barra</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.designacao') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;">Tipo</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ __('messages.preco') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">Preço Fornecedor</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">IVA %</th>
                @if ($lojas)
                @foreach ($lojas as $loja)
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ $loja->nome }}</th>
                @endforeach
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach ($produtos as $produto)
            <tr>
                <td style="padding: 3px">{{ $produto->codigo_barra }} </td>
                <td style="padding: 3px">{{ $produto->nome }}
                    <br><small>{{ $produto->categoria->categoria }}</small></td>
                @if ($produto->tipo == 'P')
                <td style="padding: 3px">{{ __('messages.designacao') }}</td>
                @endif
                @if ($produto->tipo == 'S')
                <td style="padding: 3px">{{ __('messages.servico') }}</td>
                @endif
                @if ($produto->tipo == 'O')
                <td style="padding: 3px">Outro (portes, adiantamentos, etc.)</td>
                @endif
                @if ($produto->tipo == 'I')
                <td style="padding: 3px">Imposto (excepto IVA e IS) ou Encargo Parafiscal</td>
                @endif
                @if ($produto->tipo == 'E')
                <td style="padding: 3px">Imposto Especial de Consumo (IABA, ISP e IT)</td>
                @endif
                <td style="text-align: right;padding: 3px">{{ number_format($produto->preco_venda, 2, ',', '.') }}
                    <span class="text-light-secondary">{{ $empresa->moeda }}</span> <br>
                    <small>S/IVA: {{ number_format($produto->preco, 2, ',', '.') }}
                        <span class="text-light-secondary">{{ $empresa->moeda }}</span></small>
                </td>
                <td style="text-align: right;padding: 3px">{{ number_format($produto->preco_custo, 2, ',', '.') }}
                    <span class="text-light-secondary">{{ $empresa->moeda }}</span>
                </td>
                <td style="text-align: right;padding: 3px">
                    {{ number_format($produto->taxa_imposto->valor, 2, ',', '.') }}</td>
                @foreach ($lojas as $loja)
                @php
                $estoque = App\Models\Estoque::where('loja_id', $loja->id)
                ->where('produto_id', $produto->id)
                ->first();
                @endphp
                @if ($estoque)
                <td style="text-align: right;padding: 3px"> <span class="bg-light-primary p-1 text-center">{{ number_format($estoque->stock, 2, ',', '.') }}</span>
                </td>
                @else
                <td style="text-align: right;padding: 3px"> <span class="bg-light-primary p-1 text-center">0</span>
                </td>
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="12" style="border: 1px solid #010101;padding: 2px;padding: 5px">{{ __('messages.total') }}
                    <span style="float: right">{{ count($produtos) }}</span>
                </th>
            </tr>
        </tfoot>
    </table>

    <div>
        <p style="position: absolute; bottom: 20px;font-size: 12px">-Processado por programa validado Nº 469/AGT/2024 EA-VIEGAS</p>
    </div>
</body>

</html>
