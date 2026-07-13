<div class="row">
    <div class="col-12 col-md-6 table-responsive">
        <table class=" table text-nowrap">
            <tbody>
                <tr>
                    <th>{{ __('messages.estados') }}</th>
                    <td class="text-right">{{ $triagem->status ?? "" }}</td>
                </tr>
                <tr>
                    <th>Temperatura Corporal (ºC)</th>
                    <td class="text-right">{{ $triagem->temperatura ?? "" }} (ºC)</td>
                </tr>
                <tr>
                    <th>Pressão Arterial Sistólica (mmHg)</th>
                    <td class="text-right">{{ $triagem->pressao ?? "" }}(mmHg)</td>
                </tr>
                <tr>
                    <th>Pressão Arterial Diastólica (mmHg)</th>
                    <td class="text-right">{{ $triagem->pressao_diatolica ?? "" }}(mmHg)</td>
                </tr>
                <tr>
                    <th>Peso (Kg)</th>
                    <td class="text-right">{{ $triagem->peso ?? "" }}</td>
                </tr>
                <tr>
                    <th>Altura (cm)</th>
                    <td class="text-right">{{ $triagem->altura ?? "" }}</td>
                </tr>
                <tr>
                    <th>Estado de Consciência</th>
                    <td class="text-right">{{ $triagem->estado_consciencia ?? "" }}</td>
                </tr>
                <tr>
                    <th>Circunferência Abdominal (cm)</th>
                    <td class="text-right">{{ $triagem->circunferencia_abdominal ?? "" }}</td>
                </tr>
                <tr>
                    <th>Queixa principal</th>
                    <td class="text-right">{{ $triagem->queixa_principal ?? "" }}</td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="col-12 col-md-6 table-responsive">
        <table class=" table text-nowrap">
            <tbody>
                <tr>
                    <th>Frequência Respiratória (irpm)</th>
                    <td class="text-right">{{ $triagem->freq_respiratoria ?? '' }} (irpm)</td>
                </tr>
                <tr>
                    <th>Frequência Cardíaca (bpm)</th>
                    <td class="text-right">{{ $triagem->freq_cardiaca ?? '' }} (bpm)</td>
                </tr>
                <tr>
                    <th>Saturação de Oxigénio (%)</th>
                    <td class="text-right">{{ $triagem->saturacao_oxigenio ?? '' }} (%)</td>
                </tr>
                <tr>
                    <th>Dor (Escala 0 - 10)</th>
                    <td class="text-right">{{ $triagem->escala_dor ?? '' }}</td>
                </tr>
                <tr>
                    <th>Glicemia Capilar (mg/dL)</th>
                    <td class="text-right">{{ $triagem->glicemia_capilar ?? '' }}</td>
                </tr>
                <tr>
                    <th>Gravida?</th>
                    <td class="text-right">{{ $triagem->gravidez ?? '' }}</td>
                </tr>
                <tr>
                    <th>Imc</th>
                    <td class="text-right">{{ $triagem->imc ?? '' }}</td>
                </tr>
                <tr>
                    <th>Imc classificação</th>
                    <td class="text-right">{{ $triagem->imc_classificacao ?? '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.observacao') }}</th>
                    <td class="text-right">{{ $triagem->observacoes ?? '' }}</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
