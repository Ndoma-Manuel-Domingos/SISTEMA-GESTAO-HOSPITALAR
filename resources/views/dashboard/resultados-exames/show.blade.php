@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('planos-tratamentos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Tratamento</li>
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
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-12 table-responsive"">
                                    <table class=" table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <th>Tratamento Nº</th>
                                            <td class="text-right">{{ $tratamento->id }}</td>
                                        </tr>

                                        <tr>
                                            <th>{{ __('messages.data_inicio') }}</th>
                                            <td class="text-right">{{ $tratamento->data_inicio }}</td>
                                        </tr>

                                        <tr>
                                            <th>{{ __('messages.data_final') }}</th>
                                            <td class="text-right">{{ $tratamento->data_final }}</td>
                                        </tr>

                                        @if ($tratamento->status == 'activo')
                                        <tr>
                                            <th>{{ __('messages.estados') }}</th>
                                            <td class="text-right">{{ $tratamento->status }}</td>
                                        </tr>
                                        @endif

                                        @if ($tratamento->status == 'suspenso')
                                        <tr>
                                            <th>{{ __('messages.estados') }}</th>
                                            <td class="text-right">{{ $tratamento->status }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.observacao') }}</th>
                                            <td class="text-right">{{ $tratamento->observacoes_finais }}</td>
                                        </tr>
                                        <tr>
                                            <th>Data Finalzição</th>
                                            <td class="text-right">{{ $tratamento->data_finalizacao }}</td>
                                        </tr>
                                        @endif

                                        @if ($tratamento->status == 'suspenso')
                                        <tr>
                                            <th>{{ __('messages.estados') }}</th>
                                            <td class="text-right">{{ $tratamento->status }}</td>
                                        </tr>
                                        <tr>
                                            <th>Motivo</th>
                                            <td class="text-right">{{ $tratamento->motivo_suspesao }}</td>
                                        </tr>
                                        <tr>
                                            <th>Data Suspensão</th>
                                            <td class="text-right">{{ $tratamento->data_suspesao }}</td>
                                        </tr>
                                        @endif

                                        @if ($tratamento->status == 'cancelado')
                                        <tr>
                                            <th>{{ __('messages.estados') }}</th>
                                            <td class="text-right">{{ $tratamento->status }}</td>
                                        </tr>
                                        <tr>
                                            <th>Motivo</th>
                                            <td class="text-right">{{ $tratamento->motivo_cancelamento }}</td>
                                        </tr>
                                        <tr>
                                            <th>Data Suspensão</th>
                                            <td class="text-right">{{ $tratamento->data_cancelamento }}</td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <th>Titulo</th>
                                            <td class="text-right">{{ $tratamento->titulo }}</td>
                                        </tr>

                                        <tr>
                                            <th>Frequência</th>
                                            <td class="text-right">{{ $tratamento->frequencia }}</td>
                                        </tr>

                                        <tr>
                                            <th>Duração Semanas</th>
                                            <td class="text-right">{{ $tratamento->duracao_semanas }}</td>
                                        </tr>

                                        <tr>
                                            <th> {{ __('messages.descricao') }} </th>
                                            <td class="text-right">{{ $tratamento->descricao }}</td>
                                        </tr>

                                        <tr>
                                            <th>Objectivo</th>
                                            <td class="text-right">{{ $tratamento->objectivo }}</td>
                                        </tr>

                                        <tr>
                                            <th>Orientações gerais</th>
                                            <td class="text-right">{{ $tratamento->orientacoes_gerais }}</td>
                                        </tr>

                                        <tr>
                                            <th>{{ __('messages.observacao') }}</th>
                                            <td class="text-right">{{ $tratamento->observacoes_finais }}</td>
                                        </tr>

                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar tratamento'))
                            <a href="#" data-id="{{ $tratamento->id }}" class="btn btn-app bg-light-danger delete-record"><i class="fas fa-trash"></i> {{ __('messages.eliminar') }}</a>
                            @endif

                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar tratamento'))
                            <a href="{{ route('planos-tratamentos.edit', $tratamento->id) }}" class="btn btn-app bg-light-success"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                            @endif

                            @if (Auth::user()->can('listar todos') || Auth::user()->can('listar tratamento'))
                            <a href="{{ route('planos-tratamentos.lancar_imprimir', $tratamento->id) }}" target="_blank" class="btn btn-app  bg-light-primary"><i class=" fas fa-file-pdf"></i> {{ __('messages.imprimir') }} </a>
                            @endif

                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar tratamento'))
                            @if ($tratamento->status != 'finalizado')
                            <a href="#" onclick="toggleModalFinalizacao({{$tratamento->id}})" class="btn btn-app  bg-light-primary""><i class=" fas fa-table"></i> Finalizar</a>
                            @endif

                            @if ($tratamento->status == 'suspenso')
                            <a href="#" onclick="toggleModalCancelamento({{$tratamento->id}})" class="btn btn-app bg-light-danger"><i class="fas fa-table"></i>{{ __('messages.cancelar') }} </a>
                            @endif

                            @if ($tratamento->status == 'activo' && $tratamento->status != 'suspenso')
                            <a href="#" onclick="toggleModalSuspensao({{$tratamento->id}})" class="btn btn-app  bg-light-primary"><i class=" fas fa-table"></i> Suspender</a>
                            @endif

                            @if ($tratamento->status == 'activo' && $tratamento->status != 'cancelado')
                            <a href="#" onclick="toggleModalCancelamento({{$tratamento->id}})" class="btn btn-app bg-light-danger"><i class="fas fa-table"></i>{{ __('messages.cancelar') }} </a>
                            @endif
                            @endif

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-12 table-responsive"">
                                    <table class=" table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Datas</th>
                                            <th>{{ __('messages.estados') }}</th>
                                            <th>{{ __('messages.observacao') }}</th>
                                            <th class="text-right">{{ __('messages.accoes') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($tratamento->sessoes_tratamento as $item)
                                        <tr>
                                            <td class="text-left">{{ $item->data_at ?? "---" }}</td>
                                            <td class="text-left">{{ $item->status ?? "---"  }}</td>
                                            <td class="text-left">{{ $item->observacoes ?? "---"  }}</td>
                                            <td class="text-right">
                                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar tratamento'))
                                                <button onclick="abrirModal({{ $item }})" class="btn-sm btn-light-primary">Actualizar</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>

    <form action="{{ route('planos-tratamentos.cancelar') }}" method="post" class="" id="form_cancelamento">
        @csrf
        <div class="modal fade" id="modal-lg-cancelamento">
            <div class="modal-dialog modal-xl  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('messages.cancelar') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">

                        <div class="col-12 col-md-12 mb-3">
                            <label for="data_cancelamento" class="form-label">Data Cancelamento</label>
                            <input type="date" class="form-control" id="data_cancelamento" name="data_cancelamento" value="{{ date('Y-m-d') }}">
                            <input type="hidden" class="form-control" id="tratamento_cancelamento" name="tratamento_id">
                        </div>

                        <div class="col-12 col-md-12 mb-3">
                            <label for="motivo_cancelamento" class="form-label">Motivo do cancelamento</label>
                            <div class="input-group mb-3">
                                <textarea class="form-control" name="motivo_cancelamento" id="motivo_cancelamento" cols="30" rows="5" placeholder="Descrição: "></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        {{-- @endif --}}
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->

    <form action="{{ route('planos-tratamentos.suspender') }}" method="post" class="" id="form_suspensao">
        @csrf
        <div class="modal fade" id="modal-lg-suspensao">
            <div class="modal-dialog modal-xl  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Suspender Tratamento</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">

                        <div class="col-12 col-md-12 mb-3">
                            <label for="data_suspesao" class="form-label">Data Suspensão</label>
                            <input type="date" class="form-control" id="data_suspesao" name="data_suspesao" value="{{ date('Y-m-d') }}">
                            <input type="hidden" class="form-control" id="tratamento_suspensao" name="tratamento_id">
                        </div>

                        <div class="col-12 col-md-12 mb-3">
                            <label for="motivo_suspesao" class="form-label">Motivo da Suspensão</label>
                            <div class="input-group mb-3">
                                <textarea class="form-control" name="motivo_suspesao" id="motivo_suspesao" cols="30" rows="5" placeholder="Descrição: "></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        {{-- @endif --}}
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->

    <form action="{{ route('planos-tratamentos.finalizar') }}" method="post" class="" id="form_finalizacao">
        @csrf
        <div class="modal fade" id="modal-lg-finalizacao">
            <div class="modal-dialog modal-xl  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Finalizar Tratamento</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">

                        <div class="col-12 col-md-12 mb-3">
                            <label for="data_finalizacao" class="form-label">{{ __('messages.data_final') }}</label>
                            <input type="date" class="form-control" id="data_finalizacao" name="data_finalizacao" value="{{ date('Y-m-d') }}">
                            <input type="hidden" class="form-control" id="tratamento_finalizacao" name="tratamento_id">
                        </div>

                        <div class="col-12 col-md-12 mb-3">
                            <label for="observacoes_finais" class="form-label">{{ __('messages.observacao') }}</label>
                            <div class="input-group mb-3">
                                <textarea class="form-control" name="observacoes_finais" id="observacoes_finais" cols="30" rows="5" placeholder="Descrição: "></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        {{-- @endif --}}
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->


    <!-- /.content -->
</div>


<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    let sessaoSelecionada = null;

    let modalVisible = false;

    const modalElementCancelamento = document.getElementById('modal-lg-cancelamento');
    const modalInstanceCancelamento = new bootstrap.Modal(modalElementCancelamento);

    const modalElementSuspensao = document.getElementById('modal-lg-suspensao');
    const modalInstanceSuspensao = new bootstrap.Modal(modalElementSuspensao);

    const modalElementFinalizacao = document.getElementById('modal-lg-finalizacao');
    const modalInstanceFinalizacao = new bootstrap.Modal(modalElementFinalizacao);

    const tratamento_cancelamento = document.getElementById('tratamento_cancelamento');
    const tratamento_suspensao = document.getElementById('tratamento_suspensao');
    const tratamento_finalizacao = document.getElementById('tratamento_finalizacao');


    function toggleModalCancelamento(id) {
        tratamento_cancelamento.value = id;
        if (modalVisible) {
            modalInstanceCancelamento.hide();
            modalVisible = false;
        } else {
            modalInstanceCancelamento.show();
            modalVisible = true;
        }
    }

    function toggleModalSuspensao(id) {
        tratamento_suspensao.value = id;
        if (modalVisible) {
            modalInstanceSuspensao.hide();
            modalVisible = false;
        } else {
            modalInstanceSuspensao.show();
            modalVisible = true;
        }
    }

    function toggleModalFinalizacao(id) {
        tratamento_finalizacao.value = id;
        if (modalVisible) {
            modalInstanceFinalizacao.hide();
            modalVisible = false;
        } else {
            modalInstanceFinalizacao.show();
            modalVisible = true;
        }
    }

    $(document).ready(function() {
        // Handler do form de atendimento
        $("#form_cancelamento").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });

        // Handler do segundo form
        $("#form_suspensao").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });

        // Handler do segundo form
        $("#form_finalizacao").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });
    });


    function enviarFormularioAjax(form) {
        let formData = form.serialize();

        $.ajax({
            url: form.attr('action')
            , method: form.attr('method')
            , data: formData
            , headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
            , beforeSend: function() {
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                showMessage('Sucesso!', 'Dados actualizados com sucesso!', 'success');
                window.location.reload();
            }
            , error: function(xhr) {
                Swal.close();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let messages = '';
                    $.each(errors, function(key, value) {
                        messages += `${value}\n *`;
                    });
                    showMessage('Erro de Validação!', messages, 'error');
                } else {
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            }
        });
    }



    function abrirModal(item) {
        sessaoSelecionada = item.id;

        Swal.fire({
            title: `Actualização Registro`
            , html: `
          <div class="row my-4">
            <div class="text-left mb-3 col-12 col-md-12">
              <label>Estados:</label>
              <select id="status" class="form-control">
                <option value="concluida">Concluida</option>
                <option value="faltou">Faltou</option>
              </select>
            </div>
            <div class="text-left mb-3 col-12 col-md-12">
              <label class="text-left" style="text-align: left">observacoes:</label>
              <textarea id="observacoes" class="form-control"></textarea>
            </div>
          </div>
        `
            , width: "700px"
            , showCancelButton: true
            , confirmButtonText: 'Salvar'
            , cancelButtonText: 'Cancelar'
            , didOpen: () => {
                document.getElementById('status').value = item.status;
                document.getElementById('observacoes').value = item.observacoes;
            }
            , preConfirm: () => {
                return {
                    sessaoSelecionada: sessaoSelecionada
                    , status: document.querySelector('#status').value
                    , observacoes: document.querySelector('#observacoes').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                salvarResultado(result.value);
            }
        });



    }

    function salvarResultado(data) {

        // Você pode adicionar um loader aqui, se necessário
        progressBeforeSend();

        fetch(`/planos-tratamentos/${sessaoSelecionada}/resultado`, {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
                , body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(response => {
                // Fecha o loading
                Swal.close();
                if (response.success) {
                    showMessage('Sucesso!', response.message, 'success');
                    window.location.reload();
                } else {
                    showMessage('Erro!', 'Algo deu errado', 'error');
                }
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
                    url: `{{ route('planos-tratamentos.destroy', ':id') }}`.replace(':id'
                        , recordId)
                    , method: 'DELETE'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
                        window.location.href = "/internamentos";
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!'
                            , 'Ocorreu um erro ao excluir o registro. Tente novamente.'
                            , 'error');
                    }
                , });
            }
        });
    });

</script>
@endsection
