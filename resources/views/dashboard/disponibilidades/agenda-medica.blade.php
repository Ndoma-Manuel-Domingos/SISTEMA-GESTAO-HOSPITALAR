@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Agenda Médica</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Agenda</li>
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
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="medico_id">Médico</label>
                                    <select id="medico_id" name="medico_id" class="form-control select2">
                                        <option value="">Selecionar Médico</option>
                                        @foreach($medicos as $medico)
                                        <option value="{{ $medico->id }}">{{ $medico->funcionario->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Mostrar</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="mostrarDisponibilidade">
                                        <label for="mostrarDisponibilidade" class="form-check-label">Disponibilidade</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="mostrarConsultas">
                                        <label for="mostrarConsultas" class="form-check-label">Consultas</label>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <br>
                                    <span class="badge" style="background:#28a745">Disponível</span>
                                    <span class="badge" style="background:#dc3545">Férias</span>
                                    <span class="badge" style="background:#fd7e14">Licença</span>
                                    <span class="badge" style="background:#6f42c1">Congresso</span>
                                    <span class="badge" style="background:#007bff">Consulta</span>
                                </div>
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
</div>
<!-- /.content-wrapper -->

@endsection
@section('scripts')
<script>
    const calendario = `{!! route('agendas-medicas-calendario.index') !!}`;

    $(document).ready(function() {
        $('#medico_id').select2();

        let calendar = new FullCalendar.Calendar(
            document.getElementById('calendar'), {
                locale: 'pt'
                , initialView: 'timeGridWeek'
                , height: 800
                , firstDay: 1
                , headerToolbar: {
                    left: 'prev,next today'
                    , center: 'title'
                    , right: 'dayGridMonth,timeGridWeek,timeGridDay'
                }
                , events: function(info, successCallback) {
                    let medico_id = $('#medico_id').val();
                    if (!medico_id) {
                        successCallback([]);
                        return;
                    }
                    $.ajax({
                        url: calendario
                        , data: {
                            medico_id: medico_id
                            , inicio: info.startStr
                            , fim: info.endStr
                            , disponibilidade: $('#mostrarDisponibilidade').is(':checked')
                            , consultas: $('#mostrarConsultas').is(':checked')
                        }
                        , success: function(data) {
                            successCallback(data);
                        }
                    });
                }
                , eventClick: function(info) {
                    let evento = info.event;
                    alert(evento.title);
                }
            }
        );

        calendar.render();

        $('#medico_id').change(function() {
            calendar.refetchEvents();
        });

        $('#mostrarDisponibilidade').change(function() {
            calendar.refetchEvents();
        });

        $('#mostrarConsultas').change(function() {
            calendar.refetchEvents();
        });
    });

</script>
@endsection
