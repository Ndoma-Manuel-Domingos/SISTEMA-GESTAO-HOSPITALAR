@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }} - {{ $contrato->codigo_contrato }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('clientes-contratos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">
                            Contrato
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
                                <h6>Dados Cliente</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <table class="table text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __('messages.designacao') }}</th>
                                                    <td class="text-right">{{ $contrato->cliente->nome ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th> {{ __('messages.genero') }} </th>
                                                    <td class="text-right">{{ $contrato->cliente->genero ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('messages.data_nascimento') }}</th>
                                                    <td class="text-right">
                                                        {{ $contrato->cliente->data_nascimento ?? '-------------' }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <table class="table text-nowrap">
                                            <tbody>

                                                <tr>
                                                    <th>País</th>
                                                    <td class="text-right">{{ $contrato->cliente->pais ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('messages.estado_civil') }}</th>
                                                    <td class="text-right">
                                                        {{ $contrato->cliente->estado_civil->nome ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th> {{ __('messages.bilhete_identidade') }} </th>
                                                    <td class="text-right">{{ $contrato->cliente->nif ?? '-------------' }}</td>
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
                                                    <td>{{ $contrato->cliente->morada ?? '-------------' }}
                                                        <br>{{ $contrato->cliente->codigo_postal ?? '-------------' }}
                                                    </td>
                                                    <td>{{ $contrato->cliente->provincia->nome ?? '-------------' }}</td>
                                                    <td>{{ $contrato->cliente->municipio->nome ?? '-------------' }}</td>
                                                    <td>{{ $contrato->cliente->distrito->nome ?? '-------------' }}</td>
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
                                                    <td colspan="2">{{ $contrato->cliente->telefone ?? '-------------' }}</td>
                                                    <td colspan="2">{{ $contrato->cliente->telemovel ?? '-------------' }}</td>
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
                                                    <td colspan="2">{{ $contrato->cliente->email ?? '-------------' }}</td>
                                                    <td colspan="2">{{ $contrato->cliente->website ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th colspan="4">{{ __('messages.observacao') }}</th>
                                                </tr>

                                                <tr>
                                                    <td colspan="4">{{ $contrato->cliente->observacao ?? '-------------' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Dados do Contrato</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <table class="table text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <th>Código Contrato</th>
                                                    <td class="text-right">{{ $contrato->codigo_contrato ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Estado</th>
                                                    <td class="text-right">{{ $contrato->status ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Data Final</th>
                                                    <td class="text-right">{{ $contrato->data_inicio ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Data Inicio</th>
                                                    <td class="text-right">{{ $contrato->data_final ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Dias Restantes</th>
                                                    <td class="text-right">{{ $contrato->diasRestantes($contrato->data_inicio, $contrato->data_final) ?? '-------------' }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Valor Mensal</th>
                                                    <td class="text-right">{{ number_format($contrato->valor_mensal, 2, ',', '.') }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Forma de Pagamento</th>
                                                    <td class="text-right">{{ $contrato->forma_pagamento->titulo ?? '-------------' }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar cliente'))
                                <a class="btn btn-light-success" href="{{ route('clientes-contratos.edit', $contrato->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                <a class="btn btn-light-success" href="{{ route('contratos-postos.create', ['contrato_id' => $contrato->id]) }}"><i class="fas fa-plus text-light-success"></i> Adicionar Postos</a>
                                @endif
                                @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar cliente'))
                                <button class="btn btn-light-danger delete-record" data-id="{{ $contrato->id }}">
                                    <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                </button>
                                @endif
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Postos</h3>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Designação</th>
                                            <th>Tipo Posto</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>Endereço</th>
                                            <th>Horario Permitido</th>
                                            <th>Instruções Especiais</th>
                                            <th class="text-right">{{ __('messages.accoes') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contrato->postos as $item)
                                        <tr>
                                            <td><a href="{{ route('contratos-postos.show', $item->id) }}">{{ $item->nome ?? "" }}</a></td>
                                            <td>{{ $item->tipo_posto->nome ?? "" }}</td>
                                            <td>{{ $item->latitude ?? "" }}</td>
                                            <td>{{ $item->longitude ?? "" }}</td>
                                            <td>{{ $item->endereco ?? "" }}</td>
                                            <td>{{ $item->horario_permitido ?? "" }}</td>
                                            <td>{{ $item->instrucoes_especiais ?? "" }}</td>
                                            <td class="text-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                    <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        <button class="btn btn-light-danger dropdown-item btn-atribuir" data-id="{{ $item->id ?? "" }}">
                                                            <i class="fas fa-eye text-light-primary"></i> Atribuir Equipa
                                                        </button>
                                                        @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                                        <a class="dropdown-item" href="{{ route('contratos-postos.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                        @endif
                                                        @if (Auth::user()->can('editar todos') || Auth::user()->can('editar cliente'))
                                                        <a class="dropdown-item" href="{{ route('contratos-postos.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                        @endif
                                                        <div class="dropdown-divider"></div>
                                                        @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar cliente'))
                                                        <button class="btn btn-light-danger dropdown-item delete-record-tipo-posto" data-id="{{ $item->id ?? "" }}">
                                                            <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                        </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Modal -->
    <div class="modal fade" id="modalAtribuir" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formAtribuir">
                    <div class="modal-header">
                        <h5 class="modal-title">Atribuir Equipa ao Posto</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Equipa</label>
                            <select name="equipa_id" class="form-control equipa_id" id="equipa_id" required>
                                @foreach ($equipas as $equipa)
                                <option value="{{ $equipa->id }}">{{ $equipa->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Posto</label>
                            <select name="posto_id" class="form-control posto_id" id="posto_id" required>
                                @foreach ($postos as $posto)
                                <option value="{{ $posto->id }}">{{ $posto->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-light-success">{{ __('messages.salvar') }}</button>
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">{{ __('messages.cancelar') }}</button>
                    </div>
                </form>
            </div>
        </div>
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

    $(document).on('click', '.delete-record-tipo-posto', function(e) {

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
                    url: `{{ route('contratos-postos.destroy', ':id') }}`.replace(':id', recordId)
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


    $(document).ready(function() {
        let modal = $("#modalAtribuir");

        // Abrir modal e preencher empresa_id
        $(".btn-atribuir").on("click", function() {
            let contrato_id = $(this).data("id");
            $("#posto_id").val(contrato_id);
            modal.modal("show");
        });

        // Submeter via AJAX
        $("#formAtribuir").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('contratos-postos.atribuir-equipa') }}"
                , type: "POST"
                , data: $(this).serialize()
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(res) {
                    modal.modal("hide");
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Equipa atribuída com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                }
            });
        });
    });

</script>
@endsection
