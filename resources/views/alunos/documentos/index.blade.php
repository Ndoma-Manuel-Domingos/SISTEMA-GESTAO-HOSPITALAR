@extends('layouts.alunos')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Documentos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-alunos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Documentos</li>
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
                                <a href="{{ route('alunos-documentos.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                            </h3>

                            <div class="card-tools">
                            </div>
                        </div>

                        @if ($documentos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Documentos</th>
                                        <th> {{ __('messages.descricao') }} </th>
                                        <th>Data Solicitação</th>
                                        <th><span class="float-right">{{ __('messages.accoes') }} </span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documentos as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>
                                            @if($item->tipo_documento_id == "cerfificado") CERTIFICADO @endif
                                            @if($item->tipo_documento_id == "declaracao") DECLARAÇÃO @endif
                                            @if($item->tipo_documento_id == "transferencia") TRANSFERÊNCIA @endif
                                        </td>
                                        <td>{{ $item->descricao }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="{{ route('alunos-documentos.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('alunos-documentos.destroy', $item->id ) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-light-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta solicitação?')">
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
