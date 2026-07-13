@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Modulos do Curso de {{ $curso->nome }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Modulos</li>
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
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <button type="button" class="btn btn-light-primary" data-toggle="modal" data-target="#modulosModal" id="btn-add"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</button>
                                </h3>

                                {{-- <div class="card-tools">
                                    <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                </div> --}}
                            </div>

                            @if ($modulos)
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Modulo</th>
                                            <th>Curso</th>
                                            <th>{{ __('messages.estados') }}</th>
                                            <th> {{ __('messages.descricao') }} </th>
                                            <th class="text-right">{{ __('messages.accoes') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($modulos as $item)
                                        <tr>
                                            <td>{{ $item->id ?? "" }}</td>
                                            <td>{{ $item->nome ?? "" }}</td>
                                            <td>{{ $item->curso->nome }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>{{ $item->descricao }}</td>

                                            <td class="text-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                                    <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        <a class="dropdown-item btn-edit" data-id="{{ $item->id ?? "" }}" data-nome="{{ $item->nome }}" data-descricao="{{ $item->descricao }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                        <a class="dropdown-item btn-delete" data-id="{{ $item->id ?? "" }}"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                                                        <div class="dropdown-divider"></div>
                                                    </div>
                                                </div>
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
            </div>

            <!-- Button trigger modal -->

            <!-- Modal -->
            <div class="modal fade" id="modulosModal" tabindex="-1" role="dialog" aria-labelledby="modulosModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modulosModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="modulo-form">
                            <div class="modal-body">
                                <div class="modal-body">
                                    <input type="hidden" id="modulo-id">
                                    <input type="hidden" id="curso-id" value="{{ $curso->id }}">
                                    <div class="form-group">
                                        <label for="modulo-nome">Modulo</label>
                                        <input type="text" class="form-control" id="modulo-nome" placeholder="Informe o modulo" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="modulo-descricao"> {{ __('messages.descricao') }} </label>
                                        <textarea type="text" class="form-control" id="modulo-descricao" placeholder="Informe uma descrição"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('messages.fechar') }}</button>
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
    $(function() {
        $('#btn-add').click(function() {
            $('#modulosModal').modal('show');
            $('#modulosModalLabel').text('Adicionar Modulo');
            $('#modulo-form')[0].reset();
            $('#modulo-id').val('');
        });

        // Salvar Registro
        $('#modulo-form').submit(function(e) {
            e.preventDefault();

            let id = $('#modulo-id').val();
            let url = id ? `/modulos-cursos/${id}` : '/modulos-cursos';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url
                , method: method
                , data: {
                    _token: '{{ csrf_token() }}'
                    , nome: $('#modulo-nome').val()
                    , descricao: $('#modulo-descricao').val()
                    , curso_id: $('#curso-id').val()
                , }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });
        });

        // Editar Registro
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            let nome = $(this).data('nome');
            let descricao = $(this).data('descricao');

            $('#modulosModal').modal('show');
            $('#modulosModalLabel').text('Editar Modulo');
            $('#modulo-id').val(id);
            $('#modulo-nome').val(nome);
            $('#modulo-descricao').val(descricao);
        });

        // Excluir Registro
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            let url = `/modulos-cursos/${id}`;

            Swal.fire({
                title: 'Você tem certeza?'
                , text: 'Esta ação não pode ser desfeita!'
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#198754'
                , cancelButtonColor: '#dc3545'
                , confirmButtonText: 'Sim, excluir!'
                , cancelButtonText: 'Cancelar'
            , }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url
                        , method: 'DELETE'
                        , data: {
                            _token: '{{ csrf_token() }}'
                        , }
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function(response) {
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        }
                        , error: function(xhr) {
                            Swal.close();
                            showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                        }
                    , });
                }
            });
        });

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
