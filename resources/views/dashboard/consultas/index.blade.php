@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Consultas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Consultas</li>
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
                    <form action="{{ route('consultas.index') }}" method="GET">
                        @csrf
                        <div class="card">
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="paciente_id" class="form-label">Pacientes</label>
                                    <select name="paciente_id" id="paciente_id" class="form-control select2">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($pacientes as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['paciente_id'] == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="medico_id" class="form-label">Médicos</label>
                                    <select name="medico_id" id="medico_id" class="form-control select2">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        @foreach ($medicos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['medico_id'] == $item->id ? "selected" : "" }}>{{ $item->funcionario->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        <option value="AGENDADA" {{ $requests['status'] == "AGENDADA" ? "selected" : "" }}>AGENDADA</option>
                                        <option value="CONCLUIDO" {{ $requests['status'] == "CONCLUIDO" ? "selected" : "" }}>CONCLUIDO</option>
                                        <option value="EM ATENDIMENTO" {{ $requests['status'] == "EM ATENDIMENTO" ? "selected" : "" }}>EM ATENDIMENTO</option>
                                        <option value="CANCELADA" {{ $requests['status'] == "CANCELADA" ? "selected" : "" }}>CANCELADA</option>
                                        <option value="ATRASADA" {{ $requests['status'] == "ATRASADA" ? "selected" : "" }}>ATRASADA</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="status" class="form-label"> {{ __('messages.data') }} </label>
                                    <input type="date" name="data_consulta" value="{{ $requests['data_consulta'] ?? "" }}" id="data_consulta" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary"><i class="fas fa-filter"></i> Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar consulta'))
                                <a href="{{ route('consultas.create', ['origem' => 'padrao']) }}" class="btn btn-light-primary">Marcar Consulta</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_link" href="{{ route("consultas-imprimir-all",
                                    [
                                        'data_consulta' => $requests['data_consulta'] ?? "",
                                        'status' => $requests['status'] ?? "",
                                        'medico_id' => $requests['medico_id'] ?? "",
                                        'paciente_id' => $requests['paciente_id'] ?? "",
                                    ]
                                    ) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($consultas)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Consulta Nº</th>
                                        <th>Paciente</th>
                                        <th>Nº Telefone</th>
                                        <th>Médico</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>Hora</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($consultas as $item)
                                    <tr>
                                        <td><a href="{{ route('consultas.show', $item->id) }}">{{ $item->id ?? "" }}</a></td>
                                        <td>{{ $item->paciente->nome }}</td>
                                        <td>{{ $item->paciente->telefone ?? "N/A" }}</td>
                                        <td>{{ $item->medico ? ($item->medico->funcionario ? $item->medico->funcionario->nome : '') : '' }} </td>
                                        @if ($item->status == 'AGENDADA')
                                        <td class="text-light-primary">{{ $item->status }}</td>
                                        @endif
                                        @if ($item->status == 'CONCLUIDO')
                                        <td class="text-light-success">{{ $item->status }}</td>
                                        @endif
                                        @if ($item->status == 'EM ATENDIMENTO')
                                        <td class="text-light-warning">{{ $item->status }}</td>
                                        @endif
                                        @if ($item->status == 'CANCELADA')
                                        <td class="text-light-danger">{{ $item->status }}</td>
                                        @endif
                                        @if ($item->status == 'ATRASADA')
                                        <td class="text-light-danger">{{ $item->status }}</td>
                                        @endif
                                        <td>{{ $item->data_consulta }}</td>
                                        <td>{{ $item->hora_consulta }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar consulta'))
                                                    <a class="dropdown-item" href="{{ route('consultas.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    <a class="dropdown-item" target="_blink" href="{{ route('consultas-imprimir-individual', $item->id) }}"><i class="fas fa-print text-light-primary"></i> {{ __('messages.imprimir') }} </a>
                                                    @endif

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar consulta'))
                                                    <a class="dropdown-item" href="{{ route('consultas.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    @if ($item->status == 'AGENDADA')
                                                    <a class="dropdown-item update-record" data-id="{{ $item->id ?? "" }}" href="{{ route('cancelar_consulta', $item->id) }}">
                                                        <i class="fas fa-cancel text-light-danger"></i> {{ __('messages.cancelar') }}
                                                    </a>
                                                    @endif
                                                    @if ($item->status == 'CANCELADA')
                                                    <a class="dropdown-item update-record" data-id="{{ $item->id ?? "" }}" href="{{ route('cancelar_consulta', $item->id) }}">
                                                        <i class="fas fa-undo text-light-primary"></i> Recuperar Consulta
                                                    </a>
                                                    @endif
                                                    @endif

                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar consulta'))
                                                    <button class="btn btn-light-danger dropdown-item delete-record" data-id="{{ $item->id ?? "" }}">
                                                        <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        @endif

                    </div>
                    <!-- /.card -->
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
                    url: `{{ route('cancelar_consulta', ':id') }}`.replace(':id', recordId)
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
                    url: `{{ route('consultas.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Erro!'
                            , 'Ocorreu um erro ao excluir o registro. Tente novamente.'
                            , 'error');
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
