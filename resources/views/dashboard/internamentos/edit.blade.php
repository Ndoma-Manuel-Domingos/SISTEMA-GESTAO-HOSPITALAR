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
                        <li class="breadcrumb-item"><a href="{{ route('internamentos.index') }}">Home</a></li>
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
                    <form action="{{ route('internamentos.update', $internamento->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header">
                                <h4>Internamentos</h4>
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
                                                    <option value="{{ $item->id ?? "" }}" {{ $internamento->paciente_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label for="data_internacao" class="form-label">Data da Internação</label>
                                            <input type="date" class="form-control" value="{{ $internamento->data_internacao }}" id="data_internacao" name="data_internacao">
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label for="data_alta" class="form-label">Data Previa para Alta</label>
                                            <input type="date" class="form-control" value="{{ $internamento->data_alta }}" id="data_alta" name="data_alta">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="equipa_id" class="form-label">Equipa</label>
                                            <div class="input-group mb-3">
                                                <select type="text" class="form-control " id="equipa_id" name="equipa_id">
                                                    <option value="">{{ __('messages.escolher') }} </option>
                                                    @foreach ($equipas as $item)
                                                    <option value="{{ $item->id ?? "" }}" {{ $internamento->equipa_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nome }}</option>
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
                                                    <option value="{{ $item->id ?? "" }}" {{ $item->id == $internamento->leito_id ? 'selected' : '' }}>
                                                        {{ $item->nome }} - {{ $item->status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="diagnostico_inicial" class="form-label">Diagnóstico
                                                Inicial</label>
                                            <div class="input-group mb-3">
                                                <textarea class="form-control" name="diagnostico_inicial" id="diagnostico_inicial" cols="30" rows="5" placeholder="Descrição do Diagnóstico: ">{{ $internamento->diagnostico_inicial }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="motivo" class="form-label">Motivo</label>
                                            <div class="input-group mb-3">
                                                <textarea class="form-control" name="motivo" id="motivo" cols="30" rows="5" placeholder="Descrição do motivo da internação: ">{{ $internamento->motivo }}</textarea>
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
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar internamento'))
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

    @php
    $planos = $internamento->plano_internamento;
    @endphp

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

    let medicamentosExistentes = @json($planos ? $planos : []);

    function montarOptions(lista, selecionado = '') {
        let html = '';
        lista.forEach(item => {
            let selected = item.nome == selecionado ? 'selected' : '';
            html += `
                <option value="${item.nome}" ${selected}>
                    ${item.nome}
                </option>
            `;
        });
        return html;
    }

    // Criar uma linha
    function adicionarMedicamento(item = null) {

        contador++;

        let id = item ? item.id : '';
        let medicamento = item ? item.medicamento : '';
        let dose = item ? item.dose : '';
        let via = item ? item.via : '';
        let frequencia = item ? item.frequencia : '';
        let duracao = item ? item.duracao : '';

        let linha = `
            <tr>
                <td>
                    <input type="hidden" name="medicamentos[${contador}][id]" value="${id}">
                    <select class="form-control select2" name="medicamentos[${contador}][medicamento_id]">
                        ${montarOptions(produtos, medicamento)}
                    </select>
                </td>
                
                <td>
                    <input type="text" class="form-control" name="medicamentos[${contador}][dose]" value="${dose}" placeholder="Ex: 500 mg">
                </td>
                
                <td>
                    <select class="form-control select2" name="medicamentos[${contador}][via]">
                        ${montarOptions(vias,via)}
                    </select>
                </td>
                
                <td>
                    <select class="form-control select2" name="medicamentos[${contador}][frequencia]">
                        ${montarOptions(frequencias,frequencia)}
                    </select>
                </td>
                <td>
                    <select class="form-control select2" name="medicamentos[${contador}][duracao]">
                        ${montarOptions(duracoes,duracao)}
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remover">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            `;

        $('#tblMedicamentos').append(linha);

        // activar select2
        $('.select2').select2({
            width: '100%'
            , theme: 'bootstrap4'
        });
    }

    // Abrir pagina EDIT
    $(document).ready(function() {
        if (medicamentosExistentes.length > 0) {
            medicamentosExistentes.forEach(function(item) {
                adicionarMedicamento(item);
            });
        }
    });

    // Novo medicamento
    $('#novoMedicamento').click(function(e) {
        e.preventDefault();
        adicionarMedicamento();
    });

    // Remover linha
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
