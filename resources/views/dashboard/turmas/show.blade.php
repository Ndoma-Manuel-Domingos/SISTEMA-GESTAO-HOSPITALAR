@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('turmas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Turma</li>
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
                        @if ($turma)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>Curso</th>
                                        <th>Turno</th>
                                        <th>Sala</th>
                                        <th>{{ __('messages.estados') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $turma->id }}</td>
                                        <td>{{ $turma->nome }}</td>
                                        <td>{{ $turma->curso->nome }}</td>
                                        <td>{{ $turma->turno->nome }}</td>
                                        <td>{{ $turma->sala->nome }}</td>
                                        <td>{{ $turma->status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer clearfix d-flex">
                            <a href="{{ route('turmas.edit', $turma->id) }}" class="btn btn-light-success mx-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('turmas.destroy', $turma->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light-danger mx-1" onclick="return confirm('Tens Certeza que Desejas excluir esta turma?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        @endif

                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                Lista de Alunos
                                @if (count($pautas) > 0)
                                <a href="{{ route('turma-visualizar-pautas', $turma->id) }}" class="btn btn-light-primary float-right mx-1">Visualizar Pauta</a>
                                @endif
                                <a href="#" data-id="{{ $turma->id }}" class="btn btn-light-primary float-right mx-1 distribuir-notas-alunos">Distribuir Pauta</a>
                            </h5>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela1">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>{{ __('messages.genero') }}</th>
                                        <th>{{ __('messages.estado_civil') }}</th>
                                        <th>{{ __('messages.bilhete_identidade') }}</th>
                                        <th>Codigo Postal</th>
                                        <th>{{ __('messages.telefone') }}/{{ __('messages.telemovel') }}</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alunos as $aluno)
                                    <tr>
                                        <td>{{ $aluno->aluno->id }}</td>
                                        <td><a href="{{ route('clientes.show', $aluno->aluno->id) }}">{{ $aluno->aluno->nome }} </a></td>
                                        <td>{{ $aluno->aluno->genero ?? '------' }}</td>
                                        <td>{{ $aluno->aluno->estado_civil ?? '------' }}</td>
                                        <td>{{ $aluno->aluno->nif ?? '------' }}</td>
                                        <td>{{ $aluno->aluno->codigo_postal ?? '------' }}</td>
                                        <td>{{ $aluno->aluno->telefone ?? '--- --- ---' }} / {{ $aluno->aluno->telemovel ?? '--- --- --- ---' }}</td>
                                        @if ($aluno->aluno->status == true)
                                        <td>{{ __('messages.activo') }} </td>
                                        @else
                                        <td>Inactivo</td>
                                        @endif

                                        <td class="d-flex">
                                            <a href="{{ route('clientes.show', $aluno->aluno->id) }}" class="btn btn-light-primary mx-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('clientes.edit', $aluno->aluno->id) }}" class="btn btn-light-success mx-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="submit" data-id="{{ $aluno->aluno->id }}" class="btn btn-light-danger mx-1 delete-record-alunos">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>


                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Lista de Formadores</h5>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela2">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>{{ __('messages.genero') }}</th>
                                        <th>{{ __('messages.estado_civil') }}</th>
                                        <th>{{ __('messages.bilhete_identidade') }}</th>
                                        <th>Codigo Postal</th>
                                        <th>{{ __('messages.telefone') }}/{{ __('messages.telemovel') }}</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($formadores as $item)
                                    <tr>
                                        <td>{{ $item->formador->id }}</td>
                                        <td><a href="{{ route('formadores.show', $item->formador->id) }}">{{ $item->formador->nome }} </a></td>
                                        <td>{{ $item->formador->genero ?? '------' }}</td>
                                        <td>{{ $item->formador->estado_civil ?? '------' }}</td>
                                        <td>{{ $item->formador->nif ?? '------' }}</td>
                                        <td>{{ $item->formador->codigo_postal ?? '------' }}</td>
                                        <td>{{ $item->formador->telefone ?? '--- --- ---' }} / {{ $item->formador->telemovel ?? '--- --- --- ---' }}</td>
                                        @if ($item->formador->status == true)
                                        <td>{{ __('messages.activo') }} </td>
                                        @else
                                        <td>Inactivo</td>
                                        @endif

                                        <td class="d-flex">
                                            <a href="{{ route('formadores.show', $item->formador->id) }}" class="btn btn-light-primary mx-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('formadores.edit', $item->formador->id) }}" class="btn btn-light-success mx-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('formadores.destroy', $item->formador->id ) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light-danger mx-1" onclick="return confirm('Tens Certeza que Desejas excluir esta formador?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
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
    $(document).on('click', '.distribuir-notas-alunos', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, Distribuir!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('turma-distribuir-pautas', ':id') }}`.replace(':id', recordId)
                    , method: 'GET'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });


    $(document).on('click', '.delete-record-alunos', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, excluir!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId)
                    , method: 'DELETE'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

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
    $(function() {
        $("#carregar_tabela1").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });
    $(function() {
        $("#carregar_tabela2").DataTable({
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
