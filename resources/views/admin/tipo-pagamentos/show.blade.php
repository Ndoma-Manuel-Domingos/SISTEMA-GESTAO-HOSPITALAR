@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><span class="text-uppercase">{{ $caixa->nome }}</span> - Movimentos de Caixa
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('caixas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Caixa</li>
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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> {{ __('messages.data') }} </span>
                                                </div>
                                                <input type="date" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="{{ __('messages.designacao') }} ...">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> Até </span>
                                                </div>
                                                <input type="date" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="{{ __('messages.designacao') }} ...">
                                                <button type="submit" class="btn btn-light-primary ml-2"> <i class="fas fa-search"></i>
                                                    Filtar</button>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-8">
                                            <a href="" class="float-right btn btn-light-primary">Exportar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-12">
                    @if ($caixa)
                    <!-- /.card-header -->
                    @if ($movimentos)
                    @foreach ($movimentos as $item)
                    <div class="card">
                        <div class="card-body table-responsive mb-4">
                            <table class="table text-nowrap">
                                <tbody>
                                    <tr>
                                        <td rowspan="2" class="text-center">
                                            <span>Quinta-feira </span><br>
                                            <small>6 de outubro 2022</small> <br>
                                            <span>0,00 Kz</span>
                                        </td>
                                        <td class="text-right">Abertura</td>
                                        <td class="text-left"><strong>{{ $item->hora_abertura ?? "" }}</strong></td>
                                        <td class="text-right">{{ __('messages.valor') }}</td>
                                        <td class="text-left"><strong>{{ number_format($item->valor_abertura??0, 2, ',', '.')  }} {{ $dados->empresa->moeda }}</strong></td>
                                        <td class="text-right">Utilizador</td>
                                        @if (!empty($item->user_id))
                                        <td class="text-left"><strong>
                                                {{ $item->user->name ?? "" }}
                                            </strong>
                                        </td>
                                        @else
                                        <td class="text-left"><strong>N/A</strong></td>
                                        @endif
                                        <td rowspan="2" class="text-center">
                                            <br>
                                            <a href="{{ route('caixa.caixas-detalhe', $item->id) }}" class="btn btn-light-primary float-right mr-2"> {{ __('messages.mais_detalhes') }}</a>
                                            <a href="" class="btn btn-light-primary float-right mr-2">Exportar</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">Fecho</td>
                                        <td class="text-left"><strong>{{ $item->hora_fecho }}</strong></td>
                                        <td class="text-right">{{ __('messages.valor') }}</td>
                                        <td class="text-left"><strong>{{ number_format($item->valor_valor_fecho??0, 2, ',', '.')  }} {{ $dados->empresa->moeda }}</strong></td>
                                        <td class="text-right">Utilizador</td>
                                        @if (!empty($item->user_fecho))
                                        <td class="text-left"><strong>
                                                {{ $item->user->name ?? "" }}
                                            </strong>
                                        </td>
                                        @else
                                        <td class="text-left"><strong>N/A</strong></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    @endif
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
