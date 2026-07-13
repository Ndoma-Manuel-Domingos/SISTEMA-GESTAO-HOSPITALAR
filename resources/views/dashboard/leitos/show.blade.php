@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('camas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Leito</li>
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
                <div class="col-12 col-md-12">
                    <div class="card">
                        @if ($leito)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>leito</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>Data de criação</th>
                                        <th>Data de actualização</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $leito->id }}</td>
                                        <td>{{ $leito->nome }}</td>
                                        <td>{{ $leito->status }}</td>
                                        <td>{{ $leito->created_at }}</td>
                                        <td>{{ $leito->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix d-flex">
                        </div>
                        @endif
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
</script>
@endsection
