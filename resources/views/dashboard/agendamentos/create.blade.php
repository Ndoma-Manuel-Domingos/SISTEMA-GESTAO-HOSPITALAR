@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('agendamentos.create') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Agendamento</li>
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
                        <form action="{{ route('agendamentos.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="cliente_id" class="form-label"> {{ __('messages.clientes') }} </label>
                                        <select type="text" class="form-control select2" id="cliente_id" name="cliente_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($clientes as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>

                                        <p class="text-light-danger">
                                            @error('cliente_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>



                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="servico_id" class="form-label">{{ __('messages.servico') }}</label>
                                        <select type="text" class="form-control select2" id="servico_id" name="servico_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>

                                        <p class="text-light-danger">
                                            @error('servico_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                        <select type="text" class="form-control select2" name="status">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="pendente">Pendente</option>
                                            <option value="atendido">Atendido</option>
                                            <option value="cancelado">Cancelado</option>
                                            <option value="experido">Expirado</option>
                                        </select>
                                        <p class="text-light-danger">
                                            @error('status')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <label for="hora" class="form-label">Hora</label>
                                        <input type="time" class="form-control" name="hora" value="{{ old('hora') }}" placeholder="Informe a hora">
                                        <p class="text-light-danger">
                                            @error('hora')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="data_at" class="form-label"> {{ __('messages.data') }} </label>
                                        <input type="date" class="form-control" name="data_at" value="{{ old('data_at') }}" placeholder="Informe a data">
                                        <p class="text-light-danger">
                                            @error('data_at')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="observacao" class="form-label">{{ __('messages.observacao') }}</label>
                                        <input type="text" class="form-control" name="observacao" value="{{ old('observacao') }}" placeholder="Digita uma observação">
                                        <p class="text-light-danger">
                                            @error('observacao')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar agendamento'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </form>
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
