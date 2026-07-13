@extends('layouts.admin')

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
                        <li class="breadcrumb-item"><a href="{{ route('modulos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Modulo Entidade</li>
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
                <div class="col-md-12">
                    <div class="card">
                        <form action="{{ route('modulos.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <label for="" class="form-label"> {{ __('messages.designacao') }} </label>
                                        <input type="text" class="form-control" name="modulo" value="{{ old('modulo') }}" placeholder="Informe o Modulo entidade">
                                        <p class="text-light-danger">
                                            @error('modulo')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label for="" class="form-label"> {{ __('messages.descricao') }} </label>
                                        <input type="text" class="form-control" name="descricao" value="{{ old('descricao') }}" placeholder="Informe a Descrição do Modulo entidade">
                                        <p class="text-light-danger">
                                            @error('descricao')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label for="tipo" class="form-label"> {{ __('messages.descricao') }} </label>
                                        <select name="tipo" id="tipo" class="form-control">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="Empresa">Empresa</option>
                                            <option value="Modulos">Modulos</option>
                                        </select>
                                        <p class="text-light-danger">
                                            @error('tipo')
                                            {{ $message }}
                                            @enderror
                                        </p>
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
