@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Solicitações de Internamentos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('consultorio.index') }}">Home</a></li>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos'))
                                <button type="button" onclick="toggleModal()" class="btn btn-light-primary">
                                    <i class="fas fa-plus"></i> {{ __('messages.novo') }}
                                </button>
                                @endif
                            </h3>

                            {{-- <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div> --}}
                        </div>

                        @if ($solicitacoes)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Número</th>
                                        <th>Paciente</th>
                                        <th>Prioridade</th>
                                        <th>Cor</th>
                                        <th>Médico</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($solicitacoes as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->numero }}</td>
                                        <td>{{ $item->paciente->nome }}</td>
                                        <td>{{ $item->prioridade->nome }}</td>
                                        <td>{{ $item->prioridade->tipo_cor($item->prioridade->cor) }}</td>
                                        <td>{{ $item->medico ? $item->medico->funcionario->nome : "" }}</td>

                                        @if ($item->status == "aguardando")
                                        <td><span class="badge" style="background-color: #FFF3CD;">{{ $item->status }}</span></td>
                                        @endif

                                        @if ($item->status == "atendido")
                                        <td><span class="badge" style="background-color: #D4EDDA;">{{ $item->status }}</span></td>
                                        @endif

                                        @if ($item->status == "cancelada")
                                        <td><span class="badge" style="background-color: #F8D7DA;">{{ $item->status }}</span></td>
                                        @endif

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('editar todos'))

                                                    @if ($item->status == "aguardando")
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" data-status="aceitar" class="dropdown-item text-light-primary update-record"><i class="fas fa-check"></i> Aceitar</a>
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" data-status="cancelar" class="dropdown-item text-light-danger update-record"><i class="fas fa-cancel"></i>{{ __('messages.cancelar') }} </a>
                                                    @endif
                                                    @if ($item->status == "cancelada")
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" data-status="aceitar" class="dropdown-item text-light-primary update-record"><i class="fas fa-table"></i> Aceitar</a>
                                                    @endif

                                                    @endif
                                                    @if (Auth::user()->can('editar todos'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item edit-folder text-light-success"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    @if (Auth::user()->can('eliminar todos'))
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

    <form action="{{ route('solicitacao-internamento.store') }}" method="post" class="">
        @csrf
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-xl  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Solicitações de Internamentos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">

                        <div class="col-12 col-md-6">
                            <label for="paciente_id" class="form-label">Pacientes</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control select2" style="width: 100%" id="paciente_id" name="paciente_id">
                                    <option value="">{{ __('messages.escolher') }}</option>
                                    @foreach ($pacientes as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-12 col-md-6">
                            <label for="medico_id" class="form-label">Médicos</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control select2" style="width: 100%" id="medico_id" name="medico_id">
                                    <option value="">{{ __('messages.escolher') }}</option>
                                    @foreach ($medicos as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->funcionario->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="prioridade_id" class="form-label">Prioridades</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control " id="prioridade_id" name="prioridade_id">
                                    @foreach ($prioridades as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="data_at" class="form-label">Data Solicitação</label>
                            <input type="date" class="form-control " id="data_at" name="data_at">
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="tipo_internamento" class="form-label">Tipo Internamento</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control " id="tipo_internamento" name="tipo_internamento">
                                    <option value="">{{ __('messages.escolher') }} </option>
                                    <option value="eletiva">Eletiva</option>
                                    <option value="emergencia">Emergência</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="unidate_desejada" class="form-label">Unidade desejada</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control " id="unidate_desejada" name="unidate_desejada">
                                    <option value="">{{ __('messages.escolher') }} </option>
                                    <option value="enfermaria">Enfermaria</option>
                                    <option value="uti">UTI</option>
                                    <option value="isolamento">Isolamento</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-12">
                            <label for="justificativo" class="form-label">Justificativa / Diagnóstico</label>
                            <div class="input-group mb-3">
                                <textarea class="form-control" name="justificativo" id="justificativo" cols="30" rows="5" placeholder="Descrição da Justificação da solicitação: "></textarea>
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
    let PastaID = null;
    let modalVisible = false;

    const modalElement = document.getElementById('modal-lg');
    const modalInstance = new bootstrap.Modal(modalElement);

    function toggleModal() {
        PastaID = null;
        if (modalVisible) {
            modalInstance.hide();
            modalVisible = false;
        } else {
            modalInstance.show();
            modalVisible = true;
        }
    }

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            let new_form = null;
            let method = null;

            if (PastaID == null) {
                new_form = form.attr('action');
                method = "post";
            } else {
                method = "put";
                new_form = form.attr('action') + "/" + PastaID;
            }

            $.ajax({
                url: new_form, // URL do endpoint no backend
                method: method, // Método HTTP definido no formulário
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

    $(document).on('click', '.update-record', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
        let recordStatus = $(this).data('status'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, mudar estado!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('solicitacao-internamento.atender-paciente', [':id', ':status']) }}`.replace(':id', recordId).replace(':status', recordStatus)
                    , method: 'GET'
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.edit-folder', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // Envia a solicitação AJAX para excluir o registro
        $.ajax({
            url: `{{ route('solicitacao-internamento.edit', ':id') }}`.replace(':id', recordId)
            , method: 'GET'
            , data: {
                _token: '{{ csrf_token() }}', // Inclui o token CSRF
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {

                Swal.close();
                modalInstance.show();

                document.getElementById('paciente_id').value = response.data.paciente_id;
                document.getElementById('medico_id').value = response.data.medico_id;
                document.getElementById('data_at').value = response.data.data_at;
                document.getElementById('prioridade_id').value = response.data.prioridade_id;
                document.getElementById('tipo_internamento').value = response.data.tipo_internamento;
                document.getElementById('unidate_desejada').value = response.data.unidate_desejada;
                document.getElementById('justificativo').value = response.data.justificativo;
                PastaID = response.data.id;

            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
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
                    url: `{{ route('solicitacao-internamento.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
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
