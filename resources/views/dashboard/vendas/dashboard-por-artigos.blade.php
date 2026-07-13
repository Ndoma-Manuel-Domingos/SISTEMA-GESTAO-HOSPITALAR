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
                        <form action="{{ route('vendas_por_artigo', ['tipo' => $isMateriaPrima ? 'materias-primas' : 'produto']) }}" method="get">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-2">
                                        <label for="tipo_preco" class="form-label">Tipo Preço</label>
                                        <select type="text" class="form-control select2" id="tipo_preco" name="tipo_preco">
                                            <option value="PC" {{$requests['tipo_preco'] == "PC" ? "selected": ""}}>{{ __('messages.preco_custo') }}</option>
                                            <option value="PV" {{$requests['tipo_preco'] == "PV" ? "selected": ""}}>{{ __('messages.preco_venda') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-12">
                                        <label for="loja_id" class="form-label">{{ __('messages.lojas') }}</label>
                                        <select type="text" class="form-control select2" id="loja_id" name="loja_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($empresa->lojas as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $requests['loja_id'] == $item->id ? 'selected' : ''}}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="apenas_com_quantidade" class="form-label">{{ __('messages.quantidade') }}</label>
                                        <select type="text" class="form-control select2" id="apenas_com_quantidade" name="apenas_com_quantidade">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            <option value="true">Apenas com quantidade</option>
                                            <option value="false">Apenas sem quantidade</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-12">
                                        <label for="" class="form-label">{{ __('messages.data_inicio') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-3 col-12">
                                        <label for="" class="form-label">{{ __('messages.data_final') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                                        </div>
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
                                <a href="{{ route('pdf-stock-artigos-excel',
                                    ['tipo' => $isMateriaPrima ? 'materias-primas' : 'produto', 'data_inicio' => request()->data_inicio ?? '', 'data_final' => request()->data_final ?? '', 'loja_id' => request()->loja_id ?? '', 'user_id' => request()->user_id ?? '', 'apenas_com_quantidade' => request()->apenas_com_quantidade ?? '', "tipo_preco" => request()->tipo_preco ?? ""]) }}" target="_blank" class="btn btn-light-success float-right"><i class="fas fa-file-excel"></i> {{ __('messages.exportar_excel') }}</a>
                                <a href="{{ route('pdf-stock-artigos',
                                    ['tipo' => $isMateriaPrima ? 'materias-primas' : 'produto', "data_inicio" => request()->data_inicio ?? "", "data_final" => request()->data_final ?? "", "loja_id" => request()->loja_id ?? "", "user_id" => request()->user_id ?? "", "apenas_com_quantidade" => request()->apenas_com_quantidade ?? "",  "tipo_preco" => request()->tipo_preco ?? ""]) }}" target="_blank" class="btn btn-light-primary float-right">
                                    <i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}
                                </a>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.codigo_barras') }}</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>{{ __('messages.tipo') }}</th>
                                        <th class="text-right">{{ __('messages.quantidade') }}</th>
                                        @if ($requests['tipo_preco'] == "PV")
                                        <th class="text-right">{{ __('messages.preco_venda') }}</th>
                                        @else
                                        <th class="text-right">{{ __('messages.preco_custo') }}</th>
                                        @endif
                                        <th class="text-right">{{ __('messages.imposto') }}</th>
                                        <th class="text-right">{{ __('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php
                                    $total_liquido_vendido_valor = 0;
                                    $total_liquido_restante_valor = 0;
                                    $total_liquido_geral_valor = 0;
                                    $custo = 0;
                                    $lucro = 0;
                                    $total_retencao = 0;
                                    @endphp

                                    @foreach ($vendas as $item)

                                    <tr>
                                        <td><a href="{{ route('produtos.show', $item->id) }}">{{ $item->codigo_barra }}</a></td>
                                        <td><a href="{{ route('produtos.show', $item->id) }}">{{ $item->produto }}</a></td>
                                        <td><a href="{{ route('produtos.show', $item->id) }}">{{ $item->tipo }}</a></td>
                                        <td class="text-right">{{ number_format($item->quantidade_estoque, 1, ',', '.') }} <small>{{ $item->unidade->sigla ?? "" }}</small></td>

                                        @if ($requests['tipo_preco'] == "PV")
                                        <td class="text-right">{{ number_format($item->preco, 2, ',', '.') }}</td>
                                        @else
                                        <td class="text-right">{{ number_format($item->custo, 2, ',', '.') }}</td>
                                        @endif

                                        <td class="text-right">{{ number_format($item->imposto, 2, ',', '.') }}</td>

                                        @if ($requests['tipo_preco'] == "PV")
                                        <td class="text-right">{{ number_format($item->preco * $item->quantidade_estoque, 2, ',', '.') }}</td>
                                        @else
                                        <td class="text-right">{{ number_format($item->total_liquido_geral, 2, ',', '.') }}</td>
                                        @endif

                                        @php
                                        $total_liquido_vendido_valor += $item->total_liquido_vendido;
                                        $total_liquido_restante_valor += $item->preco * $item->quantidade_estoque;
                                        $total_liquido_geral_valor += $item->total_liquido_geral;

                                        $custo += $item->total_liquido_custo;
                                        $lucro += $item->total_liquido_lucro;
                                        $total_retencao += $item->total_retencao_acumulada;
                                        @endphp
                                    </tr>
                                    @endforeach

                                </tbody>

                                <tfoot>

                                    <tr>
                                        <th>{{ __('messages.total') }}</th>
                                        <th class="text-right">---</th>
                                        <th class="text-right">---</th>
                                        <th class="text-right">---</th>
                                        <th class="text-right">---</th>
                                        <th class="text-right">---</th>

                                        @if ($requests['tipo_preco'] == "PV")
                                        <th class="text-right">{{ number_format($total_liquido_restante_valor, 2, ',', '.') }}</th>
                                        @else
                                        <th class="text-right">{{ number_format($total_liquido_geral_valor, 2, ',', '.') }}</th>
                                        @endif

                                    </tr>
                                </tfoot>
                            </table>
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
