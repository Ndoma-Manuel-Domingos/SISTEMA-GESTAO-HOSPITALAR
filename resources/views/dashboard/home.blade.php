@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h1 class="m-0">Painel Administrativo</h1> --}}
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.controle') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.configuracoes') }}</li>
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
                <div class="col-md-9 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h3 mb-3">
                                @if (Auth::user()->can('configuracoes'))
                                {{ __('messages.holla', ['name' => Auth::user()->name, 'app_name' => env('APP_NAME')  ])}}
                                @else
                                {{ __('messages.holla_utilizador', ['name' => Auth::user()->name, 'app_name' => env('APP_NAME')  ])}}
                                @endif
                            </h2>
                            <h3 class="h5"></h3>
                            <h2 class="h5 mb-3">{{ __('messages.assista_video') }}</h2>
                        </div>
                        <div class="card-footer py-4">
                            <div>
                                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 20px; background: linear-gradient(135deg, #ff7e5f, #feb47b); ">

                                    @if ($empresa_logada->empresa->tipo_entidade->sigla === 'HOSP')
                                    <img src="{{ asset('images/empresa/hospitalar.jpg') }}" alt="Descrição da imagem" style="border:none; position:absolute; top:0; left:0; width:100%; height:100%; object-fit: cover;" />
                                    @endif

                                    @if ($empresa_logada->empresa->tipo_entidade->sigla === 'REST')
                                    <img src="{{ asset('images/empresa/restaurante.png') }}" alt="Descrição da imagem" style="border:none; position:absolute; top:0; left:0; width:100%; height:100%; object-fit: cover;" />
                                    @endif

                                    @if ($empresa_logada->empresa->tipo_entidade->sigla === 'RH')
                                    <img src="{{ asset('images/empresa/recursos-humanos.jpg') }}" alt="Descrição da imagem" style="border:none; position:absolute; top:0; left:0; width:100%; height:100%; object-fit: cover;" />
                                    @endif

                                    @if ($empresa_logada->empresa->tipo_entidade->sigla === 'HOTL')
                                    <img src="{{ asset('images/empresa/hotelaria.jpg') }}" alt="Descrição da imagem" style="border:none; position:absolute; top:0; left:0; width:100%; height:100%; object-fit: cover;" />
                                    @endif

                                    @if ($empresa_logada->empresa->tipo_entidade->sigla === 'CFAT')
                                    <img src="{{ asset('images/empresa/facturacao.jpg') }}" alt="Descrição da imagem" style="border:none; position:absolute; top:0; left:0; width:100%; height:100%; object-fit: cover;" />
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    @can("configuracoes")
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h1><i class="fa fa-check-circle text-light-success"></i></h1>
                                    <h4>{{ __('messages.identificacao_actividades') }}</h4>
                                    <p>{{ __('messages.passo1') }}</p>
                                </div>

                                <div class="card-body text-center">
                                    <a href="{{ route('identidade-empresa.index') }}" class="btn btn-light-primary d-block">{{ __('messages.actualizar') }}</a>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h1><i class="fa fa-check-circle text-light-success"></i></h1>
                                    <h4>{{ __('messages.informacoes_empresa') }}</h4>
                                    <p>{{ __('messages.passo2') }}</p>
                                </div>
                                <div class="card-body text-center">
                                    <a href="{{ route('dados-empresa.index') }}" class="btn btn-light-primary d-block">{{ __('messages.actualizar') }}</a>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h1><i class="fa fa-check-circle text-light-success"></i></h1>
                                    <h4>{{ __('messages.personalizar_impressao') }}</h4>
                                    <p>{{ __('messages.passo3') }}</p>
                                </div>
                                <div class="card-body text-center">
                                    <a href="{{ route('personalizar-empressora.index') }}" class="btn btn-light-primary d-block">{{ __('messages.actualizar') }}</a>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>
                    </div>
                    @endcan
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->

@endsection
