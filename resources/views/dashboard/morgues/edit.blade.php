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
                        <li class="breadcrumb-item"><a href="{{ route('morgues.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Morgue</li>
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
                    <form action="{{ route('morgues.update', $morgue->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="obito_id" class="form-label">Obitos</label>
                                    <div class="input-group">
                                        <select type="text" class="form-control select2" width="100%" id="obito_id" name="obito_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($obitos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $morgue->obito_id == $item->id ? "selected" : "" }}>{{ $item->documento_declaracao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="data_entrada_morgue" class="form-label">Data da Entrada a Morgue</label>
                                    <input type="date" class="form-control" id="data_entrada_morgue" value="{{ $morgue->data_entrada_morgue ?? old('data_entrada_morgue') }}" name="data_entrada_morgue">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="hora_entrada_morgue" class="form-label">Hora da Entrada a Morgue</label>
                                    <input type="time" class="form-control" id="hora_entrada_morgue" value="{{ $morgue->hora_entrada_morgue ?? old('hora_entrada_morgue') }}" name="hora_entrada_morgue">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="data_liberacao" class="form-label">Data da Liberação</label>
                                    <input type="date" class="form-control" id="data_liberacao" value="{{ $morgue->data_liberacao ?? old('data_liberacao') }}" name="data_liberacao">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="hora_liberacao" class="form-label">Hora da Liberação</label>
                                    <input type="time" class="form-control" id="hora_liberacao" value="{{ $morgue->hora_liberacao ?? old('hora_liberacao') }}" name="hora_liberacao">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="camara_id" class="form-label">Camaras</label>
                                    <div class="input-group">
                                        <select type="text" class="form-control" id="camara_id" name="camara_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($camaras as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $morgue->camara_id == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="gaveta_id" class="form-label">Gavetas</label>
                                    <div class="input-group">
                                        <select type="text" class="form-control" id="gaveta_id" name="gaveta_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($gavetas as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $morgue->gaveta_id == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 col-md-6 mb-3">
                                    <label for="temperatura_armazenamento" class="form-label">Temperatura armazenamento</label>
                                    <textarea name="temperatura_armazenamento" class="form-control" id="temperatura_armazenamento" cols="30" rows="2">{{ $morgue->temperatura_armazenamento }}</textarea>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="observacoes_iniciais" class="form-label">{{ __('messages.observacao') }}</label>
                                    <textarea name="observacoes_iniciais" class="form-control" id="observacoes_iniciais" cols="30" rows="2">{{ $morgue->observacoes_iniciais }}</textarea>
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
    $("#camara_id").change(() => {
        let id = $("#camara_id").val();
        $.get('../../carregar-gavetas-camara/' + id, function(data) {
            $("#gaveta_id").html("")
            $("#gaveta_id").html(data)
        })
    })

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
