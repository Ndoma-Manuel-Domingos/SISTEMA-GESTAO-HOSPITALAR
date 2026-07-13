@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Movimentos do Stock</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Stock</li>
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
                        <div class="card-body">
                            <form action="{{ route('movimento-estoques.index') }}" method="get" id="form_pesquisa">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-2">
                                        <label class="form-label">{{ __('messages.designacao') }}</label>
                                        <select class="form-control select2" name="produto_id">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == $requests['produto_id'] ? 'selected' : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-12 col-md-2">
                                        <label for="tipo" class="form-label">Tipos {{ $requests['tipo'] ?? "" }}</label>
                                        <select class="form-control select2 tipo" name="tipo">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            <option value="E" {{ ($requests['tipo'] ?? "") == "E" ? 'selected' : "" }}>Entrada</option>
                                            <option value="S" {{ ($requests['tipo'] ?? "") == "S" ? 'selected' : "" }}>Saída</option>
                                        </select>
                                    </div>


                                    <div class="col-12 col-md-2">
                                        <label for="status" class="form-label">Movimentos</label>
                                        <select class="form-control select2 status" name="status">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            <option value="V" {{ ($requests['status'] ?? "") == "V" ? 'selected' : "" }}>Vendas</option>
                                            <option value="D" {{ ($requests['status'] ?? "") == "D" ? 'selected' : "" }}>Devoluções</option>
                                            <option value="A" {{ ($requests['status'] ?? "") == "A" ? 'selected' : "" }}>Actualizações</option>
                                            <option value="E" {{ ($requests['status'] ?? "") == "E" ? 'selected' : "" }}>Expiração ou outras Saidas</option>
                                            <option value="R" {{ ($requests['status'] ?? "") == "R" ? 'selected' : "" }}>Retorno</option>
                                            <option value="T" {{ ($requests['status'] ?? "") == "T" ? 'selected' : "" }}>Transferência</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label class="form-label">Loja</label>
                                        <select class="form-control select2" name="loja_id">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            @foreach ($lojas as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id == $requests['loja_id'] ? 'selected' : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label class="form-label">{{ __('messages.data_inicio') }}</label>
                                        <input type="date" class="form-control" name="data_inicio" value="{{ old('data_inicio') ?? $requests['data_inicio'] ?? "" }}">
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label class="form-label">{{ __('messages.data_final') }}</label>
                                        <input type="date" class="form-control" name="data_final" value="{{ old('data_final') ?? $requests['data_final'] ?? "" }}">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" form="form_pesquisa" class="btn-sm btn-light-primary ml-2 text-right"> <i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('estoques.create') }}" class="btn btn-light-primary">Actualizar Stock</a>
                            </h3>
                            <a href="{{ route('pdf-movimento-estoque-excel', ['status' => $_GET['status'] ?? '', 'tipo' => $_GET['tipo'] ?? '', 'loja_id' => $_GET['loja_id'] ?? '', 'produto_id' => $_GET['produto_id'] ?? '', 'data_inicio' => $_GET['data_inicio'] ?? '', 'data_final' => $_GET['data_final'] ?? '']) }}" target="_blank" class="btn btn-light-success float-right"><i class="fas fa-file-excel"></i> {{ __('messages.exportar_excel') }}</a>
                            <a href="{{ route('pdf-movimento-estoque', ['status' => $_GET['status'] ?? '', 'tipo' => $_GET['tipo'] ?? '', 'loja_id' => $_GET['loja_id'] ?? '', 'produto_id' => $_GET['produto_id'] ?? '', 'data_inicio' => $_GET['data_inicio'] ?? '', 'data_final' => $_GET['data_final'] ?? '']) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                        </div>

                        @if ($movimentos)
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px"></th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>Operação</th>
                                        <th>Loja</th>
                                        <th>Tipo Documento</th>
                                        <th>Preço de Movimento</th>
                                        <th>{{ __('messages.observacao') }}</th>
                                        <th><span class="float-right"> {{ __('messages.quantidade') }} </span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movimentos as $movimento)
                                    <tr>
                                        <td class="bg-light text-center">
                                            @if ($movimento->registro == "Entrada de Stock")
                                            <span class="text-light-success"><i class="fas fa-plus-circle"></i></span>
                                            @endif

                                            @if ($movimento->registro == "Receção de Encomenda")
                                            <span class="text-light-secondary"><i class="fas fa-plus-circle"></i></span>
                                            @endif

                                            @if ($movimento->registro == "Saída de Stock")
                                            <span class="text-light-danger"><i class="fas fa-minus"></i></span>
                                            @endif

                                            @if ($movimento->registro == "Actualizar de Stock")
                                            <span class="text-light-secondary"><i class="far fa-edit"></i></span>
                                            @endif
                                        </td>

                                        <td><a href="{{ route('produtos.show', $movimento->produto ? $movimento->produto->id : "") }}">{{ $movimento->produto ? $movimento->produto->nome : "" }}</a></td>
                                        <td>{{ date_format($movimento->created_at, "Y-m-d") }} <br>
                                            <small>{{ date_format($movimento->created_at, "h:i:s") }}</small></td>
                                        <td>{{ $movimento->registro }} <br>
                                            <small class="text-light-secondary">{{ $movimento->user->name }}</small>
                                        </td>
                                        <td>{{ $movimento->loja->nome }}</td>

                                        @if (($movimento->status === "D" || $movimento->status === "E" || $movimento->status === "A") && $movimento->documento_id)
                                        <td><a href="{{ route('pdf-registro-movimentos-estoque', $movimento->documento_id) }}">{{ $movimento->documento }}</a></td>
                                        @else
                                        @if ($movimento->status === "V" && $movimento->documento_id)
                                        <td><a href="{{ route('facturas.show', [$movimento->documento_id, 'tipo_documentos' => "FR"]) }}">{{ $movimento->documento }}</a></td>
                                        @else
                                        <td>{{ $movimento->documento }}</td>
                                        @endif
                                        @endif

                                        <td>{{ number_format($movimento->preco_unitario, 2, ',', '.') }}</td>

                                        @if ($movimento->registro == "Receção de Encomenda" && $movimento->encomenda_id != NULL)
                                        <td><a href="{{ route('fornecedores-encomendas.show', $movimento->encomenda_id) }}">{{ $movimento->observacao }}</a></td>
                                        @else
                                        <td>{{ $movimento->observacao }}</td>
                                        @endif

                                        <td><span class="float-right text-light-success">{{ $movimento->produto->converterDaBase($movimento->quantidade, $movimento->produto->unidade) }} {{ $movimento->produto->unidade->sigla }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->

                        @endif

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
