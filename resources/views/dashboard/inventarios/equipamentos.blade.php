@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Equipamentos/Activos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('inventarios.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Equipamentos</li>
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
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('activo contabilidade'))
                                <a href="{{ route('equipamentos-activos.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                                @endif
                            </h3>
                            {{-- <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div> --}}
                        </div>

                        @if ($equipamentos_activos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Conta</th>
                                        <th> {{ __('messages.designacao') }} </th>
                                        <th class="text-right"> {{ __('messages.quantidade') }} </th>
                                        <th class="text-right">{{ __('messages.preco') }}</th>
                                        <th class="text-right">{{ __('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totais = 0;
                                    $qtds = 0;
                                    @endphp
                                    @foreach ($equipamentos_activos as $item)
                                    <tr>
                                        <td><a href="{{ route('equipamentos-activos.show', $item->id) }}">{{ $item->conta->numero }}</a></td>
                                        <td><a href="{{ route('equipamentos-activos.show', $item->id) }}">{{ $item->nome }}</a></td>
                                        <td class="text-right">{{ $item->quantidade }}</td>
                                        <td class="text-right">{{ number_format($item->base_incidencia, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->base_incidencia * $item->quantidade, 2, ',', '.') }}</td>
                                        @php
                                        $totais += $item->base_incidencia * $item->quantidade;
                                        $qtds += $item->quantidade;
                                        @endphp
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">{{ number_format($qtds, 2, ',', '.') }}</th>
                                        <th></th>
                                        <th class="text-right">{{ number_format($totais, 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        @endif

                        <div class="card-footer">
                        </div>

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
