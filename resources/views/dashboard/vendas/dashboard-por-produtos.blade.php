@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Listagem de vendas por produtos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('vendas_por_produtos') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-2">
                                    <label for="caixa_id" class="form-label">{{ __('messages.caixa') }}</label>
                                    <select class="form-control select2" name="caixa_id">
                                        <option value="">{{ __('messages.escolher') }}</option>
                                        @foreach ($empresa->caixas as $caixa)
                                        <option value="{{ $caixa->id }}" {{ $requests['caixa_id'] == $caixa->id ? 'selected' : ''}}>{{ $caixa->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="categoria_id" class="form-label">Categoria</label>
                                    <select class="form-control select2" name="categoria_id">
                                        <option value="">{{ __('messages.escolher') }}</option>
                                        @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ $requests['categoria_id'] == $categoria->id ? 'selected' : ''}}>{{ $categoria->categoria }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="user_id" class="form-label">Operador</label>
                                    <select class="form-control select2" name="user_id">
                                        <option value="">{{ __('messages.escolher') }}</option>
                                        @foreach ($empresa->users as $user)
                                        <option value="{{ $user->id }}" {{ $requests['user_id'] == $user->id ? 'selected' : ''}}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-light-primary btn-sm ml-2 text-right"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ route('imprimir-pdf-vendas-excel', ['data_inicio' => $requests['data_inicio'] ?? date("Y-m-d"), 'data_final' => $requests['data_final'] ?? date("Y-m-d"), 'caixa_id' => $requests['caixa_id'], 'user_id' => $requests['user_id'], 'categoria_id' => $requests['categoria_id']]) }}" target="_blank" class="btn btn-light-success float-right"><i class="fas fa-file-excel"></i> {{ __('messages.exportar_pdf') }}</a>
                                <a href="{{ route('imprimir-pdf-vendas', ['data_inicio' => $requests['data_inicio'] ?? date("Y-m-d"), 'data_final' => $requests['data_final'] ?? date("Y-m-d"), 'caixa_id' => $requests['caixa_id'], 'user_id' => $requests['user_id'], 'categoria_id' => $requests['categoria_id']]) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>Ref</th>
                                        <th>{{ __('messages.codigo_barras') }}</th>
                                        <th>{{ __('messages.produtos') }}</th>
                                        <th>{{ __('messages.categoria') }}</th>
                                        {{-- <th>Preco Custo</th> --}}
                                        <th class="text-right"> {{ __('messages.quantidade') }} </th>
                                        <th>{{ __('messages.preco_custo') }}</th>
                                        <th>{{ __('messages.imposto') }}</th>
                                        {{-- <th class="text-right"> {{ __('messages.quantidade') }} devolvida</th> --}}
                                        {{-- <th class="text-right">Custo</th> --}}
                                        <th class="text-right">Lucro</th>
                                        <th class="text-right">{{ __('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $total = 0;
                                    $total_lucro = 0;
                                    $total_custo = 0;
                                    @endphp
                                    @foreach ($vendas as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->produto->codigo_barra ?? "" }}</td>
                                        <td>{{ $item->produto->nome ?? "" }}</td>
                                        <td>{{ $item->produto->categoria->categoria ?? "" }}</td>
                                        <td class="text-right">{{ number_format($item->total_quantidade, 0, ',', '.') }}</td>
                                        <td>{{ number_format(($item->total_valor / $item->total_quantidade) ?? 0, 4, ',', '.') }}</td>
                                        <td>{{ number_format($item->produto->taxa ?? 0, 0, ',', '.') }} %</td>
                                        <td class="text-right">{{ number_format($item->total_lucro, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</span></td>
                                        <td class="text-right">{{ number_format($item->total_valor, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</span></td>
                                    </tr>
                                    @php
                                    $total_custo += ($item->total_custo);
                                    $total_lucro += $item->total_lucro;
                                    $total += $item->total_valor;
                                    @endphp
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-right" colspan="7">{{ __('messages.total') }}</td>
                                        <td class="text-right">{{ number_format($total_lucro ?? 0, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda ?? "AKZ" }}</span></td>
                                        <td class="text-right">{{ number_format($total ?? 0, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda ?? "AKZ" }}</span></td>
                                    </tr>
                                </tfoot>
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
