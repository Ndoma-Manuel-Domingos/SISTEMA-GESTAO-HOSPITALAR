@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Marcar presença</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('controle-presencas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Presença</li>
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
                    <form method="POST" action="{{ route('controle-presencas.store') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-4 col-lg-4">
                                        <label for="funcionario_id" class="form-label">Funcionarios</label>
                                        <div class="input-group mb-3">
                                            <select type="text" class="form-control select2 @error('funcionario_id') is-invalid @enderror" name="funcionario_id[]" multiple="multiple" id="funcionario_id">
                                                @foreach ($funcionarios as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4">
                                        <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                        <div class="input-group mb-3">
                                            <select type="text" class="form-control select2 @error('status') is-invalid @enderror" name="status" id="status">
                                                <option value="1">Presente</option>
                                                <option value="0">Falta</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-4">
                                        <label for="date_at" class="form-label"> {{ __('messages.data') }} </label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control @error('date_at') is-invalid @enderror" value="{{ date("Y-m-d") }}" name="date_at" id="date_at">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                            </div>
                        </div>
                    </form>
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
