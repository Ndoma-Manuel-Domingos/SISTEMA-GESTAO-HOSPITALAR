@extends('layouts.formadores')

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
                        <li class="breadcrumb-item"><a href="{{ route('formadores-videos.conteudo') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Conteúdo</li>
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
                        <form action="{{ route('formadores-videos.store-conteudo') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <label for="nome" class="form-label"> {{ __('messages.designacao') }} </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Informe">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <label for="descricao" class="form-label"> {{ __('messages.descricao') }} </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="text" class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" value="{{ old('descricao') }}" placeholder="Informe">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <label for="data_at" class="form-label"> {{ __('messages.data') }} </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control @error('data_at') is-invalid @enderror" id="data_at" name="data_at" value="{{ old('data_at') }}" placeholder="Informe">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <label for="turma_id" class="form-label">Turmas</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control @error('record') is-invalid @enderror" id="turma_id" name="turma_id">
                                                @foreach ($turmas as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-6 col-lg-4">
                                        <label for="modulo_id" class="form-label">Modulos</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control @error('record') is-invalid @enderror" id="modulo_id" name="modulo_id">
                                                @foreach ($modulos as $item)
                                                <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-6 col-lg-4">
                                        <label for="arquivo" class="form-label">Upload</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="file" class="form-control @error('arquivo') is-invalid @enderror" id="arquivo" name="arquivo" value="{{ old('arquivo') }}" placeholder="Informe" required>
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

@section('scripts')
<script>

</script>
@endsection
