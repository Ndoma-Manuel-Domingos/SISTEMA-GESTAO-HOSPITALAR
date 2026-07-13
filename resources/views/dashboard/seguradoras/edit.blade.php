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
                        <li class="breadcrumb-item"><a href="{{ route('seguradoras.index') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('seguradoras.update', $seguradora->id) }}" method="post" class="">
                            @csrf
                            @method('put')

                            <div class="card-header"></div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-3">
                                        <label for="nome" class="form-label">Designação</label>
                                        <input type="text" class="form-control" id="nome" name="nome" value="{{ $seguradora->nome }}" placeholder="Informe a seguradora">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                                        <input type="text" class="form-control" id="nome_fantasia" value="{{ $seguradora->nome_fantasia }}" name="nome_fantasia" placeholder="Informe o nome fantasia">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="sigla" class="form-label">Sigla</label>
                                        <input type="text" class="form-control" id="sigla" value="{{ $seguradora->sigla }}" name="sigla" placeholder="Informe a silga">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="tipo" class="form-label">Tipo</label>
                                        <select class="form-control" id="tipo" name="tipo">
                                            <option value="PLANO_SAUDE" {{ $seguradora->tipo == "PLANO_SAUDE" ? 'selected' : '' }}>Plano de Saúde</option>
                                            <option value="SEGURO" {{ $seguradora->tipo == "SEGURO" ? 'selected' : '' }}>Seguro</option>
                                            <option value="CONVENIO" {{ $seguradora->tipo == "CONVENIO" ? 'selected' : '' }}>Convênio</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="status" class="form-label">Estado</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="activo" {{ $seguradora->status == "activo" ? 'selected' : '' }}>Activo</option>
                                            <option value="desactivo" {{ $seguradora->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="nif" class="form-label">NIF</label>
                                        <input type="text" class="form-control" id="nif" value="{{ $seguradora->nif }}" name="nif" placeholder="Informe o NIF">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="numero" class="form-label">Número</label>
                                        <input type="text" class="form-control" id="numero" value="{{ $seguradora->numero }}" name="numero" placeholder="Informe o números">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $seguradora->email }}" placeholder="informe o E-mail">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="contacto" class="form-label">Telefone</label>
                                        <input type="text" class="form-control" id="contacto" name="contacto" value="{{ $seguradora->contacto }}" placeholder="informe o telefone">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="telefone_secundario" class="form-label">Telefone Secundário</label>
                                        <input type="text" class="form-control" id="telefone_secundario" value="{{ $seguradora->telefone_secundario }}" name="telefone_secundario" placeholder="Telefone Secundário">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="text" class="form-control" id="website" value="{{ $seguradora->website }}" name="website" placeholder="informe o website">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="pessoa_contato" class="form-label">Pessoa de Contacto</label>
                                        <input type="text" class="form-control" id="pessoa_contato" value="{{ $seguradora->pessoa_contato }}" name="pessoa_contato" placeholder="informe o numero da pessoa de contacto">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="cidade" class="form-label">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" value="{{ $seguradora->cidade }}" placeholder="informe a cidade" value="Luanda">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="provincia" class="form-label">Província</label>
                                        <input type="text" class="form-control" id="provincia" name="provincia" value="{{ $seguradora->provincia }}" placeholder="informe a província" value="Luanda">
                                    </div>

                                    <div class="col-md-3 col-12 mb-3">
                                        <label for="pais" class="form-label">País</label>
                                        <input type="text" class="form-control" id="pais" name="pais" value="{{ $seguradora->pais }}" value="Angola">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <label for="observacoes" class="form-label">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="4">{{ $seguradora->observacoes }}</textarea>
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
