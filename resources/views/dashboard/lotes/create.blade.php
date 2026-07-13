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
                        <li class="breadcrumb-item"><a href="{{ route('lotes.index', ['produto_id' => $requests['produto_id'] ?? '']) }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Lote</li>
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
                        <form action="{{ route('lotes.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-6">
                                        <label for="" class="form-label">{{ __('messages.designacao') }}</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select name="produto_id" id="produto_id" class="form-control select2">
                                                @foreach ($produtos as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <p class="text-light-danger">
                                            @error('lote')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label">Lote</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="lote" value="{{ old('lote') }}" placeholder="Informe o lote">
                                        </div>
                                        <p class="text-light-danger">
                                            @error('lote')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="associar_id" class="form-label">Associar o lote os produtos existentes?</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select name="associar_id" id="associar_id" class="form-control select2">
                                                <option value="Nao"> {{ __('messages.nao') }} </option>
                                                <option value="Sim"> {{ __('messages.sim') }} </option>
                                            </select>
                                        </div>
                                        <p class="text-light-danger">
                                            @error('associar_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="" class="form-label">{{ __('messages.codigo_barras') }}</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="codigo_barra" value="{{ old('codigo_barra') }}" placeholder="Informe o codigo_barra">
                                        </div>
                                        <p class="text-light-danger">
                                            @error('codigo_barra')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="" class="form-label">Data Validade</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="date" class="form-control" name="data_validade" value="{{ old('data_validade') }}" placeholder="Informe o data_validade">
                                        </div>
                                        <p class="text-light-danger">
                                            @error('data_validade')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                </div>

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar lote'))
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
    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona todos os campos de entrada na página
        const inputs = document.querySelectorAll('input');

        // Itera sobre cada campo de entrada
        inputs.forEach(input => {
            // Garante que o campo esteja focado quando necessário (opcional)
            input.addEventListener('focus', function() {
                console.log(`Campo ${input.name || input.id} está focado.`);
            });

            // Adiciona evento de keydown para bloquear atalhos específicos
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || (e.ctrlKey && e.key === 'j')) {
                    e.preventDefault(); // Impede o comportamento padrão
                    console.log(`Ação bloqueada no campo ${input.name || input.id}.`);
                }
            });
        });
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
