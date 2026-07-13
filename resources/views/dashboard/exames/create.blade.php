@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Marcar Exame</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('exames.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Exames</li>
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
                    <form action="{{ route('exames.store') }}" method="post">
                        <div class="card">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-6">
                                    <label for="paciente_id" class="form-label">Selecionar Pacientes</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="paciente_id" name="paciente_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($pacientes as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == ($origem->cliente_id ?? $origem->paciente_id ?? '') ? 'selected' : '' }}>
                                                {{ $item->nome }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="prioridade_id" class="form-label">Selecionar Prioridade</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="prioridade_id" id="prioridade_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($prioridades as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == ($origem->prioridade_id ?? $origem->atendimento->prioridade_id ?? '')  ? 'selected' : '' }}>
                                                {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_exame" class="form-label">Data do exame</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" name="data_exame" id="data_exame" value="" placeholder="Informe a Data do exame">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="profissional_saude_id" class="form-label">Selecionar profissional de Saúde</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="profissional_saude_id" id="profissional_saude_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($medicos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == old('profissional_saude_id') ? 'selected' : '' }}>
                                                {{ $item->funcionario->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="request_ordem" value="{{ $request_ordem ?? '' }}">
                                <input type="hidden" name="origem_id" value="{{ $origem ? $origem->id : '' }}">
                                <input type="hidden" name="consulta_id" value="{{ $origem ? $origem->id : '' }}">



                                <div class="col-12 col-md-3">
                                    <label for="hora_exame" class="form-label">Horário</label>
                                    <div class="input-group mb-3">
                                        <select id="hora_exame" name="hora_exame" class="form-control">
                                            <option value=""> Selecione um médico </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="produto_id" class="form-label">Selecionar Produtos</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="produto_id" id="produto_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == old('produto_id') ? 'selected' : '' }}>
                                                {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="observacao" class="form-label">Observação (opcional)</label>
                                    <div class="input-group mb-3">
                                        <textarea name="observacao" class="form-control" id="observacao" cols="30" rows="2" placeholder="Descrever um Observação"></textarea>
                                    </div>
                                </div>
                            </div>

                            @php
                            $contaId = $origem ? $origem->contaHospitalar->id : null;
                            @endphp

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar exame'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5>Valor total dos Serviços: <span id="valor_total" class="float-right"></span></h5>
                            </div>
                            <div class="card-body">
                                <div class="col-12 col-md-12 my-3">
                                    <table id="tabela-exames" class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('messages.servico') }}</th>
                                                <th>Tipo</th>
                                                <th class="text-right">{{ __('messages.valor') }}</th>
                                                <th class="text-right">{{ __('messages.accoes') }} </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
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
    load();


    $('#data_exame').change(function() {
        let data = $(this).val();

        $.ajax({
            url: '/consultas/medicos-disponiveis'
            , type: 'GET'
            , data: {
                data: data
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(lista) {
                Swal.close();
                let option = '<option value="">Selecione</option>';
                $.each(lista, function(i, item) {
                    option += '<option value="' + item.id + '">' + item.funcionario.nome + '</option>';
                });
                $('#profissional_saude_id').html(option);
                $('#hora_exame').html('<option>Selecione um médico</option>');
            }
            , error: function(xhr) {
                // Feche o alerta de carregamento
                Swal.close();
                showMessage('Erro de Validação!', 'Ocorreu um erro inesperado', 'error');
            }
        });
    });

    $('#profissional_saude_id').change(function() {
        let medico = $(this).val();
        let data = $('#data_exame').val();
        if (medico == '') {
            return;
        }

        $.ajax({
            url: '/consultas/horarios-disponiveis'
            , type: 'GET'
            , data: {
                medico_id: medico
                , data: data
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(lista) {
                let option = '';
                $.each(lista, function(i, item) {
                    option += '<option value="' + item.inicio + '">' + item.inicio + ' - ' + item.fim + '</option>';
                });
                $('#hora_exame').html(option);
                Swal.close();
            }
            , error: function(xhr) {
                // Feche o alerta de carregamento
                Swal.close();
                showMessage('Erro de Validação!', 'Ocorreu um erro inesperado', 'error');
            }
        });
    });

    function load() {

        $.ajax({
            url: '/carregar/listar-itens'
            , type: 'GET'
            , data: {
                tipo: 'exames'
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(res) {

                Swal.close();

                carregarTabelaExames(res.items);

                $("#total-geral").text(
                    formatarMoeda(res.total)
                );

            }
            , error: function(xhr) {
                Swal.close();
            }
        });
    }

    function carregarTabelaExames(items) {
        let tbody = $("#tabela-exames tbody");
        tbody.empty();
        if (!items || items.length === 0) {
            tbody.append(`
            <tr>
                <td colspan="5" class="text-center">
                    Nenhum exame encontrado
                </td>
            </tr>
        `);
            return;
        }

        items.forEach((item, index) => {
            tbody.append(`
            <tr>
                <td>${index + 1}</td>
                <td>${item.produto?.nome ?? '-'}</td>
                <td>${item.produto?.categoria?.categoria ?? '-'}</td>
                <td class="text-right">
                    ${formatarMoeda(item.valor ?? 0)}
                </td>
                <td>
                    <a href="#" data-id="${item.id}" class="float-right delete-record text-light-danger">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        `);
        });
    }

    let contaId = @json($contaId);

    const routes = {
        atendimento: "{{ route('atendimentos.show', ':id') }}"
    , };

    $('#produto_id').on('change', function() {
        const exame_id = $(this).val();
        if (exame_id) {
            $.ajax({
                url: '/adicionar-items-exames-post'
                , method: 'POST'
                , data: {
                    _token: '{{ csrf_token() }}'
                    , exame_id
                , }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(res) {
                    Swal.close();
                    carregarTabelaExames(res.items);
                    $("#total-geral").text(
                        formatarMoeda(res.total)
                    );
                }
                , error: function(err) {
                    Swal.close();
                    // showMessage('Erro de Validação!', "ocorreu um erro ao adicionar um serviço" 'error');
                    console.log(err);
                }
            });
        }
    });

    function formatarMoeda(valor) {
        return valor.toLocaleString('pt-AO', {
            style: 'currency'
            , currency: 'AOA' // Kwanza (Angola)
        });
    }

    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, excluir!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('adicionar-items-exames-delete', ':id') }}`.replace(':id'
                        , recordId)
                    , method: 'DELETE'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(res) {
                        Swal.close();
                        carregarTabelaExames(res.items);
                        $("#total-geral").text(
                            formatarMoeda(res.total)
                        );
                        $("#valor_total").text(formatarMoeda(res.total));
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let paciente_id = $('#paciente_id').val();

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

                    let url = routes.atendimento.replace(':id', response.exame.atendimento_id);

                    Swal.fire({
                        icon: 'success'
                        , title: 'Exames registado!'
                        , text: response.message + '\n\nDeseja abrir a conta hospitalar deste paciente?'
                        , showCancelButton: true
                        , confirmButtonColor: '#28a745'
                        , cancelButtonColor: '#6c757d'
                        , confirmButtonText: '<i class="fas fa-folder-open"></i> Sim, abrir'
                        , cancelButtonText: '<i class="fas fa-times"></i> Não'
                    }).then((result) => {

                        if (result.isConfirmed) {
                            window.location.href = url;
                        } else {

                            Swal.fire({
                                icon: 'success'
                                , title: 'Concluído!'
                                , text: 'Operação realizada com sucesso.'
                                , timer: 2000
                                , showConfirmButton: false
                            });
                        }
                    });
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
