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
                        <li class="breadcrumb-item"><a href="{{ route('reservas.index') }}">{{ __('messages.voltar') }}</a></li>
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
                    <form action="{{ route('reservas.update', $reserva->id) }}" method="post">
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

                                    {{-- <div class="col-12 col-md-3">
                                        <label for="quarto_id" class="form-label">{{ __('messages.quarto') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('quartos.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select type="text" class="form-control select2 @error('quarto_id') is-invalid @enderror" id="quarto_id" name="quarto_id">
                                            @foreach ($quartos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == $reserva->quarto_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}


                                <div class="col-12 col-md-3">
                                    <label for="hora_entrada" class="form-label">Hora de Entrada</label>
                                    <div class="input-group mb-3">
                                        <input type="time" class="form-control  @error('hora_entrada') is-invalid @enderror" name="hora_entrada" id="hora_entrada" value="{{ $reserva->hora_entrada ?? old('hora_entrada') }}" placeholder="Hora da Entrada">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="hora_saida" class="form-label">Hora de Saída</label>
                                    <div class="input-group mb-3">
                                        <input type="time" class="form-control  @error('hora_saida') is-invalid @enderror" name="hora_saida" id="hora_saida" value="{{ $reserva->hora_saida ?? old('hora_saida') }}" placeholder="Hora da Entrada">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_entrada" class="form-label">Data de Entrada</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control  @error('data_entrada') is-invalid @enderror" name="data_entrada" id="data_entrada" value="{{ $reserva->data_inicio ?? old('data_entrada') }}" placeholder="Informe a quarto">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_saida" class="form-label">Data de Saída</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control  @error('data_saida') is-invalid @enderror" name="data_saida" id="data_saida" value="{{ $reserva->data_final ?? old('data_saida') }}" placeholder="Informe a quarto">
                                    </div>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="total_dias" class="form-label">Total de Dias</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control  @error('total_dias') is-invalid @enderror" name="total_dias" id="total_dias" value="{{ $reserva->total_dias ?? old('total_dias') }}" placeholder="Informe o total de dias">
                                    </div>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="motivo_reserva_id" class="form-label">Motivo da Reserva</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('motivos-reservas.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select type="text" class="form-control select2 @error('motivo_reserva_id') is-invalid @enderror" id="motivo_reserva_id" name="motivo_reserva_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($motivos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == $reserva->motivo_reserva_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="tarefario_id" class="form-label">Terifários</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('tarefarios.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select type="text" class="form-control @error('tarefario_id') is-invalid @enderror" id="tarefario_id" name="tarefario_id">
                                            @foreach ($tarefarios as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == $reserva->tarefario_id ? 'selected' : '' }}>{{ $item->nome }}</option>
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

                        <input type="hidden" id="total_segundos" name="total_segundos" class="form-control" readonly>
                        <input type="hidden" id="total_minutos" name="total_minutos" class="form-control" readonly>
                        <input type="hidden" id="total_horas" name="total_horas" class="form-control" readonly>
                        <input type="hidden" id="total_semanas" name="total_semanas" class="form-control" readonly>
                        <input type="hidden" id="total_quinzenas" name="total_quinzenas" class="form-control" readonly>
                        <input type="hidden" id="total_meses" name="total_meses" class="form-control" readonly>
                        <input type="hidden" id="total_anos" name="total_anos" class="form-control" readonly>

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
    const totalSegundos = document.getElementById('total_segundos');
    const totalMinutos = document.getElementById('total_minutos');
    const totalHoras = document.getElementById('total_horas');
    const totalSemanas = document.getElementById('total_semanas');
    const totalQuinzenas = document.getElementById('total_quinzenas');
    const totalMeses = document.getElementById('total_meses');
    const totalAnos = document.getElementById('total_anos');
    const totalDias = document.getElementById('total_dias');
    const total_factura = document.getElementById('total_factura');
    const preco_unitario = document.getElementById('preco_unitario');


    document.addEventListener('DOMContentLoaded', function() {
        const dataEntrada = document.getElementById('data_entrada');
        const dataSaida = document.getElementById('data_saida');

        function calcularIntervalos() {
            const entrada = new Date(dataEntrada.value);
            const saida = new Date(dataSaida.value);

            if (!isNaN(entrada) && !isNaN(saida)) {
                const diferencaMs = saida - entrada; // Diferença em milissegundos

                if (diferencaMs >= 0) {
                    const segundos = diferencaMs / 1000; // Converter para segundos
                    const minutos = segundos / 60; // Converter para minutos
                    const horas = minutos / 60; // Converter para horas
                    const dias = horas / 24; // Converter para dias
                    const semanas = dias / 7; // Converter para semanas
                    const quinzenas = dias / 15; // Converter para quinzenas
                    const meses = dias / 30; // Aproximação para meses
                    const anos = dias / 365; // Aproximação para anos

                    // Atualizar os valores nos campos
                    totalSegundos.value = segundos.toFixed(2);
                    totalMinutos.value = minutos.toFixed(2);
                    totalHoras.value = horas.toFixed(2);
                    totalDias.value = dias.toFixed(2);
                    totalSemanas.value = semanas.toFixed(2);
                    totalQuinzenas.value = quinzenas.toFixed(2);
                    totalMeses.value = meses.toFixed(2);
                    totalAnos.value = anos.toFixed(2);
                } else {
                    resetarValores();
                }
            } else {
                resetarValores();
            }
        }

        function resetarValores() {
            // Define todos os campos para 0
            totalSegundos.value = totalMinutos.value = totalHoras.value =
                totalDias.value = totalSemanas.value = totalQuinzenas.value =
                totalMeses.value = totalAnos.value = 0;
        }

        // Adicionar evento de alteração nos campos de data
        dataEntrada.addEventListener('change', calcularIntervalos);
        dataSaida.addEventListener('change', calcularIntervalos);
    });

    $("#quarto_id").change(() => {
        let id = $("#quarto_id").val();
        $.get('../../carregar-tarefarios-quarto/' + id, function(data) {
            $("#tarefario_id").html("")
            $("#tarefario_id").html(data)
        })
    })

    $("#tarefario_id").change(() => {
        let id = $("#tarefario_id").val();
        $.get('../../mais-detalhes-do-tarefarios/' + id, function(data) {

            if (data) {

                preco_unitario.value = data.valor;

                if (data.modo_tarefario == "Por Minutos" && data.tipo_cobranca == "Por Comodo") {
                    total_factura.value = data.valor * total_minutos.value;
                }
                if (data.modo_tarefario == "Por Minutos" && data.tipo_cobranca == "Por Pessoa") {
                    total_factura.value = data.valor * total_minutos.value * total_pessoas.value;
                }
                if (data.modo_tarefario == "Por Dia" && data.tipo_cobranca == "Por Comodo") {
                    total_factura.value = data.valor * totalDias.value;
                }
                if (data.modo_tarefario == "Por Dia" && data.tipo_cobranca == "Por Pessoa") {
                    total_factura.value = data.valor * totalDias.value * total_pessoas.value;
                }
                if (data.modo_tarefario == "Por Hora" && data.tipo_cobranca == "Por Comodo") {
                    total_factura.value = data.valor * total_minutos.total_horas;
                }
                if (data.modo_tarefario == "Por Hora" && data.tipo_cobranca == "Por Pessoa") {
                    total_factura.value = data.valor * total_minutos.total_horas * total_pessoas.value;
                }
                if (data.modo_tarefario == "Por Semana" && data.tipo_cobranca == "Por Comodo") {
                    total_factura.value = data.valor * total_minutos.total_semanas;
                }
                if (data.modo_tarefario == "Por Semana" && data.tipo_cobranca == "Por Pessoa") {
                    total_factura.value = data.valor * total_minutos.total_semanas * total_pessoas.value;
                }
                if (data.modo_tarefario == "Por Quizena" && data.tipo_cobranca == "Por Comodo") {
                    total_factura.value = data.valor * total_minutos.total_quinzenas;
                }
                if (data.modo_tarefario == "Por Quizena" && data.tipo_cobranca == "Por Pessoa") {
                    total_factura.value = data.valor * total_minutos.total_quinzenas * total_pessoas.value;
                }
                if (data.modo_tarefario == "Por Mes" && data.tipo_cobranca == "Por Comodo") {
                    total_factura.value = data.valor * total_minutos.total_meses;
                }
                if (data.modo_tarefario == "Por Mes" && data.tipo_cobranca == "Por Pessoa") {
                    total_factura.value = data.valor * total_minutos.total_meses * total_pessoas.value;
                }
                if (data.modo_tarefario == "Por Ano" && data.tipo_cobranca == "Por Comodo") {
                    total_factura.value = data.valor * total_minutos.total_anos;
                }
                if (data.modo_tarefario == "Por Ano" && data.tipo_cobranca == "Por Pessoa") {
                    total_factura.value = data.valor * total_minutos.total_anos * total_pessoas.value;
                }
            }
        })
    })


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
