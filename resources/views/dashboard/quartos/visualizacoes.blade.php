@extends('layouts.app')

@section('content')
<!-- Content Wrapper. quartoins page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.monitoramento_quartos') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Quartos</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <div class="alert alert-light mb-0">
                        <h5><i class="fas fa-info"></i>{{ __('messages.informacoes_gerais') }}!</h5>
                        <p class="mb-0">
                            <strong>Legenda de cores:</strong><br>
                            <span class="badge p-3" style="background-color: #006699;">&nbsp;&nbsp;</span> {{ __('messages.livre') }} -
                            <span class="badge p-3" style="background-color: #ffc107;">&nbsp;&nbsp;</span> {{ __('messages.reservado') }} -
                            <span class="badge p-3" style="background-color: #28a745;">&nbsp;&nbsp;</span> {{ __('messages.ocupado') }}
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-4 text-md-right mt-3 mt-md-0">
                    <a href="{{ route('reservas.create') }}" class="btn py-5 btn-light-primary btn-lg w-100">
                        <i class="fas fa-plus"></i>{{ __('messages.novo') }} {{ __('messages.reserva') }}
                    </a>
                </div>
            </div>

            <!-- /.row -->
            <div class="row mt-4">
                <div class="col-12 col-md-12">

                    @foreach ($andares as $andar)
                    <div class="card mb-4">
                        <div class="card-header bg-light text-white">
                            <strong>Andar: {{ $andar->nome }}</strong>
                            <span class="float-end">Capacidade: {{ $andar->quartos->count() }}</span>
                        </div>

                        <div class="card-body">
                            <div class="row row-cols-auto">
                                @foreach ($andar->quartos as $quarto)
                                <div class="col mb-3">
                                    <div class="text-center">

                                        @if ($quarto->solicitar_ocupacao === "RESERVADA" || $quarto->solicitar_ocupacao === "OCUPADA")
                                        <a href="{{ route("quartos.visualizacao-andares-quartos-detalhes", $quarto->id) }}">
                                            <div class="p-3 rounded text-white" style="background-color:
                                                {{ $quarto->solicitar_ocupacao === "LIVRE" ? '#006699' : ($quarto->solicitar_ocupacao === "RESERVADA" ? '#ffc107' : '#28a745') }}; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                                <strong>{{ $quarto->nome }}</strong>
                                            </div>
                                        </a>
                                        @else
                                        <div class="p-3 rounded text-white" style="background-color:
                                                {{ $quarto->solicitar_ocupacao === "LIVRE" ? '#006699' : ($quarto->solicitar_ocupacao === "RESERVADA" ? '#ffc107' : '#28a745') }}; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                            <strong>{{ $quarto->nome }}</strong>
                                        </div>
                                        @endif

                                        {{-- <small class="d-block mt-1 text-left">
                                            {{ ucfirst($quarto->solicitar_ocupacao) }}
                                        </small> --}}
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
