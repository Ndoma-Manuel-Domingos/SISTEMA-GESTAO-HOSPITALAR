@extends('layouts.vendas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Controle da Cuzinha</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.inicio') }}</a></li>
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

            <div class="row">
                <div class="col-12 col-md-12 mb-3">
                    <a href="{{ route('dashboard-principal') }}" class="btn btn-lg  btn-light-dark">«« Voltar</a>
                </div>
            </div>

            <div class="row">
                <!-- Cartão (repita esse bloco para cada cartão de cliente) -->
                @foreach ($pedidos as $item)
                <div class="col-md-3 col-sm-6 mb-4" id="pedido-{{ $item->id ?? "" }}">
                    <div class="card card-primary card-outline shadow-sm card-credito">
                        <div class="card-header  align-items-center">
                            <h3 class="card-title">Pedido: <strong>{{ $item->numero ?? "" }}</strong></h3>
                            <div class="float-right">
                                <button class="btn btn-light-primary" onclick="imprimirPedido({{ $item }})"><i class="fas fa-print"></i> Imprimir</button>
                                <button class="btn btn-light-primary" onclick="abrirPedido({{ $item }})"><i class="fas fa-list"></i> Var Item</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p><strong>{{ __('messages.estados') }}:</strong> <span class="text-light-success" id="status_updated_{{$item->id}}">{{ $item->status }}</span></p>
                            <p><strong>Tempo de espera:</strong> <span class="tempo-espera" data-timestamp="{{ $item->created_at_timestamp }}"></span></p>
                            <button class="btn btn-light-success btn-block" onclick="atualizarStatus({{ $item->id ?? "" }}, this.value)">
                                {{ __('messages.actualizar') }}
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- Fim de um cartão -->
                <!-- Adicione mais blocos de cartões col-md-4 aqui conforme necessário -->
            </div>

            <div class="row">
                <div class="col-12 col-md-12 text-center">
                    <button id="abrirTelaCheia" class="btn btn-light-primary btn-lg">Abrir Tela Cheia</button>
                    <button id="fecharTelaCheia" class="btn btn-light-danger btn-lg">Sair da Tela Cheia</button>
                </div>
            </div>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <!-- Modal Histórico -->
    <div class="modal fade" id="modalItemPedido" tabindex="-1">
        <div class="modal-dialog modal-lx modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header  bg-light-primary">
                    <h5 class=" modal-title">Item do Pedido - <span id="nomePedido"></span></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('messages.descricao') }}</th>
                                <th class="text-right">{{ __('messages.quantidade') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- conteúdo AJAX aqui -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    // Botão para entrar em tela cheia
    document.getElementById('abrirTelaCheia').addEventListener('click', () => {
        document.documentElement.requestFullscreen().then().catch((err) => console.error(err));
    });

    // Botão para sair da tela cheia
    document.getElementById('fecharTelaCheia').addEventListener('click', () => {
        if (document.fullscreenElement) {
            document.exitFullscreen().then().catch((err) => console.error(err));
        }
    });

    setTimeout(() => {
        document.getElementById('abrirTelaCheia').click();
    }, 2000);

    function imprimirPedido(item) {

        let html = `
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Itens do Pedido</title>
                <style>
                    ul {
                        list-style-type: none;
                        padding: 0;
                    }
                    ul li {
                        margin-bottom: 5px;
                        padding: 5px;
                        background: #f5f5f5;
                        border-radius: 5px;
                    }
                    h1 {
                        text-align: left;
                        text-transform: uppercase;
                    }
                    .impressao {
                        padding: 0 20px;
                    }
                    .impressao ul li {
                        font-size: 18px;
                    }
                </style>
            </head>
            <body>
                <h1>Itens do Pedido</h1>
                <div class="impressao">
                    <ul>`;
        item.items.forEach(function(item) {
            html += `<li>${item.quantidade} x ${item.produto.nome}</li>`;
        });
        html += `</ul>
                </div>
            </body>
            </html>`;

        // Abrir nova janela para imprimir
        const win = window.open('', '_blank');
        win.document.write(html);
        win.document.close();

        win.print();

    }

    function abrirPedido(item) {
        document.getElementById("nomePedido").textContent = item.numero;
        let html = '';
        item.items.forEach(e => {
            html += `
            <tr>
                <td>${e.produto.nome}</td>
                <td class="text-right">${e.quantidade}</td>
            </tr>`;
        });

        $("#modalItemPedido .modal-body tbody").html(html);
        $('#modalItemPedido').modal('show');
    }

    function atualizarStatus(id, status) {
        fetch('/pedidos/' + id + '/atualizar-status', {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
                , body: JSON.stringify({
                    status
                })
            })
            .then(response => response.json())
            .then(data => {
                document.querySelector("#status_updated_" + id + "").innerHTML = data.pedido.status;
            })
            .catch(error => console.error(error));

    }

    // Funcão para atualizar o tempo de espera automaticamente
    function atualizarTemposDeEspera() {
        document.querySelectorAll('.tempo-espera').forEach(function(el) {
            var timestamp = parseInt(el.dataset.timestamp);
            var agora = Math.floor(Date.now() / 1000);
            var diferenca = agora - timestamp;

            var minutos = Math.floor(diferenca / 60);
            var segundos = diferenca % 60;

            el.textContent = minutos + "m " + segundos + "s";

        });
    }

    // Atualiza a cada 1 segundo
    setInterval(atualizarTemposDeEspera, 1000);
    atualizarTemposDeEspera();

</script>
@endsection
