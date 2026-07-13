@extends('layouts.alunos')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Vídeos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-alunos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Vídeos</li>
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
                        </div>
                        @foreach ($uploads as $upload)
                        <video controls width="100%" height="200">
                            <source src="{{ asset('videos/' . $upload->arquivo) }}" type="video/mp4">
                        </video>
                        <div class="card-body">
                            <div>
                                <h4>{{ $upload->nome }}</h4>
                                <h5>TURMA: <strong>{{ $upload->turma ? $upload->turma->nome : "" }}</strong> | FORNECEDOR: <strong>{{ $upload->formador ? $upload->formador->nome : "Sem formador Selecionado" }}</strong></h5>
                            </div>
                            <p>{{ $upload->descricao }}</p>
                        </div>
                        <div class="card-footer mb-4">
                            <a href="{{ route('videos.conteudo-eliminar', $upload->id) }}" class="btn-sm btn-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                        </div>
                        @endforeach
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
