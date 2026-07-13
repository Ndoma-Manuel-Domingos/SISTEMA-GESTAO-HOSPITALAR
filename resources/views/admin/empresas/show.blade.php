@extends('layouts.admin')

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
                        <li class="breadcrumb-item"><a href="{{ route('empresas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Empresa</li>
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
                <div class="col-12 col-md-12 mb-3">
                    <a href="{{ route('empresas.exportar-fluxo-caixa', $empresa->id) }}" class="btn btn-light-primary"><i class="fas fa-file-pdf"></i> Exportar</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-12 border-0">
                    <div class="small-box bg-light-success p-0" title="Receita">
                        <div class="inner">
                            <h5 class="fw-bold text-uppercase">Receitas</h5>
                            <h2 class="fw-bold">{{ number_format($receita, 2, ',', '.') }}</h2>
                            <p class="mb-0 text-white">Receitas</p>
                        </div>
                        <div class="icon">
                            <i class="ion fa-arrow-trend-up"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-12 border-0">
                    <div class="small-box bg-light-danger p-0" title="Despesa">
                        <div class="inner">
                            <h5 class="fw-bold text-uppercase">Dispesas</h5>
                            <h2 class="fw-bold">{{ number_format($despesa, 2, ',', '.') }}</h2>
                            <p class="mb-0 text-white">Dispesas</p>
                        </div>
                        <div class="icon">
                            <i class="ion fa-arrow-trend-down"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-12 border-0">
                    <div class="small-box {{ $lucro > 0 ? 'bg-light-success' : 'bg-light-danger' }} p-0" title="Lucro">
                        <div class="inner">
                            <h5 class="fw-bold text-uppercase">Lucro</h5>
                            <h2 class="fw-bold">{{ number_format($lucro, 2, ',', '.') }}</h2>
                            <p class="mb-0 text-white">Lucro</p>
                        </div>
                        <div class="icon">
                            <i class="ion fas fa-wallet"></i>
                        </div>
                    </div>
                </div>

                @php
                if ($margem < 5) { $bg='bg-light-danger' ; $texto='Muito perigoso' ; $icon='fa-triangle-exclamation' ; } elseif ($margem>= 5 && $margem < 15) { $bg='bg-light-warning' ; $texto='Baixa' ; $icon='fa-arrow-trend-down' ; } elseif ($margem>= 15 && $margem <= 30) { $bg='bg-light-primary' ; $texto='Boa' ; $icon='fa-chart-line' ; } else { $bg='bg-light-success' ; $texto='Excelente' ; $icon='fa-sack-dollar' ; } @endphp <div class="col-lg-3 col-md-3 col-12">
                            <div class="small-box {{ $bg }} shadow-sm border-0 rounded-3">
                                <div class="inner">
                                    <h5 class="fw-bold text-uppercase">Margem de Lucro</h5>
                                    <h2 class="fw-bold">{{ number_format($margem, 2, ',', '.') }}%</h2>
                                    <p class="mb-0 text-white">{{ $texto }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas {{ $icon }}"></i>
                                </div>
                            </div>
            </div>
        </div>

        <!-- /.row -->
        <div class="row">
            @if ($empresa->tipo_entidade->sigla == 'RESO')
            @endif
            @if ($empresa->tipo_entidade->sigla == 'REST')
            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box  bg-light-primary" title=" Hospedes">
                    <div class="inner">
                        <h3>{{ number_format($totalCliente, 0, ',', '.')  }}</h3>
                        <p class="text-uppercase">Total de Clientes</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            @endif

            @if ($empresa->tipo_entidade->sigla == 'CFOR')

            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box  bg-light-primary" title=" Hospedes">
                    <div class="inner">
                        <h3>{{ number_format($totalAlunos, 0, ',', '.')  }}</h3>
                        <p class="text-uppercase">Total de Alunos</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box  bg-light-primary" title=" Hospedes">
                    <div class="inner">
                        <h3>{{ number_format($totalFormadores, 0, ',', '.')  }}</h3>
                        <p class="text-uppercase">Total de Formadores</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            @endif

            @if ($empresa->tipo_entidade->sigla == 'HOTL')
            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box  bg-light-primary" title=" Hospedes">
                    <div class="inner">
                        <h3>{{ number_format($totalCliente, 0, ',', '.')  }}</h3>
                        <p class="text-uppercase">Total de Hospedes</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            @endif

            @if ($empresa->tipo_entidade->sigla == 'CONS')
            @endif

            @if ($empresa->tipo_entidade->sigla == 'HOSP')
            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box  bg-light-primary" title=" Hospedes">
                    <div class="inner">
                        <h3>{{ number_format($totalCliente, 0, ',', '.')  }}</h3>
                        <p class="text-uppercase">Total de Pacientes</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            @endif

            @if ($empresa->tipo_entidade->sigla == 'SEGPRIVADA')
            <div class="col-lg-3 col-md-3 col-12">
                <div class="small-box  bg-light-primary" title=" Hospedes">
                    <div class="inner">
                        <h3>{{ number_format($totalCliente, 0, ',', '.')  }}</h3>
                        <p class="text-uppercase">Total de Clientes</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            @endif

            @if ($empresa->plano_id == NULL)
            <button class="col-lg-3 col-md-3 col-12 border-0" data-toggle="modal" data-target="#modalAssociarPlano">
                <div class="small-box bg-light-success p-0" title="ATRIBUIR PLANO">
                    <div class="inner">
                        <h3>Planos</h3>
                        <p class="text-uppercase">Associar Plano a Empresa</p>
                    </div>
                    <div class="icon">
                        <i class="ion fas fa-link"></i>
                    </div>
                </div>
            </button>
            @else
            <button class="col-lg-3 col-md-3 col-12 border-0 delete-record" data-id="{{ $empresa->id }}">
                <div class="small-box bg-light-danger p-0" title="REMOVER PLANO">
                    <div class="inner">
                        <h3>{{ $empresa->plano->nome ?? "" }}</h3>
                        <p class="text-uppercase">Remover Plano da empresa</p>
                    </div>
                    <div class="icon">
                        <i class="ion fas fa-link"></i>
                    </div>
                </div>
            </button>
            @endif


            @if ($empresa->status == 'desactivo')
            <button class="col-lg-3 col-md-3 col-12 border-0 mudar-status-record" data-id="{{ $empresa->id }}">
                <div class="small-box bg-light-danger p-0" title="ATRIBUIR PLANO">
                    <div class="inner">
                        <h3>EMPRESA ACTIVA</h3>
                        <p class="text-uppercase">Activar a empresa</p>
                    </div>
                    <div class="icon">
                        <i class="ion fas fa-times"></i>
                    </div>
                </div>
            </button>
            @else
            <button class="col-lg-3 col-md-3 col-12 border-0 mudar-status-record" data-id="{{ $empresa->id }}">
                <div class="small-box bg-light-success p-0" title="REMOVER PLANO">
                    <div class="inner">
                        <h3>EMPRESA ACTIVA</h3>
                        <p class="text-uppercase">Desactivar empresa</p>
                    </div>
                    <div class="icon">
                        <i class="ion fas fa-check"></i>
                    </div>
                </div>
            </button>
            @endif

        </div>

        <div class="row">
            @foreach ($empresa->lojas as $loja)
            <div class="col-md-12 col-12">
                <div class="card mt-3">
                    <div class="card-header bg-light-primary">
                        <h3 class="card-title">LOJAS-(POSTOS) - {{ $loja->nome ?? "" }}</h3>
                        <button class="btn btn-light-success btn-sm btn-add-caixa float-right mx-2" data-loja="{{ $loja->id }}" data-empresa="{{ $empresa->id }}">
                            <i class="fas fa-plus"></i> Caixa
                        </button>

                        <a href="{{ route('empresas.loja-detalhes', ['empresa' => $empresa->id, 'loja' => $loja->id]) }}" class="btn btn-light-warning float-right mx-2">
                            <i class="fas fa-eye"></i> Visualizar
                        </a>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($loja->caixas as $caixa)
                            <div class="col-md-3 caixa-item" style="cursor: pointer" data-id="{{ $caixa->id }}">
                                <div class="info-box {{ $caixa->status_admin == "liberado" ? ' bg-light-success ' : ' bg-light-danger ' }}">
                                    <span class="info-box-icon">
                                        <i class="fas fa-cash-register"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $caixa->nome }}</span>
                                        <span class="info-box-number">{{ $caixa->status }}</span>
                                        <span class="info-box-number text-uppercase status_admin">{{ $caixa->status_admin }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<div class="modal fade" id="modalAssociarPlano" tabindex="-1" role="dialog" aria-labelledby="modalAssociarPlanoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header  bg-light-primary">
                <h5 class=" modal-title" id="modalAssociarPlanoLabel">
                    <i class="fas fa-file-invoice-dollar"></i>
                    Associar Plano Financeiro
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>

            <!-- FORM -->
            <form action="{{ route('empresa.associar.plano',$empresa->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Selecione o plano financeiro
                        que será associado à empresa.
                    </div>

                    <div class="form-group">
                        <label>Plano Financeiro</label>
                        <select name="plano_id" class="form-control select2" required>
                            <option value="">Selecionar Plano</option>
                            @foreach($planos as $plano)
                            <option value="{{ $plano->id }}">{{ $plano->nome }} | Mensalidade:
                                AKZ {{ number_format($plano->valor_mensal,2,',','.') }} | Multa:
                                {{ $plano->multa_percentual }}% | Juros: {{ $plano->juros_diario }}%
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- RESUMO -->

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="small-box  bg-light-primary">
                                <div class=" inner">
                                    <h4> Pagamento </h4>
                                    <p> Mensal </p>

                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="small-box bg-light-warning">
                                <div class="inner">
                                    <h4>Multas</h4>
                                    <p>Automáticas </p>
                                </div>

                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="small-box bg-light-danger">
                                <div class="inner">
                                    <h4>Bloqueio</h4>
                                    <p>Por dívida</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        Fechar
                    </button>

                    <button type="submit" class="btn btn-light-primary">
                        <i class="fas fa-save"></i>
                        Associar Plano
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalCaixa">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">Criar Nova Caixa</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="empresa_id">
                <input type="hidden" id="loja_id">
                <div class="form-group">
                    <label>Nome da Caixa</label>
                    <input type="text" id="nome_caixa" class="form-control" placeholder="Ex: Caixa Principal">
                </div>
                <div class="form-group">
                    <label>Status Inicial</label>
                    <select id="status_admin" class="form-control">
                        <option value="liberado">Liberado</option>
                        <option value="bloqueado">Bloqueado</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" id="btnSalvarCaixa">
                    Criar Caixa
                </button>
            </div>
        </div>
    </div>
</div>


</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
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
    });

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
                    url: `{{ route('empresa.remover.plano', ':id') }}`.replace(':id', recordId)
                    , method: 'POST'
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
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.mudar-status-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, mudar estado!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('empresa.bloquear', ':id') }}`.replace(':id', recordId)
                    , method: 'POST'
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
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.btn-add-caixa', function() {
        let empresa_id = $(this).data('empresa');
        let loja_id = $(this).data('loja');
        $('#empresa_id').val(empresa_id);
        $('#loja_id').val(loja_id);
        $('#nome_caixa').val('');
        $('#modalCaixa').modal('show');
    });

    $(document).on('click', '.caixa-item', function() {

        let id = $(this).data('id');
        let box = $(this);

        Swal.fire({
            title: 'Alterar estado da caixa?'
            , text: "Deseja ativar/desativar esta caixa?"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#16a34a'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, confirmar'
            , cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/caixa/toggle-status'
                    , type: 'POST'
                    , data: {
                        id: id
                        , _token: $('meta[name="csrf-token"]').attr('content')
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        if (response.success) {
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });

    $('#btnSalvarCaixa').on('click', function() {
        $.ajax({
            url: '/empresas-caixa/store'
            , type: 'POST'
            , data: {
                empresa_id: $('#empresa_id').val()
                , loja_id: $('#loja_id').val()
                , nome: $('#nome_caixa').val()
                , status_admin: $('#status_admin').val()
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                Swal.fire(
                    'Sucesso!'
                    , 'Caixa criada com sucesso.'
                    , 'success'
                );
                $('#modalCaixa').modal('hide');
                // opcional: atualizar página ou lista
                location.reload();
            }
        });
    });

</script>
@endsection
