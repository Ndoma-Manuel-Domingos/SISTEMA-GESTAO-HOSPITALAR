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
                        <li class="breadcrumb-item"><a href="{{ route('utilizadores.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Utilizadores</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="{{ route('utilizadores.store') }}" method="post" class="">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="nome" class="form-label">{{ __('messages.designacao') }}</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Informe a Nome">
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="email" class="form-label"> {{ __('messages.email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Informe a Email">
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label for="email" class="form-label">Perfil</label>
                                <select type="text" id="roles" class="form-control select2" name="roles">
                                    <option value="">{{ __('messages.escolher') }} </option>
                                    @foreach ($roles as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                <select type="text" class="form-control select2" id="status" name="status">
                                    <option value="1">{{ __('messages.activo') }} </option>
                                    <option value="0">{{ __('messages.desactivo') }} </option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" id="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Informe a Senha">
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label for="password_r" class="form-label">Repetir Senha</label>
                                <input type="password" id="password_r" class="form-control" name="password_r" value="{{ old('password_r') }}" placeholder="Informe Repetir a Senha">
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="loja_id" class="form-label">Lojas</label>
                                <select type="text" class="form-control select2" multiple id="loja_id" name="loja_id[]">
                                    <option value="1">{{ __('messages.activo') }} </option>
                                    @foreach ($lojas as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
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
