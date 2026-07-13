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
                        <li class="breadcrumb-item"><a href="{{ route('funcionarios.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Funcionário</li>
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
                        <div class="card-header">
                            <h5>Numero Mecanografico | Nome | NIF | Categoria | Genero | Telefone | Email </h5>
                        </div>
                        <form action="{{ route('store_import.funcionarios') }}" method="post" class="" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-12">
                                    <label for="file" class="form-label">Carregar Excel</label>
                                    <input type="file" id="file" class="form-control" name="file" value="{{ old('file') }}" placeholder="Informe  file">
                                    <p class="text-light-danger">
                                        @error('file')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar funcionario'))
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
