@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Renovações de Contratos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Contratos</li>
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
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar contrato'))
                                <a href="{{ route('renovacoes-contratos.create') }}" class="btn btn-light-primary">Renovar Contrato</a>
                                @endif
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($contratos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nº</th>
                                        <th>Nº Mec.</th>
                                        <th>Funcionário</th>
                                        <th>{{ __('messages.cargos') }}</th>
                                        <th>{{ __('messages.categoria') }}</th>
                                        <th>Tipo Contrato</th>
                                        <th>{{ __('messages.data_inicio') }}</th>
                                        <th>{{ __('messages.data_final') }}</th>
                                        <th>Nova Data Final</th>
                                        <th>Reno. Efectuadas</th>
                                        <th>Sit. Após Renovação</th>
                                        <th>Antiguidade</th>
                                        <th>Duração Proxima Renovação</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contratos as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->numero }}</td>
                                        <td>{{ $item->funcionario->numero_mecanografico ?? "" }}</td>
                                        <td>{{ $item->funcionario->nome ?? "" }}</td>
                                        <td>{{ $item->cargo->nome ?? "" }}</td>
                                        <td>{{ $item->categoria->nome ?? "" }}</td>
                                        <td>{{ $item->tipo_contrato->nome ?? "" }}</td>
                                        <td>{{ $item->data_inicio ?? "" }}</td>
                                        <td>{{ $item->data_final ?? "" }}</td>
                                        <td>{{ $item->nova_data_final ?? "" }}</td>
                                        <td class="text-center">{{ $item->renovacoes_efectuadas ?? "" }}</td>
                                        <td class="text-center">{{ $item->situacao_apos_renovacao ?? "" }}</td>
                                        <td class="text-center">{{ $item->antiguidade ?? "" }}</td>
                                        <td class="text-center">{{ $item->duracao_renovacao ?? "" }}</td>
                                        <td>{{ $item->status }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar contrato'))
                                                    <a class="dropdown-item" href="{{ route('contratos.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif
                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar contrato'))
                                                    <a class="dropdown-item" href="{{ route('contratos.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar contrato'))
                                                    <form action="{{ route('contratos.destroy', $item->id ) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-light-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta contrato?')">
                                                            <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

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
