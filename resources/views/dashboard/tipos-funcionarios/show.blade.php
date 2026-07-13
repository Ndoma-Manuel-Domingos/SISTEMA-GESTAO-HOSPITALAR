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
                        <li class="breadcrumb-item"><a href="{{ route('tipos-funcionarios.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Tipo de Funcionário</li>
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
                        @if ($tipo_funcionario)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> {{ __('messages.designacao') }} </th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>Data de criação</th>
                                        <th>Data de actualização</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $tipo_funcionario->id }}</td>
                                        <td>{{ $tipo_funcionario->nome }}</td>
                                        <td>{{ $tipo_funcionario->status }}</td>
                                        <td>{{ $tipo_funcionario->created_at }}</td>
                                        <td>{{ $tipo_funcionario->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer clearfix d-flex">
                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar tipo funcionario'))
                            <a href="{{ route('tipos-funcionarios.edit', $tipo_funcionario->id) }}" class="btn btn-light-success mx-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar tipo funcionario'))
                            <button class="btn btn-light-danger mx-1 delete-record" data-id="{{ $tipo_funcionario->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>
                            @endif
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
    $(document).on('click', '.delete-record', function(e) {
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
                    url: `{{ route('tipos-funcionarios.destroy', ':id') }}`.replace(':id', recordId)
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

</script>
@endsection
