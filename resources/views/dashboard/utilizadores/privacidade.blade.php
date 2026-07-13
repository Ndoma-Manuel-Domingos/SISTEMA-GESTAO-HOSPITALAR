@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Actualizar as credenciais</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="{{ route('privacidade-store') }}" method="post" class="">
                    @csrf
                    <div class="card-body row">

                        <div class="col-12 col-md-12">
                            <label for="">Senha actual</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="password" class="form-control" name="senha" placeholder="Informe a senha actual">
                            </div>
                            <p class="text-light-danger">
                                @error('senha')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-12">
                            <label for="">Nova Senha</label>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="password" class="form-control" name="nova_senha" placeholder="Informe a Nova Senha">
                            </div>
                            <p class="text-light-danger">
                                @error('nova_senha')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-12">
                            <label for="">Confirmar Senha</label>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="password" class="form-control" name="confirmar_senha" placeholder="Informe a Nova Senha">
                            </div>
                            <p class="text-light-danger">
                                @error('confirmar_senha')
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
