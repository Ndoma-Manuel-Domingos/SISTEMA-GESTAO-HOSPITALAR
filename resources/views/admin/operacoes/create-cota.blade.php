@extends('layouts.admin')

@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        Pagamento de Cota
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('empresas-dashboard-financeiro-cotas.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Inicio</li>
                    </ol>
                </div><!-- /.col -->
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Confirmar Pagamento
                            </h3>
                        </div>

                        <form id="formPagamento" method="POST" enctype="multipart/form-data" action="{{ route('mensalidade-cotas.pagamento.store') }}">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="mensalidade_id" value="{{ $mensalidade->id }}">

                                <!-- RESUMO -->
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Membro:</strong>
                                            {{ $mensalidade->membro->nome }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Saldo Devedor:</strong>
                                            <span class="text-light-danger font-weight-bold">
                                                AKZ {{ number_format($mensalidade->saldo_devedor,2,',','.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Valor Pago <span class="text-light-danger">*</span></label>
                                            <input type="number" step="0.01" name="valor_pago" value="{{ $mensalidade->saldo_devedor }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Data do Pagamento <span class="text-light-danger">*</span></label>
                                            <input type="date" name="data_pagamento" value="{{ date('Y-m-d') }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Método de Pagamento <span class="text-light-danger">*</span></label>
                                            <select name="metodo_pagamento" class="form-control" required>
                                                <option value="">Selecionar</option>
                                                <option value="cash">Dinheiro</option>
                                                <option value="transferencia" selected>Transferência</option>
                                                <option value="multicaixa">Multicaixa</option>
                                                <option value="deposito">Depósito Bancário</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Banco / Conta Origem
                                            </label>
                                            <input type="text" name="banco_origem" class="form-control" placeholder="Ex: BAI">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Referência / Nº Operação
                                            </label>
                                            <input type="text" name="referencia" class="form-control" placeholder="Código da transferência">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Comprovativo
                                            </label>
                                            <input type="file" name="comprovativo" id="comprovativo" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        </div>
                                    </div>
                                    <!-- OBS -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                Observações
                                            </label>
                                            <textarea name="observacoes" rows="3" class="form-control" placeholder="Observações do pagamento"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mb-3">
                                    <img id="preview" src="" style="max-width: 250px;display:none;border-radius:10px;">
                                </div>

                                <div id="resposta"></div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" id="btnSalvar" class="btn btn-light-success">
                                    <i class="fas fa-check"></i>
                                    Confirmar Pagamento
                                </button>
                                <a href="{{ url()->previous() }}" class="btn btn-light-secondary">
                                    Voltar
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
    $("#comprovativo").on("change", function() {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $("#preview")
                    .attr("src", e.target.result)
                    .show();
            }
            reader.readAsDataURL(file);
        }
    });

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            //let formData = form.serialize();
            let formData = new FormData(this);

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                processData: false
                , contentType: false
                , cache: false
                , headers: {
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

                    window.open(
                        '/mensalidade-cotas/comprovativo/' + response.mensalidade.id
                        , '_blank'
                    );
                    // Aguarda antes de recarregar
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
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
