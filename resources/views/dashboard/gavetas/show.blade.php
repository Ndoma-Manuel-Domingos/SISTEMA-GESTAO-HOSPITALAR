@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
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
                        <li class="breadcrumb-item"><a href="{{ route('gavetas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Gaveta</li>
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
                        @if ($gaveta)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> {{ __('messages.designacao') }} </th>
                                        <th>{{ __('messages.estados') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $gaveta->id }}</td>
                                        <td>{{ $gaveta->nome }}</td>
                                        <td>{{ $gaveta->status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix d-flex">
                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar camara'))
                            <button class="btn btn-light-danger delete-record" data-id="{{ $gaveta->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>
                            @endif
                        </div>
                        @endif
                    </div>
                    <!-- /.card -->
                </div>

                @if ($gaveta->ocupacao === 1)
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>DADOS DA MORGUE <small><a href="{{ route("morgues.show", $gaveta->morgue->id) }}">Clicar aqui para mais informações</a></small></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 table-responsive"">
                                        <table class=" table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <th>Morgue Nº</th>
                                            <td class="text-right">{{ $gaveta->morgue->id ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Data da Liberação</th>
                                            <td class="text-right">{{ $gaveta->morgue->data_liberacao ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Hora da Liberação</th>
                                            <td class="text-right">{{ $gaveta->morgue->hora_liberacao ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Obito</th>
                                            <td class="text-right">{{ $gaveta->morgue->obito->documento_declaracao ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Causa do Obito</th>
                                            <td class="text-right">{{ $gaveta->morgue->obito->causa_obito ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Data e Hora do obito</th>
                                            <td class="text-right">{{ $gaveta->morgue->obito->data_obito ?? "---" }} {{ $gaveta->morgue->obito->hora_obito ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Local</th>
                                            <td class="text-right">{{ $gaveta->morgue->obito->local_obito ?? "---" }} {{ $gaveta->morgue->obito->local_obito ?? "---" }}</td>
                                        </tr>

                                    </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-md-6  table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('messages.estados') }}</th>
                                                <td class="text-right">{{ $gaveta->morgue->status ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Gaveta</th>
                                                <td class="text-right">{{ $gaveta->morgue->gaveta->nome ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Camara</th>
                                                <td class="text-right">{{ $gaveta->morgue->camara->nome ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Temperatura armazenamento</th>
                                                <td class="text-right">{{ $gaveta->morgue->temperatura_armazenamento ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.observacao') }}</th>
                                                <td class="text-right">{{ $gaveta->morgue->observacoes_iniciais ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Nome do Paciente</th>
                                                <td class="text-right">{{ $gaveta->morgue->obito->paciente->nome ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Documento Paciente</th>
                                                <td class="text-right">{{ $gaveta->morgue->obito->paciente->nif ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.idade') }}</th>
                                                <td class="text-right">{{ $gaveta->morgue->obito->paciente->data_nascimento ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Genero Paciente</th>
                                                <td class="text-right">{{ $gaveta->morgue->obito->paciente->genero ?? "---" }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>DADOS DA LIBERAÇÃO DO CORPO (CASO JÁ FOR LIBERDAO)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 table-responsive"">
                                        <table class=" table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <th>Morgue Saída Nº</th>
                                            <td class="text-right">{{ $gaveta->morgue->liberacao->morgue_registro_id ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Data da Liberação</th>
                                            <td class="text-right">{{ $gaveta->morgue->liberacao->data_liberacao ?? "---" }}</td>
                                        </tr>

                                        <tr>
                                            <th>Hora da Liberação</th>
                                            <td class="text-right">{{ $gaveta->morgue->liberacao->hora_liberacao ?? "---" }}</td>
                                        </tr>

                                    </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-md-6  table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Nome responsável retirada</th>
                                                <td class="text-right">{{ $gaveta->morgue->liberacao->nome_responsavel_retirada ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Documento responsável</th>
                                                <td class="text-right">{{ $gaveta->morgue->liberacao->documento_responsavel ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>Relacionamento</th>
                                                <td class="text-right">{{ $gaveta->morgue->liberacao->relacionamento ?? "---" }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.observacao') }}</th>
                                                <td class="text-right">{{ $gaveta->morgue->liberacao->observacoes ?? "---" }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                @endif

            </div>
            <!-- /.row -->
        </div><!-- /.quartoiner-fluid -->
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
                    url: `{{ route('gavetas.destroy', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = "/gavetas";
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

</script>
@endsection
