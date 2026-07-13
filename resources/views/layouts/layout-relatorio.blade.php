<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>{{ $titulo ?? 'Relatório' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #2c3e50;
            margin: 20px;
        }

        .header {
            border-bottom: 3px solid #0B6FA4;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        .logo {
            width: 120px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #0B6FA4;
        }

        .company-info {
            font-size: 10px;
            color: #555;
            line-height: 16px;
        }

        .title {
            text-align: center;
            background: #0B6FA4;
            color: white;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        .section {
            margin-top: 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th {
            background: #0B6FA4;
            color: white;
            padding: 8px;
            font-size: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 10px;
        }

        .footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #777;
        }

        .page-number:after {
            content: counter(page);
        }

        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 90px;
            color: #0B6FA4;
            opacity: 0.05;
            font-weight: bold;
            z-index: -1;
        }

    </style>
</head>

<body>
    <div class="watermark">
        {{ strtoupper($LOJAACTIVAOPERADOR->nome ?? 'LAB') }}
    </div>
    <!-- HEADER -->
    <div class="header">
        <table width="100%">
            <tr>
                <td width="20%">
                    @if(!empty($logotipo))
                    <img src="{{ $logotipo }}" class="logo">
                    @endif
                </td>
                <td width="80%">
                    <div class="company-name">
                        {{ $LOJAACTIVAOPERADOR->nome ?? 'LABORATÓRIO' }}
                    </div>
                    <div class="company-info">
                        <strong>NIF:</strong>
                        {{ $LOJAACTIVAOPERADOR->nif }}
                        <br>
                        <strong>Endereço:</strong>
                        {{ $empresa_logada->empresa->mprada }}
                        <br>
                        <strong>Website:</strong>
                        {{ $empresa_logada->empresa->website }}
                        <br>
                        <strong>Email:</strong>
                        {{ $empresa_logada->empresa->email ?? '---' }}
                        <br>
                        <strong>Telefone:</strong>
                        {{ $empresa_logada->empresa->telefone ?? '---' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- TITLE -->
    <div class="title">
        {{ $titulo ?? 'RELATÓRIO' }}
    </div>
    <!-- CONTENT -->
    <div class="section">
        @yield('content')
    </div>
    <!-- FOOTER -->
    <div class="footer">
        Emitido em {{ now()->format('d/m/Y H:i') }} |
        Página <span class="page-number"></span>
    </div>

</body>
</html>
