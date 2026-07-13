@extends('layouts.app')

@section('content')

<style>
    .fc-toolbar h2 {
        text-transform: capitalize;
    }

    .fc-day-header {
        text-transform: capitalize;
    }

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Marcar de Faltas/Presenças</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('marcacoes-faltas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Marcações de Faltas</li>
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
                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 col-md-3">
                                    <label for="funcionario_id" class="form-label">Funcioários</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control @error('funcionario_id') is-invalid @enderror" id="funcionario_id" name="funcionario_id">
                                            @foreach ($funcionarios as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->numero_mecanografico }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="duracao" class="form-label">Duração Horas</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error('duracao') is-invalid @enderror" value="8" id="duracao" name="duracao" placeholder="Informe a Duração">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 @error('status') is-invalid @enderror" name="status" id="status">
                                            <option value="1">Presente</option>
                                            <option value="0">Falta</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="falta_id" class="form-label">Faltas</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control @error('falta_id') is-invalid @enderror" id="falta_id" name="falta_id">
                                            <option value="default">Padrão</option>
                                            <option value="n_justificada">Falta Injustificada</option>
                                            <option value="justificada">Falta Justificada</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div id="calendar"></div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar subsidio')) --}}
                            <button id="saveDates" class="btn btn-light-primary" style="margin-top: 10px; display: none;">{{ __('messages.salvar') }}</button>
                            {{-- @endif --}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var saveButton = document.getElementById('saveDates');
        var selectedDates = [];

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth'
            , height: 600,
            // hiddenDays: [0, 6],
            locale: 'pt-br'
            , selectable: true
            , unselectAuto: false
            , select: function(info) {
                if (!selectedDates.includes(info.startStr)) {
                    selectedDates.push(info.startStr);
                } else {
                    selectedDates = selectedDates.filter(date => date !== info.startStr);
                }

                calendar.addEvent({
                    title: 'Selecionado'
                    , start: info.startStr
                    , allDay: true
                    , backgroundColor: '#FF0000'
                });

                if (selectedDates.length > 0) {
                    saveButton.style.display = 'block';
                } else {
                    saveButton.style.display = 'none';
                }
            }
        });

        calendar.render();

        saveButton.addEventListener('click', function() {
            var funcionarioId = $('#funcionario_id').val();
            var duracao = $('#duracao').val();
            var falta_id = $('#falta_id').val();
            var status = $('#status').val();

            if (funcionarioId === "") {
                alert('Por favor, selecione um funcionário.');
                return;
            }

            $.ajax({
                url: '/reshums/marcacoes-faltas'
                , method: 'POST'
                , data: {
                    _token: '{{ csrf_token() }}', // Laravel CSRF token
                    funcionario_id: funcionarioId
                    , duracao: duracao
                    , falta_id: falta_id
                    , status: status
                    , datas: selectedDates
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    selectedDates = [];
                    saveButton.style.display = 'none';
                    calendar.refetchEvents();

                    Swal.close();

                    showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');

                    window.location.reload();
                }
                , error: function(xhr, status, error) {
                    Swal.close();
                    // Verifica se a resposta contém JSON
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        console.log(xhr.responseJSON.error);

                        showMessage('Erro!', xhr.responseJSON.error, 'error');
                    } else {
                        console.log(error);
                        showMessage('Erro!', error, 'error');
                    }
                }
            });
        });
    });

</script>
@endsection
