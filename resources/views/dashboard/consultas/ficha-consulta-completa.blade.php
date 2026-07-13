```blade
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
        {{ $titulo }}
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

                    {{ $consulta->paciente->nome }}

                </td>

                <td width="15%">

                    <span class="label">Sexo</span><br>

                    {{ $consulta->paciente->genero ?? 'N/D' }}

                </td>

                <td width="20%">

                    <span class="label">Nascimento</span><br>

                    {{ $consulta->paciente->data_nascimento ?? 'N/D' }}

                </td>

                <td width="20%">

                    <span class="label">Estado Civil</span><br>

                    {{ $consulta->paciente->estado_civil->nome ?? 'N/D' }}

                </td>

            </tr>

            <tr>

                <td>

                    <span class="label">Documento</span><br>

                    {{ $consulta->paciente->nif ?? 'N/D' }}

                </td>

                <td>

                    <span class="label">Código</span><br>

                    PAC-{{ str_pad($consulta->paciente->id,6,'0',STR_PAD_LEFT) }}

                </td>

                <td colspan="2">

                    <span class="label">Telefone</span><br>

                    {{ $consulta->paciente->telefone ?? 'N/D' }}

                </td>

            </tr>

        </table>
    </div>

    <!-- ================================================= -->
    <!-- DADOS DA CONSULTA -->
    <!-- A PARTE 2 COMEÇA AQUI -->
    <!-- ================================================= -->
    <div class="section">
        <div class="section-title"> DADOS DA CONSULTA </div>
        <table class="info">
            <tr>
                <td width="25%"> <span class="label">Nº da Consulta</span><br> CON-{{ str_pad($consulta->id,6,'0',STR_PAD_LEFT) }} </td>
                <td width="25%"> <span class="label">Data</span><br> {{ $consulta->data_consulta ?? '-' }} </td>
                <td width="25%"> <span class="label">Hora</span><br> {{ $consulta->hora_consulta ?? '-' }} </td>
                <td width="25%"> <span class="label">Estado</span><br> <strong style="color:#2e7d32;"> {{ strtoupper($consulta->estado ?? 'CONCLUÍDA') }} </strong> </td>
            </tr>
            <tr>
                <td colspan="2"> <span class="label">Médico Responsável</span><br> {{ $consulta->medico->nome ?? $consulta->utilizador->name ?? 'Não informado' }} </td>
                <td colspan="2"> <span class="label">CID</span><br> {{ $consulta->cids->nome ?? '-' }} </td>
            </tr>
        </table>
    </div> <!-- ================================================= -->
    <!-- AVALIAÇÃO CLÍNICA -->
    <!-- ================================================= -->
    <div class="section">
        <div class="section-title"> AVALIAÇÃO CLÍNICA </div>
        <table class="info">
            <tr>
                <td> <span class="label">Queixa Principal</span> <br><br> {{ $consulta->queixa_principal ?? 'Não informado.' }} </td>
            </tr>
            <tr>
                <td> <span class="label">O que foi Avaliado</span> <br><br> {{ $consulta->avaliado ?? 'Não informado.' }} </td>
            </tr>
            <tr>
                <td> <span class="label">Diagnóstico</span> <br><br> {{ $consulta->diagnosticado ?? 'Não informado.' }} </td>
            </tr>
        </table>
    </div> <!-- ================================================= -->
    <!-- HISTÓRICO CLÍNICO -->
    <!-- ================================================= -->
    <div class="section">
        <div class="section-title"> HISTÓRICO CLÍNICO </div>
        <table class="info">
            <tr>
                <td> <span class="label">História da Doença Atual</span> <br><br> {{ $consulta->historia_doenca_actual ?? 'Não informado.' }} </td>
            </tr>
            <tr>
                <td> <span class="label">Histórico Médico</span> <br><br> {{ $consulta->historico_medico ?? 'Não informado.' }} </td>
            </tr>
        </table>
    </div>
    <!-- ================================================= -->
    <!-- EXAME FÍSICO -->
    <!-- ================================================= -->
    <div class="section">

        <div class="section-title">

            EXAME FÍSICO

        </div>

        <table class="info">

            <tr>

                <td>

                    {{ $consulta->exame_medico ?? 'Nenhuma informação registada.' }}

                </td>

            </tr>

        </table>

    </div>
    <!-- ================================================= -->
    <!-- ALERGIAS -->
    <!-- ================================================= -->
    <div class="section">

        <div class="section-title">

            ALERGIAS CONHECIDAS

        </div>

        <table class="info">

            <tr>

                <td>

                    {{ $consulta->alergias_conhecidas ?? 'Nenhuma alergia registada.' }}

                </td>

            </tr>

        </table>

    </div>
    <!-- ================================================= -->
    <!-- ANOTAÇÕES -->
    <!-- ================================================= -->
    <div class="section">

        <div class="section-title">

            ANOTAÇÕES GERAIS

        </div>

        <table class="info">

            <tr>

                <td>

                    {{ $consulta->anotacoes_gerais ?? 'Sem anotações.' }}

                </td>

            </tr>

        </table>

    </div>
    <!-- ================================================= -->
    <!-- PROCEDIMENTOS / SERVIÇOS -->
    <!-- ================================================= -->
    <div class="section">

        <div class="section-title">

            PROCEDIMENTOS / SERVIÇOS REALIZADOS

        </div>

        <table>

            <thead>

                <tr>

                    <th width="8%">
                        Nº
                    </th>

                    <th width="42%">
                        Serviço
                    </th>

                    <th width="25%">
                        Categoria
                    </th>

                    <th width="25%">
                        Observação
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($consulta->items as $item)

                <tr>

                    <td style="border:1px solid #DDD;text-align:center;">

                        {{ $loop->iteration }}

                    </td>

                    <td style="border:1px solid #DDD;">

                        {{ $item->produto->nome ?? 'Serviço não informado' }}

                    </td>

                    <td style="border:1px solid #DDD;">

                        {{ $item->produto->categoria->categoria ?? '-' }}

                    </td>

                    <td style="border:1px solid #DDD;">

                        {{ $item->observacao ?? '-' }}

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4" style="border:1px solid #DDD;
                           text-align:center;
                           color:#777;">

                        Nenhum procedimento registado.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>
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

                    {{ $consulta->observacao ?? 'Sem observações adicionais.' }}

                </td>

            </tr>

        </table>

    </div>
    <!-- ================================================= -->
    <!-- CONCLUSÃO -->
    <!-- ================================================= -->
    <div class="section">
        <div class="section-title">
            CONCLUSÃO MÉDICA
        </div>
        <table class="info">
            <tr>
                <td style="min-height:70px;">
                    {{ $consulta->conclusao ?? 'A consulta foi concluída com base na avaliação clínica realizada. O paciente deve seguir as orientações médicas e retornar caso necessário.' }}
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
                    {{ $consulta->medico->nome ?? $consulta->utilizador->name ?? 'Médico Responsável' }}
                </strong>
                <br>
                <small>
                    {{ $consulta->medico->especialidade ?? 'Clínica Geral' }}
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
    <!-- QR CODE E VERIFICAÇÃO -->
    <!-- ================================================= -->
    <table class="info" style="margin-top:30px;">
        <tr>
            <td width="25%" style="text-align:center;">
                @if(!empty($qrCode))
                <img src="{{ $qrCode }}" style="width:120px;">
                @endif

            </td>
            <td width="75%">
                <strong>Código de Verificação</strong>
                <br><br>
                {{ $codigoVerificacao ?? strtoupper(md5($consulta->id.$consulta->created_at)) }}
                <br><br>
                Este documento pode ser validado através do código acima ou do QR Code.
                <br><br>
                <strong>Data de Emissão:</strong>
                {{ now()->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>
    <!-- ================================================= -->
    <!-- NOTA LEGAL -->
    <!-- ================================================= -->
    <table class="info" style="margin-top:20px;">
        <tr>
            <td style="font-size:10px; color:#666; line-height:18px;">
                Este documento é confidencial e destinado exclusivamente ao paciente e/ou médico assistente.
                Os dados clínicos devem ser interpretados em conjunto com a avaliação médica completa.
                A reprodução não autorizada deste relatório não é permitida.
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
