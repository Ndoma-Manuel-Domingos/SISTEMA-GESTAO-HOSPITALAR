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
                        <li class="breadcrumb-item"><a href="{{ route('triagens.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Triagem</li>
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
                    <div class="card card-primary card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="dados-exames-tab" data-toggle="pill" href="#dados-exames" role="tab" aria-controls="dados-exames" aria-selected="true">DADOS TRIANGEM</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="detalhes-exame-tab" data-toggle="pill" href="#detalhes-exame" role="tab" aria-controls="detalhes-exame" aria-selected="false">DADOS PACIENTE</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="dados-exames" role="tabpanel" aria-labelledby="dados-exames-tab">
                                    @include('dashboard.atendimentos._views.detalhe-triagem', ['triagem' => $triagem])
                                </div>

                                <div class="tab-pane fade" id="detalhes-exame" role="tabpanel" aria-labelledby="detalhes-exame-tab">
                                    <div class="row">

                                        <div class="col-12 col-md-6 col-lg-4 table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>{{ __('messages.designacao') }}</th>
                                                        <td class="text-right"><a href="{{ route("clientes.show", $triagem->paciente->id) }}">{{ $triagem->paciente->nome ?? '----' }}</a></td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('messages.genero') }}</th>
                                                        <td class="text-right">{{ $triagem->paciente->genero ?? '-----' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.data_nascimento') }}</th>
                                                        <td class="text-right">{{ $triagem->paciente->data_nascimento ?? '-----' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.idade') }}</th>
                                                        <td class="text-right">{{ $triagem->paciente->idade($triagem->paciente->data_nascimento) }} Anos</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>

                                                    <tr>
                                                        <th>País</th>
                                                        <td class="text-right"><a href="">{{ $triagem->paciente->pais ?? '----' }}</a></td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.estado_civil') }}</th>
                                                        <td class="text-right">
                                                            {{ $triagem->paciente->estado_civil->nome ?? '----' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th> {{ __('messages.bilhete_identidade') }} </th>
                                                        <td class="text-right">{{ $triagem->paciente->nif ?? '------' }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4 table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Nome do Pai</th>
                                                        <td class="text-right">{{ $triagem->paciente->nome_do_pai ?? '------' }} </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nome da Mãe</th>
                                                        <td class="text-right">{{ $triagem->paciente->nome_da_mae ?? '------' }} </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Seguradora</th>
                                                        <td class="text-right"> {{ $triagem->paciente->seguradora->nome ?? '------' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-12">
                                            <table class="table text-nowrap table-responsive">
                                                <tbody>
                                                    <tr>
                                                        <th>Morada</th>
                                                        <th>Províncias</th>
                                                        <th>Município</th>
                                                        <th>Distrito</th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $triagem->paciente->morada ?? '-----' }}
                                                            <br>{{ $triagem->paciente->codigo_postal ?? '-----' }}
                                                        </td>
                                                        <td>{{ $triagem->paciente->provincia->nome ?? '-----' }}</td>
                                                        <td>{{ $triagem->paciente->municipio->nome ?? '-----' }}</td>
                                                        <td>{{ $triagem->paciente->distrito->nome ?? '-----' }}</td>
                                                    </tr>
                                                    {{-- ---- --}}
                                                    <tr>
                                                        <th colspan="4">Contactos</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"> {{ __('messages.telemovel') }} </td>
                                                        <td colspan="2"> {{ __('messages.telemovel') }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">{{ $triagem->paciente->telefone ?? '-----' }}</td>
                                                        <td colspan="2">{{ $triagem->paciente->telemovel ?? '-----' }}</td>
                                                    </tr>
                                                    {{-- ---- --}}
                                                    <tr>
                                                        <th colspan="4">Contactos</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"> {{ __('messages.data_nascimento') }}</td>
                                                        <td colspan="2">Website</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">{{ $triagem->paciente->email ?? '-----' }}</td>
                                                        <td colspan="2">{{ $triagem->paciente->website ?? '-----' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th colspan="4">{{ __('messages.observacao') }}</th>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="4">{{ $triagem->paciente->observacao ?? '-----' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex">
                            @if (Auth::user()->can('listar todos') || Auth::user()->can('listar triagem'))
                            <a target="_blank" href="{{ route('triangs.triagens-imprimir', $triagem->id) }}" class="btn btn-light-primary mx-2"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}
                                Ficha da Triagem
                                Médica</a>
                            @endif
                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar triagem'))
                            <button class="btn btn-light-danger delete-record" data-id="{{ $triagem->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>
                            @endif
                        </div>

                        <!-- /.card -->
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
                    url: `{{ route('triagens.destroy', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = "/triangs/triagens";
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
