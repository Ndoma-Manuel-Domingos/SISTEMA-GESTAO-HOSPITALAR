@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Relatórios de compras dos Clientes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Faltas</li>
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
                <div class="col-12 col-md-12 bg-light">
                    <div class="card">
                        <form action="{{ route('relatorio-cliente-pdf') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="cliente_id" class="form-label"> {{ __('messages.clientes') }} </label>
                                    <select type="text" class="form-control select2" name="cliente_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($clientes as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['cliente_id'] == $item->id ? 'selected' : ''}}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-light-primary btn-sm ml-2 text-right"> <i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('cliente-pdf-imprimir', ["data_inicio" => $requests['data_inicio'], "data_final" => $requests['data_final'], "cliente_id" => $requests['cliente_id'] ]) }}"><i class="fas fa-file-pdf"></i> IMPRIMIR PDF</a>
                            </div>
                        </div>

                        @if ($dadosClientes)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                    @foreach ($dadosClientes as $clienteData)
                                    <tr>
                                        <th class="bg-light text-uppercase" colspan="7">{{ $clienteData->codigo }} - {{ $clienteData->cliente }}</th>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Artigo</th>
                                        <th class="bg-light"> {{ __('messages.descricao') }} </th>
                                        <th class="bg-light text-right"> {{ __('messages.quantidade') }} </th>
                                        <th class="bg-light text-right">{{ __('messages.total') }}</th>
                                        <th class="bg-light text-right">Total Descontos</th>
                                        <th class="bg-light text-right">Custo</th>
                                        <th class="bg-light text-right">Lucro</th>
                                    </tr>
                                    @php
                                    $quantidade = 0;
                                    $valor_pagar = 0;
                                    $desconto_aplicado_valor = 0;
                                    $custo = 0;
                                    $custo_ = 0;
                                    @endphp
                                    @foreach ($clienteData->produtos as $produto)
                                    <tr>
                                        <td>#</td>
                                        <td>{{ $produto['produto'] }}</td>
                                        <td class="text-right">{{ number_format($produto['quantidade'], 2, ',', '.')  }}</td>
                                        <td class="text-right">{{ number_format($produto['valor_pagar'], 2, ',', '.')  }}</td>
                                        <td class="text-right">{{ number_format($produto['desconto_aplicado_valor'], 2, ',', '.')  }}</td>
                                        <td class="text-right">{{ number_format($produto['custo'] * $produto['quantidade'], 2, ',', '.')  }}</td>
                                        <td class="text-right">{{ number_format($produto['custo'] ?? 0, 2, ',', '.')  }}</td>
                                    </tr>

                                    @php
                                    $quantidade += $produto['quantidade'];
                                    $valor_pagar += $produto['valor_pagar'];
                                    $desconto_aplicado_valor += $produto['desconto_aplicado_valor'];
                                    $custo += ($produto['custo'] * $produto['quantidade']);
                                    $custo_ += $produto['custo'];
                                    @endphp

                                    @endforeach

                                    <tr>
                                        <th colspan="2">Totais</th>
                                        <th class="bg-light text-right">{{ number_format($quantidade, 2, ',', '.') }}</th>
                                        <th class="bg-light text-right">{{ number_format($valor_pagar, 2, ',', '.') }}</th>
                                        <th class="bg-light text-right">{{ number_format($desconto_aplicado_valor, 2, ',', '.') }}</th>
                                        <th class="bg-light text-right">{{ number_format($custo, 2, ',', '.') }}</th>
                                        <th class="bg-light text-right">{{ number_format($custo_, 2, ',', '.') }}</th>
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
