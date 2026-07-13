@extends('layouts.formadores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pautas da Turma: <strong>{{ $turma->nome }}</strong> </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('formadores-turmas') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Pautas</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        @if ($pautas)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>Turma</th>
                                        <th>Curso</th>
                                        <th>P1</th>
                                        <th>P2</th>
                                        <th>P3</th>
                                        <th>Exame</th>
                                        <th>Média</th>
                                        <th>Resultado</th>

                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pautas as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->aluno->nome }}</td>
                                        <td>{{ $item->turma->nome }}</td>
                                        <td>{{ $item->turma->curso->nome }}</td>
                                        <td>{{ $item->prova_1 }}</td>
                                        <td>{{ $item->prova_2 }}</td>
                                        <td>{{ $item->prova_3 }}</td>
                                        <td>{{ $item->exame }}</td>
                                        <td>{{ $item->media }}</td>
                                        <td>{{ $item->resultado }}</td>

                                        <td>
                                            <a href="{{ route('formadores-turma-lancamento-pautas', $item->id) }}" class="btn btn-light-success mx-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

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
