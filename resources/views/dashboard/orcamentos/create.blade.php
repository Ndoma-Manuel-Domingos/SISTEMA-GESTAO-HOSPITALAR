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
                        <form action="{{ route('orcamentos.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-3">
                                        <label for="nome" class="form-label"> {{ __('messages.designacao') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control  @error('nome') is-invalid @enderror" name="nome" id="nome" value="{{  old('nome') }}" placeholder="{{ __('messages.designacao') }}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control  @error('data_inicio') is-invalid @enderror" name="data_inicio" id="data_inicio" value="{{ old('data_inicio') }}" placeholder="Informe a Data">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control  @error('data_final') is-invalid @enderror" name="data_final" id="data_final" value="{{ old('data_final') }}" placeholder="Informe a Data">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="responsavel_usuario_id" class="form-label">Responsável</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control select2 @error('responsavel_usuario_id') is-invalid @enderror" id="responsavel_usuario_id" name="responsavel_usuario_id">
                                                <option value="">{{ __('messages.escolher') }}</option>
                                                @foreach ($funcionarios as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
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
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
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
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control select2 @error('status') is-invalid @enderror" id="status" name="status">
                                                <option value="">{{ __('messages.escolher') }}</option>
                                                <option value="rascunho">Rascunho</option>
                                                <option value="aprovado">Aprovado</option>
                                                <option value="rejeitado">Rejeitado</option>
                                                <option value="finalizado">Finalizado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="tipo" class="form-label">Tipo</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control select2 @error('tipo') is-invalid @enderror" id="tipo" name="tipo">
                                                <option value="">{{ __('messages.escolher') }}</option>
                                                <option value="anual">Anual</option>
                                                <option value="trimestral">Trimestral</option>
                                                <option value="mensal">Mensal</option>
                                                <option value="projeto">Projeto</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <h5>Itens do Orçamento</h5>
                                        <div id="itens-wrapper">
                                            <div class="item row mb-2">
                                                <div class="col-md-3">
                                                    <input type="text" name="itens[0][descricao]" class="form-control" placeholder="Descrição" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" step="0.01" name="itens[0][valor_estimado]" class="form-control" placeholder="Valor" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <select name="itens[0][categoria]" class="form-control">
                                                        <option value="receita">Receita</option>
                                                        <option value="despesa">Despesa</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-light-danger btn-remove">X</button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-light-secondary mb-3" id="add-item">+ Adicionar Item</button>
                                    </div>


                                    <div class="col-12 col-md-12">
                                        <label for="descricao" class="form-label">{{ __('messages.descricao') }} </label>
                                        <div class="input-group mb-3">
                                            <textarea name="descricao" class="form-control" id="descricao" cols="30" rows="4">{{ old('descricao') }}</textarea>
                                        </div>
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
    let index = 1;
    document.getElementById('add-item').addEventListener('click', function() {
        const wrapper = document.getElementById('itens-wrapper');
        const itemHTML = `
        <div class="item row mb-2">
            <div class="col-md-3 col-12">
                <input type="text" name="itens[${index}][descricao]" class="form-control" placeholder="Descrição" required>
            </div>
            <div class="col-md-3 col-12">
                <input type="number" step="0.01" name="itens[${index}][valor_estimado]" class="form-control" placeholder="Valor" required>
            </div>
            <div class="col-md-3 col-12">
                <select name="itens[${index}][categoria]" class="form-control">
                    <option value="receita">Receita</option>
                    <option value="despesa">Despesa</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-light-danger btn-remove">X</button>
            </div>
        </div>`;
        wrapper.insertAdjacentHTML('beforeend', itemHTML);
        index++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove')) {
            e.target.closest('.item').remove();
        }
    });

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
