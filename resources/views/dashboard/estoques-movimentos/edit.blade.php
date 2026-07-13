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
                        <li class="breadcrumb-item"><a href="{{ route('lojas.index') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('lojas.update', $loja->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">
                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="nome" value="{{ $loja->nome }}" placeholder="Informe a nome">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('nome')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="status">
                                            <option value="activo" {{ $loja->status == "activo" ? 'selected' : '' }}>{{ __('messages.activo') }} </option>
                                            <option value="desactivo" {{ $loja->status == "desactivo" ? 'selected' : '' }}>{{ __('messages.desactivo') }} </option>
                                        </select>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('status')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <textarea class="form-control" rows="3" name="descricao" placeholder="Informe a descricao da Loja ...">{{ $loja->descricao }}</textarea>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('descricao')
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
