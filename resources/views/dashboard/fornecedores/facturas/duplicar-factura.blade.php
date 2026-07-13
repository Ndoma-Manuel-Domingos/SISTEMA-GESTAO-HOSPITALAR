@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Duplicar factura</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores-facturas-encomendas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('controle') }}</li>
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
                <form action="{{ route('encomenda-duplicar-factura-store') }}" method="post" class="">
                    @csrf
                    <div class="card-body row">
                        <div class="col-12 col-md-4 mb-3">
                            <label for="loja_id" class="col-form-label text-right">{{ __('messages.fornecedores') }}:</label>
                            <div class="mb-3">
                                <select class="form-control" id="fornecedor_id" name="fornecedor_id">
                                    @foreach ($fornecedores as $item)
                                    <option value="{{ $item->id ?? "" }}" {{ $factura->fornecedor_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label for="numero" class="col-form-label text-right">Nº Factura:</label>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="factura" name="factura" value="{{ $factura->factura }}" placeholder="Número da Factura:">
                            </div>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label for="numero" class="col-form-label text-right">Valor da Factura:</label>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="valor_factura" name="valor_factura" value="{{ $factura->valor_factura }}" placeholder="Valor da Factura:">
                            </div>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label for="numero" class="col-form-label text-right">{{ __('messages.desconto') }} %:</label>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="desconto" name="desconto" value="{{ $factura->desconto }}" placeholder="Desconto:">
                            </div>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label for="numero" class="col-form-label text-right">Data Factura</label>
                            <div class="mb-3">
                                <input type="date" class="form-control" id="data_factura" name="data_factura" value="{{ $factura->data_factura }}" placeholder="Data factura:">
                            </div>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label for="numero" class="col-form-label text-right">Data Vencimento:</label>
                            <div class="mb-3">
                                <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="{{ $factura->data_vencimento }}" placeholder="Data Vencimento:">
                            </div>
                        </div>

                        <div class="col-12 col-md-4 mb-3">
                            <label for="observacao" class="col-form-label text-right">{{ __('messages.observacao') }}:</label>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="observacao" name="observacao" value="{{ $factura->observacao }}" placeholder="{{ __('messages.observacao') }} ">
                            </div>
                        </div>

                        <input type="hidden" name="encomenda_id" value="{{ $factura->encomenda_id }}">
                        <input type="hidden" name="factura_id" value="{{ $factura->id }}">

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                    </div>
                </form>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    document.getElementById("valor_factura").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número
        let numericValue = parseFloat(value) / 100; // Ajusta casas decimais

        // Formata para exibição no padrão brasileiro (10.000,50)
        e.target.value = numericValue.toLocaleString("pt-BR", {
            minimumFractionDigits: 2
        });
    });



    $(document).ready(function() {
        $('form').on('submit', function(e) {

            e.preventDefault(); // Impede o envio tradicional do formulário

            // formatar valores            
            let input = document.getElementById("valor_factura");
            // Converter de "10.000,50" para "10000.50"
            let rawValue = input.value.replace(/\./g, "").replace(",", ".");
            input.value = parseFloat(rawValue).toFixed(2); // Garantir 2 casas decimais

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário           

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');

                    window.location.href = response.redirect;
                    // window.location.reload();

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
