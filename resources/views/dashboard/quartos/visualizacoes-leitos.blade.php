@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard de Internamento</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-hospital') }}">{{ __('messages.voltar') }}</a></li>
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

            {{-- Estatísticas --}}
            <div class="row mb-4">

                <div class="col-md-3">
                    <div class="small-box bg-light-primary shadow">
                        <div class="inner">
                            <h3>{{ $internados }}</h3>
                            <p>Pacientes Internados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-injured"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-light-success shadow">
                        <div class="inner">
                            <h3>{{ $leitosLivres }}</h3>
                            <p>Leitos Livres</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-bed"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-light-warning shadow">
                        <div class="inner">
                            <h3>{{ $ocupacao }}%</h3>
                            <p>Taxa de Ocupação</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-light-danger shadow">
                        <div class="inner">
                            <h3>{{ $altasHoje }}</h3>
                            <p>Altas Hoje</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Pesquisa --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET">
                        <div class="input-group mb-4">
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="Pesquisar quarto, leito ou paciente..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Quartos --}}
            <div class="row">
                @foreach($quartos as $quarto)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-lg h-100">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0">
                                        🏥 Quarto - {{ $quarto->nome }}
                                    </h5>
                                    <small class="text-muted">
                                        {{ $quarto->andar->nome }}º Andar
                                    </small>
                                </div>
                                <span class="badge badge-primary">
                                    {{ $quarto->tipo->nome }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            @php
                            $ocupadas = $quarto->leitos->where('status', 'ocupada')->count();
                            $total = $quarto->leitos->count();
                            $percentagem = 0;
                            if ($ocupadas != 0) {
                            $percentagem = ($ocupadas/$total)*100;
                            }

                            @endphp
                            <label>Ocupação</label>
                            <div class="progress mb-3" style="height:12px;">
                                <div class="progress-bar bg-success" style="width:{{$percentagem}}%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>
                                    {{$ocupadas}} Ocupados
                                </span>
                                <span>
                                    {{$total-$ocupadas}} Livres
                                </span>
                            </div>
                            <hr>
                            @can("listar internamento")
                            <a href="{{ route('quartos.show',$quarto->id) }}" class="btn btn-primary btn-block">
                                Ver Leitos
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                @endforeach
            </div>



            <!-- /.row -->
            {{-- <div class="row">
                <div class="col-12 col-md-12">

                    @foreach ($quartos as $quarto)
                    <div class="card mb-4">
                        <div class="card-header bg-light-dark  text-white">
                            <strong>Qaurto: {{ $quarto->nome }}</strong>
            <span class="float-end">Capacidade: {{ $quarto->leitos->count() }}</span>

            <a href="{{ route('quartos.lista-pacientes-quartos', $quarto->id) }}" target="_blank" class="btn btn-light-danger float-right"> <i class="fas fa-file-pdf"></i> Lista Paciente</a>
        </div>

        <div class="card-body">
            <div class="row row-cols-auto">
                @foreach ($quarto->leitos as $leito)
                <div class="col mb-3">
                    <div class="text-center">
                        <a href="{{ route("camas.show", $leito->id) }}">
                            <div class="p-3 rounded text-white" style="background-color:
                                                    {{ $leito->status === "livre" ? '#006699' : ($leito->status === "ocupada" ? '#ffc107' : '#28a745') }}; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                <strong>{{ $leito->nome }}</strong>
                            </div>
                        </a>
                        <small class="d-block mt-1 text-left">
                            {{ ucfirst($leito->status) }}
                        </small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

</div>
</div> --}}
<!-- /.row -->
</div><!-- /.quartoiner-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')

@endsection
