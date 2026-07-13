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
        PLANO MÉDICO DE INTERNAMENTO
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
                    {{ $internamento->paciente->nome ?? 'N/D' }}
                </td>

                <td width="15%">
                    <span class="label">Sexo</span><br>
                    {{ $internamento->paciente->genero ?? 'N/D' }}
                </td>

                <td width="20%">
                    <span class="label">Nascimento</span><br>
                    {{ $internamento->paciente->data_nascimento ?? 'N/D' }}
                </td>

                <td width="20%">
                    <span class="label">Estado Civil</span><br>
                    {{ $internamento->paciente->estado_civil->nome ?? 'N/D' }}
                </td>
            </tr>

            <tr>
                <td>
                    <span class="label">Documento</span><br>
                    {{ $internamento->paciente->nif ?? 'N/D' }}
                </td>
                <td>
                    <span class="label">Código</span><br>
                    PAC-{{ str_pad($internamento->paciente->id,6,'0',STR_PAD_LEFT) }}
                </td>
                <td>
                    <span class="label">Telefone</span><br>
                    {{ $internamento->paciente->telefone ?? 'N/D' }}
                </td>
                <td>
                    <span class="label">Idade</span><br>
                    {{ $internamento->paciente->idade($internamento->paciente->data_nascimento) ?? 'N/D' }} Anos
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
                    <th>Dose</th>
                    <th>Via</th>
                    <th>Frequência</th>
                    <th>Duração</th>
                </tr>
            </thead>
            @foreach ($internamento->plano_internamento as $key => $item)
            <tr>
                <td width="20%" style="text-align: center">{{ $item->medicamento }}</td>
                <td width="20%" style="text-align: center">{{ $item->dose }}</td>
                <td width="20%" style="text-align: center">{{ $item->via }}</td>
                <td width="20%" style="text-align: center">{{ $item->frequencia }}</td>
                <td width="20%" style="text-align: center">{{ $item->duracao }}</td>
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
            DIAGNOSTICO INICIAL & MOTIVO INTERNAMENTO
        </div>
        <table class="info">
            <tr>
                <td style="min-height:80px;">
                    {{ $internamento->diagnostico_inicial ?? 'Sem observações adicionais.' }}
                </td>
            </tr>
            <tr>
                <td style="min-height:80px;">
                    {{ $internamento->motivo ?? 'Sem motivo adicionais.' }}
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

    <!-- ================================================= -->
    <!-- RODAPÉ COM PAGINAÇÃO -->
    <!-- ================================================= -->


















    @foreach ($internamento->atendimento->exames as $exame)
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
        RELATÓRIO DE RESULTADOS LABORATORIAIS
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
                <td width="55%">
                    <span class="label">Nome:</span>
                    {{ $exame->paciente->nome }}
                </td>
                <td width="20%">
                    <span class="label">Sexo:</span>
                    {{ $exame->paciente->genero ?? 'N/A' }}
                </td>
                <td width="25%">
                    <span class="label">Nascimento:</span>
                    {{ $exame->paciente->data_nascimento ?? 'N/A' }}
                </td>
            </tr>

            <tr>
                <td>
                    <span class="label">BI / Documento:</span>
                    {{ $exame->paciente->nif ?? 'N/A' }}
                </td>
                <td>
                    <span class="label">Código:</span>
                    {{ $exame->paciente->id }}
                </td>
                <td>
                    <span class="label">Idade:</span>
                    {{ $exame->paciente->idade($exame->paciente->data_nascimento) }}
                </td>
            </tr>
        </table>
    </div>
    <!-- ================================================= -->
    <!-- DADOS DO EXAME -->
    <!-- A segunda parte começa aqui -->
    <!-- ================================================= -->
    <div class="section">
        <div class="section-title">
            DADOS DO EXAME
        </div>
        <table class="info">

            <tr>

                <td width="25%">
                    <span class="label">Nº do Exame</span><br>
                    EX-{{ str_pad($exame->id,6,'0',STR_PAD_LEFT) }}
                </td>

                <td width="25%">
                    <span class="label">Data do Registo</span><br>
                    {{ date('d/m/Y', strtotime($exame->created_at)) }}
                </td>

                <td width="25%">
                    <span class="label">Estado</span><br>

                    @if($exame->estado=="Concluído")
                    <span style="color:#2e7d32;font-weight:bold">
                        CONCLUÍDO
                    </span>
                    @else
                    <span style="color:#ef6c00;font-weight:bold">
                        {{ strtoupper($exame->estado) }}
                    </span>
                    @endif

                </td>

                <td width="25%">
                    <span class="label">Responsável</span><br>
                    {{ auth()->user()->name ?? 'Laboratório' }}
                </td>

            </tr>

        </table>
    </div>
    <!-- ================================================= -->
    <!-- EXAMES SOLICITADOS -->
    <!-- ================================================= -->
    <div class="section">
        <div class="section-title">
            EXAMES SOLICITADOS
        </div>
        <table>
            <thead>
                <tr style="background:#0B6FA4;color:white;">
                    <th width="7%">
                        #
                    </th>
                    <th width="38%">
                        Exame
                    </th>
                    <th width="20%">
                        Categoria
                    </th>
                    <th width="15%">
                        Quantidade
                    </th>
                    <th width="20%">
                        Valor
                    </th>
                </tr>
            </thead>

            <tbody>
                @php
                $total = 0;
                @endphp
                @foreach($exame->items as $item)
                @php
                $total += $item->valor;
                @endphp
                <tr>
                    <td style="border:1px solid #DDD;text-align:center">
                        {{ $loop->iteration }}
                    </td>
                    <td style="border:1px solid #DDD">
                        <strong>
                            {{ $item->produto->nome ?? "" }}
                        </strong>
                    </td>
                    <td style="border:1px solid #DDD">
                        {{ $item->produto ? $item->produto->categoria->categoria : "" }}
                    </td>
                    <td style="border:1px solid #DDD;text-align:center">
                        1
                    </td>
                    <td style="border:1px solid #DDD;text-align:right">
                        {{ number_format($item->valor,2,",",".") }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="border:1px solid #DDD;text-align:right;font-weight:bold;background:#F5F5F5;">
                        TOTAL
                    </td>
                    <td style="border:1px solid #DDD;text-align:right;font-weight:bold;background:#F5F5F5;color:#0B6FA4;">
                        {{ number_format($total,2,",",".") }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- ================================================= -->
    <!-- RESULTADOS DOS EXAMES -->
    <!-- ================================================= -->

    @foreach($exame->items as $item)
    <div class="section" style="margin-top:25px;">
        <div class="section-title">
            {{ strtoupper($item->produto->nome ?? "") }}
        </div>
        <table class="info" style="margin-bottom:15px;">
            <tr>
                <td width="35%">
                    <strong>Categoria</strong><br>
                    {{ $item->produto ? $item->produto->categoria->categoria : "" }}
                </td>
                <td width="20%">
                    <strong>Código</strong><br>
                    {{ $item->produto->codigo ?? '-' }}
                </td>
                <td width="20%">
                    <strong>Valor</strong><br>
                    {{ number_format($item->valor,2,",",".") }}
                </td>
                <td width="25%">
                    <strong>Estado</strong><br>
                    <span style="color:#2e7d32;font-weight:bold;">
                        FINALIZADO
                    </span>
                </td>
            </tr>
        </table>
        @foreach($item->resultado_parametro_exame as $parametro)
        <table style="margin-top:10px;">
            <tr>
                <td colspan="5" style="background:#0B6FA4;color:white;font-weight:bold;padding:8px;">{{ strtoupper($parametro->nome) }}</td>
            </tr>

            <tr style="background:#F3F7FA;">
                <th style="border:1px solid #DDD;">Código</th>
                <th style="border:1px solid #DDD;">Parâmetro</th>
                <th style="border:1px solid #DDD;">Resultado</th>
                <th style="border:1px solid #DDD;">Unidade</th>
                <th style="border:1px solid #DDD;">Valor de Referência</th>
            </tr>

            @foreach($parametro->resultadosubparamentros as $resultado)

            @if( $resultado->subparametroexame->tipo=="lista" || $resultado->subparametroexame->tipo=="numero" || $resultado->subparametroexame->tipo=="texto" || $resultado->subparametroexame->tipo=="textarea" || $resultado->subparametroexame->tipo=="booleano" || $resultado->subparametroexame->tipo=="data")
            <tr>
                <td style="border:1px solid #DDD;">
                    {{ $resultado->subparametroexame->codigo ?? "-" }}
                </td>
                <td style="border:1px solid #DDD;">
                    {{ $resultado->subparametroexame->nome }}
                </td>
                <td style="border:1px solid #DDD;">
                    <strong style="font-size:12px;color:#0B6FA4;">
                        {{ $resultado->valor }}
                    </strong>
                </td>
                <td style="border:1px solid #DDD;">
                    @if($resultado->subparametroexame->tipo=="lista")
                    {{ $resultado->subparametroexame->opcoes }}
                    @elseif($resultado->subparametroexame->tipo=="booleano")
                    {{ $resultado->subparametroexame->texto_sim }}/{{ $resultado->subparametroexame->texto_nao }}
                    @else
                    {{ $resultado->subparametroexame->unidade }}
                    @endif
                </td>
                <td style="border:1px solid #DDD;">
                    {{ $resultado->subparametroexame->valor_referencia }}
                </td>
            </tr>
            @endif
            @endforeach
        </table>
        <br>
        @endforeach
    </div>
    @endforeach

    <!-- ================================================= -->
    <!-- IMAGENS DOS EXAMES -->
    <!-- ================================================= -->

    @foreach($exame->items as $item)
    @foreach($item->resultado_parametro_exame as $parametro)
    @php
    $possuiImagem = false;
    @endphp
    @foreach($parametro->resultadosubparamentrosImagem as $imagem)
    @if($imagem->subparametroexame->tipo=="imagem")
    @php
    $possuiImagem = true;
    @endphp
    @endif
    @endforeach
    @if($possuiImagem)
    <div class="section">
        <div class="section-title">
            IMAGENS DO EXAME
        </div>
        <table class="info">
            <tr>
                <td>
                    <strong>Exame</strong><br>
                    {{ $item->produto->nome ?? "" }}
                </td>
                <td>
                    <strong>Categoria</strong><br>
                    {{ $item->produto ? $item->produto->categoria->categoria : "" }}
                </td>
            </tr>
        </table>
        <br>
        <table width="100%">
            @php
            $contador = 0;
            @endphp
            <tr>
                @foreach($parametro->resultadosubparamentrosImagem as $imagem)
                @if($imagem->subparametroexame->tipo=="imagem")
                @php
                $ficheiros = json_decode($imagem->ficheiros,true) ?? [];
                @endphp
                @foreach($ficheiros as $ficheiro)

                <td width="50%" style="padding:10px;">
                    <div style="border:1px solid #DDD;padding:8px;">
                        <div style="text-align:center;font-weight:bold;margin-bottom:8px;color:#0B6FA4;">
                            {{ $imagem->subparametroexame->nome }}
                        </div>
                        <div style="text-align:center;">
                            <img src="{{ asset('/public/resultados/exames/'.$ficheiro) }}" title="" style="width:240px;height:220px;object-fit:contain;border:1px solid #CCC;">
                        </div>
                        @if(!empty($imagem->descricao))
                        <div style="margin-top:8px;font-size:10px;color:#555;text-align:center;">
                            {{ $imagem->descricao }}
                        </div>
                        @endif
                    </div>
                </td>

                @php
                $contador++;
                @endphp

                @if($contador % 2 == 0)

            </tr>
            <tr>
                @endif
                @endforeach
                @endif
                @endforeach
            </tr>
        </table>
    </div>

    @endif
    @endforeach
    @endforeach
    <!-- ================================================= -->
    <!-- OBSERVAÇÕES -->
    <!-- ================================================= -->
    <div class="section">
        <div class="section-title">
            OBSERVAÇÕES DO LABORATÓRIO
        </div>
        <table class="info">
            <tr>
                <td style="height:90px;">
                    {{ $exame->observacoes ?? 'Sem observações adicionais.' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- ================================================= -->
    <!-- CONCLUSÃO -->
    <!-- ================================================= -->

    <div class="section">
        <div class="section-title">
            CONCLUSÃO
        </div>
        <table class="info">
            <tr>
                <td style="height:80px; line-height:22px;">
                    {!! $exame->conclusao ?? 'Os resultados apresentados neste relatório devem ser interpretados pelo médico assistente em conjunto com os dados clínicos do paciente.' !!}
                </td>
            </tr>
        </table>
    </div>

    <br><br>

    <!-- ================================================= -->
    <!-- ASSINATURAS -->
    <!-- ================================================= -->

    <table width="100%" style="margin-top:50px;">
        <tr>
            <td width="45%" style="text-align:center;">
                @if(!empty($empresa_logada->empresa->assinatura))
                <img src="{{ public_path($empresa_logada->empresa->assinatura) }}" style="height:70px;">
                @endif
                <hr>
                <strong>
                    {{ $empresa_logada->empresa->responsavel_tecnico ?? 'Responsável Técnico' }}
                </strong>
                <br>
                <small>
                    {{ $empresa_logada->empresa->especialidade ?? 'Patologia Clínica / Laboratório' }}
                </small>
            </td>

            <td width="10%"></td>
            <td width="45%" style="text-align:center;">
                @if(!empty($empresa_logada->empresa->carimbo))
                <img src="{{ public_path($empresa_logada->empresa->carimbo) }}" style="height:90px;">
                @endif
                <hr>
                <strong>
                    Carimbo Oficial
                </strong>
            </td>
        </tr>
    </table>
    <br><br>
    <!-- ================================================= -->
    <!-- QR CODE -->
    <!-- ================================================= -->

    <table class="info">
        <tr>
            <td width="25%" style="text-align:center;">
                @if(!empty($qrCode))
                <img src="{{ $qrCode }}" style="width:120px;">
                @endif
            </td>

            <td width="75%">
                <strong>Código de Verificação</strong>
                <br><br>
                {{ $codigoVerificacao ?? strtoupper(md5($exame->id.$exame->created_at)) }}
                <br><br>
                Este relatório pode ser validado através do código acima ou por QR Code.
                <br><br>
                <strong>Data de Emissão:</strong>
                {{ now()->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>
    <br>
    <!-- ================================================= -->
    <!-- NOTA LEGAL -->
    <!-- ================================================= -->
    <table class="info">
        <tr>
            <td style="font-size:10px; line-height:18px; color:#666;">
                Este relatório contém informações confidenciais e destina-se exclusivamente ao paciente ou ao médico solicitante.
                Os resultados laboratoriais devem sempre ser interpretados em conjunto com o histórico clínico, exame físico e demais exames complementares.
                A reprodução parcial deste documento sem autorização do laboratório não é recomendada.
            </td>
        </tr>
    </table>

    <!-- ================================================= -->
    <!-- RODAPÉ -->
    <!-- ================================================= -->

    @endforeach









    @foreach ($internamento->atendimento->consultas as $consulta)

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
        RELATÓRIO DE CONSULTA MÉDICA
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
    </div>
    <!-- ================================================= -->

    <!-- ================================================= -->
    <!-- PROCEDIMENTOS / SERVIÇOS -->
    <!-- ================================================= -->

    <div class="section">
        <div class="section-title">
            SERVIÇOS REALIZADOS
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
                    <td colspan="4" style="border:1px solid #DDD;text-align:center;color:#777;">
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
            MOTIVO AGENDAMENTO
        </div>
        <table class="info">
            <tr>
                <td style="min-height:70px;">
                    {{ $consulta->movito_agendamento ?? 'A consulta foi concluída com base na avaliação clínica realizada. O paciente deve seguir as orientações médicas e retornar caso necessário.' }}
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
    @endforeach




















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
