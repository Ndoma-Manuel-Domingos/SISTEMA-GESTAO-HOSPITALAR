@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.encomendas') }} - {{ $encomenda->factura }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <div class="btn-group">
                        <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                        <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="{{ route('fornecedores-encomendas.edit', $encomenda->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                            <a class="dropdown-item" href="{{ route('encomenda-receber-produto', $encomenda->id) }}"><i class="fas fa-plus-circle"></i> Receber Produtos</a>

                            <a class="dropdown-item text-light-success entregue-record" data-id="{{ $encomenda->id }}"><i class="fas fa-check"></i> Marcar como Entregue</a>
                            <a class="dropdown-item text-light-danger cancelar-record" data-id="{{ $encomenda->id }}"><i class="fas fa-cancel"></i> Marcar como Cancelada</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('imprimir-encomenda', $encomenda->id) }}" target="_blank"><i class="fas fa-print text-light-primary"></i> {{ __('messages.imprimir') }}</a>
                            <div class="dropdown-divider"></div>

                            <button class="btn btn-light-danger dropdown-item delete-record" data-id="{{ $encomenda->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>

                        </div>
                    </div>

                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores-encomendas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
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

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Nº da Encomenda: {{ $encomenda->factura ?? '--' }}</h5>
                        </div>
                        <div class="card-body">
                            <h6>Fornecedor:<a href="{{ route('fornecedores.show',  $encomenda->fornecedor->id) }}" class="float-right">{{ $encomenda->fornecedor->nome ?? '--' }}</a></h6>
                            <h6>Data da Encomenda:<span class="float-right">{{ $encomenda->data_emissao ?? '--' }}</span></h6>
                            <h6>Utilizador:<span class="float-right">{{ $encomenda->user->name ?? '--' }}</span></h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dados da Entrega</h5>
                        </div>
                        <div class="card-body">
                            <h6>empresa_logada/Armazém:<span class="float-right">{{ $encomenda->empresa_logada->nome ?? '--' }}</span></h6>
                            <h6>Previsão de Entrega:<span class="float-right">{{ $encomenda->previsao_entrega ?? '--' }}</span></h6>
                            @if ($encomenda->status == 'pendente')
                            <h6>{{ __('messages.estados') }}:<span class="float-right bg-light-warning p-1 text-uppercase">{{ $encomenda->status ?? '--' }}</span></h6>
                            @endif

                            @if ($encomenda->status == 'entregue')
                            <h6>{{ __('messages.estados') }}:<span class="float-right  bg-light-primary" p-1 text-uppercase">{{ $encomenda->status ?? '--' }}</span></h6>
                            @endif

                            @if ($encomenda->status == 'cancelada')
                            <h6>{{ __('messages.estados') }}:<span class="float-right bg-light-danger p-1 text-uppercase">{{ $encomenda->status ?? '--' }}</span></h6>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12" id="accordion">
                    <div class="card card-info card-outline">
                        <a href="{{ route('encomenda-receber-produto', $encomenda->id) }}" class=" btn btn-light-primary btn-sm"><i class="fas fa-plus-circle"></i> Receber Produtos</a>
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="card-title w-100 mb-2 text-left text-light-secondary">
                                            >> Produtos (0% recebidos)
                                        </h4>
                                    </div>

                                    <div class="col-6">
                                        <h4 class="card-title w-100 mb-2 text-right text-light-secondary">
                                            <strong>{{ __('messages.total') }}: </strong> {{ number_format($encomenda->total, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div id="collapseTwo" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th colspan="4" class="text-center bg-light">{{ __('messages.preco_custo') }}</th>
                                            <th colspan="2" class="text-center bg-light-secondary"> {{ __('messages.quantidade') }} </th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th>{{ __('messages.codigo_barras') }}</th>
                                            <th>{{ __('messages.designacao') }}</th>
                                            <th class="text-center bg-light">IVA</th>
                                            <th class="text-center bg-light">{{ __('messages.desconto') }}</th>
                                            <th class="text-center bg-light">{{ __('messages.preco_custo') }}</th>
                                            <th class="text-center bg-light">{{ __('messages.preco_venda') }} <small class="text-light-danger">(Sugestão)</small></th>
                                            <th class="text-center bg-light-secondary">Encomendado</th>
                                            <th class="text-center bg-light-secondary">Recebido</th>
                                            <th class="text-right">{{ __('messages.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($items)
                                        @php $subtotal = 0; @endphp
                                        @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $item->produto->codigo_barra }}</td>
                                            <td>{{ $item->produto->nome ?? "" }}</td>
                                            <td class="text-center">{{ $item->iva }} %</td>
                                            <td class="text-center">{{ $item->desconto }} %</td>
                                            <td class="text-center">{{ number_format($item->custo, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                            <td class="text-center">{{ number_format($item->preco_venda, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>

                                            <td class="text-center">{{ $item->quantidade ?? 0 }} Uni</td>
                                            <td class="text-center">{{ $item->quantidade_recebida ?? 0 }} Uni</td>
                                            <td class="text-right">{{ number_format($item->total, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                            @php $subtotal += $item->total; @endphp
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">SubTotal:</td>
                                            <td class="text-right">{{ number_format($subtotal, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">Descontos:</td>
                                            <td class="text-right">{{ number_format($encomenda->desconto_valor, 2, ',', '.') }} <small>({{ $encomenda->desconto }}%)</small> {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">Imposto:</td>
                                            <td class="text-right">{{ number_format($encomenda->imposto, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">Transporte:</td>
                                            <td class="text-right">{{ number_format($encomenda->custo_transporte, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">Manuseamento:</td>
                                            <td class="text-right">{{ number_format($encomenda->custo_manuseamento, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">Outros Custos:</td>
                                            <td class="text-right">{{ number_format($encomenda->outros_custos, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">Total A Pagar:</td>
                                            <td class="text-right">{{ number_format($encomenda->total_a_pagar, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">{{ __('messages.total') }}:</td>
                                            <td class="text-right">{{ number_format($encomenda->total, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12" id="accordion">
                    <div class="card card-primary card-outline">
                        <a href="{{ route('encomenda-criar-factura-compra', $encomenda->id) }}" class=" btn btn-light-primary btn-sm"><i class="fas fa-plus-circle"></i> Adicionar Facturas</a>
                        <a class="d-block w-100" data-toggle="collapse" href="#adicionarFacturas">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="card-title w-100 mb-2 text-left text-light-secondary">
                                            >> Facturas ({{ $totalFactura }})
                                        </h4>
                                    </div>

                                    <div class="col-6">
                                        <h4 class="card-title w-100 mb-2 text-right text-light-secondary">
                                            <strong>{{ __('messages.total') }}: </strong> {{ number_format($totalValorFactura, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div id="adicionarFacturas" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nº Fatura</th>
                                            <th>Pago</th>
                                            <th class="text-right">Data Fatura</th>
                                            <th class="text-right">Data Vencimento</th>
                                            <th class="text-right">Valor Fatura</th>
                                            <th class="text-right">Total Pago </th>
                                            <th class="text-right">Dívida</th>
                                            <th class="text-right">Acções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($facturas)
                                        @php
                                        $saldo_acumulado = 0;
                                        $divida_acumulado = 0;
                                        @endphp
                                        @foreach ($facturas as $item)
                                        <tr>
                                            <td><a href="{{ route('fornecedores-facturas-encomendas.show', $item->id) }}">{{ $item->factura }}</a></td>
                                            <td>{{ $item->status == true ? 'Pago' : 'Não Pago' }}</td>
                                            <td class="text-right">{{ $item->data_factura }}</td>
                                            <td class="text-right">{{ $item->data_vencimento }}</td>
                                            <td class="text-right text-light-primary">{{ number_format($item->valor_factura, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</span>
                                            <td class="text-right text-light-primary">{{ number_format($item->total_pago, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                            <td class="text-right text-light-danger">{{ number_format($item->valor_divida, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                            <td>
                                                <a href="{{ route('imprimir-facturas-encomenda', $item->id) }}" target="_blank" class="float-right btn btn-light-danger mx-1"><i class="fas fa-file-pdf"></i> </a>
                                                <a href="{{ route('fornecedores-facturas-encomendas.show', $item->id) }}" class="float-right btn btn-light-primary"><i class="fas fa-info"></i> </a>
                                            </td>
                                            @php
                                            $saldo_acumulado += $item->total_pago;
                                            $divida_acumulado += $item->valor_divida;
                                            @endphp

                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="5" class="text-right">{{ __('messages.total') }}: </td>
                                            <td class="text-right text-uppercase text-light-primary">{{ number_format($saldo_acumulado, 2, ',', '. ') }} {{ $empresa_logada->empresa->moeda }}</td>
                                            <td class="text-right text-uppercase text-light-danger">{{ number_format($divida_acumulado, 2, ',', '. ') }} {{ $empresa_logada->empresa->moeda }}</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12" id="accordion">
                    <div class="card card-success card-outline">
                        <a href="#" class=" btn btn-light-success btn-sm"><i class="fas fa-plus-circle"></i> Adicionar Pagamentos</a>
                        <a class="d-block w-100" data-toggle="collapse" href="#adicionarPagamentos">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="card-title w-100 mb-2 text-left text-light-secondary">
                                            >> Facturas com Pagamentos ({{ $totalFacturaPaga }})
                                        </h4>
                                    </div>

                                    <div class="col-6">
                                        <h4 class="card-title w-100 mb-2 text-right text-light-secondary">
                                            <strong>{{ __('messages.total') }}: </strong> {{ number_format($totalValorPago, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div id="adicionarPagamentos" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <table class="table table-hover text-nowrap">
                                    <thead>

                                        <tr>
                                            <th>Nº Fatura</th>
                                            <th class="text-right">Data Pagamento</th>
                                            <th class="text-right">{{ __('messages.valor') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($facturasPagas)
                                        @foreach ($facturasPagas as $item)
                                        <tr>
                                            <td><a href="{{ route('fornecedores-facturas-encomendas.show', $item->id) }}">{{ $item->factura }}</a></td>
                                            <td class="text-right"><span class="text-light-success">{{ $item->data_factura }}</span> <br> data Vencimento {{ $item->data_vencimento }}</td>
                                            <td class="text-right">{{ number_format($item->valor_pago, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</span>
                                                @if ($item->status == true)
                                            <td><a href="#" class="btn btn-light-danger btn-sm float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}</a></td>
                                            @else
                                            <td>
                                                <a href="{{ route('encomenda-liquidar-factura-compra', $item->id) }}" class="btn btn-light-primary btn-sm float-right ml-1"><i class="fas fa-file-invoice dollar-sign" title="Liquidar Fatura"></i> Liquidar</a>
                                                <a href="#" class="btn btn-light-danger btn-sm float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}</a>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
            , confirmButtonText: 'Sim, entregar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('fornecedores-encomendas.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.href = response.redirect;
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', 'Ocorreu um erro ao fazer entregua da encomenda. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.entregue-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, entregar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('encomenda-marcar-como-entregue', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = response.redirect;
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', 'Ocorreu um erro ao fazer entregua da encomenda. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.cancelar-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, cancelar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('encomenda-marcar-como-cancelada', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = response.redirect;
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', 'Ocorreu um erro ao fazer cancelamento da encomenda. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });

</script>
@endsection
