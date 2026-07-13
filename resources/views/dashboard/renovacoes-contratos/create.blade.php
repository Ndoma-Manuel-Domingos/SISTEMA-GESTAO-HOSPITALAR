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
                        <li class="breadcrumb-item"><a href="{{ route('renovacoes-contratos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Contrato</li>
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
                        <form action="{{ route('renovacoes-contratos.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="contrato_id" class="form-label">Contratos</label>
                                    <div class="input-group mb-3">
                                        <select type="text" style="width: 100%" class="form-control select2 @error('contrato_id') is-invalid @enderror" name="contrato_id" id="contrato_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($contratos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->numero }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="funcionario_id" class="form-label">{{ __('messages.funcionario') }}</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control @error('funcionario_id') is-invalid @enderror" disabled name="funcionario_id" id="funcionario_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($funcionarios as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="tipo_contrato_id" class="form-label">Tipos de Contratos</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 @error('tipo_contrato_id') is-invalid @enderror" name="tipo_contrato_id" id="tipo_contrato_id">
                                            @foreach ($tipos_contratos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" name="data_inicio" id="data_inicio" value="{{ old('data_inicio') ?? 0 }}" placeholder="Informe o contrato">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control @error('data_final') is-invalid @enderror" name="data_final" id="data_final" value="{{ old('data_final') ?? 0 }}" placeholder="Informe o contrato">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="data_envio_previo" class="form-label">Data Envio Previo</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control @error('data_envio_previo') is-invalid @enderror" name="data_envio_previo" id="data_envio_previo" value="{{ old('data_envio_previo') ?? 0 }}" placeholder="Informe o contrato">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="data_demissao" class="form-label">Data de Demissão</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control @error('data_demissao') is-invalid @enderror" name="data_demissao" id="data_demissao" value="{{ old('data_demissao') ?? 0 }}" placeholder="Informe o contrato">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="hora_entrada" class="form-label">Hora Entrada</label>
                                    <div class="input-group mb-3">
                                        <input type="time" class="form-control @error('hora_entrada') is-invalid @enderror" name="hora_entrada" id="hora_entrada" value="{{ old('hora_entrada') ?? 0 }}" placeholder="Informe o contrato">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="hora_saida" class="form-label">Hora Saída</label>
                                    <div class="input-group mb-3">
                                        <input type="time" class="form-control @error('hora_saida') is-invalid @enderror" name="hora_saida" id="hora_saida" value="{{ old('hora_saida') ?? 0 }}" placeholder="Informe o contrato">
                                    </div>
                                </div>


                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                            <option value="activo">{{ __('messages.activo') }} </option>
                                            <option value="desactivo">{{ __('messages.desactivo') }} </option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="situacao_apos_renovacao" class="form-label">Estado Apos Renovação</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control @error('situacao_apos_renovacao') is-invalid @enderror" id="situacao_apos_renovacao" name="situacao_apos_renovacao">
                                            <option value="contratado">Contratado</option>
                                            <option value="nao contratado">Não Contratado</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- <div class="col-12 col-md-4 col-lg-2">
                                    <label for="tipo_contrato_id" class="form-label">Tipo de Funcionário</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 @error('tipo_contrato_id') is-invalid @enderror" name="tipo_contrato_id" id="tipo_contrato_id">
                                            @foreach ($tipos_contratos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                @endforeach
                                </select>
                            </div>
                    </div> --}}

                </div>

                <div class="card-footer">
                    @if (Auth::user()->can('criar todos') || Auth::user()->can('criar contrato'))
                    <button type="submit" class="btn btn-light-primary">Renovar contrato</button>
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

    $("#contrato_id").change(() => {
        let id = $("#contrato_id").val();
        $.get('/renovacoes-contratos/' + id + '/edit', function(data) {

            $("#funcionario_id").val(data.contrato.funcionario_id);

            $("#tipo_contrato_id").val(data.contrato.tipo_contrato_id);
            $("#data_inicio").val(data.contrato.data_inicio);
            $("#data_final").val(data.contrato.data_final);
            $("#data_envio_previo").val(data.contrato.data_envio_previo);
            $("#data_demissao").val(data.contrato.data_demissao);
            $("#hora_entrada").val(data.contrato.hora_entrada);
            $("#hora_saida").val(data.contrato.hora_saida);
            $("#status").val(data.contrato.status);

        })
    })

    window.onload = function() {
        // Obter a data e hora atual
        let agora = new Date();

        // Definir o valor do campo de data
        let dataAtual = agora.toISOString().split('T')[0];
        document.getElementById('data_inicio').value = dataAtual;
        document.getElementById('data_final').value = dataAtual;

        // Definir o valor do campo de hora
        let horas = String(agora.getHours()).padStart(2, '0');
        let minutos = String(agora.getMinutes()).padStart(2, '0');
        let horaAtual = horas + ':' + minutos;
        document.getElementById('hora_entrada').value = horaAtual;
        document.getElementById('hora_saida').value = horaAtual;
    };

    // $("#cargo_id").change(()=>{
    //     let id = $("#cargo_id").val();
    //     $.get('../carregar-salario-cargo/'+id, function(data){
    //       $('#salario').val("");
    //       $('#salario').val(data.cargo.salario_base);
    //     })
    // })

    $("#contrato_id").change(() => {
        let id = $("#contrato_id").val();

        $.get('/carregar-funcionarios-contratos/' + id, function(data) {
            $("#funcionario_id").html("")
            $("#funcionario_id").html(data)
        })
    })

    $("#cargo_id").change(() => {
        let id = $("#cargo_id").val();
        $.get('../carregar-categorias-cargo/' + id, function(data) {
            $("#categoria_id").html("")
            $("#categoria_id").html(data)
        })
    })

    // $("#departamento_id").change(()=>{
    //   let id = $("#departamento_id").val();
    //   $.get('../carregar-cargos/'+id, function(data){
    //     $("#cargo_id").html("")
    //     $("#cargo_id").html(data)
    //   })
    // })

</script>
@endsection
