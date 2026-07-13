@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inventários</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Todos</li>
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
                            <h3>Inicial Geral</h3>
                            <p>
                                Gerencie estoques com precisão, monitorando entradas, saídas e níveis de produtos, garantindo reposição eficiente e redução de desperdícios.
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <a href="{{ route('inventarios.inicial-geral') }}" class="btn btn-light-primary d-block my-4">Visualizar</a>
                            <p>{{ __('messages.mais_detalhes') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h3><i class="fas fa-file"></i></h3>
                            <h3>Equipamentos/activos</h3>
                            <p>
                                Controle todos os equipamentos da sua empresa, registrando dados, localização e estado para melhor gestão e manutenção.
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <a href="{{ route('inventarios.equipamentos-activos') }}" class="btn btn-light-primary d-block my-4">Visualizar</a>

                            <p>{{ __('messages.mais_detalhes') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h3><i class="fas fa-file"></i></h3>
                            <h3>Existências</h3>
                            <p>
                                Gerencie estoques com precisão, monitorando entradas, saídas e níveis de produtos, garantindo reposição eficiente e redução de desperdícios.
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <a href="{{ route('inventarios.existencias') }}" class="btn btn-light-primary d-block my-4">Visualizar</a>
                            <p>{{ __('messages.mais_detalhes') }}</p>
                        </div>
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
