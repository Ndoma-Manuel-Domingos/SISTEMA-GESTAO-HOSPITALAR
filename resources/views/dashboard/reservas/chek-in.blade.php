@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Chech In Diário</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Reserva</li>
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
                <div class="col-12 col-md-12 bg-light">
                    <div class="card">
                        <form action="{{ route('reservas.check_in_diario') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="cliente_id" class="form-label">
                                        @if ($empresa_logada->empresa->tem_perfil('Gestão Hotelaria'))
                                        Hospodes
                                        @else
                                        Clientes
                                        @endif
                                    </label>
                                    <select type="text" class="form-control select2" name="cliente_id">
                                        <option value="">{{ __('messages.todos') }}</option>
                                        @foreach ($clientes as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['cliente_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="quarto_id" class="form-label">
                                        Quartos
                                    </label>
                                    <select type="text" class="form-control select2" name="quarto_id">
                                        <option value="">{{ __('messages.todos') }}</option>
                                        @foreach ($quartos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['quarto_id'] == $item->id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="tipo_reserva_id" class="form-label">
                                        Tipo de Reserva
                                    </label>
                                    <select type="text" class="form-control select2" name="tipo_reserva_id">
                                        <option value="">{{ __('messages.todos') }}</option>
                                        @foreach ($tipos_reservas as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['tipo_reserva_id'] == $item->id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="status_pagamento" class="form-label">
                                        Estado Pagamento
                                    </label>
                                    <select type="text" class="form-control select2" name="status_pagamento">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        <option value="EFECTUADO" {{ $requests['status_pagamento'] == 'EFECTUADO' ? 'selected' : '' }}> EFECTUADO</option>
                                        <option value="NAO EFECTUADO" {{ $requests['status_pagamento'] == 'NAO EFECTUADO' ? 'selected' : '' }}> NÃO EFECTUADO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-light-primary btn-sm ml-2 text-right"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar reserva'))
                                <a href="{{ route('reservas.create') }}" class="btn btn-light-primary">Fazer Nova
                                    Reserva</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('pdf-reservas', [
                                    'cliente_id' => $requests['cliente_id'],
                                    'quarto_id' => $requests['quarto_id'],
                                    'status_reserva' => $requests['status_reserva'],
                                    'tipo_reserva_id' => $requests['tipo_reserva_id'],
                                    'status_pagamento' => $requests['status_pagamento'],
                                    'data_inicio' => date("Y-m-d"),
                                ]) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($reservas)
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Referência</th>
                                        <th rowspan="2">{{ __('messages.nome') }}</th>

                                        <th class="text-center">Previsão Entrada</th>
                                        <th class="text-center">Previsão Saída</th>
                                        <th class="text-center">Check IN</th>
                                        <th class="text-center">Check OUT</th>
                                        <th rowspan="2">{{ __('messages.estados') }}</th>

                                        <th rowspan="2">Dias</th>
                                        <th rowspan="2">Pagamento</th>
                                        <th rowspan="2">{{ __('messages.total') }}</th>
                                        <th rowspan="2">{{ __('messages.accoes') }} </th>
                                    </tr>

                                    <tr>
                                        <th class="text-center">Data/Hora</th>
                                        <th class="text-center">Data/Hora</th>

                                        <th class="text-center">Data/Hora</th>
                                        <th class="text-center">Data/Hora</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($reservas as $item)
                                    <tr style="background-color: {{ $item->status == 'CANCELADO' ? 'rgba(138, 39, 39, .3)' : '' }}">
                                        <td>{{ $item->codigo_referencia }}</td>
                                        <td><a href="{{ route('clientes.show', $item->cliente->id) }}">{{ $item->cliente->nome }}</a></td>

                                        <td>{{ $item->data_inicio }} - {{ $item->hora_entrada }}</td>
                                        <td>{{ $item->data_final }} - {{ $item->hora_saida }}</td>

                                        <td>{{ $item->data_check_in }} - {{ $item->hora_check_in }}</td>
                                        <td>{{ $item->data_check_out }} - {{ $item->hora_check_out }}</td>
                                        <td>{{ $item->status }}</td>

                                        <td>{{ $item->total_dias }}</td>
                                        @if ($item->pagamento == "EFECTUADO")
                                        <td class="text-light-success">{{ $item->pagamento }}</td>
                                        @endif
                                        @if ($item->pagamento == "NAO EFECTUADO")
                                        <td class="text-light-danger">{{ $item->pagamento }}</td>
                                        @endif
                                        <td>{{ number_format($item->valor_total ??0, 2, ',', '.')  }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar reserva'))
                                                    <a class="dropdown-item" href="{{ route('reservas.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif
                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar reserva'))
                                                    @if ($item->pagamento == 'NAO EFECTUADO' && $item->status != 'CANCELADO')
                                                    <a class="dropdown-item" href="{{ route('reservas-fazer-pagamento', $item->id) }}"><i class="fas fa-pager text-light-success"></i>
                                                        Efecturar Pagamento</a>
                                                    @endif
                                                    @if ($item->pagamento == 'NAO EFECTUADO' && $item->status != 'CANCELADO')
                                                    <a class="dropdown-item anular-reserva" data-id="{{ $item->id ?? "" }}"><i class="fas fa-cancel text-light-danger"></i> Anular</a>
                                                    @endif

                                                    @if ($item->pagamento == 'NAO EFECTUADO' && $item->status == 'PENDENTE')
                                                    <a class="dropdown-item" href="{{ route('reservas.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i>
                                                        Editar</a>
                                                    @endif

                                                    @if ($item->check == 'PENDENTE')
                                                    <a class="dropdown-item check_in" data-id="{{ $item->id ?? "" }}" href="{{ route('reservas.check_in', $item->id) }}"><i class="fas fa-check text-light-success"></i> Check In</a>
                                                    @endif
                                                    @if ($item->check == 'IN')
                                                    <a class="dropdown-item check_out" data-id="{{ $item->id ?? "" }}" href="{{ route('reservas.check_out', $item->id) }}"><i class="fas fa-times text-light-danger"></i> Check Out</a>
                                                    @endif
                                                    @endif

                                                    <a class="dropdown-item" target="_blank" href="{{ route('imprimir-ficha-reservas', $item->id) }}"><i class="fas fa-file-pdf text-light-primary"></i> {{ __('messages.imprimir') }} </a>

                                                    <div class="dropdown-divider"></div>
                                                    @if ($item->check == 'PENDENTE')
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar reserva'))
                                                    <button class="btn btn-light-danger dropdown-item delete-record" data-id="{{ $item->id ?? "" }}">
                                                        <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                    </button>
                                                    @endif
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
                        <!-- /.card-body -->

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
    $(document).on('click', '.check_in', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, fazer check IN!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('reservas.check_in', ':id') }}`.replace(':id', recordId)
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

    $(document).on('click', '.check_out', function(e) {
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
            , confirmButtonText: 'Sim, fazer check OUT!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('reservas.check_out', ':id') }}`.replace(':id', recordId)
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

    $(document).on('click', '.anular-reserva', function(e) {
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
            , confirmButtonText: 'Sim, anular!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('reservas-anulacao', ':id') }}`.replace(':id', recordId)
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
                    url: `{{ route('reservas.destroy', ':id') }}`.replace(':id', recordId)
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
