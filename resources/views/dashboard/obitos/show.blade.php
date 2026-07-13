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
                        <li class="breadcrumb-item"><a href="{{ route('obitos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Obitos</li>
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
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body table-responsive">
                            <table class="table text-nowrap">
                                <tbody>
                                    <tr>
                                        <th>Obito </th>
                                        <td class="text-right">{{ $obito->documento_declaracao ?? "" }}</td>
                                    </tr>
                                    <tr>
                                        <th> {{ __('messages.data') }} </th>
                                        <td class="text-right">{{ $obito->data_obito ?? "" }}</td>
                                    </tr>
                                    <tr>
                                        <th>Hora</th>
                                        <td class="text-right">{{ $obito->hora_obito ?? "" }}</td>
                                    </tr>
                                    <tr>
                                        <th>Local do Obito</th>
                                        <td class="text-right">{{ $obito->local_obito ?? "" }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                <div class="col-12 col-md-6 table-responsive">
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <table class="table text-nowrap">
                                <tbody>
                                    <tr>
                                        <th>Tipo de Obito</th>
                                        <td class="text-right">{{ $obito->tipo_obito ?? "" }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.estados') }}</th>
                                        <td class="text-right">{{ $obito->status ?? "" }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pagamento</th>
                                        <td class="text-right">{{ $obito->pago ?? "" }}</td>
                                    </tr>
                                    <tr>
                                        <th>Causa do Obito</th>
                                        <td class="text-right">
                                            {{ $obito->causa_obito ?? "" }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Dados do Obito</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('messages.designacao') }}</th>
                                                <td class="text-right">{{ $obito->paciente->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th> {{ __('messages.genero') }} </th>
                                                <td class="text-right">{{ $obito->paciente->genero ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.data_nascimento') }}</th>
                                                <td class="text-right">
                                                    {{ $obito->paciente->data_nascimento ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>País</th>
                                                <td class="text-right">{{ $obito->paciente->pais ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.estado_civil') }}</th>
                                                <td class="text-right">
                                                    {{ $obito->paciente->estado_civil->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th> {{ __('messages.bilhete_identidade') }} </th>
                                                <td class="text-right">{{ $obito->paciente->nif ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>Nome do Pai</th>
                                                <td class="text-right">{{ $obito->paciente->nome_do_pai ?? '-------------' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Nome da Mãe</th>
                                                <td class="text-right">{{ $obito->paciente->nome_da_mae ?? '-------------' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Seguradora</th>
                                                <td class="text-right">
                                                    {{ $obito->paciente->seguradora->nome ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Morada</th>
                                                <th>Províncias</th>
                                                <th>Município</th>
                                                <th>Distrito</th>
                                            </tr>
                                            <tr>
                                                <td>{{ $obito->paciente->morada ?? '-------------' }}
                                                    <br>{{ $obito->paciente->codigo_postal ?? '-------------' }}
                                                </td>
                                                <td>{{ $obito->paciente->provincia->nome ?? '-------------' }}</td>
                                                <td>{{ $obito->paciente->municipio->nome ?? '-------------' }}</td>
                                                <td>{{ $obito->paciente->distrito->nome ?? '-------------' }}</td>
                                            </tr>
                                            {{-- -------------------------------------------- --}}
                                            <tr>
                                                <th colspan="4">Contactos</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2"> {{ __('messages.telemovel') }} </td>
                                                <td colspan="2"> {{ __('messages.telemovel') }} </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ $obito->paciente->telefone ?? '-------------' }}</td>
                                                <td colspan="2">{{ $obito->paciente->telemovel ?? '-------------' }}</td>
                                            </tr>
                                            {{-- -------------------------------------------- --}}
                                            <tr>
                                                <th colspan="4">Contactos</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2"> {{ __('messages.data_nascimento') }}</td>
                                                <td colspan="2">Website</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ $obito->paciente->email ?? '-------------' }}</td>
                                                <td colspan="2">{{ $obito->paciente->website ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th colspan="4">{{ __('messages.observacao') }}</th>
                                            </tr>

                                            <tr>
                                                <td colspan="4">{{ $obito->paciente->observacao ?? '-------------' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                    url: `{{ route('internamentos.destroy', ':id') }}`.replace(':id'
                        , recordId)
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
                        window.location.href = "/internamentos";
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!'
                            , 'Ocorreu um erro ao excluir o registro. Tente novamente.'
                            , 'error');
                    }
                , });
            }
        });
    });

</script>
@endsection
