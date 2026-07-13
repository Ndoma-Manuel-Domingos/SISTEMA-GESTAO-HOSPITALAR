@extends('layouts.alunos')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Enviar Conteudo</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-alunos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Conteúdos</li>
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
                        <form action="{{ route('alunos-enviar-conteudo-post') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-12">
                                        <label for="arquivo" class="form-label">Upload</label>
                                        <div class="input-group mb-3">
                                            <input type="file" class="form-control @error('arquivo') is-invalid @enderror" id="arquivo" name="arquivo" value="{{ old('arquivo') }}" placeholder="Informe" required>
                                        </div>
                                    </div>

                                    <input type="hidden" name="conteudo_id" value="{{ $conteudo->id }}">

                                    <div class="col-12 col-md-12">
                                        <label for="nome" class="form-label"> {{ __('messages.descricao') }} </label>
                                        <div class="input-group mb-3">
                                            <textarea cols="30" class="form-control" id="nome" name="nome" rows="4"></textarea>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
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
