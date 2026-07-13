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
                        <li class="breadcrumb-item"><a href="{{ route('tarefarios.index') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('tarefarios.update', $tarefario->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">

                                <div class="col-12 col-md-6">
                                    <label for="nome" class="form-label"> {{ __('messages.designacao') }} </label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ $tarefario->nome ?? old('nome') }}" placeholder="Informe a Conta">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">{{ __('messages.imposto') }}<span class="text-light-danger">*</span></label>
                                    <div class="input-group mb-3">

                                        <select type="text" class="form-control" name="imposto" id="imposto" required>
                                            @foreach ($impostos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $tarefario->imposto_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('imposto')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Motivo de Isenção <span class="text-light-danger">*</span></label>
                                    <div class="input-group mb-3">

                                        <select type="text" class="form-control" name="motivo_isencao" id="motivo_isencao">
                                            @foreach ($motivos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $tarefario->motivo_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('motivo_isencao')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="valor" class="form-label">{{ __('messages.valor') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control  @error('valor') is-invalid @enderror" name="valor" id="valor" value="{{ $tarefario->preco ?? old('valor') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="valor_com_iva" class="form-label">Preço Com IVA<span class="text-light-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="valor_com_iva" name="valor_com_iva" value="{{ $tarefario->preco_venda ?? old('valor_com_iva') }}" placeholder="Preço Com IVA">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('valor_com_iva')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="modo_tarefario" class="form-label">Modo de Tarifário</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control @error('modo_tarefario') is-invalid @enderror" id="modo_tarefario" name="modo_tarefario">
                                            <option value="">{{ __('messages.activo') }} </option>
                                            <option value="Por Minutos" {{ $tarefario->modo_tarefario == 'Por Minutos' ? 'selected' : '' }}>Por
                                                Minutos</option>
                                            <option value="Por Hora" {{ $tarefario->modo_tarefario == 'Por Hora' ? 'selected' : '' }}>Por
                                                Hora</option>
                                            <option value="Por Dia" {{ $tarefario->modo_tarefario == 'Por Dia' ? 'selected' : '' }}>Por Dia
                                            </option>
                                            <option value="Por Semana" {{ $tarefario->modo_tarefario == 'Por Semana' ? 'selected' : '' }}>Por
                                                Semana</option>
                                            <option value="Por Quizena" {{ $tarefario->modo_tarefario == 'Por Quizena' ? 'selected' : '' }}>Por
                                                Quizena</option>
                                            <option value="Por Mes" {{ $tarefario->modo_tarefario == 'Por Mes' ? 'selected' : '' }}>Por Mês
                                            </option>
                                            <option value="Por Ano" {{ $tarefario->modo_tarefario == 'Por Ano' ? 'selected' : '' }}>Por Ano
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo_cobranca" class="form-label">Tipo Cobrança</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control @error('tipo_cobranca') is-invalid @enderror" id="tipo_cobranca" name="tipo_cobranca">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="Por Comodo" {{ $tarefario->tipo_cobranca == 'Por Comodo' ? 'selected' : '' }}>Por
                                                Comodo</option>
                                            <option value="Por Pessoa" {{ $tarefario->tipo_cobranca == 'Por Pessoa' ? 'selected' : '' }}>Por
                                                Pessoa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                            <option value="activo" {{ $tarefario->status == 'activo' ? 'selected' : '' }}>{{ __('messages.activo') }} </option>
                                            <option value="desactivo" {{ $tarefario->status == 'desactivo' ? 'selected' : '' }}>Desactivo
                                            </option>
                                        </select>
                                    </div>
                                </div>


                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar andar'))
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
    function valorImposto() {
        var elem = $("#imposto").val();
        if (elem == '1') {
            return 0;
        } else if (elem == '2') {
            return 2;
        } else if (elem == '3') {
            return 5;
        } else if (elem == '4') {
            return 7;
        } else if (elem == '5') {
            return 14;
        } else {
            return 14;
        }
    }

    $("#valor").on('input', function() {
        calcularPreco(valorImposto());
    });


    $("#imposto").change(function(eventObject) {
        var elem = $(this).val();
        if (elem == '1') {
            calcularPreco(0);
        } else if (elem == '2') {
            calcularPreco(2);
        } else if (elem == '3') {
            calcularPreco(5);
        } else if (elem == '4') {
            calcularPreco(7);
        } else if (elem == '5') {
            calcularPreco(14);
        } else {
            calcularPreco(14);
        }
    });


    function calcularPreco(imposto) {
        // var tipoImposto = $("#imposto").val();
        if (imposto == 0) {

            $("#valor_com_iva").val($("#valor").val());

        } else {

            var valorDigitado = parseInt($("#valor").val());
            var valor = valorDigitado + (valorDigitado * (imposto / 100));

            $("#valor_com_iva").val(valor);

            var precoVenda = valorDigitado + (valorDigitado * (imposto / 100));
            $("#valor_com_iva").val(precoVenda);

        }
    }


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
