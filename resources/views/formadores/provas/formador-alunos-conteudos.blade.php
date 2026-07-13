@extends('layouts.formadores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inicio conteudo alunos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('formadores-provas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Formador</li>
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
                        <form action="{{ route('formadores-conteudo-alunos-post') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-3">
                                        <label for="titulo" class="form-label">Titulo</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Titulo">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
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


                                    <div class="col-12 col-md-3">
                                        <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') }}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control @error('data_final') is-invalid @enderror" id="data_final" name="data_final" value="{{ old('data_final') }}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <label for="descricao" class="form-label"> {{ __('messages.descricao') }} </label>
                                        <div class="input-group mb-3">
                                            <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" value="{{ old('descricao') }}" placeholder="Descrição" cols="30" rows="3"></textarea>
                                        </div>
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



            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        @if ($anuncios)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Titulo</th>
                                        <th> {{ __('messages.descricao') }} </th>
                                        <th>Turma</th>
                                        <th>Formador</th>
                                        <th>{{ __('messages.data_inicio') }}</th>
                                        <th>{{ __('messages.data_final') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($anuncios as $item)
                                    <tr>
                                        <td>{{ $item->nome ?? '---' }}</td>
                                        <td>{{ $item->descricao ?? '---' }}</td>
                                        <td>{{ $item->turma->nome ?? '---' }}</td>
                                        <td>{{ $item->formador->nome ?? '---' }}</td>
                                        <td>{{ $item->data_inicio ?? '---' }}</td>
                                        <td>{{ $item->data_final ?? '---' }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('formadores-delete-anuncio', $item->id ) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-light-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta prova?')">
                                                            <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        @endif
                    </div>
                    <!-- /.card -->
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
    $(function() {
        $("#carregar_tabela").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
