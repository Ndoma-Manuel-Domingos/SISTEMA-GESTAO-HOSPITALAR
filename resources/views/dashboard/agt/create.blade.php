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
                        <li class="breadcrumb-item"><a href="{{ route('lojas.create') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">AGT</li>
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
                        <form action="{{ route('lojas.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="{{ __('messages.designacao') }}...">
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
                                            <option value="activo">{{ __('messages.activo') }} </option>
                                            <option value="desactivo">{{ __('messages.desactivo') }} </option>
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
                                        <textarea class="form-control" rows="2" name="descricao" placeholder="Informe a descricao da Loja ...">{{ old('descricao') }}</textarea>
                                    </div>
                                    <p class="text-light-danger">
                                        @error('descricao')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="codigo_postal" value="{{ old('codigo_postal') }}" placeholder="Informe o Codigo Postal">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('codigo_postal')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="morada" value="{{ old('morada') }}" placeholder="Informe a morada da Loja">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('morada')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="localidade" value="{{ old('localidade') }}" placeholder="Informe a Localidade da Loja">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('localidade')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="telefone" value="{{ old('telefone') }}" placeholder="Informe o Telefone">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('telefone')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Informe o E-mail">
                                    </div>
                                    <p class="text-light-danger">
                                        @error('email')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="cae" value="{{ old('cae') }}" placeholder="Informe o CAE">
                                    </div>
                                    <p>Código CAE que será associado aos documentos emitidos nesta loja - se não for preenchido, será assumido o CAE da empresa (se indicado)</p>
                                    <p class="text-light-danger">
                                        @error('cae')
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
