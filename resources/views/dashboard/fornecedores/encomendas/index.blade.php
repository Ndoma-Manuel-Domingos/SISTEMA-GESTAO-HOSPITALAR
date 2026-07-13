@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.encomendas') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-logistica') }}">{{ __('messages.voltar') }}</a></li>
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
                <div class="col-12 col-md-12">
                    <form action="{{ route('fornecedores-encomendas.index') }}" method="get" class="mt-3">
                        <div class="card">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="status" class="form-label">Tipo Encomenda</label>
                                        <select type="text" class="form-control select2" name="status" id="status">
                                            <option value="">{{ __('messages.todos') }} </option>
                                            <option value="pendente" {{ $requests['status'] == "pendente" ? 'selected' : '' }}>Pendentes</option>
                                            <option value="cancelada" {{ $requests['status'] == "cancelada" ? 'selected' : '' }}>Canceladas</option>
                                            <option value="entregue" {{ $requests['status'] == "entregue" ? 'selected' : '' }}>Entregues</option>
                                            <option value="rascunho" {{ $requests['status'] == "rascunho" ? 'selected' : '' }}>Rascunho</option>
                                        </select>
                                        <p class="text-light-danger">
                                            @error('status')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                        <input type="date" class="form-control" name="data_inicio" id="data_inicio" value="{{ $requests['data_inicio'] ?? '' }}">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                        <input type="date" class="form-control" id="data_final" name="data_final" value="{{ $requests['data_final'] ?? '' }}">
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary ml-2 text-right"><i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('fornecedores-encomendas.create') }}" class="btn btn-light-primary"><i class="fas fa-plus"></i> {{ __('messages.novo') }} </a>
                            </h3>
                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('imprimir-encomenda-todas', ['status' => $requests['status'] ?? "", 'data_inicio' => $requests['data_inicio'] ?? "", 'data_final' => $requests['data_final'] ?? "" ]) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($encomendas)
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nº Encomenda</th>
                                        <th> {{ __('messages.fornecedores') }} </th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>Estado Pagamento</th>
                                        <th class="text-right"> {{ __('messages.quantidade') }} </th>
                                        <th class="text-right">Qtds Recebida</th>
                                        {{-- <th>Nº Produto</th> --}}
                                        <th class="text-right">Total S/IVA</th>
                                        <th class="text-right">Total C/IVA</th>
                                        <th class="text-right">{{ __('messages.desconto') }}</th>
                                        <th class="text-right">Outros Custos</th>
                                        <th class="text-right">Total A Pagar</th>
                                        <th class="text-right">{{ __('messages.total') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($encomendas as $item)
                                    <tr>
                                        <td><a href="{{ route('fornecedores-encomendas.show', $item->id) }}">{{ $item->factura }}</a></td>
                                        <td><a href="{{ route('fornecedores.show', $item->fornecedor->id) }}">{{ $item->fornecedor->nome }}</a></td>
                                        <td>{{ $item->data_emissao }}</td>
                                        @if ($item->status == 'pendente')
                                        <td class="bg-light-warning text-white text-uppercase">{{ $item->status }}</td>
                                        @endif

                                        @if ($item->status == 'entregue')
                                        <td class="bg-light-primary text-white text-uppercase">{{ $item->status }}</td>
                                        @endif

                                        @if ($item->status == 'cancelada')
                                        <td class="bg-light-danger text-white text-uppercase">{{ $item->status }}</td>
                                        @endif
                                        <td>{{ $item->status_pagamento == 1 ? 'Pago': 'Não Pago' }}</td>
                                        <td class="text-right">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->quantidade_recebida, 2, ',', '.') }}</td>

                                        <td class="text-right">{{ number_format($item->total_sIva, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->total_cIVa, 2, ',', '.') }}</td>

                                        <td class="text-right">{{ number_format($item->desconto_valor, 2, ',', '.') }} (<small>{{ $item->desconto }}%</small>) </td>
                                        <td class="text-right">{{ number_format($item->outros_custos + $item->custo_transporte + $item->custo_manuseamento, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->total_a_pagar, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->total, 2, ',', '.') }}</td>
                                        <td>
                                            <div class="btn-group float-right">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="{{ route('fornecedores-encomendas.show', $item->id) }}"><i class="fas fa-info text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    <a class="dropdown-item" href="{{ route('fornecedores-encomendas.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    <a class="dropdown-item" href="{{ route('encomenda-receber-produto', $item->id) }}"><i class="fas fa-plus-circle"></i> Receber Encomenda</a>
                                                    <a class="dropdown-item" href="{{ route('encomenda-criar-factura-compra', $item->id) }}"><i class="fas fa-file"></i> Criar Factura de Compra</a>

                                                    <a class="dropdown-item" href="{{ route('imprimir-encomenda', $item->id) }}" target="_blank"><i class="fas fa-print text-light-primary"></i> {{ __('messages.imprimir') }} </a>
                                                    <div class="dropdown-divider"></div>

                                                    <button class="btn btn-light-danger dropdown-item delete-record" data-id="{{ $item->id ?? "" }}">
                                                        <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                    </button>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @endif

                    </div>
                    <!-- /.card -->
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
            , confirmButtonText: 'Sim, entregar!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('fornecedores-encomendas.destroy', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = response.redirect;
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', 'Ocorreu um erro ao fazer entregua da encomenda. Tente novamente.', 'error');
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
