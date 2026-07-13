<div class="card-body row">
    <!-- Paciente -->
    <div class="col-12 col-md-6 mb-3">
        <label for="paciente_id" class="form-label">Paciente</label>
        <select disabled class="form-control select2" id="paciente_id" name="paciente_id">
            <option value="">{{ __('messages.escolher') }}</option>
            @foreach ($pacientes as $item)
            <option value="{{ $item->id ?? "" }}" {{ $item->id == ($origem->triagem ? $origem->triagem->paciente_id : null) ? 'selected' : '' }}>{{ $item->nome }}</option>
            @endforeach
        </select>
    </div>

    <!-- Prioridade -->
    <div class="col-12 col-md-3 mb-3">
        <label for="prioridade_id" class="form-label">Prioridade</label>
        <select disabled class="form-control select2" id="prioridade_id" name="prioridade_id">
            <option value="">{{ __('messages.escolher') }}</option>
            @foreach ($prioridades as $item)
            <option value="{{ $item->id ?? "" }}" {{ $item->id == ($origem->triagem ? $origem->triagem->prioridade_id : null) ? 'selected' : '' }}>{{ $item->nome }}</option>
            @endforeach
        </select>
    </div>

    <!-- Estado de Consciência -->
    <div class="col-12 col-md-3 mb-3">
        <label for="estado_consciencia" class="form-label">Estado de Consciência</label>
        <select disabled class="form-control" id="estado_consciencia" name="estado_consciencia">
            <option value="">{{ __('messages.escolher') }}</option>
            <option value="Alerta" {{ "Alerta" == ($origem->triagem ? $origem->triagem->estado_consciencia : null) ? 'selected' : '' }}>Alerta</option>
            <option value="Sonolento" {{ "Sonolento" == ($origem->triagem ? $origem->triagem->estado_consciencia : null) ? 'selected' : '' }}>Sonolento</option>
            <option value="Inconsciente" {{ "Inconsciente" == ($origem->triagem ? $origem->triagem->estado_consciencia : null) ? 'selected' : '' }}>Inconsciente</option>
            <option value="Outros" {{ "Outros" == ($origem->triagem ? $origem->triagem->estado_consciencia : null) ? 'selected' : '' }}>Outros</option>
        </select>
    </div>

    <!-- Queixa Principal -->
    <div class="col-12 mb-3">
        <label for="queixa_principal" class="form-label">Queixa Principal</label>
        <textarea disabled class="form-control" name="queixa_principal" placeholder="Queixa Principal" id="queixa_principal" rows="3">{{ $origem->triagem ? $origem->triagem->queixa_principal : '' }}</textarea>
    </div>

    <!-- Sinais Vitais -->
    <div class="col-12 col-md-4 mb-3">
        <label for="temperatura" class="form-label">Temperatura Corporal (ºC)</label>
        <input disabled type="text" class="form-control" placeholder="Temperatura Corporal (ºC)" id="temperatura" name="temperatura" value="{{ $origem->triagem ? $origem->triagem->temperatura  : '' }}">
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="pressao" class="form-label">Pressão Arterial Sistólica (mmHg)</label>
        <input disabled type="text" class="form-control" placeholder="Pressão Arterial Sistólica (mmHg)" id="pressao" name="pressao" value="{{ $origem->triagem ? $origem->triagem->pressao : '' }}">
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="pressao_diatolica" class="form-label">Pressão Arterial Diastólica</label>
        <input disabled type="text" class="form-control" placeholder="Pressão Arterial Diastólica (mmHg)" id="pressao_diatolica" name="pressao_diatolica" value="{{ $origem->triagem ? $origem->triagem->pressao_diatolica : '' }}">
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="freq_cardiaca" class="form-label">Frequência Cardíaca (bpm)</label>
        <input disabled type="text" class="form-control" placeholder="Frequência Cardíaca (bpm)" id="freq_cardiaca" name="freq_cardiaca" value="{{ $origem->triagem ? $origem->triagem->freq_cardiaca : '' }}">
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="freq_respiratoria" class="form-label">Frequência Respiratória (irpm)</label>
        <input disabled type="text" class="form-control" placeholder="Frequência Respiratória (irpm)" id="freq_respiratoria" name="freq_respiratoria" value="{{ $origem->triagem ? $origem->triagem->freq_respiratoria : '' }}">
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="saturacao_oxigenio" class="form-label">Saturação de Oxigénio (%)</label>
        <input disabled type="text" class="form-control" placeholder="Saturação de Oxigénio (%)" id="saturacao_oxigenio" name="saturacao_oxigenio" value="{{ $origem->triagem ? $origem->triagem->saturacao_oxigenio : '' }}">
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="escala_dor" class="form-label">Dor (Escala 0 - 10)</label>
        <input disabled type="number" class="form-control" id="escala_dor" placeholder="Dor (Escala 0 - 10)" name="escala_dor" min="0" max="10" step="1" value="{{ $origem->triagem ? $origem->triagem->escala_dor : '' }}">
    </div>

    <!-- Antropometria -->
    <div class="col-12 col-md-4 mb-3">
        <label for="peso" class="form-label">Peso (Kg)</label>
        <input disabled type="text" class="form-control" id="peso" placeholder="Peso (Kg)" name="peso" value="{{ $origem->triagem ? $origem->triagem->peso : '' }}">
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="altura" class="form-label">Altura (cm)</label>
        <input disabled type="text" class="form-control" id="altura" placeholder="Altura (cm)" name="altura" value="{{ $origem->triagem ? $origem->triagem->altura : '' }}">
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="circunferencia_abdominal" class="form-label">Circunferência Abdominal (cm)</label>
        <input disabled type="text" class="form-control" id="circunferencia_abdominal" placeholder="Circunferência Abdominal (cm)" name="circunferencia_abdominal" value="{{ $origem->triagem ? $origem->triagem->circunferencia_abdominal : '' }}">
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="glicemia_capilar" class="form-label">Glicemia Capilar (mg/dL)</label>
        <input disabled type="text" class="form-control" id="glicemia_capilar" name="glicemia_capilar" placeholder="Glicemia Capilar (mg/dL)" value="{{ $origem->triagem ? $origem->triagem->glicemia_capilar : ''}}">
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="gravidez" class="form-label">Gravida?</label>
        <select disabled class="form-control" id="gravidez" name="gravidez">
            <option value="Não" {{ "Não" == ($origem->triagem ? $origem->triagem->gravidez : null) ? 'selected' : '' }}>Não</option>
            <option value="Sim" {{ "Sim" == ($origem->triagem ? $origem->triagem->gravidez : null) ? 'selected' : '' }}>Sim</option>
        </select>
    </div>

    <!-- Observações -->
    <div class="col-12 mb-3">
        <label for="observacoes" class="form-label">Observações</label>
        <textarea disabled class="form-control" name="observacoes" id="observacoes" rows="3" placeholder="Observações">{{ $origem->triagem ? $origem->triagem->observacoes : '' }}</textarea>
    </div>

</div>
