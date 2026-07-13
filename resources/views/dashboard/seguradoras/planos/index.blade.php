@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Planos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-hospital') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Planos</li>
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
                    <div class="card">
                        <div class="card-header">

                            <div class="card-tools">
                                <button class="btn btn-light-primary" onclick="toggleModal()">
                                    <i class="fas fa-plus"></i>
                                    Novo Plano
                                </button>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-hover" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>Plano</th>
                                        <th>Tipo</th>
                                        <th>Seguradora</th>
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
                                    @foreach($planos as $plano)
                                    <tr>
                                        <td><a href="{{ route('planos-seguradora.show', $plano->id) }}">{{ $plano->nome }}</a></td>
                                        <td>{{ $plano->tipo }}</td>
                                        <td><a href="{{ route('seguradoras.show', $plano->seguradora_id) }}">{{ $plano->seguradora->nome }}</a></td>
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

        </div><!-- /.container-fluid -->
    </div>

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
                                <label for="seguradora_id">Seguradoras</label>
                                <select name="seguradora_id" id="seguradora_id" class="form-control">
                                    <option value="">Escolher</option>
                                    @foreach ($seguradoras as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="nome">Nome do Plano</label>
                                <input type="text" name="nome" id="nome" class="form-control" placeholder="Informe o nome do plano">
                            </div>

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

    <!-- /.content -->
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
