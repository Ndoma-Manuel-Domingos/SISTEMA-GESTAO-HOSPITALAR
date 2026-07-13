@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Configurar do sistema</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Configuração</li>
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
                        <form action="{{ route('configuracao-admin-post') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <label for="dias" class="form-label">Dias</label>
                                        <input type="number" class="form-control" name="dias" id="dias" value="{{ $configuracao->limite_dias ?? '' }}" placeholder="Informe os dias de testa grantis para os clientes">
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <label for="valor_cota" class="form-label">Valor da cota</label>
                                        <input type="text" class="form-control" name="valor_cota" id="valor_cota" value="{{ $configuracao->valor_cota ?? '0' }}" placeholder="Informe o valor da cota">
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <label for="dia_limite_pagamento" class="form-label">Dia Limite do Pagamento da cota</label>
                                        <input type="text" class="form-control" name="dia_limite_pagamento" id="dia_limite_pagamento" value="{{ $configuracao->dia_limite_pagamento ?? '0' }}" placeholder="Informe o Dia Limite do Pagamento da cota">
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <label for="juros_diario" class="form-label">Juros diário</label>
                                        <input type="text" class="form-control" name="juros_diario" id="juros_diario" value="{{ $configuracao->juros_diario ?? '0' }}" placeholder="Informe a percentagem do juros diários">
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <label for="multa_percentual" class="form-label">Multa Percentual de atraso</label>
                                        <input type="text" class="form-control" name="multa_percentual" id="multa_percentual" value="{{ $configuracao->multa_percentual ?? '0' }}" placeholder="Informe a multa percentual">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
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

</script>
@endsection
