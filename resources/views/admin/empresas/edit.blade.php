@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Empresa: {{ $empresa->nome }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('empresas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Empresa</li>
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
                        <form action="{{ isset($empresa->controle) ? route('empresas.update', $empresa->controle->id) : route('empresas.store') }}" method="POST">
                            @csrf

                            @if(isset($empresa->controle))
                            @method('PUT')
                            @else
                            <input type="hidden" value="{{ $empresa->id }}" name="empresa_id" id="empresa_id">
                            @endif

                            <div class="card-header  bg-light-primary" text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-contract me-2"></i>
                                    Configurar Contrato da Licença
                                </h5>
                            </div>

                            <div class="card-body">

                                <div class="row">

                                    {{-- Data de Início --}}
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="inicio" class="form-label fw-semibold">
                                            {{ __('messages.data_inicio') }}
                                        </label>

                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>

                                            <input type="date" id="inicio" name="inicio" class="form-control @error('inicio') is-invalid @enderror" value="{{ old('inicio', $empresa->controle->inicio ?? '') }}" required>
                                        </div>

                                        @error('inicio')
                                        <div class="text-light-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    {{-- Data Final --}}
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="final" class="form-label fw-semibold">
                                            {{ __('messages.data_final') }}
                                        </label>

                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </span>

                                            <input type="date" id="final" name="final" class="form-control @error('final') is-invalid @enderror" value="{{ old('final', $empresa->controle->final ?? '') }}" required>
                                        </div>

                                        @error('final')
                                        <div class="text-light-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                </div>

                            </div>

                            <div class="card-footer d-flex justify-content-end bg-light">
                                <button type="submit" class="btn btn-light-primary">
                                    <i class="fas fa-save me-1"></i>
                                    {{ isset($empresa->controle) ? __('messages.atualizar') : __('messages.salvar') }}
                                </button>
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
