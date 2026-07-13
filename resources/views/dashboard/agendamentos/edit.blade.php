@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-edit"></i> {{ __('messages.editar') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('marcas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
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
                        <form action="{{ route('agendamentos.update', $agenda->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="cliente_id" class="form-label"> {{ __('messages.clientes') }} </label>
                                        <select type="text" class="form-control select2" id="cliente_id" name="cliente_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($clientes as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $agenda->cliente_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
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
                                            <option value="{{ $item->id ?? "" }}" {{ $agenda->servico_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>

                                        <p class="text-light-danger">
                                            @error('servico_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="" class="form-label">{{ __('messages.estados') }}</label>
                                        <select type="text" class="form-control" name="status">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="pendente" {{ $agenda->status == "pendente" ? 'selected' : '' }}>Pendente</option>
                                            <option value="atendido" {{ $agenda->status == "atendido" ? 'selected' : '' }}>Atendido</option>
                                            <option value="cancelado" {{ $agenda->status == "cancelado" ? 'selected' : '' }}>Cancelado</option>
                                            <option value="experido" {{ $agenda->status == "experido" ? 'selected' : '' }}>Expirado</option>
                                        </select>
                                        <p class="text-light-danger">
                                            @error('status')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <label for="hora" class="form-label">Hora</label>
                                        <input type="time" class="form-control" name="hora" value="{{ $agenda->hora ?? old('hora') }}" placeholder="Informe a hora">
                                        <p class="text-light-danger">
                                            @error('hora')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="data_at" class="form-label"> {{ __('messages.data') }} </label>
                                        <input type="date" class="form-control" name="data_at" value="{{ $agenda->data_at ?? old('data_at') }}" placeholder="Informe a data">
                                        <p class="text-light-danger">
                                            @error('data_at')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="observacao" class="form-label">{{ __('messages.observacao') }}</label>
                                        <input type="text" class="form-control" name="observacao" value="{{ $agenda->observacao ?? old('observacao') }}" placeholder="Digita uma Observação">
                                        <p class="text-light-danger">
                                            @error('observacao')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar agendamento'))
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
