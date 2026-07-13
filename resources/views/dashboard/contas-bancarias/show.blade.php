@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Movimentos da Conta Bancária do <span class="text-uppercase">{{ $banco->conta }} - {{ $banco->banco->sigla }}</span>
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('contas-bancarias.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
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
                    <form action="{{ route('contas-bancarias.show', $banco->id) }}" method="GET">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                @method('get')
                                <div class="row">
                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="operador_id">Operadores</label>
                                        <select name="operador_id" id="operador_id" class="select2 form-control @error('operador_id') is-invalid @enderror">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            @foreach ($utilizadores as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $requests['operador_id'] == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="data_inicio">{{ __('messages.data_inicio') }}</label>
                                        <input type="date" class="form-control" name="data_inicio" value="{{ old('data_inicio') ?? $requests['data_inicio'] }}">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="data_final">{{ __('messages.data_inicio') }}</label>
                                        <input type="date" class="form-control" name="data_final" value="{{ old('data_final') ?? $requests['data_final'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="float-right btn btn-light-primary">Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('contas-bancarias.movimentos_banco', ['data_inicio' => $requests['data_inicio'] ?? "",'data_final' => $requests['data_final'] ?? "", 'banco_id' => $banco->subconta_id, 'operador_id' => $requests['operador_id'] ?? "", 'documento_pdf' => 'exportar_pdf']) }}" target="_blank" class="float-right btn btn-light-primary">Exportar</a>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table text-nowrap" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descrição</th>
                                        <th>Operador</th>
                                        <th>Pagamento</th>
                                        <th>Centro de Custo</th>
                                        <th>{{ __('messages.data') }}</th>
                                        <th class="text-center">Movimento</th>
                                        <th class="text-right">Motante</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $saidas = 0;
                                    $entradas = 0;
                                    @endphp
                                    @foreach ($movimentos as $item)
                                    <tr>
                                        <td class="text-left">{{ $item->id ?? "" }}</td>
                                        <td class="text-left">{{ $item->nome ?? "" }}</td>
                                        <td class="text-left">{{ $item->user->name ?? "" }}</td>

                                        @if ($item->formas == "C")
                                        <td class="text-left">NUMÉRARIO</td>
                                        @else
                                        <td class="text-left">MULTICAIXA</td>
                                        @endif
                                        <td class="text-left">{{ $item->centro_custo->name ?? "" }}</td>
                                        <td class="text-left">{{ $item->date_at ?? "" }}</td>

                                        @if ($item->movimento == "E")
                                        @php
                                        $entradas += $item->motante;
                                        @endphp
                                        <td class="text-center"><i class="fas fa-arrow-up text-light-success"></i></td>
                                        @else
                                        @php
                                        $saidas += $item->motante;
                                        @endphp
                                        <td class="text-center"><i class="fas fa-arrow-down text-light-danger"></i></td>
                                        @endif
                                        <td class="text-right">{{ number_format($item->motante ??0, 2, ',', '.')  }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12 col-md-4"></div>
                                <div class="col-12 col-md-2 text-right">
                                    <i class="fas fa-arrow-up text-light-success"></i>
                                    <span class="h5">{{ number_format($entradas ?? 0, 2, ',', '.')  }}</span>
                                </div>
                                <div class="col-12 col-md-2 text-right">
                                    <i class="fas fa-arrow-down text-light-danger"></i>
                                    <span class="h5">{{ number_format($saidas ?? 0, 2, ',', '.')  }}</span>
                                </div>
                                @if (($entradas - $saidas) > 0)
                                <div class="col-12 col-md-2 text-right">
                                    <i class="fas fa-arrow-up text-light-success"></i>
                                    <span class="h5">{{ number_format(($entradas - $saidas) , 2, ',', '.')  }}</span>
                                </div>
                                @else
                                <div class="col-12 col-md-2 text-right">
                                    <i class="fas fa-arrow-down text-light-danger"></i>
                                    <span class="h5">{{ number_format(($entradas - $saidas) , 2, ',', '.')  }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
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
