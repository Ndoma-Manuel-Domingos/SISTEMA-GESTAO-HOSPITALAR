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
                        <li class="breadcrumb-item"><a href="{{ route('contrapartidas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Contrapartida</li>
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
                        @if ($contrapartida)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tipo Crédito</th>
                                        <th>Subconta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $contrapartida->id }}</td>
                                        <td>{{ $contrapartida->tipo_credito->nome }}</td>
                                        <td>{{ $contrapartida->subconta->sigla }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer clearfix d-flex">

                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar conta'))
                            <a href="{{ route('contrapartidas.edit', $contrapartida->id) }}" class="btn btn-light-success mx-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar conta'))
                            <form action="{{ route('contrapartidas.destroy', $contrapartida->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light-danger mx-1" onclick="return confirm('Tens Certeza que Desejas excluir esta contrapartida?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif

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
