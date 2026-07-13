<!DOCTYPE html>
<html lang="pdf">

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

<body>

    <table style="border: 0">
        <tr style="border: 0">
            <td style="padding: 20px 0;border: 0">UEA - UNIÃO DOS EMPRESÁRIOS DE ANGOLA</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>NIF: </strong>5099927232</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Endereço: </strong>Lorem ipsum dolor sit amet.</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Luanda - Angola</strong></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
        </thead>
    </table>
    @php
    function descricao(string $string)
    {
    if($string == "1") {return "Janeiro"; }
    if($string == "2") {return "Fevereiro"; }
    if($string == "3") {return "Março"; }
    if($string == "4") {return "Abril"; }
    if($string == "5") {return "Maio"; }
    if($string == "6") {return "Junho"; }
    if($string == "7") {return "Julho"; }
    if($string == "8") {return "Agosto"; }
    if($string == "9") {return "Setembro"; }
    if($string == "10") {return "Outubro"; }
    if($string == "11") {return "Novembro"; }
    if($string == "12") {return "Dezembro"; }
    }
    @endphp

    <table>
        <thead>
            <tr>
                <th style="padding: 3px;text-transform: uppercase;border: 1px solid #030303">Ano</th>
                <th style="padding: 3px;text-transform: uppercase;border: 1px solid #030303">Mês</th>
                <th style="padding: 3px;text-transform: uppercase;border: 1px solid #030303">Estado</th>
            </tr>
            <tr>
                <th style="padding: 3px;text-transform: uppercase;border: 1px solid #030303">{{ $requests['ano'] ?? 'Todos' }}</th>
                <th style="padding: 3px;text-transform: uppercase;border: 1px solid #030303">{{ $requests['mes'] ? descricao($requests['mes']) : 'Todos' }}</th>
                <th style="padding: 3px;text-transform: uppercase;border: 1px solid #030303">{{ $requests['status'] ?? 'Todos' }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="padding: 3px;text-align: left;border: 1px solid #030303">Membro</th>
                <th style="padding: 3px;text-align: left;border: 1px solid #030303">Mês</th>
                <th style="padding: 3px;text-align: left;border: 1px solid #030303">Valor</th>
                <th style="padding: 3px;text-align: left;border: 1px solid #030303">Multa</th>
                <th style="padding: 3px;text-align: left;border: 1px solid #030303">Juros</th>
                <th style="padding: 3px;text-align: left;border: 1px solid #030303">Total</th>
                <th style="padding: 3px;text-align: left;border: 1px solid #030303">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mensalidades as $m)

            <tr style="
                    @if($m->status == 'pago')
                        background-color: #d4edda;
                    @elseif($m->status == 'parcial')
                        background-color: #fff3cd;
                    @else
                        background-color: #f8d7da;
                    @endif
                ">

                <td style="padding: 3px;text-align: left">
                    {{ $m->membro->nome }}
                </td>

                <td style="padding: 3px;text-align: left">
                    {{ $m->mes }}/{{ $m->ano }}
                </td>

                <td style="padding: 3px;text-align: left">
                    {{ number_format($m->valor_original,2,',','.') }}
                </td>

                <td style="padding: 3px;text-align: left">
                    {{ number_format($m->multa,2,',','.') }}
                </td>

                <td style="padding: 3px;text-align: left">
                    {{ number_format($m->juros,2,',','.') }}
                </td>

                <td style="padding: 3px;text-align: left">
                    {{ number_format($m->valor_total,2,',','.') }}
                </td>

                <td style="padding: 3px;text-align: left">
                    @if($m->status == 'pago')
                    <span class="badge badge-light-success">
                        Pago
                    </span>

                    @elseif($m->status == 'parcial')

                    <span class="badge badge-light-warning">
                        Parcial
                    </span>

                    @else

                    <span class="badge badge-light-danger">
                        Vencido
                    </span>

                    @endif
                </td>

            </tr>

            @endforeach
        </tbody>

    </table>
</body>
</html>
