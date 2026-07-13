@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhes da Reserva de {{ $reservaAtiva->quarto->nome }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('quartos.visualizacao-andares-quartos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Informações da Reserva</h3>
                                </div>
                                <div class="card-body" title="Informações da Reserva">
                                    <p><strong>Codigo Referência:</strong> <a href="{{ route('reservas.show', $reservaAtiva->reserva->id) }}">{{ $reservaAtiva->reserva->codigo_referencia ?? "" }}</a></p>
                                    <p><strong>Cliente:</strong> <a href="{{ route('clientes.show', $reservaAtiva->reserva->cliente->id) }}">{{ $reservaAtiva->reserva->cliente->nome ?? "" }}</a></p>
                                    <p><strong>Data Entrada:</strong> {{ $reservaAtiva->reserva->data_inicio }} às {{ $reservaAtiva->reserva->hora_entrada }}</p>
                                    <p><strong>Data Saída:</strong> {{ $reservaAtiva->reserva->data_final }} às {{ $reservaAtiva->reserva->hora_saida }}</p>

                                    <p><strong>Data Check IN:</strong> {{ $reservaAtiva->reserva->data_check_in }} às {{ $reservaAtiva->reserva->hora_check_in }}</p>
                                    <p><strong>Data Ckeck OUT:</strong> {{ $reservaAtiva->reserva->data_check_out }} às {{ $reservaAtiva->reserva->hora_check_out }}</p>

                                    <p><strong>Atendido por:</strong> {{ $reservaAtiva->reserva->user->name ?? 'N/A' }}</p>
                                    <p><strong>Pagamento:</strong>
                                        @if($reservaAtiva->reserva->pagamento == "EFECTUADO")
                                        <span class="badge badge-light-success">Pago</span>
                                        @else
                                        <span class="badge badge-light-danger">Pendente</span>
                                        @endif
                                    </p>

                                    <p><strong>Tipo Reserva:</strong> {{ $reservaAtiva->reserva->tipo_reserva->nome ?? 'N/A' }}</p>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Resumo da Estadia</h3>
                                </div>
                                <div class="card-body">
                                    <p><strong>Dias Reservados:</strong> {{ $diasTotais }}</p>
                                    <p><strong>Dias Já Ficou:</strong> {{ $diasPassados }}</p>
                                    <p><strong>Dias Restantes:</strong> {{ $diasRestantes }}</p>

                                    @if ($reservaAtiva->reserva->check == 'IN')
                                    <p><strong>Status Check:</strong> <span class="badge badge-light-success">Activo</span></p>
                                    @endif

                                </div>
                                <div class="card-footer">
                                    @if ($reservaAtiva->reserva->check == 'PENDENTE')
                                    <a class="btn btn-light-primary check_in" data-id="{{ $reservaAtiva->reserva->id }}" href="#"><i class="fas fa-check"></i> Fazer Check In</a>
                                    @endif
                                    @if ($reservaAtiva->reserva->check == 'IN')
                                    <a class="btn btn-light-danger check_out" data-id="{{ $reservaAtiva->reserva->id }}" href="#"><i class="fas fa-times"></i> Fazer Check Out</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"> Pedidos do Cliente</h3>
                                    <div class="card-tools">
                                        @if ($reservaAtiva->reserva->check == 'IN')
                                        <a class="btn btn-light-primary" href="{{ route('pronto-venda-mesas-quartos', Crypt::encrypt($quarto->id)) }}"><i class="fas fa-shopping-cart"></i> NOVOS PEDIDOS</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p><strong>Pedidos Pagos:</strong> 0</p>
                                    <p><strong>Pedidos Pendentes:</strong> 0</p>
                                    <p><strong>Dívida Acumulada:</strong> 0 Kz</p>
                                    <p><strong>{{ __('messages.total') }}:</strong> 0</p>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.quartoiner-fluid -->
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

</script>
@endsection
