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
                        <li class="breadcrumb-item"><a href="{{ route('contratos-postos.index') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('contratos-postos.update', $contrato->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="nome" class="form-label">Designação</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $contrato->nome }}" id="nome" name="nome" placeholder="Designação">
                                    </div>
                                </div>

                                <input type="hidden" value="{{ $contrato->contrato_id }}" name="contrato_id" id="contrato_id" class="contrato_id">

                                <div class="col-12 col-md-3">
                                    <label for="tipo_posto_id" class="form-label">Tipo de Posto</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="tipo_posto_id" name="tipo_posto_id">
                                            @foreach ($tipos_postos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == $contrato->tipo_posto_id ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="uso_armas" class="form-label">Uso de Armas</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="uso_armas" name="uso_armas">
                                            <option value="N" {{ $contrato->uso_armas == "N" ? "selected" : "" }}>Não</option>
                                            <option value="Y" {{ $contrato->uso_armas == "Y" ? "selected" : "" }}>Sim</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="equipa_id" class="form-label">Equipa</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="equipa_id" name="equipa_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($equipas as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == $contrato->equipa_id ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <div class="input-group mb-3">
                                        <input type="number" step="any" class="form-control" id="latitude" value="{{ $contrato->latitude }}" name="latitude" placeholder="Latitude">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <div class="input-group mb-3">
                                        <input type="number" step="any" class="form-control" id="longitude" value="{{ $contrato->longitude }}" name="longitude" placeholder="Longitude">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="representante_posto" class="form-label">Representante Posto/Gerente</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="representante_posto" value="{{ $contrato->representante_posto }}" name="representante_posto" placeholder="Representante do Posto">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="contacto_posto" class="form-label">Contacto Posto</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="contacto_posto" value="{{ $contrato->contacto_posto }}" name="contacto_posto" placeholder="Contacto principal do Posto">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 row">
                                    <div class="col-12 col-md-3">
                                        <label for="endereco" class="form-label">Endereço</label>
                                        <div class="input-group mb-3">
                                            <textarea name="endereco" id="endereco" class="form-control" placeholder="Data Final Contrato">{{ $contrato->endereco }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="instrucoes_especiais" class="form-label">Instruções Especiais</label>
                                        <div class="input-group mb-3">
                                            <textarea name="instrucoes_especiais" id="instrucoes_especiais" class="form-control" placeholder="Instruções Especiais">{{ $contrato->instrucoes_especiais }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="horario_permitido" class="form-label">Horario Permitido</label>
                                        <div class="input-group mb-3">
                                            <textarea name="horario_permitido" id="horario_permitido" class="form-control" placeholder="Horario Permitido">{{ $contrato->horario_permitido }}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar cliente'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </form>
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
