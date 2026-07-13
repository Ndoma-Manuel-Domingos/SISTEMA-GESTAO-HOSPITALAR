@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Movimentos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pronto-venda') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Movimentos</li>
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
                        <form action="{{ route('caixa.movimentos_caixa') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="caixa_id">Caixas</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="caixa_id" id="caixa_id">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            @foreach ($caixas as $caixa)
                                            <option value="{{ $caixa->id }}" {{ $requests['caixa_id'] == $caixa->id ? 'selected' : '' }}>{{ $caixa->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('caixa_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="operador_id">Operadores</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <select type="text" class="form-control select2" id="operador_id" name="operador_id">
                                                <option value="">{{ __('messages.todos') }} </option>
                                                @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ $requests['operador_id'] == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_inicio">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" id="data_inicio" name="data_inicio">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('data_inicio')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_final">{{ __('messages.data_final') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" id="data_final" value="{{ $requests['data_final'] ?? '' }}" name="data_final">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('data_final')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">
                                    <i class="fas fa-filter"></i> {{ __('messages.filtrar') }}
                                </button>
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
                                <a href="{{ route('caixa.movimentos_caixa', ['data_inicio' => $requests['data_inicio'] ?? "",'data_final' => $requests['data_final'] ?? "", 'caixa_id' => $requests['caixa_id'] ?? "", 'operador_id' => $requests['operador_id'] ?? "", 'documento_pdf' => 'exportar_pdf']) }}" target="_blank" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.exportar_pdf') }}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>Operador</th>
                                        <th>Caixa</th>
                                        <th>Data Abertura</th>
                                        <th>Data Fecho</th>
                                        <th style="text-align: right">V. Abertura</th>
                                        <th style="text-align: right">TPA</th>
                                        <th style="text-align: right">CASH</th>
                                        <th style="text-align: right">{{ __('messages.total') }}</th>
                                        <th class="text-right">{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movimentos as $item)

                                    <tr>
                                        <td>{{ $item->user->name ?? "" }}</td>
                                        <td>{{ $item->subconta->nome ?? "" }}</td>
                                        <td>{{ $item->data_abertura }}</td>
                                        <td>{{ $item->data_fecho }}</td>
                                        <td style="text-align: right">{{ number_format($item->valor_abertura, 2, ',', '.') }}</td>
                                        <td style="text-align: right">{{ number_format($item->valor_multicaixa, 2, ',', '.') }}</td>
                                        <td style="text-align: right">{{ number_format($item->valor_cash, 2, ',', '.') }}</td>

                                        @if (($item->valor_valor_fecho) < 0) <td class="text-light-danger" style="text-align: right">{{ number_format(($item->valor_valor_fecho), 2, ',', '.') }}</td>
                                            @endif

                                            @if (($item->valor_valor_fecho) == 0)
                                            <td class="text-light-warning" style="text-align: right">{{ number_format(($item->valor_valor_fecho), 2, ',', '.') }}</td>
                                            @endif

                                            @if (($item->valor_valor_fecho) > 0)
                                            <td class="text-light-success" style="text-align: right">{{ number_format(($item->valor_valor_fecho), 2, ',', '.') }}</td>
                                            @endif
                                            @if ($item->subconta->active == false)
                                            <td class="text-light-danger text-right">FECHADO</td>
                                            @else
                                            <td class="text-light-success text-right">ABERTO</td>
                                            @endif

                                            <td class="text-light-success text-right">
                                                <a href="{{ route('caixa.movimentos_caixa_imprimir', ['id_imprimir' => $item->id]) }}" target="_blank" class="btn btn-light-primary"><i class="fas fa-print"></i> {{ __('messages.imprimir') }} </a>
                                            </td>
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <h6>Saldo Final = ((Valor Abertura + Valor Fecho + Valor Entrada) - Valor Saída) </h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
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
