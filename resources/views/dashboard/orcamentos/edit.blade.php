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
                        <li class="breadcrumb-item"><a href="{{ route('orcamentos.index') }}">{{ __('messages.voltar') }}</a></li>
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
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('orcamentos.update', $orcamento->id) }}" method="post" class="">
                            @method('put')
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="nome" class="form-label"> {{ __('messages.designacao') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control  @error('nome') is-invalid @enderror" name="nome" id="nome" value="{{  $orcamento->nome ?? old('nome') }}" placeholder="{{ __('messages.designacao') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control  @error('data_inicio') is-invalid @enderror" name="data_inicio" id="data_inicio" value="{{ $orcamento->data_inicio ?? old('data_inicio') }}" placeholder="Informe a Data">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control  @error('data_final') is-invalid @enderror" name="data_final" id="data_final" value="{{ $orcamento->data_final ?? old('data_final') }}" placeholder="Informe a Data">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="responsavel_usuario_id" class="form-label">Responsável</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('responsavel_usuario_id') is-invalid @enderror" id="responsavel_usuario_id" name="responsavel_usuario_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($funcionarios as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $orcamento->responsavel_usuario_id == $item->id  ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="exercicio_id" class="form-label">{{ __('messages.exercicio') }}</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('exercicio_id') is-invalid @enderror" id="exercicio_id" name="exercicio_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($exercicios as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $orcamento->exercicio_id == $item->id  ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="periodo_id" class="form-label">{{ __('messages.periodo') }}</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('periodo_id') is-invalid @enderror" id="periodo_id" name="periodo_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($periodos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $orcamento->periodo_id == $item->id  ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('status') is-invalid @enderror" id="status" name="status">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            <option value="rascunho" {{ $orcamento->status == "rascunho" ? "selected" : "" }}>Rascunho</option>
                                            <option value="aprovado" {{ $orcamento->status == "aprovado" ? "selected" : "" }}>Aprovado</option>
                                            <option value="rejeitado" {{ $orcamento->status == "rejeitado" ? "selected" : "" }}>Rejeitado</option>
                                            <option value="finalizado" {{ $orcamento->status == "finalizado" ? "selected" : "" }}>Finalizado</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo" class="form-label">Tipo</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('tipo') is-invalid @enderror" id="tipo" name="tipo">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            <option value="anual" {{ $orcamento->tipo == "anual" ? "selected" : "" }}>Anual</option>
                                            <option value="trimestral" {{ $orcamento->tipo == "trimestral" ? "selected" : "" }}>Trimestral</option>
                                            <option value="mensal" {{ $orcamento->tipo == "mensal" ? "selected" : "" }}>Mensal</option>
                                            <option value="projeto" {{ $orcamento->tipo == "projeto" ? "selected" : "" }}>Projeto</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="descricao" class="form-label">{{ __('messages.descricao') }} </label>
                                    <div class="input-group mb-3">
                                        <textarea name="descricao" class="form-control" id="descricao" cols="30" rows="4">{{ $orcamento->descricao ?? old('descricao') }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos'))
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
            //=======================================================

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
