@extends('layouts.alunos')

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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-alunos') }}">{{ __('messages.voltar') }}</a></li>
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
            <div class="card">
                <form action="{{ route('alunos-dados-update', $utilizador->id) }}" method="post" class="">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12 mb-3">
                                <label for="nome" class="form-label">{{ __('messages.designacao') }}</label>
                                <input type="text" class="form-control" name="nome" value="{{ $utilizador->name }}" placeholder="Informe a utilizador">
                                <p class="text-light-danger">
                                    @error('nome')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            <div class="col-12 col-md-12 mb-3">
                                <label for="email" class="form-label"> {{ __('messages.email') }}</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{ $utilizador->email }}" placeholder="Informe E-mail">
                                <p class="text-light-danger">
                                    @error('email')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            <div class="col-12 col-md-12 mb-3">
                                <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                <select type="text" id="status" class="form-control select2" name="status">
                                    <option value="1" {{ $utilizador->status == "1" ? 'selected' : '' }}>{{ __('messages.activo') }} </option>
                                    <option value="0" {{ $utilizador->status == "0" ? 'selected' : '' }}>{{ __('messages.desactivo') }} </option>
                                </select>
                            </div>
                        </div>
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
