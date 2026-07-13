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

        {{ strtoupper($LOJAACTIVAOPERADOR->nome ?? 'TRIAGEM') }}

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
        RELATÓRIO DA TRIAGEM
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

                    {{ $triagem->paciente->nome }}

                </td>

                <td width="15%">

                    <span class="label">Sexo</span><br>

                    {{ $triagem->paciente->genero ?? 'N/D' }}

                </td>

                <td width="20%">

                    <span class="label">Nascimento</span><br>

                    {{ $triagem->paciente->data_nascimento ?? 'N/D' }}

                </td>

                <td width="20%">

                    <span class="label">Estado Civil</span><br>

                    {{ $triagem->paciente->estado_civil->nome ?? 'N/D' }}

                </td>

            </tr>

            <tr>

                <td>

                    <span class="label">Documento</span><br>

                    {{ $triagem->paciente->nif ?? 'N/D' }}

                </td>

                <td>

                    <span class="label">Código</span><br>

                    PAC-{{ str_pad($triagem->paciente->id,6,'0',STR_PAD_LEFT) }}

                </td>

                <td colspan="2">

                    <span class="label">Telefone</span><br>

                    {{ $triagem->paciente->telefone ?? 'N/D' }}

                </td>

            </tr>

        </table>
    </div>


    <!-- ================================================= -->
    <!-- DADOS DA CONSULTA -->
    <!-- A PARTE 2 COMEÇA AQUI -->
    <!-- ================================================= -->
    <div class="section">
        <div class="section-title"> DADOS DA TRIAGEM </div>
        <table class="info">


            <tr>
                <td width="25%"> <span class="label">Nº da Triagem</span><br> CON-{{ str_pad($triagem->id,6,'0',STR_PAD_LEFT) }} </td>
                <td width="25%"> <span class="label">Temperatura Corporal (ºC)</span><br> {{ $triagem->temperatura ?? "-" }} (ºC) </td>
                <td width="25%"> <span class="label">Pressão Arterial Sistólica (mmHg)</span><br> {{ $triagem->pressao ?? "" }}(mmHg) </td>
                <td width="25%"> <span class="label">Estado</span><br> <strong style="color:#2e7d32;"> {{ strtoupper($triagem->status ?? 'CONCLUÍDA') }} </strong> </td>
            </tr>
            <tr>
                <td width="25%"> <span class="label">Pressão Arterial Diastólica (mmHg)</span><br> {{ $triagem->pressao_diatolica ?? "" }}(mmHg)</td>
                <td width="25%"> <span class="label">Peso (Kg)</span><br> {{ $triagem->peso ?? "" }} </td>
                <td width="25%"> <span class="label">Altura (cm)</span><br> {{ $triagem->altura ?? "" }}</td>
                <td width="25%"> <span class="label">Estado de Consciência</span><br> <strong style="color:#2e7d32;">{{ $triagem->estado_consciencia ?? "" }} </strong> </td>
            </tr>
            <tr>
                <td width="25%"> <span class="label">Circunferência Abdominal (cm)</span><br> {{ $triagem->circunferencia_abdominal ?? "" }}</td>
                <td width="25%"> <span class="label">Frequência Respiratória (irpm)</span><br> {{ $triagem->freq_respiratoria ?? '' }} (irpm)</td>
                <td width="25%"> <span class="label">Frequência Cardíaca (bpm)</span><br> {{ $triagem->freq_cardiaca ?? '' }} (bpm)</td>
                <td width="25%"> <span class="label">Saturação de Oxigénio (%)</span><br> {{ $triagem->saturacao_oxigenio ?? '' }} (%)</td>
            </tr>
            <tr>
                <td width="25%"> <span class="label">Dor (Escala 0 - 10)</span><br> {{ $triagem->escala_dor ?? "" }}</td>
                <td width="25%"> <span class="label">Glicemia Capilar (mg/dL)</span><br> {{ $triagem->glicemia_capilar ?? '' }} (mg/dL)</td>
                <td width="25%"> <span class="label">Gravida?</span><br> {{ $triagem->gravidez ?? '' }}</td>
                <td width="25%"> <span class="label">Imc</span><br> {{ $triagem->imc ?? '' }}</td>
            </tr>
            <tr>
                <td width="25%"> <span class="label">Imc classificação</span><br> {{ $triagem->imc_classificacao ?? "" }} </td>
                <td colspan="3"> <span class="label">Queixa principal</span><br> {{ $triagem->queixa_principal ?? "" }} </td>
            </tr>
        </table>
    </div>
    <!-- ================================================= -->



    <!-- ================================================= -->
    <!-- OBSERVAÇÕES MÉDICAS -->
    <!-- ================================================= -->

    <div class="section">
        <div class="section-title">
            OBSERVAÇÕES DA TRIAGEM
        </div>
        <table class="info">
            <tr>
                <td style="min-height:80px;">
                    {{ $triagem->observacoes ?? 'Sem observações adicionais.' }}
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
                    {{ $triagem->medico->nome ?? $triagem->utilizador->name ?? 'Técnico Responsável' }}
                </strong>
                <br>
                <small>
                    {{ $triagem->medico->especialidade ?? 'Enfermagem' }}
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
