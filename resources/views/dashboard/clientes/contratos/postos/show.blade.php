@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }} - {{ $posto->nome }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('clientes-contratos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">
                            Posto
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Dados Cliente</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <table class="table text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <th>Representante do posto/Gerente</th>
                                                    <td class="text-right">{{ $posto->representante_posto ?? "" }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Contacto do posto</th>
                                                    <td class="text-right">{{ $posto->contacto_posto ?? "" }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Contrato</th>
                                                    <td class="text-right"><a href="{{ route('clientes-contratos.show', $posto->contrato->id) }}">{{ $posto->contrato->codigo_contrato }}</a></td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('messages.designacao') }}</th>
                                                    <td class="text-right">{{ $posto->nome ?? '-------------' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Endereço</th>
                                                    <td class="text-right">{{ $posto->endereco ?? '-------------' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Latitude</th>
                                                    <td class="text-right"> {{ $posto->latitude ?? '-------------' }}</td>
                                                </tr>
                                                </tr>
                                                <tr>
                                                    <th>Longitude</th>
                                                    <td class="text-right"> {{ $posto->longitude ?? '-------------' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <table class="table text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <th>Horário Permitido</th>
                                                    <td class="text-right">{{ $posto->horario_permitido ?? '-------------' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Instruções Especiais</th>
                                                    <td class="text-right"> {{ $posto->instrucoes_especiais ?? '-------------' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-8 ">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Equipa Responsável</h6>
                                </div>
                                <div class="card-body">
                                    @if ($posto->equipa)
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <table class="table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Nome</th>
                                                        <th>Área Actuação</th>
                                                        <th>Estado</th>
                                                        <th>Responsável</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $posto->id ?? '-------------' }}</td>
                                                        <td><a href="{{ route('equipas.show', $posto->equipa->id) }}">{{ $posto->equipa ? $posto->equipa->nome : '-------------' }}</a></td>
                                                        <td>{{ $posto->equipa ? $posto->equipa->area_atuacao : '-------------' }}</td>
                                                        <td>{{ $posto->equipa ? $posto->equipa->status : '-------------' }}</td>
                                                        <td><a href="{{ route('funcionarios.show', $posto->equipa->responsavel->id) }}">{{ $posto->equipa ? ($posto->equipa->responsavel ? $posto->equipa->responsavel->nome : "")  : "" }}</a></td>
                                                    </tr>

                                                    <tr>
                                                        <th colspan="5">Membros da Equipa</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Numero Mecanografico</th>
                                                        <th>Nome</th>
                                                        <th colspan="2">Escola</th>
                                                        <th>Genero</th>
                                                    </tr>

                                                    @foreach ($posto->equipa->membros as $item)
                                                    <tr>
                                                        <td><a href="{{ route('funcionarios.show', $item->profissional->id) }}">{{ $item->profissional->numero_mecanografico ?? "" }}</a></td>
                                                        <td>{{ $item->profissional->conta ?? "" }} - {{ $item->profissional->nome ?? "" }}</td>
                                                        <td colspan="2">
                                                            @if ($item->profissional->horarios)
                                                            @foreach ($item->profissional->horarios as $horario)
                                                            <small style="border: 1px solid #eaeaea" class="d-block p-1">
                                                                {{ ucfirst($horario->dia_semana) }}: {{ $horario->hora_inicio }} - {{ $horario->hora_fim }} ({{ ucfirst($horario->turno) }}) ({{ ucfirst($horario->tipo) }})
                                                                <a href="#" data-id="{{ $horario->id }}" class="delete-horario btn btn-light-danger py-0 px-1 float-right"><i class="fas fa-trash"></i></a>
                                                            </small>
                                                            @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->profissional->genero ?? "Indefinido" }}</td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-light-primary btn-sm" onclick="openCreateEscala()">Definir Escola de Trabalho</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Recursos (Equipamentos)</h6>
                                </div>
                                <div class="card-body">
                                    @if ($posto->recursos)
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <table class="table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Nome</th>
                                                        <th>Serie</th>
                                                        <th>Descrição</th>
                                                        <th>Acções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($posto->recursos as $item)
                                                    <tr>
                                                        <td>{{ $item->recurso->id ?? "" }}</td>
                                                        <td><a href="{{ route('equipamentos-activos.show', $item->recurso->id) }}">{{ $item->recurso->conta->numero ?? "" }} - {{ $item->recurso->nome ?? "" }}</a></td>
                                                        <td>{{ $item->recurso->numero_serie ?? "" }}</td>
                                                        <td>{{ $item->descricao ?? "" }}</td>
                                                        <td>
                                                            <a href="#" data-id="{{ $item->id ?? "" }}" class="delete-recurso btn btn-light-danger py-0 px-1"><i class="fas fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-light-primary btn-sm" onclick="openCreateRecurso()">Atribuir Recursos de Trabalho</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Modal Criar/Editar Escala -->
    <div class="modal fade" id="escalaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="formEscalaModal" action="{{ route('escalas.create_horario') }}">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <h5 class="modal-title" id="escalaTitle">Definir Escala</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="posto_id" id="posto_id" value="{{ $posto->id }}">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Dia de semana</label>
                                    <select name="dia_semana" id="dia_semana" class="form-control" required>
                                        <option value="segunda">Segunda</option>
                                        <option value="terca">Terça</option>
                                        <option value="quarta">Quarta</option>
                                        <option value="quinta">Quinta</option>
                                        <option value="sexta">Sexta</option>
                                        <option value="sabado">Sabado</option>
                                        <option value="domingo">Domingo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Funcionários</label>
                                    <select name="funcionario_id" id="funcionario_id" class="form-control funcionario_id" required>
                                        <option value="">Escolher</option>
                                        @foreach ($posto->equipa->membros as $item)
                                        <option value="{{ $item->profissional->id ?? "" }}">{{ $item->profissional->nome ?? "" }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hora Entrada</label>
                                    <input type="time" name="hora_entrada" id="hora_entrada" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hora Saída</label>
                                    <input type="time" name="hora_saida" id="hora_saida" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">{{ __('messages.cancelar') }}</button>
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Criar/Editar Escala -->
    <div class="modal fade" id="recursosModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="formRecursoModal" action="{{ route('escalas.create_recursos') }}">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <h5 class="modal-title" id="escalaTitle">Atribuir Recursos(Equipamento)</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="posto_id" id="posto_id" value="{{ $posto->id }}">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Selecione o Equipamento</label>
                                    <select name="recurso_id[]" id="recurso_id" class="form-control recurso_id select2" multiple required>
                                        <option value="">Escolher</option>
                                        @foreach ($equipamentos as $item)
                                        <option value="{{ $item->id ?? "" }}">{{ $item->nome ?? "" }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="descricao">Equipamento</label>
                                    <textarea name="descricao" id="descricao" cols="30" rows="5" class="form-control" placeholder="Podes descrever o estado do equipamentos"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">{{ __('messages.cancelar') }}</button>
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    let escalaModal = new bootstrap.Modal(document.getElementById('escalaModal'));
    let recursosModal = new bootstrap.Modal(document.getElementById('recursosModal'));

    function openCreateEscala() {
        escalaModal.show();
    }

    function openCreateRecurso() {
        recursosModal.show();
    }

    $(document).ready(function() {
        $('#formEscalaModal').on('submit', function(e) {
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

    $(document).ready(function() {
        $('#formRecursoModal').on('submit', function(e) {
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

    $(document).on('click', '.delete-recurso', function(e) {

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
                    url: `{{ route('escalas.destroy-recursos', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });


    $(document).on('click', '.delete-horario', function(e) {
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
                    url: `{{ route('escalas.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
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
                    url: `{{ route('clientes-contratos.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.delete-record-tipo-posto', function(e) {

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
                    url: `{{ route('contratos-postos.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });

</script>
@endsection
