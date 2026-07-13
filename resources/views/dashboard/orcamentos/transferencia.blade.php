@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transferências Financeiras</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-financeiro') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Transferências</li>
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
                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('transacoes-financeiras-transferencia-store') }}" method="POST">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="tipo_transferencia" class="form-label">Tipo transferência</label>
                                    <select type="text" class="form-control" id="tipo_transferencia" name="tipo_transferencia">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        <option value="depositos">Depositos</option>
                                        <option value="levantamentos">Levantamentos</option>
                                        <option value="e_bancos">Entre Bancos</option>
                                        <option value="e_caixaas">Entre Caixas</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3" id="form_banco">
                                    <label for="banco_id" class="form-label">Bancos</label>
                                    <select type="text" class="form-control select2" name="banco_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($bancos as $item)
                                        <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3" style="display: none" id="form_banco_destino">
                                    <label for="banco_destino_id" class="form-label">Bancos de destino</label>
                                    <select type="text" class="form-control select2" name="banco_destino_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($bancos as $item)
                                        <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3" id="form_caixa">
                                    <label for="caixa_id" class="form-label">Caixas</label>
                                    <select type="text" class="form-control select2" name="caixa_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($caixas as $item)
                                        <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3" style="display: none" id="form_caixa_destino">
                                    <label for="caixa_destino_id" class="form-label">Caixas de destino</label>
                                    <select type="text" class="form-control select2" name="caixa_destino_id">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($caixas as $item)
                                        <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="motante" class="form-label">Motante</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $requests['motante'] ?? '' }}" name="motante" placeholder="Motante">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="data_operacao" class="form-label">Data da operação</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ date('Y-m-d') ?? '' }}" name="data_operacao" placeholder="Data operação">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary"> <i class="fas fa-save"></i> Confirmar</button>
                            </div>

                        </form>
                    </div>
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
    const tipo_transferencia = document.getElementById('tipo_transferencia');

    const form_banco_destino = document.getElementById('form_banco_destino');
    const form_caixa_destino = document.getElementById('form_caixa_destino');

    const form_banco = document.getElementById('form_banco');
    const form_caixa = document.getElementById('form_caixa');


    tipo_transferencia.addEventListener('change', function() {
        if (this.value === 'e_bancos') {
            form_banco_destino.style.display = 'block';
            form_caixa_destino.style.display = 'none';
            form_caixa.style.display = 'none';
            form_banco.style.display = 'block';
        } else if (this.value === 'e_caixaas') {
            form_caixa_destino.style.display = 'block';
            form_banco_destino.style.display = 'none';
            form_banco.style.display = 'none';
            form_caixa.style.display = 'block';
        } else {
            form_caixa_destino.style.display = 'none';
            form_banco_destino.style.display = 'none';
            form_banco.style.display = 'block';
            form_caixa.style.display = 'block';
        }
    });



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
