@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-plus"></i> Internamento de paciente</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('internamentos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Internamentos</li>
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
                    <form action="{{ route('internamentos.store') }}" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    @if (Auth::user()->can('adicionar item conta hospitalar'))
                                    <a href="{{ route('atendimentos.show', $atendimento->id) }}" class="btn btn-light-primary">Actualizar conta Hospitalar do paciente</a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body row">
                                <div class="col-12 col-md-5">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label for="paciente_id" class="form-label">Pacientes</label>
                                            <div class="input-group mb-3">
                                                <select type="text" class="form-control select2" style="width: 100%" id="paciente_id" name="paciente_id">
                                                    <option value="">{{ __('messages.escolher') }}</option>
                                                    @foreach ($pacientes as $item)
                                                    <option value="{{ $item->id ?? "" }}" {{ $item->id == $atendimento->cliente_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label for="data_internacao" class="form-label">Data da Internação</label>
                                            <input type="date" class="form-control" id="data_internacao" value="{{ old('data_internacao') ?? date("Y-m-d") }}" name="data_internacao">
                                        </div>

                                        <input type="hidden" value="{{ $atendimento->id ?? null }}" name="atendimento_id">

                                        <div class="col-12 col-md-3">
                                            <label for="data_alta" class="form-label">Data Previa para Alta</label>
                                            <input type="date" class="form-control" id="data_alta" name="data_alta">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="equipa_id" class="form-label">Equipa</label>
                                            <div class="input-group mb-3">
                                                <select type="text" class="form-control " id="equipa_id" name="equipa_id">
                                                    <option value="">{{ __('messages.escolher') }} </option>
                                                    @foreach ($equipas as $item)
                                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="leito_id" class="form-label">Leitos</label>
                                            <div class="input-group mb-3">
                                                <select type="text" class="form-control " id="leito_id" name="leito_id">
                                                    <option value="">{{ __('messages.escolher') }} </option>
                                                    @foreach ($leitos as $item)
                                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }} /
                                                        {{ $item->quarto ? $item->quarto->nome : "" }} /
                                                        {{ $item->quarto->andar ? $item->quarto->andar->nome : "" }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="diagnostico_inicial" class="form-label">Diagnóstico
                                                Inicial</label>
                                            <div class="input-group mb-3">
                                                <textarea class="form-control" name="diagnostico_inicial" id="diagnostico_inicial" cols="30" rows="5" placeholder="Descrição do Diagnóstico: "></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="motivo" class="form-label">Motivo</label>
                                            <div class="input-group mb-3">
                                                <textarea class="form-control" name="motivo" id="motivo" cols="30" rows="5" placeholder="Descrição do motivo da internação: "></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-7">
                                    <h5>Prescrição Medicamentosa</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tblMedicamentos">
                                            <thead>
                                                <tr>
                                                    <th>Medicamento</th>
                                                    <th>Dose</th>
                                                    <th>Via</th>
                                                    <th>Frequência</th>
                                                    <th>Duração</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-light-success" id="novoMedicamento">
                                        Adicionar Medicamento
                                    </button>
                                </div>
                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar internamento') || Auth::user()->can('consultorio'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
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
    let contador = 0;
    let produtos = @json($produtos);
    let vias = @json($vias);
    let duracoes = @json($duracoes);
    let frequencias = @json($frequencias);

    let optionsProdutos = '';
    let optionsVias = '';
    let optionsDuracao = '';
    let optionsFrequencia = '';

    produtos.forEach(i => {
        optionsProdutos += `<option value="${i.nome}">${i.nome}</option>`;
    });

    vias.forEach(i => {
        optionsVias += `<option value="${i.nome}">${i.nome}</option>`;
    });

    duracoes.forEach(i => {
        optionsDuracao += `<option value="${i.nome}">${i.nome}</option>`;
    });

    frequencias.forEach(i => {
        optionsFrequencia += `<option value="${i.nome}">${i.nome}</option>`;
    });

    $('#novoMedicamento').click(function(e) {
        e.preventDefault();

        contador++;
        $('#tblMedicamentos tbody').append(`
            <tr>
                <td>
                    <select class="form-control select2" id="medicamento_id${contador}" name="medicamentos[${contador}][medicamento_id]">
                        ${optionsProdutos}
                    </select>
                </td>
                <td>
                    <input class="form-control" name="medicamentos[${contador}][dose]">
                </td>
                <td>
                    <select class="form-control select2" id="via${contador}" name="medicamentos[${contador}][via]">
                        ${optionsVias}
                    </select>
                </td>
                <td>
                    <select class="form-control select2" id="frequencia${contador}" name="medicamentos[${contador}][frequencia]">
                        ${optionsFrequencia}
                    </select>
                </td>
                <td>
                    <select class="form-control select2" id="duracao${contador}" name="medicamentos[${contador}][duracao]">
                        ${optionsDuracao}
                    </select>
                </td>
                <td>
                    <button class="btn btn-danger remover"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `);
        $('.select2').select2({
            width: '100%'
        });
    });

    $(document).on('click', '.remover', function() {
        $(this).closest('tr').remove();
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

                    showMessage('Sucesso!', response.message, 'success');

                    const internamentoIndex = `{!! route('internamentos.index') !!}`;

                    window.location.href = internamentoIndex;

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
