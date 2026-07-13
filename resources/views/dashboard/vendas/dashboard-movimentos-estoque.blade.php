@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Relatório de Movimentos de Estoque</h1>
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
                        <form action="{{ route('vendas_movimentos_estoques') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-2">
                                    <label for="produto_id" class="form-label">{{ __('messages.produtos') }}</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 produto_id" name="produto_id" id="produto_id">
                                            <option value="">{{ __('messages.todos') }}</option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $requests['produto_id'] == $item->id ? 'selected' : ''}}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="categoria_id" class="form-label">{{ __('messages.categoria') }}</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 categoria_id" name="categoria_id" id="categoria_id">
                                            <option value="">{{ __('messages.todos') }}</option>
                                            @foreach ($categorias as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $requests['categoria_id'] == $item->id ? 'selected' : ''}}>{{ $item->categoria }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">Tipo Operação</label>
                                    <select type="text" class="form-control select2" name="tipo">
                                        <option value="">{{ __('messages.todos') }}</option>

                                        <optgroup label="ENTRADAS">
                                            <option value="CN" {{ $requests['tipo'] == "CF" ? 'selected' : ''}}>COMPRAS A PRONTO PAGAMENTO</option>
                                            <option value="CF" {{ $requests['tipo'] == "CF" ? 'selected' : ''}}>COMPRAS A PRAZO</option>
                                            <option value="IO" {{ $requests['tipo'] == "IO" ? 'selected' : ''}}>EXISTÊNCIA INICIAS</option>
                                            <option value="IP" {{ $requests['tipo'] == "IP" ? 'selected' : ''}}>ACERTO INVENTÁRIO</option>
                                        </optgroup>

                                        <optgroup label="SAÍDAS">
                                            <option value="D1" {{ $requests['tipo'] == "D1" ? 'selected' : ''}}>DEVOLUÇÃO A FRONECEDOR</option>
                                            <option value="L1" {{ $requests['tipo'] == "L1" ? 'selected' : ''}}>REQUISIÇÕES COM CUSTOS</option>
                                            <option value="L4" {{ $requests['tipo'] == "L4" ? 'selected' : ''}}>QUEBRA EM ARAMAZÉM</option>
                                            <option value="IN" {{ $requests['tipo'] == "IN" ? 'selected' : ''}}>ACERTO INVENTÁRIO</option>
                                        </optgroup>

                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="fornecedor_id" class="form-label">Fornecedores</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 fornecedor_id" name="fornecedor_id" id="fornecedor_id">
                                            <option value="">{{ __('messages.todos') }}</option>
                                            @foreach ($fornecedores as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $requests['fornecedor_id'] == $item->id ? 'selected' : ''}}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                <a href="{{ route('imprimir-pdf-movimentos-estoques-excel', ['data_inicio' => $requests['data_inicio'] ?? date("Y-m-d"), 'data_final' => $requests['data_final'] ?? date("Y-m-d"), 'tipo' => $requests['tipo'], 'produto_id' => $requests['produto_id'], 'categoria_id' => $requests['categoria_id'], 'fornecedor_id' => $requests['fornecedor_id']]) }}" target="_blank" class="btn btn-light-success float-right"><i class="fas fa-file-excel"></i> {{ __('messages.exportar_pdf') }}</a>
                                <a href="{{ route('imprimir-pdf-movimentos-estoques', ['data_inicio' => $requests['data_inicio'] ?? date("Y-m-d"), 'data_final' => $requests['data_final'] ?? date("Y-m-d"), 'tipo' => $requests['tipo'], 'produto_id' => $requests['produto_id'], 'categoria_id' => $requests['categoria_id'], 'fornecedor_id' => $requests['fornecedor_id']]) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>Registro</th>
                                        <th>Cliente</th>
                                        <th>Fornecedor</th>
                                        <th>Tipo</th>
                                        <th>Referência</th>
                                        <th>{{ __('messages.data') }}</th>
                                        <th>Data & Hora</th>
                                        <th>Operação</th>
                                        <th>Observação</th>
                                        <th>{{ __('messages.total') }}</th>
                                        <th>Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movimentos as $item)
                                    <tr>
                                        <td>{{ $item->sigla ?? "" }}</td>
                                        <td>{{ $item->cliente->nome ?? "" }}</td>
                                        <td>{{ $item->fornecedor->nome ?? "" }}</td>
                                        <td>{{ $item->tipo_documento }}</td>
                                        <td>{{ $item->codigo ?? "" }}</td>
                                        <td>{{ $item->data_at ?? "" }}</td>
                                        <td>{{ $item->created_at ?? "" }}</td>
                                        <td>{{ $item->operacao ?? "" }}</td>
                                        <td>{{ $item->observacao ?? "" }}</td>
                                        <td>{{ number_format($item->total ?? 0, 2, ",", ".") }}</td>
                                        <td style="width: 200px">
                                            <a href="{{ route('pdf-registro-movimentos-estoque', $item->id) }}" target="_blink" class="btn btn-light-danger"><i class="fas fa-file-pdf"></i> Imprimir</a>
                                        </td>
                                    </tr>
                                    @endforeach
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
