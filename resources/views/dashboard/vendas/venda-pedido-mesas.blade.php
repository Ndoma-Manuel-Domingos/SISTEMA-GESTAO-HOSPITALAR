@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Escolher Mesa</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('controle') }}</li>
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
                            @if ($checkCaixa)
                            <a href="{{ route('contabilidade-diarios') }}" class="btn btn-light-primary"> Ver Movimentos do Caixa</a>
                            @else
                            <a href="{{ route('caixa.abertura_caixa') }}" class="btn btn-light-primary"> Abrir Caixa</a>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="row">
                                @foreach ($mesas as $item)
                                <div class="col-6 col-md-3 col-lg-2">
                                    <a href="{{ route('pronto-venda-mesas-pedidos', Crypt::encrypt($item->id)) }}">
                                        <div class="card {{
                                            $item->solicitar_ocupacao == "OCUPADA" ?  "bg-light-primary" : ( $item->solicitar_ocupacao == "LIVRE" ? "bg-light-success" : ($item->solicitar_ocupacao == "RESERVADA" ? "bg-light-warning" : "" ) )   }}">
                                            <div class="card-body {{ $item->solicitar_ocupacao == "OCUPADA" ?  "bg-light-primary" : ( $item->solicitar_ocupacao == "LIVRE" ? "bg-light-success" : ($item->solicitar_ocupacao == "RESERVADA" ? "bg-light-warning" : "" ) )   }}">
                                                <div class="col-12 col-md-12 col-sm-12">
                                                    <h6 class="text-uppercase">{{ $item->nome }}</h6>
                                                    <p class="">{{ __('messages.estados') }}: {{ $item->solicitar_ocupacao }}</p>
                                                </div>
                                            </div>
                                            <div class="card-footer p-1 px-4 bg-light">
                                                <a href="#" data-id="{{ $item->id ?? "" }}" class="mudar-status-mesa" style="display: block;font-size: 11pt">Mudar o Estado para livre</a>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- /.card -->
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('mesas.create') }}" class="btn btn-light-primary btn-sm"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
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
    $(document).on('click', '.mudar-status-mesa', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, mudar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('mudar-status-mesa', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
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
