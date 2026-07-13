@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><span class="text-uppercase">{{ __('messages.mais_detalhes') }}: {{ $lote->lote }}</span></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('lotes.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Lote</li>
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
                        @if ($lote)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Lote</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>{{ __('messages.codigo_barras') }}</th>
                                        <th>Data Validação</th>
                                        <th class="text-right">{{ __('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{ $lote->lote ?? "" }}
                                        </td>
                                        <td>
                                            {{ $lote->produto->nome ?? "" }}
                                        </td>
                                        <td>
                                            {{ $lote->status ?? "" }}
                                        </td>
                                        <td>
                                            {{ $lote->codigo_barra ?? "" }}
                                        </td>
                                        <td>
                                            {{ $lote->data_validade ?? "" }}
                                        </td>
                                        <td class="text-right">
                                            {{ $lote->stock_total ?? "" }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer clearfix d-flex">
                            <a href="{{ route('lotes.edit', $lote->id) }}" class="btn btn-light-success mx-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('lotes.destroy', $lote->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light-danger mx-1" onclick="return confirm('Tens Certeza que Desejas excluir esta Pin?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
