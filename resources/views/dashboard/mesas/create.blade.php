@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('mesas.visualizacao-mesas') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('mesas.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-4">
                                    <label for="nome"> {{ __('messages.designacao') }} </label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" placeholder="{{ __('messages.designacao') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="ocupacao">Numero de Ocupação</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="ocupacao" name="ocupacao" value="{{ old('ocupacao') }}" placeholder="Ocupação">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="solicitar_ocupacao">Ocupado</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control" id="solicitar_ocupacao" name="solicitar_ocupacao">
                                            <option value="LIVRE"> LIVRE</option>
                                            <option value="OCUPADA"> OCUPADA</option>
                                            <option value="RESERVADA"> RESERVADA</option>
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="sala_id" value="{{ $sala_id }}">

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

                    showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');

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
                            messages += `${value}\n`; // Exibe os erros
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
