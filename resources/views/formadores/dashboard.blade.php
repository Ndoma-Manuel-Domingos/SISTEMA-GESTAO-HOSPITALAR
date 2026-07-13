@extends('layouts.formadores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Meu Perfil</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-formadores') }}">Home</a></li>
                        <li class="breadcrumb-item active">Inicio</li>
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
                <div class="col-12 col-md-6">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr>
                                <th>{{ __('messages.designacao') }}</th>
                                <td class="text-right">{{ $entidade->formador->nome ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('messages.genero') }}</th>
                                <td class="text-right">{{ $entidade->formador->genero ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('messages.estado_civil') }}</th>
                                <td class="text-right">{{ $entidade->formador->estado_civil ?? '-------------' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-12 col-md-6">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr>
                                <th> {{ __('messages.bilhete_identidade') }} </th>
                                <td class="text-right">{{ $entidade->formador->nif ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>País</th>
                                <td class="text-right">{{ $entidade->formador->pais ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('messages.telefone') }}/{{ __('messages.email') }}</th>
                                <td class="text-right">{{ $entidade->formador->telefone ?? '-------------' }} / {{ $entidade->formador->email ?? '-------------' }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Turmas">
                        <div class="inner">
                            <h3>{{ number_format($total_turmas, 1, ',', '.') }}</h3>
                            <p class="text-uppercase">Turmas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('formadores-turmas') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Vídeos">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Vídeos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('formadores-videos.videos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Conteúdos">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Conteúdos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('formadores-videos.conteudo') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Provas">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Provas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('formadores-provas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Conteudos Alunos">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Conteudos Alunos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('formadores-conteudo-alunos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
