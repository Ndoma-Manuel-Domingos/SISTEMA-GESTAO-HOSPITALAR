@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Facturas Por Pagar</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ $cliente->nome }}</li>
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
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">

                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Factura</th>
                                        <th>Emissão Data</th>
                                        <th>Vencimento</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>Condição</th>
                                        <th class="text-right">Valor Dívida</th>
                                        <th class="text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($facturas)
                                    @foreach ($facturas as $item)
                                    <tr>
                                        <td><a href="{{ route('facturas.show', [$item->id, 'tipo_documentos' => $item->factura]) }}">{{ $item->factura_next }}</a>
                                        </td>
                                        <td>{{ $item->data_emissao }}</td>
                                        <td>{{ $item->data_vencimento }}</td>
                                        <td class="text-uppercase">
                                            @if ($item->status_factura == 'por pagar')
                                            <span class="bg-light-warning p-1"><i class="fas fa-exclamation-triangle"></i></span>
                                            {{ $item->status_factura }}
                                            @else
                                            @if ($item->status_factura == 'anulada')
                                            <span class="bg-light-danger p-1"><i class="fas fa-cancel"></i></span>
                                            {{ $item->status_factura }}
                                            @else
                                            @if ($item->status_factura == 'pago')
                                            <span class="bg-light-success p-1"><i class="fas fa-check"></i></span>
                                            {{ $item->status_factura }}
                                            @endif
                                            @endif
                                            @endif
                                        </td>
                                        <td class="text-uppercase">
                                            @if ($item->data_vencimento < date('Y-m-d')) <span class="text-light-danger">Vencida</span>
                                                @endif
                                                @if ($item->data_emissao < date('Y-m-d') && $item->data_vencimento > date('Y-m-d'))
                                                    <span class="text-light-success">Corrente</span>
                                                    @endif
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($item->valor_total, 2, ',', '.') }}
                                            {{ $empresa_logada->empresa->moeda }}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a href="{{ route('facturas.show', [$item->id, 'tipo_documentos' => $item->factura]) }}" class="dropdown-item"><i class="fas fa-file-invoice dollar-sign float-left mr-2" title="Liquidar Fatura"></i> Liquidar</a>
                                                    <a href="{{ route('anular-factura', $item->id) }}" data-id="{{ $item->id ?? "" }}" class="dropdown-item delete-record text-light-danger"><i class="fas fa-cancel float-left mr-2"></i>
                                                        Anular</a>
                                                    <div class="dropdown-divider"></div>
                                                    @if ($item->factura == 'FT')
                                                    <a href="{{ route('factura-factura', $item->code) }}" class="dropdown-item" target="_blank"><i class="fas fa-print float-left mr-2"></i>
                                                        {{ __('messages.imprimir') }}</a>
                                                    @else
                                                    @if ($item->factura == 'FR')
                                                    <a href="{{ route('factura-recibo', $item->code) }}" class="dropdown-item" target="_blank"><i class="fas fa-print float-left mr-2"></i>
                                                        {{ __('messages.imprimir') }}</a>
                                                    @else
                                                    @if ($item->factura == 'PP')
                                                    <a href="{{ route('factura-proforma', $item->code) }}" class="dropdown-item" target="_blank"><i class="fas fa-print float-left mr-2"></i>
                                                        {{ __('messages.imprimir') }}</a>
                                                    @else
                                                    @if ($item->factura == 'RG')
                                                    <a href="{{ route('factura-recibo-recibo', $item->code) }}" class="dropdown-item" target="_blank"><i class="fas fa-print float-left mr-2"></i>
                                                        {{ __('messages.imprimir') }}</a>
                                                    @else
                                                    <a href=" {{ route('factura-nota-credito', $item->code) }}" class="dropdown-item" target="_blank"><i class="fas fa-print float-left mr-2"></i>
                                                        {{ __('messages.imprimir') }}</a>
                                                    @endif
                                                    @endif
                                                    @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- /.card -->
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
            , confirmButtonText: 'Sim, anular!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('anular-factura', ':id') }}`.replace(':id', recordId)
                    , method: 'GET'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        if (response.success) {
                            // Gerar a URL usando o Laravel Blade
                            const url = `{{ route('factura-nota-credito', ':code') }}`.replace(':code', response.factura.code);
                            // Redirecionar
                            window.location.href = url;
                        }
                        // alert(response.mensagem || 'Arquivo exportado com sucesso!');
                        showMessage('Sucesso!', 'Exportação concluída com sucesso!'
                            , 'success');
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
