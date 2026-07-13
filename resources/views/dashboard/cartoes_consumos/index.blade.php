@extends('layouts.vendas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.cartao_consumo') }}</h1>
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
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar cartao'))
                                <button type="button" onclick="toggleModal()" class="btn btn-light-primary">
                                    <i class="fas fa-plus"></i> {{ __('messages.novo') }}
                                </button>
                                @endif
                            </h3>
                        </div>

                        <div class="card-body row">
                            @if ($empresa_logada->empresa->tipo_entidade->tipo_venda != "Normal")
                            @can("criar vendas")
                            <div class="col-md-6 col-12">
                                <div class="small-box  bg-light-primary" title=" Reservas">
                                    <div class="inner">
                                        <h5>::</h5>
                                        <p class="text-uppercase">Venda Normais</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-pie-graph"></i>
                                    </div>

                                    @if ($empresa_logada->empresa->tipo_pronto_venda == 'Grelha')
                                    <a href="{{ route('pronto-venda') }}" class="small-box-footer {{ Route::currentRouteNamed('pronto-venda') ? 'active' : '' }}"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i> </a>
                                    @endif

                                    @if ($empresa_logada->empresa->tipo_pronto_venda == 'Lista')
                                    <a href="{{ route('pos.index') }}" class="small-box-footer {{ Route::currentRouteNamed('pos.index') ? 'active' : '' }}"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i> </a>
                                    @endif

                                </div>
                            </div>
                            @endcan
                            @can("criar vendas")
                            <div class="col-md-6 col-12">
                                <div class="small-box  bg-light-primary" title=" Reservas">
                                    <div class="inner">
                                        <h5>::</h5>
                                        <p class="text-uppercase">Venda por Mesa</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-pie-graph"></i>
                                    </div>
                                    <a href="{{ route('mesas.visualizacao-mesas') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            @endcan
                            @endif
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <div class="row" id="cartoes-container">
                <!-- Cartão (repita esse bloco para cada cartão de cliente) -->
                @foreach ($cartoes as $item)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card card-primary card-outline shadow-sm card-credito">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Cartão: <strong>{{ $item->nome ?? "" }}</strong></h3>
                            <div class="btn-group btn-group-sm">
                                @can("historico cartao")
                                <button class="btn btn-light-primary" onclick="abrirHistorico({{ $item }})"><i class="fas fa-history"></i></button>
                                @endcan
                                @can("historico cartao")
                                <button class="btn btn-light-warning" onclick="abrirMovimentos({{ $item }})"><i class="fas fa-exchange-alt"></i></button>
                                @endcan
                                @can("listar cartao")
                                <a href="{{ route("cartoes-consumos.show", $item->id) }}" class="btn btn-light-primary"><i class="fas fa-eye"></i></a>
                                @endcan
                                @can("editar cartao")
                                <button class="btn btn-light-secondary" onclick="abrirEditar({{ $item }})"><i class="fas fa-edit"></i></button>
                                @endcan
                                @can("eliminar cartao")
                                <button class="btn btn-light-danger delete-record" data-id="{{ $item->id ?? "" }}"><i class="fas fa-trash"></i></button>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <p><strong>Saldo:</strong> <span class="text-light-success">{{ number_format($item->saldo, 2, ',', '.')  }}</span></p>
                            <p><strong>Validade:</strong> <span class="text-light-danger">Expira em 24h</span> - <strong>Estado:</strong> <span class="text-light-primary">{{ $item->status == "Y" ? "Em consumo" : "Livre" }}</span></p>
                            @can("carregar cartao")
                            <button class="btn btn-light-success btn-block" onclick="abrirCarregamento({{ $item }})">
                                <i class="fas fa-plus"></i> Carregar Saldo
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- Fim de um cartão -->
                <!-- Adicione mais blocos de cartões col-md-4 aqui conforme necessário -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <!-- Modal Carregar Saldo -->
    <div class="modal fade" id="modalCarregarSaldo" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-dialog-centered">
            <form action="{{ route("cartoes-consumos.carregar") }}" class="modal-content" id="formCarregarSaldo" method="post">
                @csrf
                <div class="modal-header bg-light-success">
                    <h5 class="modal-title">Carregar Saldo - <span id="nomeCartaoCarregar"></span></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="cartao" id="inputCartaoCarregar">
                    <div class="form-group">
                        <label>Valor a carregar (Kz)</label>
                        <input type="number" name="valor" class="form-control" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light-success">{{ __('messages.salvar') }}</button>
                    <button class="btn btn-light-secondary" data-dismiss="modal">{{ __('messages.cancelar') }} </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Cartão -->
    <div class="modal fade" id="modalEditarCartao" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route("cartoes-consumos.editar") }}" id="formEditarCartao">
                @csrf
                <div class="modal-header bg-light-secondary">
                    <h5 class="modal-title"> <i class="fas fa-edit"></i> {{ __('messages.editar') }} - <span id="nomeCartaoEditar"></span></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="inputCartaoEditar" name="cartao">
                    <div class="form-group">
                        <label> {{ __('messages.designacao') }} </label>
                        <input type="text" class="form-control" name="designacao" id="editar_nome_cartao" required>
                    </div>
                    <div class="form-group">
                        <label>Zera saldo</label>
                        <input type="number" class="form-control" name="saldo" id="editar_saldo_cartao" value="0" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light-secondary"> <i class="fas fa-edit"></i> {{ __('messages.editar') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Histórico -->
    <div class="modal fade" id="modalHistorico" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header  bg-light-primary">
                    <h5 class=" modal-title">Histórico - <span id="nomeCartaoHistorico"></span></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered" id="carregar_tabela">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo</th>
                                <th class="text-right">Saldo</th>
                                <th class="text-right"> {{ __('messages.data') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- conteúdo AJAX aqui -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Saldo</th>
                                <th>{{ __('messages.valor') }}</th>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('cartoes-consumos.store') }}" method="post" id="formCartoesConsumo" class="">
        @csrf
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cartões Consumo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">
                        <div class="col-12 col-md-12">
                            <label for="nome" class="form-label"> {{ __('messages.designacao') }} </label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-lg" name="nome" id="nome" value="{{ old('nome') }}" placeholder="{{ __('messages.designacao') }}...">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        @if (Auth::user()->can('criar todos') || Auth::user()->can('criar cartao'))
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        @endif
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->

