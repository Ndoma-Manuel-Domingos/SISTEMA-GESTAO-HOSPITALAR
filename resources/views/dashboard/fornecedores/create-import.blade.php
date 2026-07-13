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
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active"> {{ __('messages.fornecedores') }} </li>
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
                            <h5>Nome | NIF | Telefone | Email | Tipo </h5>
                        </div>

                        <form action="{{ route('store_import.fornecedores') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="file" class="col-form-label text-right">Documento</label>
                                    <input type="file" class="form-control" id="file" name="file" value="{{ old('file') }}" placeholder="Informe file Fornecedor">
                                    <p class="text-light-danger">
                                        @error('file')
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
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
