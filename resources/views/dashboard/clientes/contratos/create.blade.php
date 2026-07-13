@extends('layouts.app')

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
                        <li class="breadcrumb-item"><a href="{{ route('clientes-contratos.index') }}">{{ __('messages.voltar') }}</a></li>
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
                    <form action="{{ route('clientes-contratos.store') }}" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="codigo_contrato" class="form-label">Código contrato</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="codigo_contrato" name="codigo_contrato" placeholder="Codigo de Contrato">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="cliente_id" class="form-label">Clientes</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="cliente_id" name="cliente_id">
                                            @foreach ($clientes as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="estado" name="estado">
                                            <option value="">Escolher</option>
                                            <option value="Pendente">Pendente</option>
                                            <option value="Activo">Activo</option>
                                            <option value="Terminado">Terminado</option>
                                            <option value="Cancelado">Cancelado</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="valor_mensal" class="form-label">Valor Mensal</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="valor_mensal" name="valor_mensal" placeholder="Valor Mensal">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_inicio" class="form-label">Data Início</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" id="data_inicio" name="data_inicio" placeholder="Data Inicio Contrato">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_final" class="form-label">Data Final</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" id="data_final" name="data_final" placeholder="Data Final Contrato">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="forma_pagamento_id" class="form-label">Forma de Pagamentos</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="forma_pagamento_id" name="forma_pagamento_id">
                                            @foreach ($tipos_pagamentos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->titulo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <div class="input-group mb-3">
                                        <textarea name="descricao" id="descricao" class="form-control" placeholder="Data Final Contrato"></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar cliente'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
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
