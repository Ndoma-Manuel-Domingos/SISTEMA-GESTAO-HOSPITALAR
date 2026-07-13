@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.utilizador') }}</h1>
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
                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('nosso-utilizadores-pdf') }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($users)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th> {{ __('messages.data_nascimento') }}</th>
                                        <th>Empresa</th>
                                        <th>NIF</th>
                                        <th> {{ __('messages.telemovel') }} </th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>Modulo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $item)
                                    @if ($item->company)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->company->nome ?? "" }}</td>
                                        <td>{{ $item->company->nif ?? "" }}</td>
                                        <td>{{ $item->company->telefone ?? "" }}</td>
                                        <td>{{ $item->company->status ?? "" }}</td>
                                        <td>{{ $item->company->tipo_entidade->tipo ?? "" }}</td>
                                    </tr>
                                    @endif
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
