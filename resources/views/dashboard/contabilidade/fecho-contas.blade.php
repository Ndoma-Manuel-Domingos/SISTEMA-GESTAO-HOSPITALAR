@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Fecho de contas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ __('messages.inicio') }}</a></li>
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
                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h3><i class="fas fa-file"></i></h3>
                            <h3>CMV</h3>
                            <p>
                                Realize o apuramento detalhado dos custos das mercadorias vendidas, garantindo maior controlo financeiro e precisão nas análises de rentabilidade.
                            </p>
                        </div>
                        <div class="card-body text-center">
                            <a href="{{ route('contabilidade-fecho-contas-cmv') }}" class="btn btn-light-primary d-block my-4">Apurar</a>
                            <p>Clicar em Apurar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
