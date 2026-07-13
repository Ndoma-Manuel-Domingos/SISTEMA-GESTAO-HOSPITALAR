@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Solitações Exames & Consultas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('solicitacoes-medicas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Solitações</li>
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
                    <form action="{{ route('solicitacoes-medicas.store') }}" method="post" class="">
                        <div class="card">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="paciente_id" class="form-label">Selecionar Pacientes</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="paciente_id" name="paciente_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($pacientes as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == ($origem->cliente_id ? $origem->cliente_id : ($origem->paciente_id ? $origem->paciente_id : null)) ? 'selected' : '' }}>
                                                {{ $item->nome }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="request_ordem" value="{{ $request_ordem ?? '' }}">
                                <input type="hidden" name="origem_id" value="{{ $origem ? $origem->id : '' }}">
                                <input type="hidden" name="consulta_id" value="{{ $origem ? $origem->id : '' }}">

                                <div class="col-12 col-md-3">
                                    <label for="prioridade_id" class="form-label">Selecionar Prioridade</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="prioridade_id" id="prioridade_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($prioridades as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == old('prioridade_id') ? 'selected' : '' }}>
                                                {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo" class="form-label">Tipo de Solicitação</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="tipo" id="tipo">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="exame">Exames</option>
                                            <option value="consulta">Consultas</option>
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
                                    <label for="justificativa" class="form-label">Justificativa (opcional)</label>
                                    <div class="input-group mb-3">
                                        <textarea name="justificativa" class="form-control" id="justificativa" cols="30" rows="2" placeholder="Descrever uma justificativa"></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('monitoramento consultorio') || Auth::user()->can('consultorio'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>

                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5>Item Solicitados</h5>
                            </div>
                            <div class="card-body">
                                <div class="col-12 col-md-12 my-3">
                                    <table id="tabela-exames" class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('messages.servico') }}</th>
                                                <th>Categoria</th>
                                                <th class="text-right">{{ __('messages.accoes') }} </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer"></div>
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
    let total_exames_preco = 0;

    $('#produto_id').on('change', function() {
        const item_id = $(this).val();
        if (item_id) {
            $.ajax({
                url: '/adicionar-item-solicitacoes-medicas'
                , method: 'POST'
                , data: {
                    _token: '{{ csrf_token() }}'
                    , item_id
                , }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(res) {
                    Swal.close();

                    $("#tabela-exames tbody").html("")

                    for (let index = 0; index < res.items.length; index++) {
                        let item = res.items[index];

                        $("#tabela-exames tbody").append(`
                            <tr>
                                <td>${ index + 1 }</td>
                                <td>${item.produto.nome}</td>
                                <td>${item.produto.categoria.categoria}</td>
                                <td><a href="#" data-id="${item.id}" class="float-right delete-record text-light-danger"><i class="fas fa-trash"></i></a></td>
                            </tr>
                            `);
                    }

                }
                , error: function(err) {
                    Swal.close();
                    // showMessage('Erro de Validação!', "ocorreu um erro ao adicionar um serviço" 'error');
                    console.log(err);
                }
            });
        }
    });

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
                    url: `{{ route('remover-items-solicitacoes-medicas', ':id') }}`.replace(':id', recordId)
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
                        $("#tabela-exames tbody").html("")

                        for (let index = 0; index < res.items.length; index++) {
                            let item = res.items[index];
                            $("#tabela-exames tbody").append(`
                          <tr>
                            <td>${ index + 1 }</td>
                            <td>${item.produto.nome}</td>
                            <td>${item.produto.categoria.categoria}</td>
                            <td><a href="#" data-id="${item.id}" class="float-right delete-record text-light-danger"><i class="fas fa-trash"></i></a></td>
                          </tr>
                        `);
                        }
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

                    window.location.href = "/consultor/consultorio";

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
