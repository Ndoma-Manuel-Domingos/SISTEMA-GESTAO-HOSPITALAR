@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $titulo }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('categorias-cargos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ $descricao }}</li>
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
                        <form action="{{ route('configuracao-rh.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="horas_diarias" class="form-label">Hora diárias</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="horas_diarias" id="horas_diarias" value="{{ $configuracao->horas_diarias ?? old('horas_diarias') }}" placeholder="Informe a hora diárias">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('horas_diarias')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="horas_semanais" class="form-label">Horas Semanais</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="horas_semanais" id="horas_semanais" value="{{ $configuracao->horas_semanais ?? old('horas_semanais') }}" placeholder="Informe a horas semanais">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('horas_semanais')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="caixa_pagamento_id" class="form-label text-right">Caixas</label>
                                    <select class="form-control select2" id="caixa_pagamento_id" name="caixa_pagamento_id">
                                        @foreach ($caixas as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $configuracao->caixa_pagamento_id ?? ""  == $item->id ? 'selected': '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-light-danger col-sm-3">
                                        @error('caixa_pagamento_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="banco_pagamento_id" class="form-label text-right">Caixas</label>
                                    <select class="form-control select2" id="banco_pagamento_id" name="banco_pagamento_id">
                                        @foreach ($bancos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $configuracao->banco_pagamento_id ?? "" == $item->id ? 'selected': '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-light-danger col-sm-3">
                                        @error('banco_pagamento_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="dispesa_pagamento_id" class="form-label text-right">Dispesas</label>
                                    <select class="form-control select2" id="dispesa_pagamento_id" name="dispesa_pagamento_id">
                                        @foreach ($dispesas as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $configuracao->dispesa_pagamento_id ?? ""  == $item->id ? 'selected': '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-light-danger col-sm-3">
                                        @error('dispesa_pagamento_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <input type="hidden" name="configuracao_id" value="{{ $configuracao->id ?? "" }}">

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar categoria'))
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
