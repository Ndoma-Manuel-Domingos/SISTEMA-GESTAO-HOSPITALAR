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
                        <li class="breadcrumb-item"><a href="{{ route('planos.index') }}">{{ __('messages.voltar') }}</a></li>
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
                <form action="{{ route('planos.update', $plano->id) }}" method="post" class="">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="nome" class="form-label"> {{ __('messages.designacao') }} </label>
                                <input type="text" id="nome" class="form-control" name="nome" value="{{ $plano->nome ?? old('nome') }}" placeholder="Informe o Plano">
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label for="valor_mensal" class="form-label">Valor Mensal</label>
                                <input type="text" id="valor_mensal" class="form-control" name="valor_mensal" value="{{ $plano->valor_mensal ?? old('valor_mensal') }}" placeholder="Informe valor mensal">
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label for="dia_vencimento" class="form-label">Dia de Vencimento</label>
                                <input type="text" id="dia_vencimento" class="form-control" name="dia_vencimento" value="{{ $plano->dia_vencimento ?? old('dia_vencimento') }}" placeholder="Informe dia de vencimento">
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label for="juros_diario" class="form-label">Multa %</label>
                                <input type="text" id="multa_percentual" class="form-control" name="multa_percentual" value="{{ $plano->multa_percentual ?? old('multa_percentual') }}" placeholder="Informe multa percentual">
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <label for="juros_diario" class="form-label">Juro Diário %</label>
                                <input type="text" id="juros_diario" class="form-control" name="juros_diario" value="{{ $plano->juros_diario ?? old('juros_diario') }}" placeholder="Informe juros diário">
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
