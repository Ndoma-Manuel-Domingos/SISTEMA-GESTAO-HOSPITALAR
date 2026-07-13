@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Listagem de vendas por operadores</h1>
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
                        <form action="{{ route('vendas_por_operadores') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">

                                <div class="col-3">
                                    <label for="" class="form-label">Operador</label>
                                    <select type="text" class="form-control select2" name="user_id">
                                        <option value="">{{ __('messages.todos') }}</option>
                                        @foreach ($empresa->users as $user)
                                        <option value="{{ $user->id }}" {{ $requests['user_id'] == $user->id ? 'selected' : ''}}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-3">
                                    <label for="" class="form-label">{{ __('messages.caixa') }}</label>
                                    <select type="text" class="form-control select2" name="caixa_id">
                                        <option value="">{{ __('messages.todos') }}</option>
                                        @foreach ($empresa->caixas as $caixa)
                                        <option value="{{ $caixa->id }}" {{ $requests['caixa_id'] == $caixa->id ? 'selected' : ''}}>{{ $caixa->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-3">
                                    <label for="" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-3">
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
                                <a href="{{ route('imprimir-pdf-vendas-operadores-excel', ['data_inicio' => $requests['data_inicio'] ?? date("Y-m-d"), 'data_final' => $requests['data_final'] ?? date("Y-m-d"), 'caixa_id' => $requests['caixa_id'], 'user_id' => $requests['user_id']]) }}" target="_blank" class="btn btn-light-success float-right"><i class="fas fa-file-excel"></i> {{ __('messages.exportar_pdf') }}</a>
                                <a href="{{ route('imprimir-pdf-vendas-operadores', ['data_inicio' => $requests['data_inicio'] ?? date("Y-m-d"), 'data_final' => $requests['data_final'] ?? date("Y-m-d"), 'caixa_id' => $requests['caixa_id'], 'user_id' => $requests['user_id']]) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>Ref</th>
                                        <th>Operador</th>
                                        <th>E-mail</th>
                                        <th>Administrador</th>
                                        <th class="text-right">{{ __('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $total = 0;
                                    @endphp
                                    @foreach ($vendas as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->email }}</td>
                                        <td>{{ $item->user->is_admin == 1 ? "Sim": "Não" }}</td>
                                        <td class="text-right">{{ number_format($item->total_valor, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</span></td>
                                    </tr>
                                    @php $total += $item->total_valor; @endphp
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-right" colspan="4">{{ __('messages.total') }}</td>
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
