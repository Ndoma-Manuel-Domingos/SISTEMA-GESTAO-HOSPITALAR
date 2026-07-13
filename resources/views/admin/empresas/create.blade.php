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
                        <li class="breadcrumb-item"><a href="{{ route('empresas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Empresa</li>
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
                        <form action="{{ route('empresas.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="empresa">Nome da Empresa</label>
                                            <input type="text" name="empresa" id="empresa" class="form-control" placeholder="Informe o nome da empresa">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="nif">NIF</label>
                                            <input type="text" name="nif" id="nif" class="form-control" placeholder="Informe o nif da empresa">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="tipo_negocio">Ramo da Actividade</label>
                                            <select name="tipo_negocio" id="tipo_negocio" class="form-control select2">
                                                <option value="">Tipo de Negócio</option>
                                                @foreach ($tipos_entidade as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->tipo ?? "" }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="plano_id">Plano de Assinatura</label>
                                            <select name="plano_id" id="plano_id" class="form-control select2">
                                                <option value="">Escolher</option>
                                                @foreach ($planos as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome ?? "" }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="telefone_empresa">Telefone</label>
                                            <input type="text" name="telefone_empresa" id="telefone_empresa" class="form-control" placeholder="Informe o número da empresa">
                                        </div>
                                    </div>


                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="email_empresa">E-mail</label>
                                            <input type="email_empresa" id="email_empresa" name="email_empresa" class="form-control" placeholder="Informe o e-mail do empresário">
                                        </div>
                                    </div>


                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="membro_id">Membros</label>
                                            <select name="membro_id" id="membro_id" class="form-control select2">
                                                <option value="">Selecione membros</option>
                                                @foreach ($membros as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome ?? "" }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="provincia_id">Província</label>
                                            <select name="provincia_id" id="provincia_id" class="form-control select2">
                                                <option value="">Escolher</option>
                                                @foreach ($provincias as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome ?? "" }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="municipio">Município</label>
                                            <select name="municipio_id" id="municipio_id" class="form-control select2">
                                                <option value="">Escolher</option>
                                                @foreach ($municipios as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome ?? "" }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="residente">Residente</label>
                                            <input type="text" name="residente" id="residente" class="form-control" placeholder="Informe a residência do empresário">
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="local_inscricao">Local de Inscrição</label>
                                            <input type="text" name="local_inscricao" id="local_inscricao" value="UEA - União dos Empresário de Angola" class="form-control" placeholder="Informe o local da inscrição">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
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
    });

</script>
@endsection
