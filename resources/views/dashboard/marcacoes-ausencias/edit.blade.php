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
                        <li class="breadcrumb-item"><a href="{{ route('marcacoes-ausencias.index') }}">{{ __('messages.voltar') }}</a></li>
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
                    <form action="{{ route('marcacoes-ausencias.update', $ausencia->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-6">
                                        <label for="funcionario_id" class="form-label">{{ __('messages.funcionario') }}</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control @error('funcionario_id') is-invalid @enderror" id="funcionario_id" name="funcionario_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($funcionarios as $item)
                                                <option value="{{ $item->id ?? "" }}" {{  $ausencia->funcionario_id == $item->id ? 'selected' : '' }}>{{ $item->numero_mecanografico }} - {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio" value="{{ $ausencia->data_inicio }}" name="data_inicio" placeholder="Informe a data inicio">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control @error('data_final') is-invalid @enderror" id="data_final" value="{{ $ausencia->data_final }}" name="data_final" placeholder="Informe a data final">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="data_referenciada" class="form-label">Data Referênciada</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control @error('data_referenciada') is-invalid @enderror" id="data_referenciada" value="{{ $ausencia->data_referenciada }}" name="data_referenciada" placeholder="Informe a data Referências">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="ausencia_id" class="form-label">Motivos</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control @error('ausencia_id') is-invalid @enderror" id="ausencia_id" name="ausencia_id">
                                                <option value="">{{ __('messages.escolher') }} </option>
                                                @foreach ($motivos as $item)
                                                <option value="{{ $item->id ?? "" }}" {{  $ausencia->ausencia_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar subsidio')) --}}
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                {{-- @endif --}}
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
