@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-setting"></i>Configurações de Backup</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Backup</li>
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
                    <form id="backupForm" action="{{ route('backup.settings.save') }}" method="POST">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="folder_path">Pasta de Backup (caminho absoluto)</label>
                                            <input type="text" name="folder_path" id="folder_path" class="form-control" value="{{ $setting->folder_path }}">
                                            <small class="form-text text-muted">Exemplo: C:\backups\meu_sistema — o servidor (Laravel) precisa ter permissão de escrita.</small>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="retain">Manter</label>
                                            <input type="number" name="retain" id="retain" class="form-control" value="{{ $setting->retain }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="frequency_minutes">Frequência (minutos)</label>
                                            <input type="number" name="frequency_minutes" id="frequency_minutes" class="form-control" value="{{ $setting->frequency_minutes }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="tipo_mysql">Escolher O Tipo de configuração do computador para backups</label>
                                            <select name="tipo_mysql" id="tipo_mysql" class="form-control">
                                                <option value="padrao" {{ $setting->tipo_mysql == 'padrao' ? 'selected' : '' }}>Padrão</option>
                                                <option value="definido" {{ $setting->tipo_mysql == 'definido' ? 'selected' : '' }}>Definido</option>
                                                <option value="outro" {{ $setting->tipo_mysql == 'outro' ? 'selected' : '' }}>Outros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <div class="form-check">
                                            <label for="enabled">Clica Aqui para Habilitar Backup Automatico</label> <br>
                                            <input type="checkbox" class="form-check-input" name="enabled" id="enabled" {{ $setting->enabled ? 'checked' : '' }}>
                                            <label class="form-check-label" for="enabled">Habilitar backups automáticos</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-light-primary mt-3" type="submit">{{ __('messages.salvar') }}</button>
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
