@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Empresas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">{{ __('messages.voltar') }}</a></li>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('empresas.create-controle') }}" class="btn btn-light-primary">Nova Licença</a>
                                <a href="{{ route('inscricoes.create') }}" class="btn btn-light-primary">Nova Inscrição</a>
                            </h3>

                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('nossa-empresas-pdf') }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($empresas)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Código</th>
                                        <th>NIF</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>Tipo</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th> {{ __('messages.telemovel') }} </th>
                                        <th>{{ __('messages.data_inicio') }}</th>
                                        <th>{{ __('messages.data_final') }}</th>
                                        <th>Licença</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($empresas as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td><a href="{{ route('empresas.show', $item->id) }}">{{ $item->nif }}</a></td>
                                        <td><a href="{{ route('empresas.show', $item->id) }}">{{ $item->nome }}</a></td>
                                        @if ($item->tipo_entidade)
                                        <td><span class="badge badge-light-primary">{{ $item->tipo_entidade ? $item->tipo_entidade->tipo : '"' }}</span></td>
                                        @else
                                        <td><span class="badge badge-light-primary">Comerciante</span></td>
                                        @endif
                                        <td class="text-uppercase">{{ $item->status }}</td>
                                        <td>{{ $item->telefone ?? '000 000 000' }}</td>
                                        <td>{{ $item->controle->inicio ?? "" }}</td>
                                        <td>{{ $item->controle->final ?? "" }}</td>

                                        @if ($item->dias_licencas($item->id) > 30)
                                        <td class="text-light-success">Faltam {{ $item->dias_licencas($item->id) ?? "" }} dias </td>
                                        @else
                                        <td class="text-light-danger">Faltam {{ $item->dias_licencas($item->id) ?? "" }} dias </td>
                                        @endif
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="{{ route('empresas.actualizar-modulos', $item->id) }}"><i class="fas fa-table text-light-primary"></i> Actualizar Modulos</a>
                                                    <a class="dropdown-item" href="{{ route('empresas.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    <a class="dropdown-item" href="{{ route('empresas.edit', $item->id) }}"><i class="fas fa-cog text-light-success"></i> Configurar Licença</a>

                                                    @if ($item->status == "activo")
                                                    <a class="dropdown-item" href="{{ route('empresas.desactivar', $item->id) }}"><i class="fas fa-close text-light-danger"></i> {{ __('messages.desactivo') }}</a>
                                                    @endif
                                                    @if ($item->status == "desactivo")
                                                    <a class="dropdown-item" href="{{ route('empresas.actvar', $item->id) }}"><i class="fas fa-check text-light-success"></i> {{ __('messages.activo') }}</a>
                                                    @endif
                                                    <a class="dropdown-item" href="{{ route('empresas.destroys', $item->id) }}"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>

                                                    <div class="dropdown-divider"></div>
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
