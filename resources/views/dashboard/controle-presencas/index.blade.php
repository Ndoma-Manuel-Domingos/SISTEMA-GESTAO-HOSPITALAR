@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Controle presença</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Presença</li>
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
                    <form method="GET" action="{{ route('controle-presencas.index') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label for="mes" class="form-labrl">Mês:</label>
                                        <input type="month" name="mes" id="mes" class="form-control" value="{{ request('mes') ?? now()->format('Y-m') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('controle-presencas.create') }}" class="btn btn-light-primary btn-sm">Adicionar Presença</a>
                            <a href="{{ route('controle-presencas.create') }}" class="btn btn-light-danger btn-sm float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}</a>
                        </div>
                        @if (count($funcionarios) !== 0)
                        <div class="card-body">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Funcionário</th>
                                        @foreach ($diasDoMes as $dia)
                                        <th>{{ $dia->format('d') }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($funcionarios as $funcionario)
                                    <tr>
                                        <td style="border: 1px solid #eaeaea;">{{ $funcionario->nome }}</td>
                                        @foreach ($diasDoMes as $dia)
                                        @php

                                        $isWeekendPresenca = in_array($dia->dayOfWeek, [Carbon\Carbon::SATURDAY, Carbon\Carbon::SUNDAY]);

                                        $isWeekendFerias = in_array($dia->dayOfWeek, [Carbon\Carbon::SATURDAY, Carbon\Carbon::SUNDAY]);

                                        $presente = !$isWeekendPresenca && $funcionario->faltas->contains(function ($presenca) use ($dia) {
                                        return $presenca->data_registro === $dia->format('Y-m-d') && $presenca->status;
                                        });

                                        // $presenca = $funcionario->faltas->firstWhere('data_registro', $dia->format('Y-m-d'));
                                        // $presente = $presenca && $presenca->presente === true;
                                        // $faltou = $presenca && $presenca->presente === false;


                                        $ferias = !$isWeekendFerias && $funcionario->ferias->filter(function($feria) use ($dia) {
                                        return $dia->between(Carbon\Carbon::parse($feria->data_inicio), Carbon\Carbon::parse($feria->data_final));
                                        })->isNotEmpty();
                                        $isWeekend = in_array($dia->dayOfWeek, [Carbon\Carbon::SATURDAY, Carbon\Carbon::SUNDAY]);

                                        $cor = '';
                                        if ($ferias) $cor = 'background-color: blue; color: white;';
                                        elseif ($isWeekend) $cor = 'background-color: red; color: white;';
                                        elseif ($presente) $cor = 'background-color: green; color: white;';
                                        // elseif ($faltou) $cor = 'background-color: yellow; color: black;'
                                        else $cor = 'background-color: yellow;';
                                        @endphp
                                        <td style="border: 1px solid #dadada; {{ $cor }}">{{ $presente ? '✅' : ($ferias ? '🌴' : '❓') }}</td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <div class="card-footer">
                            <ul>
                                <li style="color: green;font-size: 16px;text-shadow: 1px 1px 1px #000000">Presente: Verde <span>*</span></li>
                                <li style="color: yellow;font-size: 16px;text-shadow: 1px 1px 1px #000000">Falta: Amarelo <span>*</span></li>
                                <li style="color: blue;font-size: 16px;text-shadow: 1px 1px 1px #000000">Férias: Azul <span>*</span></li>
                                <li style="color: red;font-size: 16px;text-shadow: 1px 1px 1px #000000">Finais de semana: Vermelho <span>*</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
