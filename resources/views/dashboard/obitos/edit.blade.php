@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-edit"></i> {{ __('messages.editar') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('obitos.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('obitos.update', $obito->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header">
                                <h4>Obitos</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-12 col-md-6">
                                    <label for="paciente_id" class="form-label">Pacientes</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" style="width: 100%" id="paciente_id" name="paciente_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($pacientes as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $obito->paciente_id == $item->id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="medico_id" class="form-label">Médicos</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" style="width: 100%" id="medico_id" name="medico_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($medicos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $obito->medico_id == $item->id ? 'selected' : '' }}> {{ $item->funcionario->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="data_obito" class="form-label">Data do Obito</label>
                                    <input type="date" class="form-control" id="data_obito" name="data_obito" value="{{ $obito->data_obito ?? date('Y-m-d') }}">
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="hora_obito" class="form-label">Hora do Obito</label>
                                    <input type="time" class="form-control" id="hora_obito" name="hora_obito" value="{{ $obito->hora_obito ?? date('H:i') }}">
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="local_obito" class="form-label">Local do Obito</label>
                                    <input type="text" class="form-control" id="local_obito" name="local_obito" value="{{ $obito->local_obito }}" placeholder="Local do obito">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="tipo_obito" class="form-label">Tipo do Obito</label>
                                    <select name="tipo_obito" id="tipo_obito" class="form-control">
                                        <option value="natural" {{ $obito->tipo_obito == "natural" ? "selected" : "" }}>Natural</option>
                                        <option value="acidental" {{ $obito->tipo_obito == "acidental" ? "selected" : "" }}>Acidental</option>
                                        <option value="violento" {{ $obito->tipo_obito == "violento" ? "selected" : "" }}>Violento</option>
                                        <option value="suspeito" {{ $obito->tipo_obito == "suspeito" ? "selected" : "" }}>Suspeito</option>
                                        <option value="indefinido" {{ $obito->tipo_obito == "indefinido" ? "selected" : "" }}>Indefinido</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="comunicacao_obito" class="form-label">Comunicado aos familiares</label>
                                    <select name="comunicacao_obito" id="comunicacao_obito" class="form-control">
                                        <option value="0" {{ $obito->local_obito == "0" ? "selected" : "" }}> {{ __('messages.nao') }} </option>
                                        <option value="1" {{ $obito->local_obito == "1" ? "selected" : "" }}> {{ __('messages.sim') }} </option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-12 mb-3">
                                    <label for="resumo" class="form-label">Resumo do Obito (Causa do Obito)</label>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="resumo" id="resumo" cols="30" rows="5" placeholder="Descrição: ">{{ $obito->causa_obito }}</textarea>
                                    </div>
                                </div>


                            </div>
                            <div class="card-footer">
                                {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                {{-- @endif --}}
                            </div>
                        </div>
                    </form>
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
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    showMessage('Sucesso!', response.message, 'success');
                    window.location.reload();

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
