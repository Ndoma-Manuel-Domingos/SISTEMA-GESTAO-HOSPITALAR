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
                        <li class="breadcrumb-item"><a href="{{ route('seguradoras.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Seguradora</li>
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

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-light-primary">
                        <div class="inner">
                            <h3>{{ count($seguradora->facturas) ?? 0 }}</h3>
                            <p>Total de Facturas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-4">
                    <div class="small-box bg-light-success">
                        <div class="inner">
                            <h3>{{ $facturasPagas ?? 0 }}</h3>
                            <p>Facturas Pagas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-4">
                    <div class="small-box bg-light-warning">
                        <div class="inner">
                            <h3>{{ $facturas_correntes }}</h3>
                            <p>Facturas Correntes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-4">
                    <div class="small-box bg-light-danger">
                        <div class="inner">
                            <h3>{{ $facturas_vencidas }}</h3>
                            <p>Facturas Vencidas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <!-- Coluna esquerda -->
                <div class="col-md-4">
                    <div class="card bg-light-primary card-outline">
                        <div class="card-body box-profile text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset('dist/img/sem-imagem.jpg') }}" alt="Seguradora">
                            <h3 class="profile-username">
                                {{ $seguradora->nome ?? "N/A" }}
                            </h3>
                            <p class="text-muted">
                                {{ $seguradora->nome_fantasia ?? "N/A" }}
                            </p>
                            <span class="badge badge-success">
                                {{ $seguradora->status ?? "N/A" }}
                            </span>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Informações Gerais
                            </h3>
                        </div>
                        <div class="card-body">
                            <strong>Tipo</strong>
                            <p>{{ $seguradora->tipo ?? "N/A" }}</p>
                            <hr>
                            <strong>Sigla</strong>
                            <p>{{ $seguradora->sigla ?? "N/A" }}</p>
                            <hr>
                            <strong>NIF</strong>
                            <p>{{ $seguradora->nif ?? "N/A" }}</p>
                            <hr>
                            <strong>Número</strong>
                            <p>{{ $seguradora->numero ?? "N/A" }}</p>
                        </div>
                    </div>

                </div>
                <!-- Coluna direita -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-light-primary">
                            <h3 class="card-title">
                                Dados da Seguradora
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>E-mail</strong>
                                    <p>{{ $seguradora->email ?? "N/A" }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Website</strong>
                                    <p>{{ $seguradora->website ?? "N/A" }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Contacto</strong>
                                    <p>{{ $seguradora->contacto ?? "N/A" }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Telefone Secundário</strong>
                                    <p>{{ $seguradora->telefone_secundario ?? "N/A" }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Pessoa de Contacto</strong>
                                    <p>{{ $seguradora->pessoa_contato ?? "N/A" }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light-primary">
                            <h3 class="card-title">
                                Endereço
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Cidade</strong>
                                    <p>{{ $seguradora->cidade ?? "N/A" }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Província</strong>
                                    <p>{{ $seguradora->provincia ?? "N/A" }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>País</strong>
                                    <p>{{ $seguradora->pais ?? "N/A" }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light-warning">
                            <h3 class="card-title">
                                Observações
                            </h3>
                        </div>
                        <div class="card-body">
                            {{ $seguradora->observacoes ?? 'Sem observações.' }}
                        </div>
                        <div class="card-footer">
                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar seguradora'))
                            <a class="btn btn-light-success" href="{{ route('seguradoras.edit', $seguradora->id) }}"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                            @endif

                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar seguradora'))
                            <a class="btn btn-light-danger delete-record" data-id="{{ $seguradora->id }}" href="#"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-id-card"></i>
                                Planos da Seguradora
                            </h3>
                            <div class="card-tools">
                                <button class="btn btn-light-primary" onclick="toggleModal()">
                                    <i class="fas fa-plus"></i>
                                    Novo Plano
                                </button>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-hover" id="carregar_tabela1">
                                <thead>
                                    <tr>
                                        <th>Plano</th>
                                        <th>Tipo</th>
                                        <th>P. Cobertura</th>
                                        <th>P .Copagamento</th>
                                        <th>Limite anual</th>
                                        <th>Limite atendimento</th>
                                        <th>Limite atendimento</th>
                                        <th>Status</th>
                                        <th width="120">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seguradora->planos as $plano)
                                    <tr>
                                        <td><a href="{{ route('planos-seguradora.show', $plano->id) }}">{{ $plano->nome }}</a></td>
                                        <td>{{ $plano->tipo }}</td>
                                        <td>{{ $plano->percentual_cobertura }}</td>
                                        <td>{{ $plano->percentual_coparticipacao }}</td>
                                        <td>{{ number_format($plano->limite_anual) }} Kz</td>
                                        <td>{{ number_format($plano->limite_por_atendimento) }} Kz</td>
                                        <td>{{ $plano->necessita_autorizacao ? 'Sim' : 'Não' }}</td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ $plano->ativo ? 'Sim' : 'Não'  }}
                                            </span>
                                        </td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar seguradora'))
                                                    <a class="dropdown-item" href="{{ route('planos-seguradora.show', $plano->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar seguradora'))
                                                    <a href="#" data-id="{{ $plano->id }}" class="dropdown-item edit-folder text-light-success"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar seguradora'))
                                                    <a href="#" data-id="{{ $plano->id }}" class="dropdown-item delete-plano text-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Factura</th>
                                        <th>Operador</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>Vencimento</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">Valor Total</th>
                                        <th class="text-right">Dívida</th>
                                        <th class="text-right">Valor Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($seguradora->facturas)
                                    @foreach ($seguradora->facturas as $item)
                                    <tr>
                                        <td><a href="{{ route('fechos-contas-seguradora.imprimir', $item->id) }}" target="_blink">{{ $item->numero}}</a> </td>
                                        <td>{{ $item->user->name ?? "" }}</td>
                                        <td>{{ $item->data_emissao }}</td>
                                        <td>{{ $item->data_vencimento }}</td>
                                        <td class="text-uppercase">
                                            {{ $item->status }}
                                        </td>
                                        <td class="text-right">{{ number_format($item->total, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->saldo, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->valor_pago, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->


    <div class="modal fade" id="modalPlano">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('planos-seguradora.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-light-primary">
                        <h4 class="modal-title">
                            Novo Plano
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="nome">Nome do Plano</label>
                                <input type="text" name="nome" id="nome" class="form-control" placeholder="Informe o nome do plano">
                            </div>

                            <input type="hidden" name="seguradora_id" id="seguradora_id" value="{{ $seguradora->id }}">

                            <div class="col-md-6 col-12 mb-3">
                                <label for="codigo">Codigo</label>
                                <input type="text" name="codigo" id="codigo" class="form-control" placeholder="Informe o codigo do plano">
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="tipo">Tipo</label>
                                <select name="tipo" id="tipo" class="form-control">
                                    <option value="Individual">Individual</option>
                                    <option value="Familiar">Familiar</option>
                                    <option value="Empresarial">Empresarial</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="percentual_cobertura">Percentual da cobertura (%)</label>
                                <input type="number" name="percentual_cobertura" id="percentual_cobertura" class="form-control" placeholder="Informe o percentual cobertura do plano">
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="percentual_coparticipacao">Percentual da coparticipação (%)</label>
                                <input type="number" name="percentual_coparticipacao" id="percentual_coparticipacao" class="form-control" placeholder="Informe o percentual coparticipação do plano">
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="limite_anual">Limite Anual</label>
                                <input type="number" name="limite_anual" id="limite_anual" class="form-control" placeholder="Informe o limite anual do plano">
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="limite_por_atendimento">Limite por Atendimento</label>
                                <input type="number" name="limite_por_atendimento" id="limite_por_atendimento" class="form-control" placeholder="Informe o limite por atendimento do plano">
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="dias_carencia">dias de carência</label>
                                <input type="number" name="dias_carencia" id="dias_carencia" value="30" class="form-control" placeholder="Informe dias carência">
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label d-block">Necessita autorização?</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="necessita_autorizacao" id="necessita_autorizacao_sim" value="1" checked>
                                    <label class="form-check-label" for="necessita_autorizacao_sim">Sim</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="necessita_autorizacao" id="necessita_autorizacao_nao" value="0">
                                    <label class="form-check-label" for="necessita_autorizacao_nao">Não</label>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="form-label d-block">Plano ativo?</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ativo" id="ativo_sim" value="1" checked>
                                    <label class="form-check-label" for="ativo_sim">Sim</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ativo" id="ativo_nao" value="0">
                                    <label class="form-check-label" for="ativo_nao">Não</label>
                                </div>
                            </div>

                            <div class="col-md-12 col-12 mt-3">
                                <label for="descricao">Descrição</label>
                                <textarea class="form-control" id="descricao" rows="4" name="descricao" placeholder="Digite uma descrição"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-light-secondary" data-dismiss="modal">
                            Cancelar
                        </button>
                        <button class="btn btn-light-primary">
                            Salvar Plano
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    let PastaID = null;
    let modalVisible = false;

    const modalElement = document.getElementById('modalPlano');
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

    $(document).on('click', '.edit-folder', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // Envia a solicitação AJAX para excluir o registro
        $.ajax({
            url: `{{ route('planos-seguradora.edit', ':id') }}`.replace(':id', recordId)
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
                PastaID = response.data.id;
                $("#nome").val(response.data.nome);
                $("#codigo").val(response.data.codigo);
                $("#seguradora_id").val(response.data.seguradora_id);
                $("#percentual_cobertura").val(response.data.percentual_cobertura);
                $("#percentual_coparticipacao").val(response.data.percentual_coparticipacao);
                $("#limite_anual").val(response.data.limite_anual);
                $("#limite_por_atendimento").val(response.data.limite_por_atendimento);
                $("#necessita_autorizacao").val(response.data.necessita_autorizacao);
                $("#tipo").val(response.data.tipo);
                $("#descricao").val(response.data.descricao);
            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
    });

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

    $(document).on('click', '.delete-plano', function(e) {

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
                    url: `{{ route('planos-seguradora.destroy', ':id') }}`.replace(':id'
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
                    url: `{{ route('seguradoras.destroy', ':id') }}`.replace(':id'
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
        $("#carregar_tabela1").DataTable({
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
