@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Liberação a Funerária</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('morgues.index') }}">{{ __('messages.inicio') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.morgue') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('morgues.liberacao-store') }}" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-header"></div>
                            <div class="card-body row">

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="morgue_registro" class="form-label">Registro Morgue</label>
                                    <input type="text" class="form-control" disabled id="morgue_registro" value="Nº {{ $morgue->id }}" name="morgue_registro" placeholder="Corpo a ser liberado">
                                    <input type="hidden" class="form-control" id="morgue_registro_id" value="{{ $morgue->id }}" name="morgue_registro_id">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="data_liberacao" class="form-label">Data da Liberação</label>
                                    <input type="date" class="form-control" id="data_liberacao" name="data_liberacao">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="hora_liberacao" class="form-label">Hora da Liberação</label>
                                    <input type="time" class="form-control" id="hora_liberacao" name="hora_liberacao">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="nome_responsavel_retirada" class="form-label">Nome do responsável da retirada</label>
                                    <input type="text" class="form-control" id="nome_responsavel_retirada" name="nome_responsavel_retirada" placeholder="Nome do familiar ou agente funerário">
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="documento_responsavel" class="form-label">Documento do responsável</label>
                                    <input type="text" class="form-control" id="documento_responsavel" name="documento_responsavel" placeholder="BI/RG/Passaporte">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="relacionamento" class="form-label">Relacionamento</label>
                                    <input type="text" class="form-control" id="relacionamento" name="relacionamento" placeholder="Parentesco ou função">
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="empresa_funeraria" class="form-label">Empresa funerária</label>
                                    <input type="text" class="form-control" id="empresa_funeraria" name="empresa_funeraria" placeholder="Se for funerária">
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="observacoes" class="form-label">{{ __('messages.observacao') }}</label>
                                    <textarea name="observacoes" class="form-control" id="observacoes" cols="30" rows="2"></textarea>
                                </div>

                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                            </div>
                        </div>
                    </form>
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

                    showMessage('Sucesso!', response.message, 'success');
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
