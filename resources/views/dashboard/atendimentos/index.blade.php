@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Central de Atendimento</h1>
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

            <div class="row mb-4">

                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('solicitacoes-medicas.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-primary shadow">
                            <div class="inner">
                                <h3>{{ $solicitacoes }}</h3>
                                <p>Solicitações</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-medical"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('solicitacoes-medicas.index', ['status' => 'pendente']) }}" class="text-decoration-none">
                        <div class="small-box bg-light-warning shadow">
                            <div class="inner">
                                <h3>{{ $solicitacoes_pendente }}</h3>
                                <p>Pendentes</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('solicitacoes-medicas.index', ['status' => 'executado']) }}" class="text-decoration-none">
                        <div class="small-box bg-light-success shadow">
                            <div class="inner">
                                <h3>{{ $solicitacoes_executado }}</h3>
                                <p>Executadas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('solicitacoes-medicas.index', ['status' => 'cancelado']) }}" class="text-decoration-none">
                        <div class="small-box bg-light-danger shadow">
                            <div class="inner">
                                <h3>{{ $solicitacoes_cancelado }}</h3>
                                <p>Canceladas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('solicitacoes-medicas.index', ['status' => 'agendado']) }}" class="text-decoration-none">
                        <div class="small-box bg-light-info shadow">
                            <div class="inner">
                                <h3>{{ $solicitacoes_agendado }}</h3>
                                <p>Agendadas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-outline card-primary">

                        <div class="card-header">
                            <h3 class="card-title">Pesquisar atendimento e agendas</h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="row" action="{{ route('atendimentos.index') }}">

                                <!-- SEARCH GLOBAL -->
                                <div class="col-md-3">
                                    <label for="search">Pesquisa Geral</label>
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Paciente, BI, Nº Conta, Referência, Atendimento">
                                </div>

                                <div class="col-md-3">
                                    <label for="data_at">Data de Marcação</label>
                                    <input type="date" name="data_at" id="data_at" value="{{ request('data_at') }}" class="form-control">
                                </div>

                                <!-- STATUS -->
                                <div class="col-md-3">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="aguardando" {{ request('status')=='aguardando'?'selected':'' }}>Aguardando</option>
                                        <option value="em atendimento" {{ request('status')=='em atendimento'?'selected':'' }}>Em atendimento</option>
                                        <option value="ausente" {{ request('status')=='ausente'?'selected':'' }}>Ausente</option>
                                        <option value="atendido" {{ request('status')=='atendido'?'selected':'' }}>Atendido</option>
                                        <option value="tratamento" {{ request('status')=='tratamento'?'selected':'' }}>Tratamento</option>
                                        <option value="internamento" {{ request('status')=='internamento'?'selected':'' }}>Internamento</option>
                                    </select>
                                </div>

                                <!-- BOTÃO -->
                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i>
                                        Pesquisar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar atendimento'))
                                <button type="button" onclick="toggleModal()" class="btn btn-light-primary">
                                    <i class="fas fa-plus"></i> {{ __('messages.novo') }}
                                </button>
                                @endif
                                <a href="{{ route('clientes.create') }}" class="btn btn-light-primary"><i class="fas fa-user-injured"></i> Novo Paciente</a>
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('atendimentos.imprimir', [
                                    'paciente_id' => $requests['paciente_id'],
                                    'prioridad_id' => $requests['prioridad_id'],
                                    'status' => $requests['status'],
                                    'funcionario_id' => $requests['funcionario_id'],
                                    'data_inicio' => $requests['data_inicio'],
                                    'data_final' => $requests['data_final']
                                ]) }}"><i class="fas fa-file-pdf"></i>
                                    PDF</a>
                            </div>
                        </div>

                        @if ($atendimentos)
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
                                        <th>Tipo</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>Data Prevista</th>
                                        <th>Data Criação</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($atendimentos as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->numero }}</td>
                                        <td>{{ $item->paciente->nome }}</td>
                                        <td>{{ $item->prioridade->nome }}</td>
                                        <td>{{ $item->prioridade->tipo_cor($item->prioridade->cor) }}</td>
                                        <td>{{ $item->tipo->nome }}</td>

                                        @if ($item->status == 'aguardando')
                                        <td><span class="badge" style="background-color: #FFF3CD;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'em atendimento')
                                        <td><span class="badge" style="background-color: #B8DAFF;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'atendido')
                                        <td><span class="badge" style="background-color: #D4EDDA;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'ausente')
                                        <td><span class="badge" style="background-color: #F8D7DA;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'tratamento')
                                        <td><span class="badge" style="background-color: #B8DAFF;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'internamento')
                                        <td><span class="badge" style="background-color: #B8DAFF;">{{ $item->status }}</span></td>
                                        @endif
                                        <td>{{ $item->data_at }}</td>
                                        <td>{{ $item->created_at }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar atendimento'))
                                                    <a href="{{ route('atendimentos.show', $item->id) }}" class="dropdown-item text-light-primary"><i class="fas fa-info"></i> {{ __('messages.mais_detalhes') }}</a>
                                                    @endif

                                                    {{-- @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP' && $item->status == 'em atendimento')
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('criar consulta'))
                                                    <a class="dropdown-item" href="{{ route('consultas.create', ['origem' => 'atendimento', 'atendimento_id' => $item->id, 'paciente_id' => $item->cliente_id]) }}">
                                                    <i class="fas fa-user-nurse text-light-primary"></i> Marcar Consulta
                                                    </a>
                                                    @endif

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('criar exame'))
                                                    <a class="dropdown-item" href="{{ route('exames.create', ['origem' => 'atendimento', 'atendimento_id' => $item->id, 'paciente_id' => $item->cliente_id]) }}">
                                                        <i class="fas fa-user-nurse text-light-primary"></i> Marcar Exame
                                                    </a>
                                                    @endif

                                                    @if (Auth::user()->can('criar triagem') || Auth::user()->can('editar triagem'))
                                                    <a class="dropdown-item text-light-primary" href="{{ route('triagens.create', ['atendimento_id' => $item->id]) }}">
                                                        <i class="fas fa-table"></i> Fazer Triagem
                                                    </a>
                                                    @endif
                                                    @endif --}}

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar atendimento'))
                                                    @if ($item->status == 'aguardando')
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item text-light-primary update-record"><i class="fas fa-table"></i> Atender</a>
                                                    @endif
                                                    @endif

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar consulta') || Auth::user()->can('editar atendimento'))
                                                    @if ($item->status != 'atendido')
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item edit-folder text-light-success"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item edit-definir-atendido text-light-primary"><i class="fas fa-user-check"></i> Definir Atendido</a>
                                                    @endif

                                                    {{-- @if ($item->status == 'atendido' && ())
                                                    <a class="dropdown-item text-light-success" href="{{ route('triagens.create', ['atendimento_id' => $item->id]) }}">
                                                    <i class="fas fa-credit-card"></i> Fazer Primeiro pagamento
                                                    </a>
                                                    @endif --}}

                                                    @endif

                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar atendimento'))
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

    <form action="{{ route('atendimentos.store') }}" method="post" class="" id="form_atendimento">
        @csrf
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-xl  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Central de Atendimento</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">
                        <div class="col-12 col-md-6">
                            <label for="cliente_id" class="form-label">Pacientes</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control select2" style="width: 100%" id="cliente_id" name="cliente_id">
                                    <option value="">{{ __('messages.escolher') }}</option>
                                    @foreach ($pacientes as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="profissional_id" class="form-label">Medicos</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control select2" style="width: 100%" id="profissional_id" name="profissional_id">
                                    <option value="">{{ __('messages.escolher') }}</option>
                                    @foreach ($medicos as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->funcionario->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="prioridade_id" class="form-label">Prioridades</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control " id="prioridade_id" name="prioridade_id">
                                    @foreach ($prioridades as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="tipo_atendimento_id" class="form-label">Tipo Atendimento (Destino)</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control " id="tipo_atendimento_id" name="tipo_atendimento_id">
                                    @foreach ($tipos_atendimentos as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-12 col-md-3">
                            <label for="especialidade_id" class="form-label">Especialidade</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control " id="especialidade_id" name="especialidade_id">
                                    @foreach ($produtos as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                        @endforeach
                        </select>
                    </div>
                </div> --}}
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar atendimento'))
                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                @endif
            </div>
        </div>
        <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
</form>
<!-- /.modal -->
@php
$permissao = Auth::user()->can('listar todos') || Auth::user()->can('monitoramento central atendimento');
@endphp


</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')

<script src="{{ asset('js/verificacao-atendimento.js') }}"></script>

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

    const podeAtender = @json($permissao);
    const urlAtender = "{{ route('solicitacoes-medicas.index') }}";
    const urlVerificar = "{{ route('atendimentos-verificar') }}";

    function mostrarPaciente(dados) {

        if (modalAberta) return;

        modalAberta = true;
        pacienteAtual = dados;

        iniciarPiscarTitulo();

        mostrarNotificacao(dados.paciente);

        falar("Solicitações enviada pelo consultório. Paciente " + dados.paciente);

        clearInterval(intervaloFala);

        intervaloFala = setInterval(() => {
            if (modalAberta) {
                falar("Solicitações enviada pelo consultório. Paciente " + dados.paciente);
            }
        }, 10000);

        Swal.fire({
            icon: "info"
            , title: "🏥 Novo Paciente"
            , html: `
                    <h3>${dados.paciente}</h3>
                    <hr>
                    <b>Processo:</b>
                    ${dados.processo}
                    <br><br>
                    <b>Solicitações:</b>
                    ${dados.especialidade}
                    `
            , showConfirmButton: true
            , confirmButtonText: "✅ Atender"
            , showDenyButton: true
            , denyButtonText: "⏰ Lembrar depois de 2 minutos"
            , allowOutsideClick: false
            , allowEscapeKey: false
        }).then((resultado) => {
            if (resultado.isConfirmed) {
                if (!podeAtender) {
                    Swal.fire({
                        icon: 'warning'
                        , title: 'Sem permissão'
                        , text: 'Você não tem permissão para atender pacientes.'
                    });
                    return;
                }
                window.location.href = urlAtender;
            }

            if (resultado.isDenied) {
                guardarLembrete(2);
                pararAlertas();
            }
        });
    }

    function verificarPaciente() {
        if (modalAberta)
            return;
        $.ajax({
            url: urlVerificar
            , type: "GET"
            , dataType: "json"
            , timeout: 10000
            , success: function(retorno) {

                if (!retorno.status) return;

                if (pacienteEmEspera(retorno.id)) {
                    return;
                }

                mostrarPaciente(retorno);
            }
            , error: function(xhr, status, error) {
                console.error("Erro AJAX:", status, error);
            }
        });
    }

    iniciarMonitorizacao();

    $(document).ready(function() {
        if ("Notification" in window) {
            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            }
        }
    });

    $(document).ready(function() {
        // Handler do form de atendimento
        $("#form_atendimento").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });
    });

    function enviarFormularioAjax(form) {
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
    }

    $(document).on('click', '.edit-definir-atendido', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
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
                    url: `{{ route('atendimentos.definir-atendido-paciente', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
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
            url: `{{ route('atendimentos.edit', ':id') }}`.replace(':id', recordId)
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

                document.getElementById('cliente_id').value = response.data.cliente_id;
                document.getElementById('prioridade_id').value = response.data.prioridade_id;
                document.getElementById('tipo_atendimento_id').value = response.data
                    .tipo_atendimento_id;
                document.getElementById('profissional_id').value = response.data.profissional_id;
                PastaID = response.data.id;

            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
    });


    $(document).on('click', '.update-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
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
                    url: `{{ route('atendimentos.atender-paciente', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();

                        if (xhr.status === 409) {
                            Swal.fire({
                                icon: 'warning'
                                , title: 'Área ocupada'
                                , text: xhr.responseJSON.message
                            });
                            return;
                        }

                        showMessage('Erro!'
                            , 'Ocorreu um erro ao excluir o registro. Tente novamente.'
                            , 'error');
                    }
                , });
            }
        });
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
                    url: `{{ route('atendimentos.destroy', ':id') }}`.replace(':id', recordId)
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
