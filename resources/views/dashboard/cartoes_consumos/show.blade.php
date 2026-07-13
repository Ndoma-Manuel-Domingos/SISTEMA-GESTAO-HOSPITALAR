@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }} - {{ $cartao->nome }}</h1>
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
                <div class="col-12 col-md-4">
                    <div class="card card-primary card-outline shadow-sm card-credito">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Cartão: <strong>{{ $cartao->nome ?? "" }}</strong></h3>
                            <div class="btn-group btn-group-sm">
                                @can("editar cartao")
                                <button class="btn btn-light-secondary" onclick="abrirEditar({{ $cartao }})"><i class="fas fa-edit"></i></button>
                                @endcan
                                @can("eliminar cartao")
                                <button class="btn btn-light-danger delete-record" data-id="{{ $cartao->id }}"><i class="fas fa-trash"></i></button>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <p><strong>Saldo:</strong> <span class="text-light-success">{{ number_format($cartao->saldo, 2, '.', ',')  }}</span></p>
                            <p><strong>Validade:</strong> <span class="text-light-danger">Expira em 24h</span> - <strong>Estado:</strong> <span class="text-light-primary">{{ $cartao->status == "Y" ? "Em consumo" : "Livre" }}</span></p>

                            @can("carregar cartao")
                            <button class="btn btn-light-success btn-block" onclick="abrirCarregamento({{ $cartao }})">
                                <i class="fas fa-plus"></i> Carregar Saldo
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-8">
                    <div class="row">


                        @if ($cartao->movimentos)
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Movimentos
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive">
                                    <table class="table table-hover text-nowrap" id="carregar_tabela1" style="width: 100%">
                                        <thead>
                                            <tr>
                                                {{-- <th>#</th> --}}
                                                <th> {{ __('messages.descricao') }} </th>
                                                <th>Saldo</th>
                                                <th> {{ __('messages.data') }} </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cartao->movimentos as $key => $item)
                                            <tr>
                                                {{-- <td>{{ $key + 1 }}</td> --}}
                                                <td>{{ $item->descricao }}</td>
                                                <td>{{ number_format($item->saldo, 2, '.', ',') }} Kz</td>
                                                <td>{{ $item->date_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        @endif

                        @if ($cartao->historicos)
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Historicos
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive">
                                    <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                        <thead>
                                            <tr>
                                                {{-- <th>#</th> --}}
                                                <th>Tipo</th>
                                                <th>Saldo</th>
                                                <th> {{ __('messages.data') }} </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cartao->historicos as $item)
                                            <tr>
                                                {{-- <td>{{ $item->id ?? "" }}</td> --}}
                                                <td>{{ $item->tipo == "D" ? "Debito" : "Crédito" }}</td>
                                                <td>{{ number_format($item->saldo, 2, '.', ',') }} Kz</td>
                                                <td>{{ $item->date_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        @endif

                    </div>
                </div>

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

</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    function abrirCarregamento(item) {
        document.getElementById("nomeCartaoCarregar").textContent = item.nome;
        document.getElementById("inputCartaoCarregar").value = item.id
        $('#modalCarregarSaldo').modal('show');
    }

    function abrirEditar(item) {
        document.getElementById("nomeCartaoEditar").textContent = item.nome;
        document.getElementById("inputCartaoEditar").value = item.id;

        document.getElementById("editar_nome_cartao").value = item.nome;
        document.getElementById("editar_saldo_cartao").value = item.saldo;

        $('#modalEditarCartao').modal('show');
    }

    $(document).ready(function() {

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
                        window.location.href = "/cartoes-consumos";
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
