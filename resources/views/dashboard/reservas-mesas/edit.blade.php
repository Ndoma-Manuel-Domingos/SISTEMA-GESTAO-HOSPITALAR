@extends('layouts.app')

@section('content')

<style>
    .fc-toolbar h2 {
        text-transform: capitalize;
    }

    .fc-day-header {
        text-transform: capitalize;
    }

</style>

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
                        <li class="breadcrumb-item"><a href="{{ route('reservas-mesas.index') }}">{{ __('messages.voltar') }}</a></li>
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
                    <form action="{{ route('reservas-mesas.update', $reserva->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="cliente_id" class="form-label"> {{ __('messages.clientes') }} </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('clientes.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" class="form-control select2 @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id">
                                                @foreach ($clientes as $item)
                                                <option value="{{ $item->id ?? "" }}" {{ $item->id == $reserva->cliente_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="mesa_id" class="form-label">{{ __('messages.mesa') }}</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('mesas.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" class="form-control select2 @error('mesa_id') is-invalid @enderror" id="mesa_id" name="mesa_id">
                                                @foreach ($mesas as $item)
                                                {{-- <option value="{{ $item->id ?? "" }}" {{ $item->id == $reserva->mesa_id ? 'selected' : '' }}>{{ $item->nome }}</option> --}}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_entrada" class="form-label">{{ __('messages.data') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control  @error('data_entrada') is-invalid @enderror" name="data_entrada" id="data_entrada" value="{{ $reserva->data_entrada ?? old('data_entrada') }}" placeholder="Informe a data entrada">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="total_mesas" class="form-label">{{ __('messages.mesas') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control  @error('total_mesas') is-invalid @enderror" name="total_mesas" id="total_mesas" value="{{ $reserva->total_mesas ?? old('total_mesas') }}" placeholder="Informe o total de mesas">
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-3">
                                        <label for="produto_id" class="form-label">{{ __('messages.servico') }}</label>
                                        <div class="input-group mb-3">

                                            <select type="text" class="form-control @error('produto_id') is-invalid @enderror" id="produto_id" name="produto_id">
                                                @foreach ($produtos as $item)
                                                <option value="{{ $item->id ?? "" }}" {{ $item->id == $reserva->produto_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="exercicio_id" class="form-label"> {{ __('messages.exercicio') }} </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('exercicios.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" class="form-control select2 @error('exercicio_id') is-invalid @enderror" id="exercicio_id" name="exercicio_id">
                                                @foreach ($exercicios as $item)
                                                <option value="{{ $item->id ?? "" }}" {{ $item->id == $reserva->exercicio_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="periodo_id" class="form-label"> {{ __('messages.periodo') }} </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('periodos.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" class="form-control @error('periodo_id') is-invalid @enderror" id="periodo_id" name="periodo_id">
                                                @foreach ($periodos as $item)
                                                <option value="{{ $item->id ?? "" }}" {{ $item->id == $reserva->periodo_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="preco_unitario" class="form-label">Preço Unitário</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control  @error('preco_unitario') is-invalid @enderror" name="preco_unitario" id="preco_unitario" value="{{ $reserva->valor_unitario ?? old('preco_unitario') ?? 0 }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="total_factura" class="form-label">Total da Factura</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control  @error('total_factura') is-invalid @enderror" name="total_factura" id="total_factura" value="{{ $reserva->valor_total ?? old('total_factura') ?? 0 }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="criancas" class="form-label">Tem Criança</label>
                                        <div class="input-group mb-3">
                                            <select type="text" class="form-control select2 @error('criancas') is-invalid @enderror" id="criancas" name="criancas">
                                                <option value="0" {{ $reserva->criancas == "0" ? 'selected' : '' }}> {{ __('messages.nao') }} </option>
                                                <option value="1" {{ $reserva->criancas == "1" ? 'selected' : '' }}> {{ __('messages.sim') }} </option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-2">
                                        <label for="total_pessoas" class="form-label">Nº de Adultos</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control  @error('total_pessoas') is-invalid @enderror" name="total_pessoas" id="total_pessoas" value="{{ $reserva->total_pessoas ?? old('total_pessoas') ?? 1 }}" placeholder="Informe o total de dias">
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-2">
                                        <label for="numero_criancas" class="form-label">Nº de Crianças</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control  @error('numero_criancas') is-invalid @enderror" name="numero_criancas" id="numero_criancas" value="{{ $reserva->numero_criancas ?? old('numero_criancas')}}" placeholder="Informe o número de crianças">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <label for="observacao" class="form-label">Observação (Opcional)</label>
                                        <div class="input-group mb-3">
                                            <textarea name="observacao" class="form-control" id="observacao" placeholder="Informe uma Observação" rows="3">{{ $reserva->observacao ?? old('observacao') ?? "" }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar reserva'))
                                <button type="submit" class="btn btn-light-primary">Actualizar a Reserva</button>
                                @endif
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
    // Elementos para exibir os resultados
    const totalMesas = document.getElementById('total_mesas');
    const total_factura = document.getElementById('total_factura');
    const preco_unitario = document.getElementById('preco_unitario');

    $('#produto_id').on('change', function() {
        const produto_id = $(this).val();

        if (produto_id) {
            fetch('../recuperar-produto-por-id/' + produto_id)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        preco_unitario.value = data.preco_venda;
                        total_factura.value = data.preco_venda * Number(totalMesas.value || 1);
                    }
                })
                .catch(error => console.error('Erro ao buscar produto:', error));
        }
    });

    $('#total_mesas').on('input', function() {
        const total_mesas = $(this).val();
        if (total_mesas > 0) {
            total_factura.value = preco_unitario.value * Number(totalMesas.value || 1);
        }
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
