@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.actualizar_stock') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('estoques.index') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('estoques.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="loja_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @if ($lojas)
                                            @foreach ($lojas as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('loja_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="produto_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @if ($produtos)
                                            @foreach ($produtos as $item2)
                                            <option value="{{ $item2->id }}">{{ $item2->nome }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('produto_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="stock" value="{{ old('stock') }}" placeholder="{{ __('messages.quantidade') }} ...">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('stock')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="operacao">
                                            {{-- <option value="">{{ __('messages.escolher') }}</option> --}}
                                            <option value="Entrada de Stock">Entrada de Stock</option>
                                            <option value="Saída de Stock">Saída de Stock</option>
                                            <option value="Actualizar de Stock">Actualizar de Stock</option>
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('operacao')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-2">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="observacao" value="{{ old('observacao') }}" placeholder="Observação">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('observacao')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.row -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
