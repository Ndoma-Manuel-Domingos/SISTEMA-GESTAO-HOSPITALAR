@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.marcacoes_faltas') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">{{ __('messages.voltar') }}</a></li>
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
                <div class="col-12 col-md-12 bg-light">
                    <div class="card">
                        <form action="{{ route('marcacoes-faltas.index') }}" method="get" class="mt-3">
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
                                    <label for="funcionario_id" class="form-label">{{ __('messages.funcionario') }}</label>
                                    <select type="text" class="form-control select2" name="funcionario_id">
                                        <option value="">{{ __('messages.escolher') }}</option>
                                        @foreach ($funcionarios as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $requests['funcionario_id'] == $item->id ? 'selected' : ''}}>{{ $item->numero_mecanografico }} - {{ $item->nome }}</option>
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

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos'))
                                <a href="{{ route('marcacoes-faltas.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                                @endif
                            </h3>

                            {{-- <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div> --}}
                        </div>

                        @if ($funcionarios)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('messages.nome') }}</th>
                                        <th>Faltas</th>
                                        <th><span class="float-right">Total Faltas</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($funcionarios as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->nome ?? "" }}</td>
                                        @php $falta_ = 0; @endphp
                                        <td>
                                            @foreach ($item->faltas as $falta)
                                            <span class="bg-light-danger badge">{{ $falta->data_registro }}</span>
                                            @php $falta_++; @endphp
                                            @endforeach
                                        </td>

                                        <td><span class="float-right">{{ $falta_ }}</span></td>
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
