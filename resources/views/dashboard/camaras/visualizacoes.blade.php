@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Monitoramento de cameras e gavetas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-hospital') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Camara</li>
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

                    @foreach ($camaras as $camara)
                    <div class="card mb-4">
                        <div class="card-header bg-light-dark  text-white">
                            <strong>Câmara: {{ $camara->nome }}</strong>
                            <span class="float-end">Capacidade: {{ $camara->gavetas->count() }}</span>
                        </div>

                        <div class="card-body">
                            <div class="row row-cols-auto">
                                @foreach ($camara->gavetas as $gaveta)
                                <div class="col mb-3">
                                    <div class="text-center">
                                        <a href="{{ route("gavetas.show", $gaveta->id) }}">
                                            <div class="p-3 rounded text-white" style="background-color:
                                                    {{ $gaveta->ocupacao === 1 ? '#28a745' : ($gaveta->ocupacao === 0 ? '#dc3545' : '#ffc107') }}; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                                <strong>{{ $gaveta->nome }}</strong>
                                            </div>
                                        </a>
                                        <small class="d-block mt-1 text-left">
                                            {{ ucfirst($gaveta->ocupacao === 1 ? "Ocupado" : "Livre") }}
                                        </small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.quartoiner-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')

@endsection
