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
                        <li class="breadcrumb-item"><a href="{{ route('contrapartidas.index') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('contrapartidas.update', $contrapartida->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">
                                <div class="col-12 col-md-6">
                                    <label for="tipo_credito_id" class="form-label">Tipos de Créditos</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 @error('tipo_credito_id') is-invalid @enderror" id="tipo_credito_id" name="tipo_credito_id">
                                            <option value="">{{ __('messages.opcoes') }}</option>
                                            @foreach ($tipos_creditos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $contrapartida->tipo_credito_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="subconta_id" class="form-label">Subconta</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2 @error('subconta_id') is-invalid @enderror" id="subconta_id" name="subconta_id">
                                            <option value="">{{ __('messages.opcoes') }}</option>
                                            @foreach ($subcontas as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $contrapartida->subconta_id == $item->id ? 'selected' : '' }}>{{ $item->numero }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar conta'))
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
