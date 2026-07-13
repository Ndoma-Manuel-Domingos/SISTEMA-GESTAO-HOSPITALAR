@extends('layouts.alunos')

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
                        <li class="breadcrumb-item"><a href="{{ route('alunos-documentos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Documentos</li>
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
                        <form action="{{ route('alunos-documentos.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-12">
                                    <label for="tipo_documento_id" class="form-label">Tipo Documento</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control @error('tipo_documento_id') is-invalid @enderror" id="tipo_documento_id" name="tipo_documento_id">
                                            <option value="cerfificado">CERTIFICADO</option>
                                            <option value="declaracao">DECLARAÇÃO</option>
                                            <option value="transferencia">TRANSFERÊNCIA</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="descricao" class="form-label"> {{ __('messages.descricao') }} </label>
                                    <div class="input-group mb-3">
                                        <textarea name="descricao" class="form-control" id="descricao" cols="30" rows="5" placeholder="Informe uma descrição ou observação">{{ old('descricao') }}</textarea>
                                    </div>
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
