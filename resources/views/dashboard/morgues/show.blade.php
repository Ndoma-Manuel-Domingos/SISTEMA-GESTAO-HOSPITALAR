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
                        <li class="breadcrumb-item"><a href="{{ route('morgues.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.morgue') }}</li>
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
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 table-responsive"">
                                    <table class=" table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <th>Morgue Nº</th>
                                            <td class="text-right">{{ $morgue->id ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Data da Liberação</th>
                                            <td class="text-right">{{ $morgue->data_liberacao ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Hora da Liberação</th>
                                            <td class="text-right">{{ $morgue->hora_liberacao ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Obito</th>
                                            <td class="text-right">{{ $morgue->obito->documento_declaracao ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Causa do Obito</th>
                                            <td class="text-right">{{ $morgue->obito->causa_obito ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Data e Hora do obito</th>
                                            <td class="text-right">{{ $morgue->obito->data_obito ?? "---" }} {{ $morgue->obito->hora_obito ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Local</th>
                                            <td class="text-right">{{ $morgue->obito->local_obito ?? "---" }} {{ $morgue->obito->local_obito ?? "---" }}</td>
                                        </tr>

                                    </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-md-6  table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('messages.estados') }}</th>
                                                <td class="text-right">{{ $morgue->status ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Gaveta</th>
                                                <td class="text-right">{{ $morgue->gaveta->nome ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Camara</th>
                                                <td class="text-right">{{ $morgue->camara->nome ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Temperatura armazenamento</th>
                                                <td class="text-right">{{ $morgue->temperatura_armazenamento ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.observacao') }}</th>
                                                <td class="text-right">{{ $morgue->observacoes_iniciais ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Nome do Paciente</th>
                                                <td class="text-right">{{ $morgue->obito->paciente->nome ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Documento Paciente</th>
                                                <td class="text-right">{{ $morgue->obito->paciente->nif ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.idade') }}</th>
                                                <td class="text-right">{{ $morgue->obito->paciente->idade($morgue->obito->paciente->data_nascimento) ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Genero Paciente</th>
                                                <td class="text-right">{{ $morgue->obito->paciente->genero ?? "---" }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route("morgues.imprimir", $morgue->id) }}" target="_blank" class="btn  bg-light-primary" btn-app"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}</a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                @if ($morgue->liberacao)
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">DADOS DA LIBERAÇÃO DO CORPO</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 table-responsive"">
                                        <table class=" table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <th>Morgue Saída Nº</th>
                                            <td class="text-right">{{ $morgue->liberacao->morgue_registro_id ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Data da Liberação</th>
                                            <td class="text-right">{{ $morgue->liberacao->data_liberacao ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Hora da Liberação</th>
                                            <td class="text-right">{{ $morgue->liberacao->hora_liberacao ?? "---" }}</td>
                                        </tr>

                                    </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-md-6  table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Nome responsável retirada</th>
                                                <td class="text-right">{{ $morgue->liberacao->nome_responsavel_retirada ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Documento responsável</th>
                                                <td class="text-right">{{ $morgue->liberacao->documento_responsavel ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Relacionamento</th>
                                                <td class="text-right">{{ $morgue->liberacao->relacionamento ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.observacao') }}</th>
                                                <td class="text-right">{{ $morgue->liberacao->observacoes ?? "---" }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route("morgues.liberacao-imprimir", $morgue->liberacao->id) }}" target="_blank" class="btn  bg-light-primary" btn-app"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}</a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                @endif
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>


<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    let PastaID = null;
    let modalVisible = false;

    const modalElement = document.getElementById('modal-lg');
    const modalInstance = new bootstrap.Modal(modalElement);

    const modalElementAlta = document.getElementById('modal-lg-alta');
    const modalInstanceAlta = new bootstrap.Modal(modalElementAlta);

    const modalElementObito = document.getElementById('modal-lg-obito');
    const modalInstanceObito = new bootstrap.Modal(modalElementObito);

    const modalElementTransferir = document.getElementById('modal-lg-transferir');
    const modalInstanceTransferir = new bootstrap.Modal(modalElementTransferir);

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

    function toggleModalAlta() {
        if (modalVisible) {
            modalInstanceAlta.hide();
            modalVisible = false;
        } else {
            modalInstanceAlta.show();
            modalVisible = true;
        }
    }

    function toggleModalObito() {
        if (modalVisible) {
            modalInstanceObito.hide();
            modalVisible = false;
        } else {
            modalInstanceObito.show();
            modalVisible = true;
        }
    }

    function toggleModalTransferencia() {
        if (modalVisible) {
            modalInstanceTransferir.hide();
            modalVisible = false;
        } else {
            modalInstanceTransferir.show();
            modalVisible = true;
        }
    }


    $(document).ready(function() {
        // Handler do form de atendimento
        $("#form_atendimento").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });

        // Handler do segundo form
        $("#form_alta").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });

        // Handler do segundo form
        $("#form_obito").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });

        // Handler do segundo form
        $("#form_transferencia").on('submit', function(e) {
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
                    url: `{{ route('internamentos.destroy', ':id') }}`.replace(':id'
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
