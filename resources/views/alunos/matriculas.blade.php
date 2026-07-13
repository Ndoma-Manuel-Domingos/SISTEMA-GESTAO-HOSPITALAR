@extends('layouts.alunos')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Matrículas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-alunos') }}">{{ __('messages.voltar') }}</a></li>
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
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        @if ($matriculas)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>{{ __('messages.genero') }}</th>
                                        <th>{{ __('messages.estado_civil') }}</th>
                                        <th>{{ __('messages.bilhete_identidade') }}</th>
                                        <th>{{ __('messages.curso') }}</th>
                                        <th>{{ __('messages.turno') }}</th>
                                        <th>{{ __('messages.sala') }}</th>
                                        <th>{{ __('messages.ano_lectivo') }}</th>
                                        <th> {{ __('messages.telemovel') }} </th>
                                        <th>{{ __('messages.estados') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($matriculas as $item)
                                    <tr>
                                        <td>{{ $item->aluno->id }}</td>
                                        <td>{{ $item->aluno->nome }}</td>
                                        <td>{{ $item->aluno->genero ?? '------' }}</td>
                                        <td>{{ $item->aluno->estado_civil ?? '------' }}</td>
                                        <td>{{ $item->aluno->nif ?? '------' }}</td>
                                        <td>{{ $item->curso->nome ?? '------' }}</td>
                                        <td>{{ $item->turno->nome ?? '------' }}</td>
                                        <td>{{ $item->sala->nome ?? '------' }}</td>
                                        <td>{{ $item->ano_lectivo->nome ?? '------' }}</td>
                                        <td>{{ $item->aluno->telefone ?? '--- --- ---' }} / {{ $item->aluno->telemovel ?? '--- --- --- ---' }}</td>
                                        <td>{{ $item->status }}</td>
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
