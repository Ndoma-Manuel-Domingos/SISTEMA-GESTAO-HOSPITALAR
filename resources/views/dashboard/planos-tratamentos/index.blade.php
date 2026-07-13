@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tratamentos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-hospital') }}">Home</a></li>
                        <li class="breadcrumb-item active">Todos</li>
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
                    <form action="{{ route('planos-tratamentos.index') }}" method="GET">
                        @csrf
                        <div class="card">
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="paciente_id" class="form-label">Pacientes</label>
                                    <select name="paciente_id" id="paciente_id" class="form-control select2">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($pacientes as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['paciente_id'] == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        <option value="AGENDADA" {{ $requests['status'] == "AGENDADA" ? "selected" : "" }}>AGENDADA</option>
                                        <option value="CONCLUIDO" {{ $requests['status'] == "CONCLUIDO" ? "selected" : "" }}>CONCLUIDO</option>
                                        <option value="EM ATENDIMENTO" {{ $requests['status'] == "EM ATENDIMENTO" ? "selected" : "" }}>EM ATENDIMENTO</option>
                                        <option value="CANCELADA" {{ $requests['status'] == "CANCELADA" ? "selected" : "" }}>CANCELADA</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <input type="date" name="data_inicio" value="{{ $requests['data_inicio'] ?? "" }}" id="data_inicio" class="form-control">
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                    <input type="date" name="data_final" value="{{ $requests['data_final'] ?? "" }}" id="data_final" class="form-control">
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary"><i class="fas fa-filter"></i> Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('planos-tratamentos.imprimir-all', [
                                    'paciente_id' => $requests['paciente_id'],
                                    'status' => $requests['status'],
                                ]) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($tratamentos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Titulo</th>
                                        <th>Paciente</th>
                                        <th>{{ __('messages.genero') }}</th>
                                        <th>{{ __('messages.idade') }}</th>
                                        <th>Equipa Responsável</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>{{ __('messages.data_inicio') }}</th>
                                        <th>{{ __('messages.data_final') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tratamentos as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->titulo }}</td>
                                        <td><a href="{{ route('clientes.show', $item->paciente->id) }}">{{ $item->paciente->nome }}</a></td>
                                        <td>{{ $item->paciente->genero }}</td>
                                        <td>{{ $item->paciente->idade($item->paciente->data_nascimento) }} Anos</td>
                                        <td><a href="{{ route('equipas.show', $item->equipa->id) }}">{{ $item->equipa->nome }}</a></td>

                                        @if ($item->status == 'finalizado')
                                        <td><span class="badge" style="background-color: #FFF3CD;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'activo')
                                        <td><span class="badge" style="background-color: #D4EDDA;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'cancelado')
                                        <td><span class="badge" style="background-color: #F8D7DA;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'suspenso')
                                        <td><span class="badge" style="background-color: #f8f3d7;">{{ $item->status }}</span>
                                        </td>
                                        @endif
                                        <td>{{ $item->data_inicio }}</td>
                                        <td>{{ $item->data_final }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar tratamento'))
                                                    <a href="{{ route('planos-tratamentos.show', $item->id) }}" class="dropdown-item text-light-primary"><i class="fas fa-info"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    <a href="{{ route('planos-tratamentos.lancar_imprimir', $item->id) }}" target="_blank" class="dropdown-item text-light-primary"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }} </a>
                                                    @endif

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar tratamento'))

                                                    @if ($item->status == 'suspenso')
                                                    <a href="#" onclick="toggleModalCancelamento({{$item->id}})" class="dropdown-item text-light-primary"><i class="fas fa-table"></i> {{ __('messages.cancelar') }} </a>
                                                    @endif

                                                    @if ($item->status == 'activo' && $item->status != 'suspenso')
                                                    <a href="#" onclick="toggleModalSuspensao({{$item->id}})" class="dropdown-item text-light-primary"><i class="fas fa-table"></i> Suspender</a>
                                                    @endif

                                                    @if ($item->status == 'activo' && $item->status != 'cancelado')
                                                    <a href="#" onclick="toggleModalCancelamento({{$item->id}})" class="dropdown-item text-light-primary"><i class="fas fa-table"></i> {{ __('messages.cancelar') }} </a>
                                                    @endif

                                                    <a href="{{ route('planos-tratamentos.edit', $item->id) }}" class="dropdown-item text-light-success"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif

                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar tratamento'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item delete-record text-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        @endif

                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

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

</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    let modalVisible = false;

    const modalElementCancelamento = document.getElementById('modal-lg-cancelamento');
    const modalInstanceCancelamento = new bootstrap.Modal(modalElementCancelamento);

    const modalElementSuspensao = document.getElementById('modal-lg-suspensao');
    const modalInstanceSuspensao = new bootstrap.Modal(modalElementSuspensao);

    const tratamento_cancelamento = document.getElementById('tratamento_cancelamento');
    const tratamento_suspensao = document.getElementById('tratamento_suspensao');


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
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

    $(function() {
        $("#carregar_tabela").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
