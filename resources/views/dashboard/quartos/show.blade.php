@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
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
                        <li class="breadcrumb-item"><a href="{{ route('quartos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Quarto</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">Quarto {{ $quarto->nome }}</h4>
                            <small>
                                {{ $quarto->andar->nome }}º Andar
                            </small>
                        </div>
                        <span class="badge badge-light p-2">
                            {{ $quarto->tipo->nome }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        @foreach($quarto->leitos as $cama)
                        <div class="col-lg-3 mb-4">
                            <div class="card h-100 border-0 shadow-sm cama-card" style="cursor: pointer" onclick="abrirPaciente({{$cama->id}})">
                                <div class="card-body">
                                    <div class="text-center">
                                        <i class="fas fa-bed fa-3x text-primary mb-3"></i>
                                        <h5>Leito {{ $cama->nome }}</h5>

                                        @if($cama->status == "ocupada")
                                        <span class="badge badge-danger">
                                            Ocupado
                                        </span>
                                        <hr>
                                        <strong>
                                            {{ $cama->internamento ? $cama->internamento->paciente->nome : "" }}
                                        </strong>
                                        <br>
                                        <small>
                                            {{ $cama->internamento ? $cama->internamento->diagnostico_inicial : "" }}
                                        </small>
                                        @else
                                        <span class="badge badge-success">
                                            Disponível
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="drawer drawer-right" id="drawerPaciente">
                <div class="drawer-header">
                    <h4>Dados do Paciente</h4>
                    <button onclick="fecharDrawer()">
                        ✕
                    </button>
                </div>
                <div id="conteudoPaciente"></div>
            </div>
            @else
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        @if ($quarto)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> {{ __('messages.designacao') }} </th>
                                        <th>Tipo</th>
                                        <th>Andar</th>
                                        <th>Ocupação</th>
                                        <th>{{ __('messages.estados') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $quarto->id }}</td>
                                        <td>{{ $quarto->nome }}</td>
                                        <td>{{ $quarto->tipo->nome }}</td>
                                        <td>{{ $quarto->andar->nome }}</td>
                                        <td>{{ $quarto->solicitar_ocupacao }}</td>
                                        <td>{{ $quarto->status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer clearfix d-flex">
                            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'REST' || $empresa_logada->empresa->tipo_entidade->sigla == 'HOTL')
                            <a href="{{ route('quartos.associar_tarefario', $quarto->id) }}" class="btn btn-light-primary mx-1">
                                <i class="fas fa-edit"></i> Associonar à Tarifários
                            </a>
                            @endif
                            <button class="btn btn-light-danger delete-record" data-id="{{ $quarto->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>
                        </div>
                        @endif

                    </div>
                    <!-- /.card -->

                    @if ($empresa_logada->empresa->tipo_entidade->sigla == 'REST' || $empresa_logada->empresa->tipo_entidade->sigla == 'HOTL')
                    <div class="card">
                        <div class="card-header">
                            <h5>Tarifários Associados ao Quarto</h5>
                        </div>
                        @if ($quarto->quartos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> {{ __('messages.designacao') }} </th>
                                        <th>{{ __('messages.valor') }}</th>
                                        <th>Modo Tarifário</th>
                                        <th>Tipo Cobrança</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quarto->quartos as $q)
                                    <tr>
                                        <td><a href="{{ route('tarefarios.show', $q->tarefario->id) }}">{{ $q->tarefario->id }}</a>
                                        </td>
                                        <td><a href="{{ route('tarefarios.show', $q->tarefario->id) }}">{{ $q->tarefario->nome ?? '' }}</a>
                                        </td>
                                        <td>{{ number_format($q->tarefario->valor ?? 0, 2, ',', '.') }}</td>
                                        <td>{{ $q->tarefario->modo_tarefario ?? '' }}</td>
                                        <td>{{ $q->tarefario->tipo_cobranca ?? '' }}</td>
                                        <td>{{ $q->tarefario->status ?? '' }}</td>
                                        <td>
                                            <button class="btn btn-light-danger btn-sm float-right delete-record-quarto" data-id="{{ $q->id }}">
                                                <i class="fas fa-trash text-light-danger"></i> Desassociar do Quarto
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        @endif
                    </div>
                    @endif

                </div>
            </div>
            <!-- /.row -->
            @endif
        </div><!-- /.quartoiner-fluid -->
    </div>

    @php
    $permission = Auth::user()->can('editar todos') || Auth::user()->can('listar internamento') || Auth::user()->can('monitoramento consultorio');
    @endphp

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    const permission = @json($permission);

    function abrirPaciente(id) {
        $.get('/internamento/leito/' + id, function(response) {
            let internamento = response.internamento;

            if (!internamento) {
                Swal.fire(
                    'Leito Livre'
                    , 'Nenhum paciente internado'
                    , 'info'
                );
                return;
            }

            let paciente = internamento.paciente;

            let equipa = internamento.equipa;

            let planos = internamento.plano_internamento;

            let planoMedicoHTML = '';

            if (permission === true) {
                planoMedicoHTML = `
                    <div class="col-12 col-md-12 p-2">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="5">
                                        Plano Médico de Internamento
                                    </th>
                                </tr>
                                <tr>
                                    <th>Medicamento</th>
                                    <th>Dose</th>
                                    <th>Via</th>
                                    <th>Frequência</th>
                                    <th>Duração</th>
                                </tr>
                            </thead>
                            <tbody id="tabelaPlanoMedicamentos">
                            </tbody>
                        </table>
                    </div>
                `;
            }

            $('#drawerPaciente').addClass('open');

            $('#conteudoPaciente').html(`
                <div class="p-2">
                    <div class="text-center mb-4">
                        <div class="avatar-lg">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h4>${paciente.nome}</h4>
                    </div>
                    <hr>
                    <p>
                        <b>Bilhete:</b>
                        ${paciente.nif}
                    </p>
                    <p>
                        <b>Genero:</b>
                        ${paciente.nif}
                    </p>
                    <p>
                        <b>Nascimento:</b>
                        ${paciente.data_nascimento?? "Não definida"}
                    </p>
                    <p>
                        <b>Número Internação:</b>
                        <a href="/internamentos/${internamento.id}" class="text-primary font-weight-bold">
                            ${internamento.numero}
                        </a>
                    </p>
                    <p>
                        <b>Diagnóstico:</b>
                        ${internamento.diagnostico_inicial}
                    </p>
                    <p>
                        <b>Internado em:</b>
                        ${internamento.data_internacao}
                    </p>
                    <p>
                        <b>Recebeu alte em:</b>
                        ${internamento.data_alta ?? "Pendente"}
                    </p>
                    <p>
                        <b>Equipa Médica:</b>
                        <a
                            href="/equipas/${internamento.equipa_id}"
                            class="text-primary font-weight-bold"
                        >
                            ${equipa?.nome ?? 'Não definida'}
                        </a>
                    </p>
                </div>
                
                ${planoMedicoHTML}
            `);

            let tbody = $('#tabelaPlanoMedicamentos');
            tbody.empty();

            if (planos && planos.length > 0) {
                planos.forEach(function(item) {
                    tbody.append(`
                        <tr>
                            <td>${item.medicamento ?? ''}</td>
                            <td>${item.dose ?? ''}</td>
                            <td>${item.via ?? ''}</td>
                            <td>${item.frequencia ?? ''}</td>
                            <td>${item.duracao ?? ''}</td>
                        </tr>
                    `);
                });
            } else {
                tbody.append(`
                    <tr>
                        <td colspan="5" class="text-center">
                            Nenhum plano médico encontrado
                        </td>
                    </tr>
                `);
            }
        });
    }

    function fecharDrawer() {
        $("#drawerPaciente").removeClass("open");
    }

    $(document).on('click', '.delete-record-quarto', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, desassociar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('tarefarios.desassociar_tarefario', ':id') }}`.replace(
                        ':id', recordId)
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
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);

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
                    url: `{{ route('quartos.destroy', ':id') }}`.replace(':id', recordId)
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
