@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-edit"></i> {{ __('messages.editar') }}: <a href="{{ route('clientes.show', $triagem->paciente->id) }}">{{ $triagem->paciente->nome }}</a></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('triagens.index') }}">{{ __('messages.voltar') }}</a></li>
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
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header"></div>
                        <form action="{{ route('triagens.update', $triagem->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="peso" class="form-label">Pressão:</label>
                                    <input type="text" class="form-control" id="pressao" name="pressao" value="{{ $triagem->pressao ?? old('pressao') }}" placeholder="Informe a Pressão">
                                    <p class="text-light-danger"> @error('pressao') {{ $message }} @enderror </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="peso" class="form-label">Peso:</label>
                                    <input type="text" class="form-control" id="peso" name="peso" value="{{ $triagem->peso ?? old('peso') }}" placeholder="Informe o Peso">
                                    <p class="text-light-danger"> @error('peso') {{ $message }} @enderror </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="altura" class="form-label">Altura:</label>
                                    <input type="text" class="form-control" id="altura" name="altura" value="{{ $triagem->altura ?? old('altura') }}" placeholder="Informe Altura">
                                    <p class="text-light-danger"> @error('altura') {{ $message }} @enderror </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="temperatura" class="form-label">Temperatura:</label>
                                    <input type="text" class="form-control" id="temperatura" name="temperatura" value="{{ $triagem->temperatura ?? old('temperatura') }}" placeholder="Informe Temperatura">
                                    <p class="text-light-danger"> @error('temperatura') {{ $message }} @enderror </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="freq_respiratoria" class="form-label">Frequência respiratória:</label>
                                    <input type="text" class="form-control" id="freq_respiratoria" name="freq_respiratoria" value="{{ $triagem->freq_respiratoria ?? old('freq_respiratoria') }}" placeholder="Informe a Frequência respiratória">
                                    <p class="text-light-danger"> @error('freq_respiratoria') {{ $message }} @enderror </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="freq_cardiaca" class="form-label">Frequência Cardiaca:</label>
                                    <input type="text" class="form-control" id="freq_cardiaca" name="freq_cardiaca" value="{{ $triagem->freq_cardiaca ?? old('freq_cardiaca') }}" placeholder="Informe a Frequência Cardiaca">
                                    <p class="text-light-danger"> @error('freq_cardiaca') {{ $message }} @enderror </p>
                                </div>



                                <div class="col-12 col-md-3">
                                    <label for="profissional_id" class="form-label">Medicos</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" style="width: 100%" id="profissional_id" name="profissional_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($medicos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $triagem->profissional_id == $item->id ? "selected" : "" }}>{{ $item->funcionario->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="prioridade_id" class="form-label">Prioridade</label>
                                    <select type="text" class="form-control select2" id="prioridade_id" name="prioridade_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($prioridades as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $triagem->prioridade_id == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-light-danger"> @error('prioridade_id') {{ $message }} @enderror </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo_atendimento_id" class="form-label">Tipo Atendimento (Destino)</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control " id="tipo_atendimento_id" name="tipo_atendimento_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($tipos_atendimentos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $triagem->tipo_atendimento_id == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="observacoes" class="form-label">Observação:</label>
                                    <input type="text" class="form-control" id="observacoes" name="observacoes" value="{{ $triagem->observacoes ?? old('observacoes') }}" placeholder="Observação">
                                    <p class="text-light-danger"> @error('observacoes') {{ $message }} @enderror </p>
                                </div>

                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar triagem'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.row -->
                </div>
            </div>
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

                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
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
