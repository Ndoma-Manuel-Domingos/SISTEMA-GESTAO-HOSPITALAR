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
                        <li class="breadcrumb-item"><a href="{{ route('anos-lectivos.index') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('anos-lectivos.update', $ano_lectivo->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">
                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">{{ __('messages.ano_lectivo') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="nome" value="{{ $ano_lectivo->nome ?? old('nome') }}" placeholder="Informe a ano lectivo">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('nome')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="sigla" class="form-label">Sigla</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="sigla" value="{{ $ano_lectivo->sigla ?? old('sigla') }}" placeholder="Informe a Sigla">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('sigla')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" name="data_inicio" value="{{ $ano_lectivo->data_inicio ?? old('data_inicio') }}" placeholder="Informe a data inicio">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('data_inicio')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" name="data_final" value="{{ $ano_lectivo->data_final ?? old('data_final') }}" placeholder="Informe a data final">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('data_final')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">{{ __('messages.estados') }}</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="status">
                                            <option value="activo" {{ $ano_lectivo->status == "activo" ? 'selected' : '' }}>{{ __('messages.activo') }} </option>
                                            <option value="desactivo" {{ $ano_lectivo->status == "desactivo" ? 'selected' : '' }}>{{ __('messages.desactivo') }} </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
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
