@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Disponibilidade Médica</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Médica</li>
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
                            {{-- <h4>Disponibilidade Médica</h4> --}}
                            <div class="mb-3">
                                <span class="badge" style="background:#28a745">
                                    Disponível
                                </span>
                                <span class="badge" style="background:#6c757d">
                                    Indisponível
                                </span>
                                <span class="badge" style="background:#dc3545">
                                    Férias
                                </span>
                                <span class="badge" style="background:#fd7e14">
                                    Licença
                                </span>
                                <span class="badge" style="background:#6f42c1">
                                    Congresso
                                </span>
                                <span class="badge" style="background:#17a2b8">
                                    Outros
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    @include('dashboard.disponibilidades.modal')

</div>
<!-- /.content-wrapper -->

@endsection
@section('scripts')
<script>
    const store = `{!! route('disponibilidades-medica.store') !!}`;
    const eventos = `{!! route('disponibilidades-eventos') !!}`;

    document.addEventListener('DOMContentLoaded', function() {
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'timeGridWeek'
            , locale: 'pt'
            , selectable: true
            , editable: true
            , headerToolbar: {
                left: 'prev,next today'
                , center: 'title'
                , right: 'dayGridMonth,timeGridWeek,timeGridDay'
            }
            , select: function(info) {
                limparModal();
                $('#inicio').val(info.startStr);
                $('#fim').val(info.endStr);
                $('#modalDisponibilidade').modal('show');
            }
            , events: eventos
            , eventDrop: function(info) {
                atualizarDataEvento(info);
            }
            , eventClick: function(info) {

                let recordId = info.event.id; // Obtém o ID do registro
                const url = `{{ route('disponibilidades-medica.show', ':id') }}`.replace(':id', recordId);

                $.get(url, function(data) {
                    $('#id').val(data.id);
                    $('#estado').val(data.estado);
                    $('#medico_id').val(data.medico_id).trigger('change');
                    $('#inicio').val(data.data_inicio);
                    $('#fim').val(data.data_fim);
                    $('#obs').val(data.observacao);
                    $('#modalDisponibilidade').modal('show');
                });
            }
            , eventResize: function(info) {
                atualizarDataEvento(info);
            }
        });

        calendar.render();

        function atualizarDataEvento(info) {
            let recordId = info.event.id; // Obtém o ID do registro
            const url = `{{ route('disponibilidades-drop', ':id') }}`.replace(':id', recordId);

            $.ajax({
                url: url
                , method: 'PUT'
                , data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                    , inicio: info.event.startStr
                    , fim: info.event.endStr
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function() {
                    $('#modalDisponibilidade').modal('hide');
                    calendar.refetchEvents();
                    Swal.close();
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    showMessage('Erro de Validação!', 'Ocorreu um erro inesperado!', 'error');
                }
            });
        }

        function limparModal() {
            $('#id').val('');
            $('#medico_id').val('').trigger('change');
            $('#estado').val('Disponivel');
            $('#inicio').val('');
            $('#fim').val('');
            $('#obs').val('');
        }

        $('#salvar').click(function() {

            let id = $('#id').val();
            let url = store;
            let method = 'POST';
            if (id != '') {
                url = store + "/" + id;
                method = 'PUT';
            }

            $.ajax({
                url: url
                , method: method
                , data: {
                    _token: $('input[name=_token]').val()
                    , medico_id: $('#medico_id').val()
                    , estado: $('#estado').val()
                    , inicio: $('#inicio').val()
                    , fim: $('#fim').val()
                    , obs: $('#obs').val()
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function() {
                    $('#modalDisponibilidade').modal('hide');
                    calendar.refetchEvents();

                    Swal.close();
                    showMessage('Sucesso!', "Dados actualizados", 'success');

                    $("#medico_id").val("");
                    $("#obs").val("");
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
            });
        });

        $('#btnExcluir').click(function() {

            let recordId = $('#id').val(); // Obtém o ID do registro

            Swal.fire({
                title: 'Tem certeza?'
                , text: 'Deseja excluir esta disponibilidade?'
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#198754'
                , cancelButtonColor: '#6c757d'
                , confirmButtonText: 'Sim, excluir'
                , cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('disponibilidades-medica.destroy', ':id') }}`.replace(':id', recordId)
                        , type: 'DELETE'
                        , data: {
                            _token: $('input[name="_token"]').val()
                        }
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function() {
                            $('#modalDisponibilidade').modal('hide');
                            calendar.refetchEvents();
                            Swal.close();
                            showMessage('Excluído!', 'A disponibilidade foi excluída com sucesso.', 'success');
                        }
                        , error: function() {
                            // Feche o alerta de carregamento
                            Swal.close();
                            showMessage('Erro!', 'Não foi possível excluir a disponibilidade.', 'error');
                        }
                    });
                }
            });
        });
    });

</script>
@endsection
