@extends('layouts.admin')

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
                        <li class="breadcrumb-item"><a href="{{ route('membros.index') }}">{{ __('messages.voltar') }}</a></li>
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
            <div class="card">
                <form action="{{ route('membros.update', $membro->id) }}" method="post" class="">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="row">

                            <div class="col-12 col-md-3">
                                <div class="form-group text-center">
                                    <label for="foto">Foto</label>
                                    <div class="mb-4">
                                        <img id="preview" src="https://via.placeholder.com/150" class="img-thumbnail" style="width:200px;height:200px;">
                                    </div>
                                    <input type="file" name="foto" id="foto" class="form-control" onchange="previewImage(event)">
                                </div>
                            </div>

                            <div class="col-12 col-md-9">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="nome">Nome</label>
                                            <input type="text" name="nome" id="nome" value="{{ $membro->nome }}" class="form-control" placeholder="Informe o nome completo do empresário" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="bilhete">Nº Bilhete</label>
                                            <input type="text" name="bilhete" id="bilhete" value="{{ $membro->documento }}" class="form-control" placeholder="Informe o bilhete do membro">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="genero">Sexo</label>
                                            <select name="genero" id="genero" class="form-control">
                                                <option value="Masculino">Masculino</option>
                                                <option value="Feminino">Feminino</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="nacionalidade">Nacionalidade</label>
                                            <input type="text" name="nacionalidade" value="{{ $membro->nacionalidade }}" id="nacionalidade" value="Angolana" class="form-control" placeholder="Informe a nacionalidade do empresário">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="profissao_id">Profissão</label>
                                            <select name="profissao_id" id="funcao_id" class="form-control select2">
                                                <option value="">Escolher</option>
                                                @foreach ($profissoes as $item)
                                                <option value="{{ $item->id ?? "" }}" {{ $item->id == $membro->profissao_id ? 'selected' : '' }}>{{ $item->nome ?? "" }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="telefone_empresa">Telefone Pessoal</label>
                                            <input type="text" name="telefone" value="{{ $membro->telefone }}" id="telefone" class="form-control" placeholder="Informe o número do empresário">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="email">E-mail</label>
                                            <input type="email" id="email" name="email" value="{{ $membro->email }}" class="form-control" placeholder="Informe o e-mail do empresário">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="funcao_id">Função</label>
                                            <select name="funcao_id" id="funcao_id" class="form-control select2">
                                                <option value="">Escolher</option>
                                                @foreach ($funcoes as $item)
                                                <option value="{{ $item->id ?? "" }}" {{ $item->id == $membro->funcao_id ? 'selected' : '' }}>{{ $item->nome ?? "" }}</option>
                                                @endforeach
                                            </select>
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

</script>
@endsection
