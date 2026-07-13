@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.marcacoes_ausencias') }}</h1>
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
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12 bg-light">
                    <div class="card">
                        <form action="{{ route('marcacoes-ausencias.index') }}" method="get" class="mt-3">
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
                                <a href="{{ route('marcacoes-ausencias.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                                @endif
                            </h3>
                        </div>

                        @if ($ausencias)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('messages.nome') }}</th>
                                        <th>{{ __('messages.data_inicio') }}</th>
                                        <th>{{ __('messages.data_final') }}</th>
                                        <th>Data Referênciada</th>
                                        <th>Número dias</th>
                                        <th>Ausência</th>
                                        <th>
                                            <span class="float-right">{{ __('messages.accoes') }} </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ausencias as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td><a href="{{ route('funcionarios.show', $item->funcionario->id) }}">{{ $item->funcionario->nome }}</a></td>
                                        <td>{{ $item->data_inicio }}</td>
                                        <td>{{ $item->data_final }}</td>
                                        <td>{{ $item->data_referenciada }}</td>
                                        <td>{{ $item->ausencia->nome }}</td>
                                        <td>{{ $item->dias }}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="{{ route('marcacoes-ausencias.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    <a class="dropdown-item" href="{{ route('marcacoes-ausencias.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('marcacoes-ausencias.destroy', $item->id ) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-light-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Marcação?')">
                                                            <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
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
