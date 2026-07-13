@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }} - {{ $ocorrencia->numero }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('ocorrencias.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">
                            Ocorrência
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Dados da Ocorrência</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <table class="table text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <th>Data</th>
                                                    <td class="text-right">{{ $ocorrencia->data_at ?? "" }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Hora</th>
                                                    <td class="text-right">{{ $ocorrencia->hora_at ?? "" }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Descrição</th>
                                                    <td class="text-right">
                                                        <p style="white-space: pre-line;">
                                                            {{ $ocorrencia->descricao }}
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Posto</th>
                                                    <td class="text-right">{{ $ocorrencia->posto->nome ?? '-------------' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tipo de Ocorrência</th>
                                                    <td class="text-right"> {{ $ocorrencia->tipo_ocorrencia->nome ?? '-------------' }}</td>
                                                </tr>
                                                </tr>
                                                <tr>
                                                    <th>Registrado Por</th>
                                                    <td class="text-right"> {{ $ocorrencia->registrado_por->nome ?? '-------------' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

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
                    url: `{{ route('clientes-contratos.destroy', ':id') }}`.replace(':id', recordId)
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
