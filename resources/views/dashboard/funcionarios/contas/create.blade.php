@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Regularização Conta Corrente</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Conta</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="{{ route('conta-clientes.store') }}" method="post" class="">
                    @csrf
                    <div class="card-body row">

                        <div class="col-12 col-md-12">
                            <label for="">{{ __('messages.observacao') }}:</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="observacao" value="{{ old('observacao') }}" placeholder="{{ __('messages.observacao') }}:">
                            </div>
                            <p class="text-light-danger">
                                @error('observacao')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-12">
                            <label for="">{{ __('messages.valor') }}</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="montante" value="{{ old('montante') }}" placeholder="Montante">
                            </div>
                            <p class="text-light-danger">
                                @error('montante')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-12">
                            <label for="">Tipo Movimento</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control" name="tipo_movimento">
                                    <option value="1">Crédito (aumenta dívida do cliente)</option>
                                    <option value="-1">Dédito (aumenta saldo a favor do cliente)</option>
                                </select>
                            </div>
                            <p class="text-light-danger">
                                @error('tipo_movimento')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>
                        <input type="hidden" name="cliente_id" value="{{ $clienteSaldo->id }}">
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                    </div>
                </form>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