</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    let PastaID = null;
    let modalVisible = false;

    const modalElement = document.getElementById('modal-lg');
    const modalInstance = new bootstrap.Modal(modalElement);

    function abrirCarregamento(item) {
        document.getElementById("nomeCartaoCarregar").textContent = item.nome;
        document.getElementById("inputCartaoCarregar").value = item.id
        $('#modalCarregarSaldo').modal('show');
    }

    function abrirHistorico(item) {

        document.getElementById("nomeCartaoHistorico").textContent = item.nome;

        $.get(`/cartaos/${item.id}/historico`, function(res) {

            let html = '';
            let html_tfoot = '';
            res.forEach(e => {
                html += `
                <tr>
                    <td>${e.id}</td>
                    <td>${e.tipo == "D" ? "Debito" : "Credito"}</td>
                    <td class="text-right">${e.saldo} Kz</td>
                    <td class="text-right">${e.date_at}</td>
                </tr>`;
            });

            html_tfoot += `
            <tr>
                <th>Saldo</th>
                <th></th>
                <th class="text-right">${item.saldo}</th>
                <th></th>
            </tr>`;

            $("#modalHistorico .modal-body tbody").html(html);
            $("#modalHistorico .modal-body tfoot").html(html_tfoot);
            $("#modalHistorico").modal("show");

        })

        $('#modalHistorico').modal('show');
    }

    function abrirEditar(item) {
        document.getElementById("nomeCartaoEditar").textContent = item.nome;
        document.getElementById("inputCartaoEditar").value = item.id;

        document.getElementById("editar_nome_cartao").value = item.nome;
        document.getElementById("editar_saldo_cartao").value = item.saldo;

        $('#modalEditarCartao').modal('show');
    }

    function toggleModal() {
        PastaID = null;
        if (modalVisible) {
            modalInstance.hide();
            modalVisible = false;
        } else {
            modalInstance.show();
            modalVisible = true;
        }
    }

    $(document).ready(function() {
        $('#formCartoesConsumo').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            let new_form = null;
            let method = null;

            if (PastaID == null) {
                new_form = form.attr('action');
                method = "post";
            } else {
                method = "put";
                new_form = form.attr('action') + "/" + PastaID;
            }

            $.ajax({
                url: new_form, // URL do endpoint no backend
                method: method, // Método HTTP definido no formulário
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
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
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
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });

        });

        $('#formCarregarSaldo').submit(function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: "post", // Método HTTP definido no formulário
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
                    Swal.fire({
                        title: `Saldo carregado com sucesso!`
                        , html: `<p><strong>Código:</strong> ${response.cartao_carregado.id}</p>Deseja gerar Comprovativo?`
                        , icon: 'question'
                        , showCancelButton: true
                        , confirmButtonText: 'Sim, gerar '
                        , cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const url = `{{ route('comprovativo-cartoes-consumos', ':code') }}`.replace(':code', response.cartao_carregado.id);
                            // Redirecionar
                            window.location.href = url;
                        }
                    });

                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });

        })

        $('#formEditarCartao').submit(function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: "post", // Método HTTP definido no formulário
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
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
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
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });
        })
    });


    function gerarComprovativo(comprovativo) {
        // Montar a URL com os parâmetros
        const url = `/comprovativo/${comprovativo}/cartoes-consumos?_token={{ csrf_token() }}`;

        $.ajax({
            url: url
            , method: 'GET', // Mudamos de POST para GET
            success: function(response) {
                // Gerar a URL usando o Laravel Blade
                const url = `{{ route('factura-recibo', ':code') }}`.replace(':code', response.cartao.id);
                // Redirecionar
                window.location.href = url;
                Swal.fire('Sucesso!', 'Fatura gerada com sucesso.', 'success');
            }
            , error: function(err) {
                Swal.fire('Erro!', 'Erro ao gerar fatura.', 'error');
                console.log(err);
            }
        });
    }

    $(document).on('click', '.edit-folder', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // Envia a solicitação AJAX para excluir o registro
        $.ajax({
            url: `{{ route('cartoes-consumos.edit', ':id') }}`.replace(':id', recordId)
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

                document.getElementById('nome').value = response.data.nome;
                document.getElementById('status').value = response.data.status;
                document.getElementById('cor').value = response.data.cor;
                PastaID = response.data.id;

            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
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
                    url: `{{ route('cartoes-consumos.destroy', ':id') }}`.replace(':id', recordId)
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
    });

</script>
@endsection
