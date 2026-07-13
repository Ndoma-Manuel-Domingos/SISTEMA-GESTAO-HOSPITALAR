@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Listagem de vendas</h1>
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
                        <form action="{{ route('vendas') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-2">
                                    <label for="loja_id" class="form-label">{{ __('messages.lojas') }}</label>
                                    <select type="text" class="form-control select2" name="loja_id" id="loja_id">
                                        <option value="">{{ __('messages.escolher') }}</option>
                                        @foreach ($empresa->lojas as $loja)
                                        <option value="{{ $loja->id }}" {{ $requests['loja_id'] == $loja->id ? 'selected' : ''}}>{{ $loja->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="caixa_id" class="form-label">{{ __('messages.caixa') }}</label>
                                    <select type="text" class="form-control select2" name="caixa_id" id="caixa_id">
                                        <option value="">{{ __('messages.escolher') }}</option>
                                        @foreach ($empresa->caixas as $caixa)
                                        <option value="{{ $caixa->id }}" {{ $requests['caixa_id'] == $caixa->id ? 'selected' : ''}}>{{ $caixa->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" id="data_inicio" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" id="data_final" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="user_id" class="form-label">Operador</label>
                                    <select type="text" class="form-control select2" id="user_id" name="user_id">
                                        <option value="">{{ __('messages.escolher') }}</option>
                                        @foreach ($empresa->users as $user)
                                        <option value="{{ $user->id }}" {{ $requests['user_id'] == $user->id ? 'selected' : ''}}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
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
                                <a href="{{ route('pdf-vendas', ['data_inicio' => $requests['data_inicio'] ?? '', 'data_final' => $requests['data_final'] ?? '', 'caixa_id' => $requests['caixa_id'], 'user_id' => $requests['user_id'], 'loja_id' => $requests['loja_id']]) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Ref</th>
                                        <th>Factura</th>
                                        <th>Operador</th>
                                        <th> {{ __('messages.clientes') }} </th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th class="text-right">Valor Entregue</th>
                                        <th class="text-right">Troco</th>
                                        <th class="text-right">{{ __('messages.desconto') }}</th>
                                        <th class="text-right">{{ __('messages.total') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendas as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->factura_next }}</td>
                                        <td>{{ $item->user->name ?? "" }}</td>
                                        <td>{{ $item->cliente->nome }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td class="text-right">{{ number_format($item->valor_entregue, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</span></td>
                                        <td class="text-right">{{ number_format($item->valor_troco, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</span></td>
                                        <td class="text-right">{{ number_format($item->desconto, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</span></td>
                                        <td class="text-right">{{ number_format($item->valor_total, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</span></td>
                                        <td class="text-right">
                                            <a href="{{ route('contabilidade-diarios-detalhe', $item->id) }}" class="btn btn-light-primary"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
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
