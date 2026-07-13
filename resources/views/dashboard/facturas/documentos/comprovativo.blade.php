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
            padding: 0px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 15px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 12x;
        }

        p {
            /* margin-bottom: 20px; */
            line-height: 25px;
            font-size: 12px;
            text-align: justify;
        }

        strong {
            font-size: 12px;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 12px;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 12px;
        }

        th,
        td {
            padding: 6px;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        strong {
            font-size: 12px;
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

        /* Estilo para a impressão */
        @media print {
            @page {
                margin: 10px;
                /* Remove todas as margens da página */
                background-color: #000;
            }

            .pagina {
                width: 500px;
                height: 100vh;
                page-break-after: always;
                /* Força quebra de página após cada seção */
            }

            body {
                margin: 0;
                padding: 0;
            }
        }

        /* Estilo para visualização na tela */
        @media screen {
            .pagina {
                width: 500px;
                height: 100vh;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                padding: 20px;
            }
        }
    </style>

</head>

<body>
    <div class="pagina">
        <header style="max-width: 350px; top: 10;right: 0;left: 0;">
            <table>
                <tr>
                    <td>
                        <img src="{{ $logotipo }}" alt="Logotipo da Empresa" style="text-align: center;height: 100px;width: 170px;">
                    </td>
                    <td style="text-align: right">
                        <span style="margin-bottom: 50px;font-weight: bolder;font-size:15px">Pág: 1/1</span> <br>
                        <br>
                        {{ date('d-m-Y', strtotime($cartao->created_at)) }} <br> <br>
                        ORIGINAL
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" style="padding: 5px 0;font-weight: bolder;font-size:20px">
                        <strong style="font-weight: bolder;font-size:20px;padding: 20px 0">{{ $LOJAACTIVAOPERADOR->nome }}</strong>
                    </td>
                </tr>
            </table>
        </header>

        <main style="max-width: 350px; top: 230px;right: 0;left: 0;">

            <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                <thead style="border-bottom: 1px dashed #000;">
                    <tr>
                        <th colspan="2" style="font-weight: bolder;font-size:15px;padding: 20px 0;">COMPROVATIVO</th>
                    </tr>
                    <tr>
                        <th colspan="2" style="font-weight: bolder;font-size:15px;padding: 20px 0;">CARTÃO Nº {{ $cartao->cartao->nome }}</th>
                    </tr>
                    <tr>
                        <th style="font-weight: bolder;border: 1px solid #040303;font-size:15px;padding: 10px;"> SALDO</th>
                        <th style="font-weight: bolder;border: 1px solid #040303;font-size:15px;padding: 10px;"> TIPO MOVIMENTO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: bolder;border: 1px solid #040303;font-size:15px;padding: 20px 10px;"> {{ number_format(($cartao->saldo ?? 0), 2, ',', '.') }}</td>
                        <td style="font-weight: bolder;border: 1px solid #040303;font-size:15px;padding: 20px 10px;"> {{ $cartao->tipo == "D" ? 'Debito' : 'Crédito' }}</td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>

    <script>

        window.print();
        setTimeout(() => {
            window.top.location = "/cartoes-consumos";
            return;
        }, 3000);
    </script>

</body>

</html>
