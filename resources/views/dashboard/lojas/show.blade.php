@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }} : {{ $loja->nome }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('lojas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
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

                @if ($loja->ramo)
                <div class="col-12 col-md-8">

                    @if ($loja->ramo && $loja->ramo->sigla == 'HOTL')
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="small-box  bg-light-primary" title=" Reservas">
                                <div class="inner">
                                    <h3>{{ number_format($totalReservasFeitasHoje, 0, ',', '.') }}</h3>
                                    <p class="text-uppercase">Total Reserva de Hoje</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{ route('reservas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="small-box  bg-light-success" title=" Reservas">
                                <div class="inner">
                                    <h3>{{ number_format($reservasEmUso, 0, ',', '.') }}</h3>
                                    <p class="text-uppercase">Reservas activas</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{ route('reservas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="small-box  bg-light-primary" title=" Reservas">
                                <div class="inner">
                                    <h3>{{ number_format($totalReservas, 0, ',', '.') }}</h3>
                                    <p class="text-uppercase">{{ __('messages.reserva') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{ route('reservas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="small-box bg-light-success" title="Check In Diário">
                                <div class="inner">
                                    <h3>{{ number_format($totalReservasCheckIn, 0, ',', '.') }}</h3>
                                    <p class="text-uppercase">{{ __('messages.check_in_diario') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{ route('reservas.check_in_diario') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="small-box bg-light-danger" title="Check Out Diário">
                                <div class="inner">
                                    <h3>{{ number_format($totalReservasCheckOut, 0, ',', '.') }}</h3>
                                    <p class="text-uppercase">{{ __('messages.check_out_diario') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{ route('reservas.check_out_diario') }}" class="small-box-footer">{{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($loja->ramo && $loja->ramo->sigla == 'CFAT')
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="small-box  bg-light-primary">
                                <div class=" inner">
                                    <h3>{{ number_format($vendas->total_quantidade ?? 0, 2, ',', '.') }}</h3>
                                    <p class="text-uppercase">{{ __('messages.quantidade_produtos_vendidos') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{ route('vendas', ['loja_id' => $loja->id]) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-12">

                            <div class="small-box  bg-light-primary">
                                <div class=" inner">
                                    <h3>{{ number_format($vendas->total_vendas ?? 0, 2, ',', '.') }}</h3>
                                    <p class="text-uppercase">{{ __('messages.valor_acumulado_vendas') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{ route('vendas', ['loja_id' => $loja->id]) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="small-box bg-light-success" title="Quantidade Produtos Em Stock">
                                <div class="inner">
                                    <h3>{{ $total_estoque_activo ?? 0 }}</h3>
                                    <p class="text-uppercase">{{ __('messages.quantidade_produtos_stock') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{ route('estoques-produtos', ['status' => 'activo', 'loja_id' => $loja->id]) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-12">

                            <div class="small-box bg-light-danger">
                                <div class="inner">
                                    <h3>{{ $total_estoque_expirado ?? 0 }}</h3>
                                    <p class="text-uppercase">{{ __('messages.produtos_expirados_stock') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="{{ route('estoques-produtos', ['status' => 'expirado', 'loja_id' => $loja->id]) }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($loja->ramo && ($loja->ramo->sigla != 'HOSP' || $loja->ramo->sigla == 'REST' || $loja->ramo->sigla == 'HOTL'))
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">

                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12 col-md-5">
                                            <h4>{{ __('messages.produtos_mais_vendidos') }}</h4>
                                        </div>
                                        <div class="col-12 col-md-7">
                                            <div class="float-right">
                                                <button class="btn btn-light-primary" onclick="printGraficoProdutoMaisVendido()"><i class="fas fa-print"></i> {{ __('messages.imprimir_grafico') }}</button>
                                                <a href="{{ route('dashboard.produtos_mais_vendidos.pdf', ['inicio' => now()->subDays(14)->format('Y-m-d'), 'fim' => now()->format('Y-m-d')]) }}" id="btnPdfDetalhado" target="_blank" class="btn btn-light-success">
                                                    📥 {{ __('messages.imprimir_relatorio_detalhado') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="">
                                        <label>{{ __('messages.periodo') }}:</label>
                                        <input type="date" id="inicio" class="form-control d-inline" style="width: 200px;">
                                        <input type="date" id="fim" class="form-control d-inline" style="width: 200px;">
                                        <button onclick="carregarProdutosMaisVendidos()" class="btn btn-light-primary"> {{ __('messages.filtrar') }}</button>
                                    </div>
                                    <canvas id="graficoProdutosMaisVendidos" width="400" height="80"></canvas>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <h4>{{ __('messages.estoque_critico_por_loja') }}</h4>
                                        </div>
                                        <div class="col-md-9 col-12">
                                            <div class="float-right">
                                                <select id="lojaId" class="form-control d-inline" style="width: 400px;" onchange="carregarEstoqueCriticoPorLoja()">
                                                    <option value="" selected>{{ __('messages.escolher') }}</option>
                                                    @foreach ($empresa_logada->empresa->lojas as $item)
                                                    <option value="{{ $item->id ?? "" }}" {{ $loja->id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                                    @endforeach
                                                </select>
                                                <button class="btn btn-light-primary" onclick="imprimirGraficoEstoque()"><i class="fas fa-print"></i> {{ __('messages.imprimir_grafico') }}</button>
                                                <a href="{{ route('dashboard.estoque_critico_pdf') }}" target="_blank" class="btn btn-light-danger">
                                                    🧾 {{ __('messages.imprimir_relatorio_detalhado') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="graficoEstoqueLoja" width="400" height="80"></canvas>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                @endif

                <div class="col-12 {{ $loja->ramo ? 'col-md-4' : 'col-md-12' }}">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Caixa/POS</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loja->caixas as $item)
                                    <tr>
                                        <td><a href="{{ route('caixas.show', $item->id) }}">{{ $item->conta }} - {{ $item->nome }}</a></td>
                                        <td>{{ $item->status }}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar caixa'))
                                                    <a class="dropdown-item" href="{{ route('caixas.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif
                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar caixa'))
                                                    <a class="dropdown-item" href="{{ route('caixas.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar caixa'))
                                                    <button class="btn btn-light-danger dropdown-item delete-record-caixa" data-id="{{ $item->id ?? "" }}">
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
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            @if (Auth::user()->can('criar todos') || Auth::user()->can('criar caixa'))
                            <a href="{{ route('caixas.create', ['createLoja' => $loja->id] ) }}" class="btn btn-md btn-light-primary">Adicionar Caixa</a>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="card-body table-responsive">
                                <table class="table table-hover text-nowrap" id="carregar_tabela2" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Conta Bancária</th>
                                            <th>{{ __('messages.estados') }}</th>
                                            <th class="text-right">{{ __('messages.accoes') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($loja->bancos as $item)
                                        <tr>
                                            <td>{{ $item->conta }} - {{ $item->banco->sigla }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td class="text-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                                    <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        @if (Auth::user()->can('listar todos') || Auth::user()->can('listar banco'))
                                                        <a class="dropdown-item" href="{{ route('contas-bancarias.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                        @endif
                                                        @if (Auth::user()->can('editar todos') || Auth::user()->can('editar banco'))
                                                        <a class="dropdown-item" href="{{ route('contas-bancarias.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                        @endif
                                                        <div class="dropdown-divider"></div>
                                                        @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar banco'))
                                                        <button class="btn btn-light-danger dropdown-item delete-record-banco" data-id="{{ $item->id ?? "" }}">
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
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar banco'))
                                <a href="{{ route('contas-bancarias.create', ['createLoja' => $loja->id] ) }}" class="btn btn-md btn-light-primary">Adicionar Conta Bancária</a>
                                @endif
                            </div>
                        </div>
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
    let chartInstance;
    let chartEstoqueLoja;
    let chartProdutos = null;

    carregarProdutosMaisVendidos();
    carregarEstoqueCriticoPorLoja();

    function carregarProdutosMaisVendidos() {
        const inicio = document.getElementById("inicio").value;
        const fim = document.getElementById("fim").value;
        const lojaId = document.getElementById('lojaId').value;

        let url = `{{ route('dashboard.produtos_mais_vendidos') }}`;
        let params = [];

        if (inicio) params.push(`inicio=${inicio}`);
        if (fim) params.push(`fim=${fim}`);
        if (lojaId) params.push(`loja_id=${lojaId}`);

        if (params.length > 0) {
            url += '?' + params.join('&');
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {

                const labels = data.map(item => item.produto.nome);
                const quantidadeVendida = data.map(item => item.total_vendido);
                const totalVendido = data.map(item => item.valor_total);
                const lucros = data.map(item => item.lucro_total || 0); // lucro deve vir da API

                const ctx = document.getElementById('graficoProdutosMaisVendidos').getContext('2d');

                // Atualizar ou criar o gráfico
                if (chartProdutos) {
                    chartProdutos.destroy();
                }

                chartProdutos = new Chart(ctx, {
                    type: 'bar', // ou 'doughnut'
                    data: {
                        labels: labels
                        , datasets: [{
                                label: 'Quantidade Vendida'
                                , data: quantidadeVendida
                                , backgroundColor: 'rgba(54, 162, 235, 0.6)'
                                , borderColor: 'rgba(54, 162, 235, 1)'
                                , borderWidth: 1
                            }
                            , {
                                label: 'Total Vendido (Kz)'
                                , data: totalVendido
                                , backgroundColor: 'rgba(255, 159, 64, 0.6)'
                                , borderColor: 'rgba(255, 159, 64, 1)'
                                , borderWidth: 1
                            }
                            , {
                                label: 'Lucro Estimado (Kz)'
                                , data: lucros
                                , backgroundColor: 'rgba(75, 192, 192, 0.6)'
                                , borderColor: 'rgba(75, 192, 192, 1)'
                                , borderWidth: 1
                            }
                        ]
                    }
                    , options: {
                        indexAxis: 'y'
                        , responsive: true
                        , plugins: {
                            legend: {
                                display: true
                                , position: 'top'
                            }
                            , tooltip: {
                                enabled: true
                            , }
                        }
                        , scales: {
                            x: {
                                beginAtZero: true
                            , }
                        }
                    }
                });
            });
    }

    function carregarEstoqueCriticoPorLoja() {
        const lojaId = document.getElementById('lojaId').value;

        fetch(`{{ route('dashboard.estoque.critico') }}${lojaId ? '?loja_id=' + lojaId : ''}`)
            .then(res => res.json())
            .then(data => {
                const labels = data.map(p => `${p.loja.nome} - ${p.produto.nome}`);
                const saldo = data.map(p => p.saldo_atual);
                const minimo = data.map(p => p.stock_minimo);

                const ctx = document.getElementById("graficoEstoqueLoja").getContext("2d");

                if (window.graficoEstoqueCritico) {
                    window.graficoEstoqueCritico.destroy();
                }

                window.graficoEstoqueCritico = new Chart(ctx, {
                    type: 'bar'
                    , data: {
                        labels: labels
                        , datasets: [{
                                label: 'Saldo Atual'
                                , data: saldo
                                , backgroundColor: 'rgba(255, 99, 132, 0.7)'
                            }
                            , {
                                label: 'Estoque Mínimo'
                                , data: minimo
                                , backgroundColor: 'rgba(54, 162, 235, 0.5)'
                            }
                        ]
                    }
                    , options: {
                        responsive: true
                        , plugins: {
                            tooltip: {
                                callbacks: {
                                    label: ctx => ` ${ctx.dataset.label}: ${ctx.raw}`
                                }
                            }
                            , legend: {
                                display: true
                                , position: 'bottom'
                            }
                        }
                        , scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    }

    $(document).on('click', '.delete-record-banco', function(e) {

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
                    url: `{{ route('contas-bancarias.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.delete-record-caixa', function(e) {

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
                    url: `{{ route('caixas.destroy', ':id') }}`.replace(':id', recordId)
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

        $("#carregar_tabela2").DataTable({
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
