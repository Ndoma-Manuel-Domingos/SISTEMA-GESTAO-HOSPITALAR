@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Actualizar modulos da Empresa</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('empresas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Empresa</li>
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
                        <div class="card-header">
                            <h6 class="card-title">Conceder Permissões</h6>
                        </div>
                        <form action="{{ route('empresas.actualizar-modulos-post') }}" method="post" class="">
                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    @foreach ($modulos as $modulo)
                                    <div class="col-12 col-md-4 col-lg-2 mt-3">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="modulo{{ $modulo->id }}" value="{{ $modulo->id }}" name="modulo_id[]" @if(in_array($modulo->id, $entidade_permissions)) checked @endif>
                                                <label for="modulo{{ $modulo->id }}">
                                                    {{ $modulo->modulo }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    <div class="col-12 col-md-12 mt-3">
                                        <label for="tipo_facturacao">Tipo de Facturação</label>
                                        <select name="tipo_facturacao" id="tipo_facturacao" class="form-control">
                                            <option value="">Escolher</option>
                                            <option value="saft" {{ $entidade->tipo_facturacao == "saft" ? 'selected' : '' }}>SAFT</option>
                                            <option value="fe" {{ $entidade->tipo_facturacao == "fe" ? 'selected' : '' }}>FACTURAÇÃO ELECTRONÍCA</option>
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" value="{{ $entidade->id }}" name="entidade_id">
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
