@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Começar a Consulta</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('consultorio.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Consultório</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-6">
                    <form action="{{ route('consultorio.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    @if (Auth::user()->can('adicionar item conta hospitalar'))
                                    <a href="{{ route('atendimentos.show', $origem->id) }}" class="btn btn-light-primary">Actualizar conta Hospitalar do paciente</a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label for="queixa_principal" class="form-label">Queixa principal</label>
                                        <div class="input-group mb-3">
                                            <textarea name="queixa_principal" class="form-control" id="queixa_principal" cols="30" rows="2" placeholder="Início da consulta">{{ $origem->triagem ? $origem->triagem->queixa_principal : '' }}</textarea>
                                        </div>
                                    </div>

                                    <input type="hidden" name="cliente_id" value="{{ $origem->cliente_id }}">

                                    <div class="col-12 col-md-6">
                                        <label for="historia_doenca_actual" class="form-label">História da doença atual</label>
                                        <div class="input-group mb-3">
                                            <textarea name="historia_doenca_actual" class="form-control" id="historia_doenca_actual" cols="30" rows="2" placeholder="Após ouvir a queixa">{{ $origem->consultas[0]->historia_doenca_actual ?? "" }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="historico_medico" class="form-label">Histórico médico</label>
                                        <div class="input-group mb-3">
                                            <textarea name="historico_medico" class="form-control" id="historico_medico" cols="30" rows="2" placeholder="Após entender os sintomas">{{ $origem->consultas[0]->historico_medico ?? "" }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="exame_medico" class="form-label">Exame físico</label>
                                        <div class="input-group mb-3">
                                            <textarea name="exame_medico" class="form-control" id="exame_medico" cols="30" rows="2" placeholder="Durante a consulta">{{ $origem->consultas[0]->exame_medico ?? "" }}</textarea>
                                        </div>
                                    </div>

                                    <input type="hidden" name="request_ordem" value="{{ $request_ordem ?? '' }}">
                                    <input type="hidden" name="origem_id" value="{{ $origem ? $origem->id : '' }}">

                                    <div class="col-12 col-md-6">
                                        <label for="alergias_conhecidas" class="form-label">Alergias conhecidas</label>
                                        <div class="input-group mb-3">
                                            <textarea name="alergias_conhecidas" class="form-control" id="alergias_conhecidas" cols="30" rows="2" placeholder="Em qualquer ponto">{{ $origem->consultas[0]->alergias_conhecidas ?? "" }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="anotacoes_gerais" class="form-label">Anotações gerais</label>
                                        <div class="input-group mb-3">
                                            <textarea name="anotacoes_gerais" class="form-control" id="anotacoes_gerais" cols="30" rows="2" placeholder="Durante ou ao final">{{ $origem->consultas[0]->anotacoes_gerais ?? "" }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="avaliado" class="form-label">O que foi avaliado</label>
                                        <div class="input-group mb-3">
                                            <textarea name="avaliado" class="form-control" id="avaliado" cols="30" rows="2" placeholder="O que foi avaliado no final da consulta">{{ $origem->consultas[0]->avaliado ?? "" }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="diagnosticado" class="form-label">O que foi diagnosticado</label>
                                        <div class="input-group mb-3">
                                            <textarea name="diagnosticado" class="form-control" id="diagnosticado" cols="30" rows="2" placeholder="O que foi diagnosticado no final da consulta">{{ $origem->consultas[0]->diagnosticado ?? "" }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="cids_id" class="form-label">CIDS</label>
                                        <div class="input-group mb-3">
                                            <select type="text" class="form-control select2" name="cids_id" id="cids_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($cids as $item)
                                                <option value="{{ $item->id ?? "" }}" {{ $item->id == ($origem->consultas[0]->cids_id ?? "") }}>{{ $item->sigla }} - {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="tipo_atendimento_id" class="form-label">Tipo Atendimento (Destino)</label>
                                        <div class="input-group mb-3">
                                            <select type="text" class="form-control select2" name="tipo_atendimento_id" id="tipo_atendimento_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($tipos_atendimentos as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-12 bg-light-primary my-3">
                                        <h3 class="h4 p-2">Campos adicionais</h3>
                                    </div>
                                    @if ($origem->consultas && count($origem->consultas) != 0)
                                    @foreach ($origem->consultas[0]->items as $item)
                                    @foreach ($item->paramentos_consultas as $param_consulta)
                                    @if ($param_consulta->paramentro->tipo !== "imagem")
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ $param_consulta->paramentro->nome ?? '' }}</label>
                                        <div class="input-group mb-3">
                                            @if ($param_consulta->paramentro->tipo === "textarea" || $param_consulta->paramentro->tipo === "texto")
                                            <textarea name="campos[{{ $param_consulta->id }}]" class="form-control" rows="1" placeholder="{{ $param_consulta->paramentro->nome ?? '' }}">{{ $param_consulta->valor ?? '' }}</textarea>
                                            @endif

                                            @if ($param_consulta->paramentro->tipo == "lista")
                                            <select name="campos[{{ $param_consulta->id }}]" class="form-control">
                                                @foreach (json_decode($param_consulta->paramentro->opcoes, true) as $key => $op)
                                                <option value="{{ $key }}" {{ $param_consulta->valor == $key ? 'selected' : '' }}>{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            @endif

                                            @if ($param_consulta->paramentro->tipo === "booleano")
                                            <select name="campos[{{ $param_consulta->id }}]" class="form-control">
                                                <option value="{{ $param_consulta->paramentro->texto_sim }}" {{ $param_consulta->valor == $param_consulta->paramentro->texto_sim ? 'selected' : '' }}>Sim</option>
                                                <option value="{{ $param_consulta->paramentro->texto_nao }}" {{ $param_consulta->valor == $param_consulta->paramentro->texto_nao ? 'selected' : '' }}>Não</option>
                                            </select>
                                            @endif

                                            @if ($param_consulta->paramentro->tipo === "numero")
                                            <input type="text" name="campos[{{ $param_consulta->id }}]" value="{{ $param_consulta->valor ?? '' }}" class="form-control" placeholder="{{ $param_consulta->paramentro->nome ?? '' }}">
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach

                                    @foreach ($item->paramentos_consultas_imagem as $param_consulta_imagem)
                                    <div class="col-12 col-md-12">
                                        <label for="diagnosticado" class="form-label">{{ $param_consulta_imagem->paramentro->nome ?? '' }}</label>
                                        <div class="input-group mb-3">
                                            <input type="file" multiple name="imagens[{{ $param_consulta_imagem->id }}][]" class="form-control">
                                        </div>
                                        @php
                                        $ficheiros = json_decode($param_consulta_imagem->ficheiro, true) ?? [];
                                        @endphp
                                        @if(count($ficheiros))
                                        <div class="row">
                                            @foreach($ficheiros as $ficheiro)
                                            <div class="col-md-2 mb-3">
                                                <a href="{{ asset($ficheiro) }}" target="_blank">
                                                    <img src="{{ asset($ficheiro) }}" class="img-fluid rounded border" style="height:120px;width:100%;object-fit:cover;">
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                    @endforeach

                                    @endif
                                </div>

                            </div>
                            <div class="card-footer d-flex">
                                @if (Auth::user()->can('consultorio'))
                                <button type="submit" class="btn btn-light-success">{{ __('messages.salvar') }}</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dados da Triagem</h5>
                        </div>

                        @include('dashboard.atendimentos._views.card-triagem-visualizacao')

                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection


@section('scripts')
<script>
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            // let formData = form.serialize(); // Serializa os dados do formulário
            let formData = new FormData(this);

            const examesRoute = `{!! route('solicitacoes-medicas.create', [ 'origem' => 'atendimento', 'atendimento_id' => 'ATENDIMENTO_ID', 'paciente_id' => 'PACIENTE_ID', ]) !!}`;
            const internamentoRoute = `{!! route('internamentos.create', ['atendimento_id' => 'ATENDIMENTO_ID']) !!}`;
            const casaRoute = `{!! route('consulta-receita-medica', 'ATENDIMENTO_ID') !!}`;
            const casaConsultorio = `{!! route('consultas.show', 'CONSULTA_ID') !!}`;

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                processData: false
                , contentType: false
                , headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');

                    if (response.destino === "Exames") {
                        const url = examesRoute.replace('ATENDIMENTO_ID', response.consulta.atendimento_id).replace('PACIENTE_ID', response.consulta.paciente_id);
                        window.location.href = url;
                    } else

                    if (response.destino === "Internamento") {
                        const url = internamentoRoute.replace('ATENDIMENTO_ID', response.consulta.atendimento_id);
                        window.location.href = url;
                    } else
                    if (response.destino === "Casa") {
                        const url = casaRoute.replace('ATENDIMENTO_ID', response.consulta.atendimento_id);
                        window.location.href = url;
                    } else
                    if (response.destino === "Consulta") {
                        const url = casaConsultorio.replace('CONSULTA_ID', response.consulta.id);
                        window.location.href = url;
                    } else {
                        window.location.reload();
                    }
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n *`; // Exibe os erros
                        });

                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });
        });
    });

</script>
@endsection
