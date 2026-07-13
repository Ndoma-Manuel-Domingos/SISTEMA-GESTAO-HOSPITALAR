<!DOCTYPE html>
<html lang="pt">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $titulo }}</title>

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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 7px;
            vertical-align: top;
        }

        .header {
            border-bottom: 3px solid #0B6FA4;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 150px;
            height: auto;
        }

        .lab-name {
            font-size: 22px;
            font-weight: bold;
            color: #0B6FA4;
        }

        .lab-info {
            font-size: 10px;
            color: #555;
            line-height: 18px;
        }

        .report-title {
            margin-top: 15px;
            text-align: center;
            background: #0B6FA4;
            color: #fff;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .section {
            margin-top: 18px;
        }

        .section-title {
            background: #EDF5FB;
            border-left: 6px solid #0B6FA4;
            color: #0B6FA4;
            font-size: 13px;
            font-weight: bold;
            padding: 8px;
        }

        .info {
            border: 1px solid #DDD;
            margin-top: 5px;
        }

        .info td {
            border: 1px solid #E5E5E5;
            padding: 8px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .watermark {
            position: fixed;
            top: 42%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 110px;
            color: #0B6FA4;
            opacity: .05;
            z-index: -1;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

    </style>

</head>

<body>

    <div class="watermark">

        {{ strtoupper($LOJAACTIVAOPERADOR->nome ?? 'LABORATÓRIO') }}

    </div>

    <!-- ================================================= -->
    <!-- CABEÇALHO -->
    <!-- ================================================= -->

    <table class="header">

        <tr>

            <td width="22%">

                @if(!empty($logotipo))
                <img src="{{ $logotipo }}" class="logo">
                @endif

            </td>

            <td width="78%">

                <div class="lab-name">

                    {{ $LOJAACTIVAOPERADOR->nome }}

                </div>

                <div class="lab-info">

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

    <div class="report-title">
        RECEITA MÉDICA
    </div>

    <!-- ================================================= -->
    <!-- IDENTIFICAÇÃO DO PACIENTE -->
    <!-- ================================================= -->

    <div class="section">

        <div class="section-title">

            IDENTIFICAÇÃO DO PACIENTE

        </div>

        <table class="info">
            <tr>
                <td width="45%">
                    <span class="label">Nome</span><br>
                    {{ $receita->atendimento->paciente->nome ?? 'N/D' }}
                </td>

                <td width="15%">
                    <span class="label">Sexo</span><br>
                    {{ $receita->atendimento->paciente->genero ?? 'N/D' }}
                </td>

                <td width="20%">
                    <span class="label">Nascimento</span><br>
                    {{ $receita->atendimento->paciente->data_nascimento ?? 'N/D' }}
                </td>

                <td width="20%">
                    <span class="label">Estado Civil</span><br>
                    {{ $receita->atendimento->paciente->estado_civil->nome ?? 'N/D' }}
                </td>
            </tr>

            <tr>
                <td>
                    <span class="label">Documento</span><br>
                    {{ $receita->atendimento->paciente->nif ?? 'N/D' }}
                </td>
                <td>
                    <span class="label">Código</span><br>
                    PAC-{{ str_pad($receita->atendimento->paciente->id,6,'0',STR_PAD_LEFT) }}
                </td>
                <td>
                    <span class="label">Telefone</span><br>
                    {{ $receita->atendimento->paciente->telefone ?? 'N/D' }}
                </td>
                <td>
                    <span class="label">Idade</span><br>
                    {{ $receita->atendimento->paciente->idade($receita->atendimento->paciente->data_nascimento) ?? 'N/D' }} Anos
                </td>
            </tr>
        </table>

    </div>

    <!-- ================================================= -->
    <!-- DADOS DA CONSULTA -->
    <!-- A PARTE 2 COMEÇA AQUI -->
    <!-- ================================================= -->
    <div class="section">
        <div class="section-title"> PRÉ-INSCRIÇÕES </div>
        <table class="info">

            <thead>
                <tr>
                    <th>Medicamento</th>
                    <th>Posologia</th>
                    <th>Duraçao dias</th>
                    <th>{{ __('messages.observacao') }}</th>
                </tr>
            </thead>
            @foreach ($receita->items as $key => $item)
            <tr>
                <td width="20%" style="text-align: center">{{ $item->medicamento }}</td>
                <td width="20%" style="text-align: center">{{ $item->posologia }}</td>
                <td width="20%" style="text-align: center">{{ $item->duracao_dias }}</td>
                <td width="40%" style="text-align: center">{{ $item->observacoes }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <!-- ================================================= -->


    <!-- ================================================= -->
    <!-- OBSERVAÇÕES MÉDICAS -->
    <!-- ================================================= -->

    <div class="section">
        <div class="section-title">
            OBSERVAÇÕES MÉDICAS
        </div>
        <table class="info">
            <tr>
                <td style="min-height:80px;">
                    {{ $receita->observacoes ?? 'Sem observações adicionais.' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- ================================================= -->
    <!-- ASSINATURA E CARIMBO -->
    <!-- ================================================= -->

    <table style="width:100%; margin-top:40px;">
        <tr>
            <td width="45%" style="text-align:center;">
                @if(!empty($empresa_logada->empresa->assinatura))
                <img src="{{ public_path($empresa_logada->empresa->assinatura) }}" style="height:70px;">
                @endif
                <hr style="margin-top:10px;">
                <strong>
                    {{ $receita->atendimento->medico->nome ?? $receita->atendimento->utilizador->name ?? 'Médico Responsável' }}
                </strong>
                <br>
                <small>
                    {{ $receita->atendimento->medico->especialidade ?? 'Clínica Geral' }}
                </small>
            </td>

            <td width="10%"></td>

            <td width="45%" style="text-align:center;">
                @if(!empty($empresa_logada->empresa->carimbo))
                <img src="{{ public_path($empresa_logada->empresa->carimbo) }}" style="height:90px;">
                @endif
                <hr style="margin-top:10px;">
                <strong>
                    Carimbo Oficial
                </strong>
            </td>
        </tr>
    </table>

    <div class="section">
        <table class="info">
            <tr>
                <td style="min-height:80px;">
                    Local e Data: ____________________, ___ de _______________ de ______.
                </td>
            </tr>
        </table>
    </div>


    <!-- ================================================= -->
    <!-- RODAPÉ COM PAGINAÇÃO -->
    <!-- ================================================= -->

    <script type="text/php">

        if(isset($pdf)){

    $font = $fontMetrics->get_font("Arial", "normal");

    $pdf->page_text(
        40,
        820,
        "Emitido em: {{ now()->format('d/m/Y H:i') }}",
        $font,
        8,
        array(0,0,0)
    );

    $pdf->page_text(
        250,
        820,
        "{{ $LOJAACTIVAOPERADOR->nome }}",
        $font,
        8,
        array(0,0,0)
    );

    $pdf->page_text(
        500,
        820,
        "Página {PAGE_NUM} de {PAGE_COUNT}",
        $font,
        8,
        array(0,0,0)
    );

}

</script>

</body>
</html>
