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
                        <li class="breadcrumb-item active">Nova Empresa</li>
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
                        <form action="{{ route('inscricoes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <!-- FOTO -->
                                    <div class="col-md-3 col-12 text-center">
                                        <div class="form-group">
                                            <label for="foto">Foto</label>
                                            <div class="mb-2">
                                                <img id="preview" src="https://via.placeholder.com/150" class="img-thumbnail" style="width:430px;height:335px;">
                                            </div>
                                            <input type="file" name="foto" id="foto" class="form-control" onchange="previewImage(event)">
                                        </div>
                                    </div>

                                    <!-- DADOS -->
                                    <div class="col-md-9">

                                        <div class="row">

                                            <div class="col-12 col-md-12 mb-2">
                                                <h1 class="card-title">Dados Pessoais</h1>
                                                <hr class="border mt-4">
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="bilhete">Nº Bilhete</label>
                                                    <input type="text" name="bilhete" id="bilhete" class="form-control" placeholder="Informe o bilhete do membro">
                                                </div>
                                            </div>


                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="nome">Nome</label>
                                                    <input type="text" name="nome" id="nome" class="form-control" placeholder="Informe o nome completo do empresário">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="genero">Sexo</label>
                                                    <select name="genero" id="genero" class="form-control">
                                                        <option value="Masculino">Masculino</option>
                                                        <option value="Feminino">Feminino</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="nacionalidade">Nacionalidade</label>
                                                    <input type="text" name="nacionalidade" id="nacionalidade" value="Angolana" class="form-control" placeholder="Informe a nacionalidade do empresário">
                                                </div>
                                            </div>

                                            <input type="hidden" name="membro_id" id="membro_id" value="">

                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="profissao_id">Profissão</label>
                                                    <select name="profissao_id" id="funcao_id" class="form-control select2">
                                                        <option value="">Escolher</option>
                                                        @foreach ($profissoes as $item)
                                                        <option value="{{ $item->id ?? "" }}">{{ $item->nome ?? "" }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="telefone_empresa">Telefone Pessoal</label>
                                                    <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Informe o número do empresário">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="email">E-mail</label>
                                                    <input type="email" id="email" name="email" class="form-control" placeholder="Informe o e-mail do empresário">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="funcao_id">Função</label>
                                                    <select name="funcao_id" id="funcao_id" class="form-control select2">
                                                        <option value="">Escolher</option>
                                                        @foreach ($funcoes as $item)
                                                        <option value="{{ $item->id ?? "" }}">{{ $item->nome ?? "" }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-12 col-md-12 mb-2">
                                                <h1 class="card-title">Dados da empresa</h1>
                                                <hr class="border mt-4">
                                            </div>


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

                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="local_inscricao">Local de Inscrição</label>
                                                    <input type="text" name="local_inscricao" id="local_inscricao" value="UEA - União dos Empresário de Angola" class="form-control" placeholder="Informe o local da inscrição">
                                                </div>
                                            </div>

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
    function previewImage(event) {
        let reader = new FileReader();

        reader.onload = function() {
            let output = document.getElementById('preview');
            output.src = reader.result;
        }

        reader.readAsDataURL(event.target.files[0]);
    }

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


    $('#bilhete').on('input', function() {

        let bilhete = $(this).val();

        if (bilhete.length < 5) return;

        $.ajax({
            url: '/membro/buscar'
            , type: 'GET'
            , data: {
                bilhete: bilhete
            }
            , success: function(response) {

                if (response.found) {

                    let m = response.membro;

                    $("#membro_id").val(m.id);
                    $('#nome').val(m.nome);
                    $('#genero').val(m.genero);
                    $('#nacionalidade').val(m.nacionalidade);
                    $('#telefone').val(m.telefone);
                    $('#email').val(m.email);

                    $('#profissao_id').val(m.profissao_id).trigger('change');
                    $('#funcao_id').val(m.funcao_id).trigger('change');

                    showMessage('Info', 'Membro encontrado e carregado!', 'success');

                } else {
                    showMessage('Aviso', 'Membro não encontrado. Preencher manualmente.', 'warning');

                    // opcional: limpar campos
                    $('#nome').val('');
                    $('#genero').val('Masculino');
                    $('#telefone').val('');
                    $('#email').val('');
                    $('#nacionalidade').val('Angolana');
                }
            }
        });

    });

</script>
@endsection
