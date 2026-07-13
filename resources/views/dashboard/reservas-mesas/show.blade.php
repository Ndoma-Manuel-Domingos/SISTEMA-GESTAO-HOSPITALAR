@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $titulo }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('reservas-mesas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Reservas</li>
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
                <div class="col-12 col-md-12">
                    <div class="card" style="border: {{ $reserva->status == 'CANCELADO' ? '1px solid red' : '' }}">
                        @if ($reserva->status == 'CANCELADO')
                        <div class="card-header">
                            <h5 class="text-light-danger">Reserva Anulada</h5>
                        </div>
                        @endif
                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('messages.designacao') }}</th>
                                                <td class="text-right">{{ $reserva->cliente->nome ?? '-------------' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th> {{ __('messages.genero') }} </th>
                                                <td class="text-right">
                                                    {{ $reserva->cliente->genero ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.data_nascimento') }}</th>
                                                <td class="text-right">
                                                    {{ $reserva->cliente->data_nascimento ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>País</th>
                                                <td class="text-right">{{ $reserva->cliente->pais ?? '-------------' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.estado_civil') }}</th>
                                                <td class="text-right">
                                                    {{ $reserva->cliente->estado_civil->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th> {{ __('messages.bilhete_identidade') }} </th>
                                                <td class="text-right">{{ $reserva->cliente->nif ?? '-------------' }}
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('messages.estados') }}</th>
                                                <td class="text-right">{{ $reserva->status ?? '-------------' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            {{-- -------------------------------------------- --}}
                                            <tr>
                                                <th colspan="4">Reserva</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Data Entrada</th>
                                                <th>Data Registro</th>
                                                <th>Total de Mesas</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ $reserva->data_entrada ?? '-------------' }}</td>
                                                <td>{{ $reserva->created_at ?? '-------------' }}</td>
                                                <td>{{ $reserva->total_mesas ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Data Check IN</th>
                                                <th>Hora Check IN</th>
                                                <th>Operador Check IN</th>
                                                <th> {{ __('messages.exercicio') }} </th>
                                            </tr>
                                            <tr>
                                                <td>{{ $reserva->data_check_in ?? '-------------' }}</td>
                                                <td>{{ $reserva->hora_check_in ?? '-------------' }}</td>
                                                <td>{{ $reserva->user_in_ckeck ? $reserva->user_in_ckeck->name : '-------------' }} </td>
                                                <td>{{ $reserva->exercicio->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Data Check OUT</th>
                                                <th>Hora Check OUT</th>
                                                <th>Operador Check OUT</th>
                                                <th> {{ __('messages.periodo') }} </th>
                                            </tr>
                                            <tr>
                                                <td>{{ $reserva->data_check_out ?? '-------------' }}</td>
                                                <td>{{ $reserva->hora_check_out ?? '-------------' }}</td>
                                                <td>{{ $reserva->user_out_ckeck ? $reserva->user_out_ckeck->name : '-------------' }} </td>
                                                <td>{{ $reserva->periodo->nome ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            {{-- -------------------------------------------- --}}
                                            <tr>
                                                <th colspan="6">Informações Financeiras</th>
                                            </tr>

                                            <tr>
                                                <th colspan="2">Valor Total</th>
                                                <th colspan="2">Valor Divída</th>
                                                <th>Valor Pago</th>
                                                <th>Estado Pagamento</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ number_format($reserva->valor_total ?? 0, 2, ',', '.') }}</td>
                                                <td colspan="2">{{ number_format($reserva->valor_divida ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($reserva->valor_pago ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ $reserva->pagamento }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="3">#</th>
                                                <th colspan="3">Quarto</th>
                                            </tr>

                                            @foreach ($reserva->items as $key => $item)
                                            <tr>
                                                <td colspan="3">{{ $key + 1 }}</td>
                                                <td colspan="3">{{ $item->mesa ? $item->mesa->nome : 'indefinido' }}</td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">

                            <a class="btn btn-light-danger" target="_blank" href="{{ route('imprimir-ficha-reservas-mesa', $reserva->id) }}"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }} </a>

                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar reserva'))
                            @if ($reserva->pagamento == 'NAO EFECTUADO' && $reserva->status != 'CANCELADO')
                            <a class="btn btn-light-success" href="{{ route('reservas-mesas-fazer-pagamento', $reserva->id) }}"><i class="fas fa-pager"></i> Efecturar Pagamento</a>
                            @endif
                            @if ($reserva->status != 'CANCELADO')
                            <a class="btn btn-light-danger anular-reserva" data-id="{{ $reserva->id }}" href="#"><i class="fas fa-cancel"></i> Anular</a>
                            @endif
                            @if ($reserva->check == 'PENDENTE')
                            <a class="btn btn-light-success check_in" data-id="{{ $reserva->id }}" href="#"><i class="fas fa-check"></i> Check In</a>
                            @endif
                            @if ($reserva->check == 'IN')
                            <a class="btn btn-light-danger check_out" data-id="{{ $reserva->id }}" href="#"><i class="fas fa-times"></i> Check Out</a>
                            @endif
                            @endif

                            @if (Auth::user()->can('listar todos') || Auth::user()->can('listar reserva'))
                            @if ($reserva->check == 'IN')
                            @foreach ($reserva->items as $item)
                            <a class="btn btn-light-primary" href="{{ route('pronto-venda-mesas-pedidos', Crypt::encrypt($item->mesa_id)) }}"><i class="fas fa-eye"></i> Fazer Pedidos a {{ $item->mesa->nome }}</a>
                            @endforeach
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    $(document).on('click', '.check_in', function(e) {
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
            , confirmButtonText: 'Sim, fazer check IN!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('reservas-mesas.check_in', ':id') }}`.replace(':id', recordId)
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
                    url: `{{ route('reservas-mesas.check_out', ':id') }}`.replace(':id', recordId)
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
                    url: `{{ route('reservas-anulacao-mesas', ':id') }}`.replace(':id', recordId)
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
                    url: `{{ route('reservas-mesas.destroy', ':id') }}`.replace(':id', recordId)
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

</script>
@endsection
