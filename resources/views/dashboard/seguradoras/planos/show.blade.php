@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }} do plano</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('seguradoras.show', $plano->seguradora_id) }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Plano</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- Botões -->
            <div class="mb-3">
                <button class="btn btn-light-danger">
                    <i class="fas fa-ban"></i>
                    Desativar
                </button>
            </div>

            <!-- Cabeçalho -->
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <img src="{{ asset('dist/img/sem-imagem.jpg') }}" class="img-circle elevation-2" width="120">
                        </div>
                        <div class="col-md-10">
                            <h3>
                                {{ $plano->nome ?? "" }}
                                @if($plano->ativo)
                                <span class="badge badge-light-success">
                                    Activo
                                </span>
                                @else
                                <span class="badge badge-light-danger">
                                    Inactivo
                                </span>
                                @endif
                            </h3>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <strong>Seguradora</strong>
                                    <p>
                                        {{ $plano->seguradora->nome ?? "" }}
                                    </p>
                                </div>

                                <div class="col-md-3">
                                    <strong>Tipo</strong>
                                    <p>
                                        {{ $plano->tipo ?? "N/A" }}
                                    </p>
                                </div>

                                <div class="col-md-3">
                                    <strong>Cobertura (Total ou Parcial em percentagem)</strong>
                                    <p>
                                        {{ $plano->percentual_cobertura ?? 0 }}%
                                    </p>
                                </div>

                                <div class="col-md-3">
                                    <strong>Copagamento (Sim/Não em percentagem.)</strong>
                                    <p>
                                        {{ $plano->percentual_coparticipacao ?? 0 }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicadores -->

            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-light-primary">
                        <div class="inner">
                            <h3>{{ count($plano->beneficiarios) ?? 0 }}</h3>
                            <p>Beneficiários</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-light-success">
                        <div class="inner">
                            <h3>{{ $consultas ?? 0 }}</h3>
                            <p>Consultas</p>
                        </div>

                        <div class="icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-light-warning">
                        <div class="inner">
                            <h3>{{ $facturas ?? 0 }}</h3>
                            <p>Facturas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-light-danger">
                        <div class="inner">
                            <h3>{{ number_format($saldoAberto ?? 0,2,',','.') }}</h3>
                            <p>Saldo em Aberto</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações Gerais -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle"></i>
                                Informações Gerais
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Código</th>
                                    <td>{{ $plano->codigo ?? "N/A" }}</td>
                                </tr>
                                <tr>
                                    <th>Nome</th>
                                    <td>{{ $plano->nome ?? "N/A" }}</td>
                                </tr>
                                <tr>
                                    <th>Tipo</th>
                                    <td>{{ $plano->tipo ?? "N/A" }}</td>
                                </tr>
                                <tr>
                                    <th>Estado</th>
                                    <td>{{ $plano->ativo ?? "N/A" }}</td>
                                </tr>
                                <tr>
                                    <th>Data de Início</th>
                                    <td>{{ $plano->data_inicio ?? "N/A" }}</td>
                                </tr>
                                <tr>
                                    <th>Data de Término</th>
                                    <td>{{ $plano->data_fim ?? "N/A" }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">
                                <i class="fas fa-money-check"></i>
                                Dados Financeiros
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="45%">Limite Financeiro Anual</th>
                                    <td>
                                        {{ number_format($plano->limite_anual ?? 0,2,',','.') }} Kz
                                    </td>
                                </tr>
                                <tr>
                                    <th width="45%">Limite Financeiro por Atendimento</th>
                                    <td>
                                        {{ number_format($plano->limite_por_atendimento ?? 0,2,',','.') }} Kz
                                    </td>
                                </tr>
                                <tr>
                                    <th>Percentual Cobertura</th>
                                    <td>
                                        {{ $plano->percentual_cobertura ?? 0 }} %
                                    </td>
                                </tr>
                                <tr>
                                    <th>Percentual coparticipação</th>
                                    <td>
                                        {{ $plano->percentual_coparticipacao ?? 0 }} %
                                    </td>
                                </tr>
                                <tr>
                                    <th>Franquia</th>
                                    <td>
                                        {{ number_format($plano->franquia ?? 0,2,',','.') }} Kz
                                    </td>
                                </tr>
                                <tr>
                                    <th>Valor Utilizado</th>
                                    <td>
                                        {{ number_format($valorUtilizado ?? 0,2,',','.') }} Kz
                                    </td>
                                </tr>
                                <tr>
                                    <th>Saldo Disponível</th>
                                    <td>
                                        {{ number_format($saldoDisponivel ?? 0,2,',','.') }} Kz
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-shield-alt"></i>
                                Coberturas do Plano
                            </h3>
                            <div class="card-tools">
                                <button class="btn btn-light-primary" data-toggle="modal" data-target="#modalCobertura">
                                    <i class="fas fa-plus"></i>
                                    Nova Cobertura
                                </button>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Serviço</th>
                                        <th>Limite</th>
                                        <th>Copagamento</th>
                                        <th>Cobertura</th>
                                        <th>Status</th>
                                        <th width="120">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($plano->coberturas as $cobertura)
                                    <tr>
                                        <td><a href="{{ route('produtos.show', $cobertura->servico_id) }}">{{ $cobertura->servico->nome ?? "N/A" }}</a></td>
                                        <td>{{ number_format($cobertura->limite,2,',','.') }} Kz</td>
                                        <td>{{ $cobertura->copagamento }}%</td>
                                        <td>{{ $cobertura->percentual }}%</td>
                                        <td>

                                            @if($cobertura->status)
                                            <span class="badge badge-success">
                                                Activa
                                            </span>
                                            @else
                                            <span class="badge badge-danger">
                                                Inactiva
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-light-warning editar" data-tipo="cobertura" data-id="{{ $cobertura->id }}" data-route="{{ route('plano-seguradora-coberturas.edit', ':id') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-light-danger deletar" data-id="{{ $cobertura->id }}" data-route="{{ route('plano-seguradora-coberturas.destroy', ':id') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            Nenhuma cobertura cadastrada.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users"></i>
                                Beneficiários
                            </h3>
                            <div class="card-tools">
                                <button class="btn btn-light-success btn-sm" data-toggle="modal" data-target="#modalBeneficiario">
                                    <i class="fas fa-user-plus"></i>
                                    Associar Beneficiário
                                </button>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nº Cartão</th>
                                        <th>Paciente</th>
                                        <th>Telefone</th>
                                        <th>Início</th>
                                        <th>Final</th>
                                        <th>Adesão</th>
                                        <th>Limite Indivídual</th>
                                        <th>Status</th>
                                        <th class="text-right">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plano->beneficiarios as $beneficiario)
                                    <tr>
                                        <td>{{ $beneficiario->numero_cartao ?? "N/A" }}</td>
                                        <td><a href="{{ route('clientes.show', $beneficiario->beneficiario_id) }}">{{ $beneficiario->beneficiario->nome ?? "N/A" }}</a></td>
                                        <td>{{ $beneficiario->beneficiario->telefone ?? "N/A" }}</td>
                                        <td>{{ $beneficiario->data_inicio }}</td>
                                        <td>{{ $beneficiario->data_fim }}</td>
                                        <td>{{ $beneficiario->created_at->format('d/m/Y') }}</td>
                                        <td>{{ number_format($beneficiario->limite,2,',','.') }} Kz</td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ $beneficiario->status }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <button class="btn btn-light-warning editar" data-tipo="beneficiario" data-id="{{ $beneficiario->id }}" data-route="{{ route('plano-seguradora-beneficiadores.edit', ':id') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-light-danger deletar" data-id="{{ $beneficiario->id }}" data-route="{{ route('plano-seguradora-beneficiadores.destroy', ':id') }}">
                                                <i class="fas fa-unlink"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalCobertura">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('plano-seguradora-coberturas.store') }}" id="formCobertura" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h4 class="modal-title">
                                    Nova Cobertura
                                </h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-3">
                                        <label for="servico_id">Serviço</label>
                                        <select class="form-control servico_id_cobertura" name="servico_id" id="servico_id">
                                            <option value="">Escolher</option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <input type="hidden" name="plano_id" value="{{ $plano->id }}">

                                    <div class="col-md-6 col-12 mb-3">
                                        <label for="percentual">Percentual Coberto</label>
                                        <input type="number" class="form-control percentual_cobertura" value="0" name="percentual" id="percentual">
                                    </div>

                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="limite">Limite Financeiro</label>
                                        <input type="number" class="form-control limite_cobertura" value="0" name="limite" id="limite">
                                    </div>

                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="copagamento">Copagamento (%)</label>
                                        <input type="number" class="form-control copagamento_cobertura" value="0" id="copagamento" name="copagamento">
                                    </div>

                                    <div class="col-md-12 col-12 mt-3">
                                        <label for="observacoes">Observações</label>
                                        <textarea class="form-control observacoes_cobertura" rows="3" id="observacoes" name="observacoes"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-light-secondary" data-dismiss="modal">
                                    Cancelar
                                </button>
                                <button class="btn btn-light-primary" type="submit" form="formCobertura">
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="modalBeneficiario" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('plano-seguradora-beneficiadores.store') }}" id="formBeneficiario" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h4 class="modal-title">
                                    <i class="fas fa-user-plus"></i>
                                    Associar Beneficiário
                                </h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <!-- Beneficiário -->
                                    <div class="col-md-12 col-12 mb-3">
                                        <label for="beneficiario_id">Beneficiário</label>
                                        <select class="form-control beneficiario_id_beneficiario select2" name="beneficiario_id" id="beneficiario_id" required>
                                            <option value="">Selecione o beneficiário</option>
                                            @foreach($beneficiarios as $beneficiario)
                                            <option value="{{ $beneficiario->id }}">{{ $beneficiario->conta }} - {{ $beneficiario->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <input type="hidden" name="plano_id" value="{{ $plano->id }}">

                                    <!-- Número do cartão -->
                                    <div class="col-md-6 col-12 mb-3">
                                        <label for="numero_cartao">Nº do Cartão</label>
                                        <input type="text" name="numero_cartao" id="numero_cartao" class="form-control numero_cartao_beneficiario">
                                    </div>

                                    <!-- Matrícula -->
                                    <div class="col-md-6 col-12 mb-3">
                                        <label for="matricula">Matrícula</label>
                                        <input type="text" name="matricula" id="matricula" class="form-control matricula_beneficiario">
                                    </div>

                                    <!-- Data início -->
                                    <div class="col-md-6 mt-3">
                                        <label for="data_inicio">Data de Início</label>
                                        <input type="date" name="data_inicio" id="data_inicio" class="form-control data_inicio_beneficiario" value="{{ date('Y-m-d') }}">
                                    </div>

                                    <!-- Data fim -->
                                    <div class="col-md-6 mt-3">
                                        <label for="data_fim">Data de Término</label>
                                        <input type="date" name="data_fim" id="data_fim" class="form-control data_fim_beneficiario">
                                    </div>

                                    <!-- Limite individual -->
                                    <div class="col-md-6 mt-3">
                                        <label for="limite">Limite Individual</label>
                                        <input type="number" class="form-control limite_beneficiario" value="0" id="limite" name="limite">
                                    </div>

                                    <!-- Estado -->
                                    <div class="col-md-6 mt-3">
                                        <label for="status">Estado</label>
                                        <select class="form-control status_beneficiario" name="status" id="status">
                                            <option value="ACTIVO">Activo</option>
                                            <option value="SUSPENSO">Suspenso</option>
                                            <option value="INACTIVO">Inactivo</option>
                                        </select>
                                    </div>

                                    <!-- Observações -->
                                    <div class="col-md-12 mt-3">
                                        <label for="observacoes">Observações</label>
                                        <textarea class="form-control observacoes_beneficiario" rows="3" id="observacoes" name="observacoes"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-light-secondary" data-dismiss="modal">
                                    Cancelar
                                </button>
                                <button class="btn btn-light-success" type="submit" form="formBeneficiario">
                                    <i class="fas fa-save"></i>
                                    Associar Beneficiário
                                </button>
                            </div>
                        </div>
                    </form>
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
    $(function() {
        $('.select2').select2({
            theme: 'bootstrap4'
            , width: '100%'
        });
        $('#modalBeneficiario').on('shown.bs.modal', function() {
            $('.select2').select2({
                dropdownParent: $('#modalBeneficiario')
            });
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

    let PastaID = null;

    $(document).ready(function() {

        $('#formCobertura, #formBeneficiario').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize();

            let url = form.attr('action');
            let method = 'POST';

            if (PastaID != null) {
                url += '/' + PastaID;
                method = 'PUT';
            }

            $.ajax({
                url: url
                , method: method
                , data: formData
                , headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    Swal.close();
                    if (xhr.status === 422) {
                        let messages = '';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            messages += `${value}\n* `;
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage(
                            'Erro!'
                            , xhr.responseJSON ? xhr.responseJSON.message : 'Ocorreu um erro inesperado.'
                            , 'error'
                        );
                    }
                }
            });
        });

    });

    $(document).on('click', '.deletar', function(e) {
        e.preventDefault();

        let recordId = $(this).data('id');
        let rota = $(this).data('route').replace(':id', recordId);

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
                    url: rota
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


    const modalCobertura = document.getElementById('modalCobertura');
    const modalInstanceCobertura = new bootstrap.Modal(modalCobertura);

    const modalBeneficiario = document.getElementById('modalBeneficiario');
    const modalInstanceBeneficiario = new bootstrap.Modal(modalBeneficiario);


    $(document).on('click', '.editar', function(e) {
        e.preventDefault();

        let recordId = $(this).data('id');
        let rota = $(this).data('route').replace(':id', recordId);
        let tipo = $(this).data('tipo');




        $.ajax({
            url: rota
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
                if (tipo == "cobertura") {
                    modalInstanceCobertura.show();
                }
                if (tipo == "beneficiario") {
                    modalInstanceBeneficiario.show();
                }
                PastaID = response.data.id;

                $(".servico_id_cobertura").val(response.data ? response.data.servico_id : '');
                $(".percentual_cobertura").val(response.data ? response.data.percentual : '');
                $(".limite_cobertura").val(response.data ? response.data.limite : '');
                $(".copagamento_cobertura").val(response.data ? response.data.copagamento : '');
                $(".observacoes_cobertura").val(response.data ? response.data.observacoes : '');

                $(".numero_cartao_beneficiario").val(response.data ? response.data.numero_cartao : '');
                $(".beneficiario_id_beneficiario").val(response.data ? response.data.beneficiario_id : '');
                $(".matricula_beneficiario").val(response.data ? response.data.matricula : '');
                $(".data_inicio_beneficiario").val(response.data ? response.data.data_inicio : '');
                $(".data_fim_beneficiario").val(response.data ? response.data.data_fim : '');
                $(".limite_beneficiario").val(response.data ? response.data.limite : '');
                $(".status_beneficiario").val(response.data ? response.data.status : '');
                $(".observacoes_beneficiario").val(response.data ? response.data.observacoes : '');

            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });

    });

    $(document).on('click', '.edit-folder', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // Envia a solicitação AJAX para excluir o registro
        $.ajax({
            url: `{{ route('planos-seguradora.edit', ':id') }}`.replace(':id', recordId)
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
                modalInstance.show();
                PastaID = response.data.id;
                $("#nome").val(response.data.nome);
                $("#codigo").val(response.data.codigo);
                $("#seguradora_id").val(response.data.seguradora_id);
                $("#percentual_cobertura").val(response.data.percentual_cobertura);
                $("#percentual_coparticipacao").val(response.data.percentual_coparticipacao);
                $("#limite_anual").val(response.data.limite_anual);
                $("#limite_por_atendimento").val(response.data.limite_por_atendimento);
                $("#necessita_autorizacao").val(response.data.necessita_autorizacao);
                $("#tipo").val(response.data.tipo);
                $("#descricao").val(response.data.descricao);
            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
    });

</script>
@endsection
