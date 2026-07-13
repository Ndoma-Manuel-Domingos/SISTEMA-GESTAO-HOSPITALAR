@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Regularização Conta Corrente</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Conta</li>
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
                <form action="{{ route('conta-clientes.store') }}" method="post" class="">
                    @csrf
                    <div class="card-body row">

                        <div class="col-12 col-md-12">
                            <label for="">{{ __('messages.observacao') }}:</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="observacao" value="{{ old('observacao') }}" placeholder="{{ __('messages.observacao') }}">
                            </div>

                        </div>

                        <div class="col-12 col-md-12">
                            <label for="">{{ __('messages.valor') }}:</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="montante" value="{{ old('montante') }}" placeholder="{{ __('messages.valor') }}">
                            </div>

                        </div>

                        <div class="col-12 col-md-12">
                            <label for="">Tipo Movimento</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control" name="tipo_movimento">
                                    <option value="1">Crédito (aumenta dívida do cliente)</option>
                                    <option value="-1">Dédito (aumenta saldo a favor do cliente)</option>
                                </select>
                            </div>

                        </div>
                        <input type="hidden" name="cliente_id" value="{{ $clienteSaldo->id }}">
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
                    showMessage('Sucesso!', 'Exportação concluída com sucesso!', 'success');
                    // Exibe uma mensagem de sucesso
                    window.location.href = response.url;
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
