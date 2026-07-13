@extends('layouts.app')

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
                        <li class="breadcrumb-item"><a href="{{ route('ocorrencias.index') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('ocorrencias.update', $ocorrencia->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="posto_id" class="form-label">Postos</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="posto_id" name="posto_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($postos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == ($ocorrencia->posto_id ?? "") ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo_ocorrencia_id" class="form-label">Tipo de Posto</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="tipo_ocorrencia_id" name="tipo_ocorrencia_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($tipos_ocorrencias as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == ($ocorrencia->tipo_ocorrencia_id ?? "") ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_at" class="form-label">Data</label>
                                    <div class="input-group mb-3">
                                        <input type="date" name="data_at" id="data_at" value="{{ $ocorrencia->data_at ?? date("Y-m-d") }}" class="form-control" placeholder="Data da ocorrência" />
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="hora_at" class="form-label">Hora</label>
                                    <div class="input-group mb-3">
                                        <input type="time" name="hora_at" id="hora_at" value="{{ $ocorrencia->hora_at ?? date("H:i") }}" class="form-control" placeholder="Data da ocorrência" />
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <div class="input-group mb-3">
                                        <textarea name="descricao" id="descricao" class="form-control" placeholder="Descrição da Ocorrência">{{ $ocorrencia->descricao }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('editar todos'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </form>
                    </div>
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
