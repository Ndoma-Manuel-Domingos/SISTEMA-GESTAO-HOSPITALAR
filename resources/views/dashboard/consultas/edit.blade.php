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
                        <li class="breadcrumb-item"><a href="{{ route('consultas.index') }}">{{ __('messages.voltar') }}</a></li>
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
                    <form action="{{ route('consultas.update', $consulta->id) }}" method="post" class="">
                        <div class="card">
                            @csrf
                            @method('put')
                            <div class="card-body row">

                                <div class="col-12 col-12 col-md-6">
                                    <label for="paciente_id" class="form-label">Selecionar Pacientes</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="paciente_id" name="paciente_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($pacientes as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == $consulta->paciente_id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('paciente_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <input type="hidden" name="consulta_id" id="consulta_id" value="{{ $consulta->id }}">

                                <div class="col-12 col-12 col-md-6">
                                    <label for="" class="form-label">Selecionar Médico</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="medico_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($medicos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == $consulta->medico_id ? 'selected' : ''  }}>{{ $item->funcionario->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('medico_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="prioridade_id" class="form-label">Selecionar Prioridade</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="prioridade_id" id="prioridade_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($prioridades as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == $consulta->prioridade_id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-light-danger"> @error('prioridade_id') {{ $message }} @enderror </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">{{ __('messages.data') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" name="data_consulta" value="{{ $consulta->data_consulta ?? old('data_consulta') }}" placeholder="Informe a Data da consulta">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('data_consulta')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">{{ __('messages.hora') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="time" class="form-control" name="hora_consulta" value="{{ $consulta->hora_consulta ?? old('hora_consulta') }}" placeholder="Informe a hora da consulta">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('hora_consulta')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="produto_id" class="form-label">Selecionar Produtos</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="produto_id" id="produto_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-light-danger"> @error('produto_id') {{ $message }} @enderror </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="movito_agendamento" class="form-label">Motivo do Agendamento (opcional)</label>
                                    <div class="input-group mb-3">
                                        <textarea name="movito_agendamento" class="form-control" id="movito_agendamento" cols="30" rows="2" placeholder="Informe o Motivo do Agendamento">{{ $consulta->movito_agendamento }}</textarea>
                                    </div>
                                    <p class="text-light-danger"> @error('observacao') {{ $message }} @enderror </p>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="observacao" class="form-label">Observação (opcional)</label>
                                    <div class="input-group mb-3">
                                        <textarea name="observacao" class="form-control" id="observacao" cols="30" rows="2" placeholder="Descrever um Observação">{{ $consulta->observacao }}</textarea>
                                    </div>
                                    <p class="text-light-danger"> @error('observacao') {{ $message }} @enderror </p>
                                </div>

                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar consulta'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </div>
                    </form>

                    @php $total = 0; @endphp
                    @foreach ($consulta->items as $item1)
                    @php $total = $total + $item1->valor; @endphp
                    @endforeach

                    <div class="card">
                        <div class="card-header">
                            <h5>Valor total dos Serviços:
                                <span id="valor_total" class="float-right">{{ number_format($total, 2, ',', '.') }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="col-12 col-md-12 my-3">
                                <table id="tabela-exames-update" class="table table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.servico') }}</th>
                                            <th>Tipo</th>
                                            <th class="text-right">{{ __('messages.valor') }}</th>
                                            <th class="text-right">{{ __('messages.accoes') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($consulta->items as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{$item->produto->nome}}</td>
                                            <td>{{$item->produto->categoria->categoria}}</td>
                                            <td class="text-right">{{ number_format($item->valor ?? 0, 2, ',', '.') }}</td>
                                            <td><a href="#" data-id="{{ $item->id ?? "" }}" class="float-right delete-record text-light-danger"><i class="fas fa-trash"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer"></div>
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
    let total_exames_preco = 0;
    $('#produto_id').on('change', function() {
        const exame_id = $(this).val();
        const consulta_id = $("#consulta_id").val();
        if (exame_id) {
            $.ajax({
                url: '/actualizar-items-consultas'
                , method: 'POST'
                , data: {
                    _token: '{{ csrf_token() }}'
                    , exame_id
                    , consulta_id
                , }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(res) {
                    Swal.close();

                    $("#tabela-exames-update tbody").html("")

                    for (let index = 0; index < res.items.length; index++) {
                        let item = res.items[index];

                        $("#tabela-exames-update tbody").append(`
                    <tr>
                      <td>${ index + 1 }</td>
                      <td>${item.produto.nome}</td>
                      <td>${item.produto.categoria.categoria}</td>
                      <td class="text-right">${formatarMoeda(item.valor ?? 9)}</td>
                      <td><a href="#" data-id="${item.id}" class="float-right delete-record text-light-danger"><i class="fas fa-trash"></i></a></td>
                    </tr>
                  `);
                    }

                    $("#valor_total").text(formatarMoeda(res.total));

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
        const consultaId = $("#consulta_id").val();

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
                    url: `{{ route('actualizar-items-consultas-delete', ['id' => ':id', 'consulta_id' => ':consulta_id']) }}`.replace(':id', recordId).replace(':consulta_id', consultaId)
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
                        $("#tabela-exames-update tbody").html("")
                        for (let index = 0; index < res.items.length; index++) {
                            let item = res.items[index];

                            $("#tabela-exames-update tbody").append(`
                        <tr>
                          <td>${ index + 1 }</td>
                          <td>${item.produto.nome}</td>
                          <td>${item.produto.categoria.categoria}</td>
                          <td class="text-right">${item.valor ?? ''}</td>
                          <td><a href="#" data-id="${item.id}" class="float-right delete-record text-light-danger"><i class="fas fa-trash"></i></a></td>
                        </tr>
                      `);
                        }

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

                    Swal.close();
                    showMessage('Sucesso!', 'Dados actualizados com sucesso!', 'success');
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

    function formatarMoeda(valor) {
        return valor.toLocaleString('pt-AO', {
            style: 'currency'
            , currency: 'AOA' // Kwanza (Angola)
        });
    }

</script>
@endsection
