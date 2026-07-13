@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.configuracoes') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.controle') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.configuracoes') }}</li>
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

                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'SEGPRIVADA')

                <div class="col-md-3 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">Cartão</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="h6 mb-3 text-light-dark">Configuração do cartão de identificação dos funcionários: ajuste de tamanho, cores, largura, altura, tipo de fonte e demais elementos visuais.</h2>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('configuracao-carto-funcionario.index') }}" class="btn btn-light-primary">{{ __('messages.editar') }}</a>
                        </div>
                    </div>
                </div>

                @else
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">Ultima factura</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="h6 mb-3">É necessário regularizar o pagamento referente à última fatura, garantindo que todos os valores estejam <br> corretos e atualizados.</h2>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('dashboard.configuracao-regularizar-factura') }}" class="btn btn-light-danger">Actualizar</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">{{ __('messages.config_inicializacao') }}</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="h6 mb-3">{{ __('messages.abertura_auto_caixa') }}</h2>
                        </div>
                        <div class="card-footer">
                            @if($empresa_logada->empresa->inicializacao == "Y")
                            <a href="{{ route('dashboard.configuracao-inicializacao') }}" class="btn btn-light-danger">{{ __('messages.nao') }}</a>
                            @endif
                            @if($empresa_logada->empresa->inicializacao == "N")
                            <a href="{{ route('dashboard.configuracao-inicializacao') }}" class="btn btn-light-success">{{ __('messages.sim') }}</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">{{ __('messages.config_finalizacao') }}</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="h6 mb-3">{{ __('messages.fecho_auto_caixa') }}</h2>
                        </div>
                        <div class="card-footer">
                            @if($empresa_logada->empresa->finalizacao == "Y")
                            <a href="{{ route('dashboard.configuracao-finalizacao') }}" class="btn btn-light-danger">{{ __('messages.nao') }}</a>
                            @endif
                            @if($empresa_logada->empresa->finalizacao == "N")
                            <a href="{{ route('dashboard.configuracao-finalizacao') }}" class="btn btn-light-success">{{ __('messages.sim') }}</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">{{ __('messages.tpa_ativo') }}</h5>
                        </div>
                        <div class="card-body">
                            @if ($bancoActivo)
                            <h2 class="h6 mb-3 text-light-success">{{ __('messages.caixa_ativo_nome', ['name' => Auth::user()->name, 'banco' => $bancoActivo->nome]) }}</h2>
                            @else
                            <h2 class="h6 mb-3 text-light-danger">{{ __('messages.sem_tpa', ['name' => Auth::user()->name]) }}</h2>
                            @endif
                        </div>
                        <div class="card-footer">
                            @if ($bancoActivo)
                            <a href="{{ route('contas-bancarias.fechamento', $bancoActivo->id) }}" data-id="{{ $bancoActivo->id }}" class="btn btn-light-danger fechar-conta-bancaria">{{ __('messages.fechar_caixa') }}</a>
                            @else
                            <a href="{{ route('contas-bancarias.abertura') }}" class="btn btn-light-success">{{ __('messages.activar_tpa') }}</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">{{ __('messages.caixa_ativo') }}</h5>
                        </div>
                        <div class="card-body">
                            @if ($caixaActivo)
                            <h2 class="h6 mb-3 text-light-success">{{ __('messages.caixa_ativo_nome', ['name' => Auth::user()->name, 'caixa' => $caixaActivo->nome]) }}</h2>
                            @else
                            <h2 class="h6 mb-3 text-light-danger">{{ __('messages.sem_caixa', ['name' => Auth::user()->name]) }}</h2>
                            @endif
                        </div>
                        <div class="card-footer">
                            @if ($caixaActivo)
                            @if (Auth::user()->can('fecho do caixa'))
                            <a href="{{ route('caixa.fechamento_caixa', $caixaActivo->id) }}" class="btn btn-light-danger">{{ __('messages.fechar_caixa') }}</a>
                            @endif
                            @else
                            @if (Auth::user()->can('abertura do caixa'))
                            <a href="{{ route('caixa.abertura_caixa') }}" class="btn btn-light-success">{{ __('messages.activar_caixa') }}</a>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">{{ __('messages.gestao_saldos_cartoes') }}</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="h6 mb-3 text-light-dark">{{ __('messages.recuperar_saldos') }}</h2>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="recuperacao-saldo btn btn-light-success">{{ __('messages.recupera_saldo') }}</a>
                        </div>
                    </div>
                </div>

                @if ($empresa_logada->empresa->tipo_venda == "Normal")
                <div class="col-md-3 col-12">
                    <div class="card bg-light-dark ">
                        <div class="card-header">
                            <h5 class="h5">{{ __('messages.venda_cartao_consumo') }}</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="h6 mb-3">{{ __('messages.pedido_com_saldo') }}</h2>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="definir_tipo_vendas btn btn-light-primary">{{ __('messages.mudar_para_com_cartao') }}</a>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-md-3 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">{{ __('messages.venda_sem_cartao_consumo') }}</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="h6 mb-3 text-light-dark">{{ __('messages.pedidos_com_saldo') }}</h2>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="definir_tipo_vendas btn btn-light-primary">{{ __('messages.mudar_para_sem_cartao') }}</a>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-md-3 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">{{ __('messages.acessar_painel_vendas') }}</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="h6 mb-3 text-light-dark">{{ __('messages.comecar_vendas') }}</h2>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route("pronto-venda") }}" class="btn btn-light-primary">{{ __('messages.inicio_vendas') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5">Definir Gestão de Pedidos para a Cozinha</h5>
                        </div>
                        <div class="card-body">
                            @if ($empresa_logada->empresa->destino_pedidos == "Normal")
                            <h2 class="h6 mb-3 text-light-dark">Atualmente, os pedidos devem ser levados manualmente à cozinha pelo atendente após serem anotados</h2>
                            @else
                            <h2 class="h6 mb-3 text-light-dark">A configuração atual envia os pedidos automaticamente para a cozinha, sem necessidade de ação manual</h2>
                            @endif
                        </div>
                        <div class="card-footer">
                            @if ($empresa_logada->empresa->destino_pedidos == "Normal")
                            <a href="#" class="btn btn-light-primary definir_destino_pedidos">Enviar automaticamente os pedidos para a cozinha</a>
                            @else
                            <a href="#" class="btn btn-light-primary definir_destino_pedidos">Levar pedidos para a cozinha</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

{{-- @include('dashboard.config.modal.dados-empresa') --}}
@endsection

@section('scripts')
<script>
    $(document).on('click', '.fechar-conta-bancaria', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, desejo!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('contas-bancarias.fechamento', ':id') }}`.replace(':id', recordId)
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

    $(document).on('click', '.definir_tipo_vendas', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Essa ação vai alterar a forma como as vendas são realizadas no sistema!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, alterar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('cartoes-consumos.definir-tipo-venda') }}`
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

    $(document).on('click', '.definir_destino_pedidos', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Essa ação vai alterar a forma como as vendas são realizadas no sistema!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, alterar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('dashboard.configuracao-pedidos-cuzinha') }}`
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

    $(document).on('click', '.recuperacao-saldo', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, recuperar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('cartaos-recuperar-saldos') }}`
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

</script>
@endsection
