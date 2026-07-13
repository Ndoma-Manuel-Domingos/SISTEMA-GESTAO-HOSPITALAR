@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }} - {{ $factura->factura }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <div class="btn-group">
                        <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                        <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            @if ($factura->status == false)
                            <a class="dropdown-item" href="{{ route('encomenda-liquidar-factura-compra', $factura->id) }}"><i class="fas fa-file-invoice dollar-sign" title="Liquidar Fatura"></i> Liquidar Factura</a>
                            @endif
                            <a class="dropdown-item" href="{{ route('encomenda-duplicar-factura', $factura->id) }}"><i class="fas fa-copy text-light-primary"></i> Duplicar Factura</a>
                            <a class="dropdown-item" target="_blank" href="{{ route('imprimir-facturas-encomenda', $factura->id) }}"><i class="fas fa-file-pdf text-light-primary"></i> {{ __('messages.imprimir') }}</a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('fornecedores-encomendas.destroy', $factura->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Encomenda?')">
                                    <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                </button>
                            </form>
                        </div>

                    </div>

                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores-encomendas.show', $encomenda->id) }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.encomendas') }}</li>
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
                            <h5>Nº Factura: {{ $factura->factura ?? '--' }}</h5>
                        </div>
                        <div class="card-body">
                            <h6>Fornecedor:<a href="{{ route('fornecedores.show',  $factura->fornecedor->id) }}" class="float-right">{{ $factura->fornecedor->nome ?? '--' }}</a></h6>
                            <h6>Data da Factura:<span class="float-right">{{ $factura->data_factura ?? '--' }}</span></h6>
                            <h6>Data Vencimento:<span class="float-right">{{ $factura->data_vencimento ?? '--' }}</span></h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Totais</h5>
                        </div>
                        <div class="card-body">
                            <h6>Valor Factura:<span class="float-right">{{ number_format($factura->valor_factura ?? '0', 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</span></h6>
                            <h6>Valor A Pago (No Momento):<span class="float-right">{{ number_format($factura->valor_pago ?? '0' , 2, ',', '.')}} {{ $empresa_logada->empresa->moeda }}</span></h6>
                            <h6>Em Dívida:<span class="float-right">{{ number_format($factura->valor_divida ?? '0', 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</span></h6>
                        </div>
                    </div>
                </div>

                @if ($encomenda)
                <div class="col-12 col-md-12" id="accordion">
                    <div class="card card-info card-outline">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="card-title w-100 mb-2 text-left text-light-secondary">
                                            >> Dados da encomenda
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
                                            <th class="text-center bg-light">Encomendado</th>
                                            <th class="text-center bg-light">Atual</th>
                                            <th class="text-center bg-light-secondary">Encomendado</th>
                                            <th class="text-center bg-light-secondary">Recebido</th>
                                            <th class="text-right">{{ __('messages.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($items)
                                        @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $item->produto->codigo_barra }}</td>
                                            <td>{{ $item->produto->nome ?? "" }}</td>
                                            <td class="text-center">{{ $item->iva }} %</td>
                                            <td class="text-center">{{ $item->desconto }} %</td>
                                            <td class="text-center">
                                                @if ($item->custo != $item->produto->preco_custo)
                                                <span style="text-decoration: line-through;">{{ number_format($item->produto->preco_custo, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }} |</span>
                                                @endif
                                                <span>{{ number_format($item->preco_venda, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</span></td>
                                            <td class="text-center">{{ number_format($item->produto->preco_custo, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                            <td class="text-center">{{ $item->quantidade ?? 0 }} Uni</td>
                                            <td class="text-center">{{ $item->quantidade_recebida ?? 0 }} Uni</td>
                                            <td class="text-right">{{ number_format($item->totalSiva, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">SubTotal:</td>
                                            <td class="text-right">{{ number_format($encomenda->total_sIva, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">Descontos:</td>
                                            <td class="text-right">{{ number_format($encomenda->desconto, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">Imposto:</td>
                                            <td class="text-right">{{ number_format($encomenda->imposto, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">Total Pago:</td>
                                            <td class="text-right">{{ number_format($encomenda->tota_pago, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right text-uppercase" colspan="8">Total A Pagar:</td>
                                            <td class="text-right">{{ number_format($encomenda->total_a_pagar, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-12 col-md-12" id="accordion">
                    <div class="card card-success card-outline">
                        <a class="d-block w-100" data-toggle="collapse" href="#adicionarPagamentos">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="card-title w-100 mb-2 text-left text-light-secondary">
                                            >> Pagamentos ({{ count($factura->pagamentos) }})
                                        </h4>
                                    </div>

                                    <div class="col-6">
                                        <h4 class="card-title w-100 mb-2 text-right text-light-secondary">
                                            <strong>{{ __('messages.total') }}: </strong> {{ number_format($factura->total_pago, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}
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
                                            <th class="text-right">Forma de Pagamento</th>
                                            <th class="text-right">{{ __('messages.valor') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($factura && count($factura->pagamentos) > 0)
                                        @foreach ($factura->pagamentos as $item)
                                        <tr>
                                            <td>{{ $item->descricao }}</td>
                                            <td class="text-right"><span class="text-light-success"> <i class="fas fa-check-circle"></i> {{ $item->data_pagamento }}</span> <br> Vencimento {{ $factura->data_vencimento }}</td>
                                            <td class="text-right">{{ $item->forma_pagamento->titulo }}</td>
                                            <td class="text-right">{{ number_format($item->valor_pago, 2, ',', '.') }} {{ $empresa_logada->empresa->moeda }}</span>
                                            <td>
                                                <a href="#" class="btn btn-light-danger btn-sm float-right">
                                                    <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="4">Não existem Pagamentos. <br>
                                                Adicione Facturas relacionadas com esta encomenda e posteriomente registe os respetivos pagamentos.</td>
                                        </tr>
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
