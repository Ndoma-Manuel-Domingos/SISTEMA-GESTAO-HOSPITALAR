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
                        <li class="breadcrumb-item"><a href="{{ route('tipo-pagamentos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Tipo Pagamento</li>
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
                        <form action="{{ route('tipo-pagamentos.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">{{ __('messages.designacao') }}</label>
                                    <div class="input-group mb-3">

                                        <input type="text" class="form-control" name="titulo" value="{{ old('titulo') }}" placeholder="Informe o Titulo">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('titulo')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">{{ __('messages.estados') }}</label>
                                    <div class="input-group mb-3">

                                        <select type="text" class="form-control" name="status">
                                            <option value="1">{{ __('messages.activo') }} </option>
                                            <option value="0">{{ __('messages.desactivo') }} </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Tipo</label>
                                    <div class="input-group mb-3">

                                        <select name="tipo" id="tipo" class="form-control">
                                            <option value="">Informe o tipo do caixa</option>
                                            <option value="NU">Numerário</option>
                                            <option value="CC">Cartão de Crédito</option>
                                            <option value="CD">Cartão de Débito</option>
                                            <option value="CO">Cartão Oferta</option>
                                            <option value="CS">Compensação de Saldos C/C</option>
                                            <option value="DE">Cartão de Pontos</option>
                                            <option value="TR">Ticket Restaurante</option>
                                            <option value="MB">Multicaixa</option>
                                            <option value="OU">Duplo Pagamento</option>
                                            <option value="CH">Cheque Bancário</option>
                                            <option value="LC">Letra Comercial</option>
                                            <option value="TB">Transferência Bancária</option>
                                            <option value="PR">Permuta de Bens</option>
                                            <option value="DNP">Pagamento em conta corrente - entre 15 e 90 dias ou numa data específica</option>
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('tipo')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Pode originar troco?</label>
                                    <div class="input-group mb-3">

                                        <select name="troco" class="form-control" id="troco" aria-placeholder="Pode originar troco">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="1"> {{ __('messages.sim') }} </option>
                                            <option value="0"> {{ __('messages.nao') }} </option>
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('troco')
                                        {{ $message }}
                                        @enderror
                                    </p>
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
