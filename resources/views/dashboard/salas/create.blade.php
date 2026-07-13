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
                        <li class="breadcrumb-item"><a href="{{ route('salas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Sala</li>
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
                        <div class="card-header">
                        </div>
                        <form action="{{ route('salas.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-4">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="{{ __('messages.designacao') }}">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('nome')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="status">
                                            <option value="activo">{{ __('messages.activo') }} </option>
                                            <option value="desactivo">{{ __('messages.desactivo') }} </option>
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('status')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-12 col-md-4">
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control" name="solicitar_ocupacao">
                                            <option value="">Solicitar ocupação</option>
                                            <option value="1"> {{ __('messages.sim') }} </option>
                                            <option value="0"> {{ __('messages.nao') }} </option>
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('solicitar_ocupacao')
                                        {{ $message }}
                                        @enderror
                                    </p>
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
