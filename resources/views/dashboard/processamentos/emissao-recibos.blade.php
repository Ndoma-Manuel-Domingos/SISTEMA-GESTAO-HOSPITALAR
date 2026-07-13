@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Emissão de Recibos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Recibos</li>
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
                        <form action="{{ route('emissao-recibo-processamentos') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="processamento_id" class="form-label">Tipo Processamento</label>
                                    <select type="text" class="form-control select2" id="processamento_id" name="processamento_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($tipo_processamentos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['processamento_id'] == $item->id ? 'selected' : '' }}> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="exercicio_id" class="form-label"> {{ __('messages.exercicio') }} </label>
                                    <select type="text" class="form-control select2" id="exercicio_id" name="exercicio_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($exercicios as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['exercicio_id'] == $item->id ? 'selected' : '' }}> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="periodo_id" class="form-label"> {{ __('messages.periodo') }} </label>
                                    <select type="text" class="form-control select2" id="periodo_id" name="periodo_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($periodos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['periodo_id'] == $item->id ? 'selected' : '' }}> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <select type="text" class="form-control select2" id="status" name="status">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        <option value="Pendente" {{ $requests['status'] == "Pendente" ? 'selected' : '' }}>Pendente</option>
                                        <option value="Pago" {{ $requests['status'] == "Pago" ? 'selected' : '' }}>Pago</option>
                                        <option value="Anulado" {{ $requests['status'] == "Anulado" ? 'selected' : '' }}>Anulado</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? "" }}" id="data_inicio" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_final'] ?? "" }}" id="data_final" name="data_final" placeholder="Data final">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-light-primary btn-sm ml-2 text-right"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('imprimir-recibos-processamentos', ['processamento_id' => $requests['processamento_id'] ?? '', 'exercicio_id' => $requests['exercicio_id'] ?? '', 'periodo_id' => $requests['periodo_id'] ?? '', 'status' => $requests['status'] ?? '', 'data_inicio' => $requests['data_inicio'] ?? '', 'data_final' => $requests['data_final'] ?? '' ]) }}"><i class="fas fa-file-pdf"></i> EMITIR OS RECIBOS</a>
                            </div>
                        </div>

                        @if ($processamentos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Proc Nº</th>
                                        <th>Nº MAC</th>
                                        <th>{{ __('messages.nome') }}</th>
                                        <th>Processamento</th>
                                        <th> {{ __('messages.estados') }} </th>
                                        <th>Salário Base</th>
                                        <th>Salário Iliquido</th>
                                        <th>{{ __('messages.desconto') }}</th>
                                        <th>Salário líquido</th>
                                        <th> {{ __('messages.exercicio') }} </th>
                                        <th> {{ __('messages.periodo') }} </th>
                                        <th>Operador</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>{{ __('messages.imprimir') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($processamentos as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td><a href="{{ route('funcionarios.show', $item->funcionario->id) }}">{{ $item->funcionario->numero_mecanografico }}</a></td>
                                        <td>{{ $item->funcionario->nome }}</td>
                                        <td>{{ $item->processamento->nome }}</td>
                                        @if ($item->status == 'Pendente')
                                        <td><span class="badge  bg-light-primary">{{ $item->status }}</span></td>
                                        @endif
                                        @if ($item->status == 'Pago')
                                        <td><span class=" badge bg-light-success">{{ $item->status }}</span></td>
                                        @endif
                                        @if ($item->status == 'Anulado')
                                        <td><span class="badge bg-light-warning">{{ $item->status }}</span></td>
                                        @endif
                                        <td>{{ number_format($item->valor_base, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->valor_iliquido, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->total_desconto, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->valor_liquido, 2, ',', '.') }}</td>

                                        <td>{{ $item->exercicio->nome }}</td>
                                        <td>{{ $item->periodo->nome }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->data_registro }}</td>

                                        <td><a href="{{ route('recibo-processamentos', $item->id) }}" class="text-center" target="_blank"><i class="fas fa-print"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- /.card-body -->

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

@section('scripts')
<script>
    $("#exercicio_id").change(() => {
        let id = $("#exercicio_id").val();
        $.get('../carregar-periodos/' + id, function(data) {
            $("#periodo_id").html("")
            $("#periodo_id").html(data)
        })
    })

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
