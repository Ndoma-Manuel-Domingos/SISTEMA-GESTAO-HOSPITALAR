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
                        <li class="breadcrumb-item"><a href="{{ route('equipas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Equipas</li>
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
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-4">{{ $equipe->nome }}</h5>
                            <p><strong>Responsável:</strong> {{ $equipe->responsavel->nome ?? 'N/A' }}</p>
                            <h6>Área de Actuação: {{ $equipe->area_atuacao }}</h6>
                        </div>
                        <div class="card-body">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-4">Membros da Equipe</h5>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>Função</th>
                                        <th>Escala Semanal</th>
                                        <th>{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipe->membros as $key => $medico)
                                    <tr>
                                        <td class="align-middle">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ $medico->profissional->nome }}</td>
                                        <td class="align-middle">{{ $medico->cargo }}</td>
                                        <td>
                                            @if ($medico->profissional->horarios)
                                            @foreach ($medico->profissional->horarios as $horario)
                                            <small style="border: 1px solid #eaeaea" class="d-block p-1">
                                                {{ ucfirst($horario->dia_semana) }}: {{ $horario->data_inicio }} as {{ $horario->hora_inicio }} - {{ $horario->data_fim }} as {{ $horario->hora_fim }} ({{ ucfirst($horario->turno) }}) ({{ ucfirst($horario->tipo) }})
                                                <a href="#" data-id="{{ $horario->id }}" class="delete-horario btn btn-light-danger py-0 px-1 float-right"><i class="fas fa-trash"></i></a>
                                            </small>
                                            @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-light-success" onclick="openCreateEscala({{ $medico->profissional->id }})">Definir Escala</button>
                                            <button class="btn btn-sm btn-light-primary" onclick="listarEscalas({{ $medico->profissional->id }})">Listar Escalas</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-sm btn-light-success" onclick="alert('ola')">Definir Escala Em Geral</button>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>

    <!-- Modal Criar/Editar Escala -->
    <div class="modal fade" id="escalaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="escalaForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="escalaTitle">Definir Escala</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="escala_id">
                        <input type="hidden" id="funcionario_id">
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
                                    <label class="form-label">Posto</label>
                                    <select name="posto_id" id="posto_id" class="form-control">
                                        <option value="">Escolher</option>
                                        @foreach ($postos as $item)
                                        <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Data Entrada</label>
                                    <input type="date" id="data_entrada" value="{{ date("Y-m-d") }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Hora Entrada</label>
                                    <input type="time" id="hora_entrada" value="{{ date("h:i") }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Data Saída</label>
                                    <input type="date" id="data_saida" value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Hora Saída</label>
                                    <input type="time" id="hora_saida" value="{{ date("h:i") }}" class="form-control" required>
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

    <!-- Modal Listar Escalas -->
    <div class="modal fade" id="listarModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Escalas do Funcionário</h5>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Posto</th>
                                <th>Dia Semana</th>
                                <th>Entrada</th>
                                <th>Saída</th>
                                <th>Data Entrada</th>
                                <th>Data Saída</th>
                                <th>{{ __('messages.accoes') }}</th>
                            </tr>
                        </thead>
                        <tbody id="listarBody">
                            <!-- Populado via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    let saveMethod = 'create';
    let listarModal = new bootstrap.Modal(document.getElementById('listarModal'));
    let escalaModal = new bootstrap.Modal(document.getElementById('escalaModal'));

    function openCreateEscala(funcionario_id) {
        saveMethod = 'create';
        document.getElementById('escalaForm').reset();
        document.getElementById('escala_id').value = '';
        document.getElementById('funcionario_id').value = funcionario_id;
        document.getElementById('escalaTitle').innerText = 'Definir Escala';
        escalaModal.show();
    }

    function openEditEscala(escala) {
        saveMethod = 'edit';

        console.log(escala)

        document.getElementById('escala_id').value = escala.id;
        document.getElementById('funcionario_id').value = escala.funcionario_id;
        document.getElementById('dia_semana').value = escala.dia_semana;
        document.getElementById('posto_id').value = escala.posto_id;
        document.getElementById('hora_entrada').value = escala.hora_inicio;
        document.getElementById('hora_saida').value = escala.hora_fim;
        document.getElementById('data_entrada').value = escala.data_inicio;
        document.getElementById('data_saida').value = escala.data_fim;
        document.getElementById('escalaTitle').innerText = 'Editar Escala';
        listarModal.hide();
        escalaModal.show();
    }

    document.getElementById('escalaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let id = document.getElementById('escala_id').value;
        let url = saveMethod === 'create' ? '/escalas' : '/escalas/' + id;
        let method = saveMethod === 'create' ? 'POST' : 'PUT';

        // Você pode adicionar um loader aqui, se necessário
        progressBeforeSend();

        fetch(url, {
                method: method
                , headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    , 'Content-Type': 'application/json'
                }
                , body: JSON.stringify({
                    funcionario_id: document.getElementById('funcionario_id').value
                    , posto_id: document.getElementById('posto_id').value
                    , dia_semana: document.getElementById('dia_semana').value
                    , hora_entrada: document.getElementById('hora_entrada').value
                    , data_entrada: document.getElementById('data_entrada').value
                    , data_saida: document.getElementById('data_saida').value
                    , hora_saida: document.getElementById('hora_saida').value
                , })
            })
            .then(res => res.json())
            .then(data => {
                // Feche o alerta de carregamento
                Swal.close();
                showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');
                window.location.reload();
            }).catch(err => {
                Swal.close();
                showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
            });
    });


    function listarEscalas(funcionario_id) {
        fetch('/escalas?funcionario_id=' + funcionario_id)
            .then(res => res.json())
            .then(data => {
                let tbody = document.getElementById('listarBody');
                tbody.innerHTML = '';
                data.forEach(e => {
                    tbody.innerHTML += `
                <tr>
                    <td>${e.posto ? e.posto.nome : ''} <br/> <small>${e.posto ? e.posto.endereco : ''}</small></td>
                    <td>${e.dia_semana}</td>
                    <td>${e.hora_inicio}</td>
                    <td>${e.hora_fim}</td>
                    <td>${e.data_inicio}</td>
                    <td>${e.data_fim}</td>
                    <td>
                        <button class="btn btn-sm btn-light-warning" onclick='openEditEscala(${JSON.stringify(e)})'>Editar</button>
                        <button class="btn btn-sm btn-light-danger" onclick="deleteEscala(${e.id}, ${funcionario_id})">Eliminar</button>
                    </td>
                </tr>
            `;
                });
                listarModal.show();
            });
    }


    function deleteEscala(id, funcionario_id) {

        Swal.fire({
            title: "Tens certeza?"
            , text: "Esta escala será eliminada permanentemente!"
            , icon: "warning"
            , showCancelButton: true
            , confirmButtonColor: "#d33"
            , cancelButtonColor: "#3085d6"
            , confirmButtonText: "Sim, eliminar!"
            , cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/escalas/' + id, {
                        method: 'DELETE'
                        , headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.reload();
                    })
                    .catch(err => {
                        Swal.close();
                        showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                    });
            }
        });
    }


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

</script>
@endsection
