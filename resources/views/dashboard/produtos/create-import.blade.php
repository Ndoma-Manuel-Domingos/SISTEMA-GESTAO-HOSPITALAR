@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-plus"></i> {{ __('messages.novo') }} (Exportando Excel)</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('produtos.index') }}">{{ __('messages.voltar') }}</a></li>
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
                    <div class="card">
                        <form action="{{ route('store_import.produtos') }}" method="post" class="" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h5>{{ __('messages.codigo_barras') }} | {{ __('messages.designacao') }} | {{ __('messages.tipo') }} | {{ __('messages.preco_custo') }} | {{ __('messages.preco_venda') }} | {{ __('messages.preco') }} | {{ __('messages.quantidade') }} </h5>
                            </div>
                            <div class="card-body">
                                <div class="col-12 col-md-4 col-lg-4">
                                    <label for="file" class="form-label">Carregar Documento</label>
                                    <input type="file" class="form-control" accept=".xls,.xlsx" required name="file" id="file" value="{{ old('file') }}" placeholder="file">
                                    <p class="text-light-danger">
                                        @error('file')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar produtos'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
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
{{-- <script>
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = new FormData(); // Cria o objeto FormData
            
            // Adiciona os dados serializados ao FormData
            let serializedData = form.serializeArray();
            $.each(serializedData, function(_, field) {
                formData.append(field.name, field.value);
            });
            
            let fileInput2 = $('#file')[0].files[0];
            if (fileInput2) {
                formData.append('file', fileInput2); // Adiciona o arquivo
            }

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                processData: false, // Impede o processamento dos dados pelo jQuery
                contentType: false, // Impede a configuração automática do cabeçalho
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso

                    // alert(response.mensagem || 'Arquivo exportado com sucesso!');
                    showMessage('Sucesso!', 'Dados Actualozados com sucesso!', 'success');

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
                            messages += `${value} *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', 'Erro ao processar o pedido. Tente novamente.', 'error');
                    }
                }
            , });
        });
    });
</script> --}}
@endsection
