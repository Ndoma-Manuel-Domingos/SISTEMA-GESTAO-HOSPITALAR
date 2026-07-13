@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 col-md-6">
                    <h1 class="m-0">Requisição - {{ $requisicao->numero }}</h1>
                </div><!-- /.col -->
                <div class="col-12 col-md-6">
                    <div class="btn-group">
                        <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                        <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">

                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar requisacao'))
                            <a class="dropdown-item" href="{{ route('requisacoes.edit', $requisicao->id) }}">{{ __('messages.actualizar') }}</a>
                            @endif


                            @if (Auth::user()->can('aprovar requisicao'))
                            @if ($requisicao->status != 'aprovada')
                            <a class="dropdown-item text-light-success" href="{{ route('requisacoes.aprovada', $requisicao->id) }}">Marcar como Apravada</a>
                            @endif
                            @endif


                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar requisacao'))
                            @if ($requisicao->status != 'rascunho')
                            <a class="dropdown-item text-light-warning" href="{{ route('requisacoes.rascunho', $requisicao->id) }}">Marcar como Rascunho</a>
                            @endif
                            @endif

                            @if (Auth::user()->can('rejeitar requisicao'))
                            @if ($requisicao->status != 'rejeitada')
                            <a class="dropdown-item text-light-danger" href="{{ route('requisacoes.rejeitar', $requisicao->id) }}">Marcar como Rejeitado</a>
                            @endif
                            @endif

                            <div class="dropdown-divider"></div>
                            @if (Auth::user()->can('listar todos') || Auth::user()->can('listar requisacao'))
                            <a class="dropdown-item" target="_blank" href="{{ route('requisacoes-imprimir', $requisicao->id) }}">{{ __('messages.imprimir') }}</a>
                            @endif
                            <div class="dropdown-divider"></div>

                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar requisacao'))
                            <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item delete-record text-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                            @endif

                        </div>
                    </div>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('requisacoes.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Requisição</li>
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

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Nº da Requisição: {{ $requisicao->numero ?? '--' }}</h5>
                        </div>
                        <div class="card-body">
                            <h6>Operador(a): <span class="float-right">{{ $requisicao->user->name ?? '--' }}</span></h6>
                            <h6>Data da Requisição: <span class="float-right">{{ $requisicao->data_emissao ?? '--' }}</span></h6>
                            <h6>Aprovador(a): <span class="float-right">{{ $requisicao->aprovador ? $requisicao->aprovador->name : 'Nenhum' }}</span></h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dados da Entrega</h5>
                        </div>
                        <div class="card-body">
                            <h6>Loja/Armazém:<span class="float-right">{{ $requisicao->loja->nome ?? '--' }}</span></h6>
                            <h6>Previsão de Entrega:<span class="float-right">{{ $requisicao->previsao_entrega ?? '--' }}</span></h6>
                            @if ($requisicao->status == 'pendente')
                            <h6>Estado:<span class="float-right bg-light-warning p-1 text-uppercase">{{ $requisicao->status ?? '--' }}</span></h6>
                            @endif

                            @if ($requisicao->status == 'rejeitada')
                            <h6>Estado:<span class="float-right bg-light-danger p-1 text-uppercase">{{ $requisicao->status ?? '--' }}</span></h6>
                            @endif

                            @if ($requisicao->status == 'rascunho')
                            <h6>Estado:<span class="float-right bg-light-warning p-1 text-uppercase">{{ $requisicao->status ?? '--' }}</span></h6>
                            @endif

                            @if ($requisicao->status == 'aprovada')
                            <h6>Estado:<span class="float-right bg-light-success p-1 text-uppercase">{{ $requisicao->status ?? '--' }}</span></h6>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Codigo Barra</th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th>{{ __('messages.categoria') }}</th>
                                        <th>{{ __('messages.marcas') }}</th>
                                        <th>{{ __('messages.variacoes') }}</th>
                                        <th class="text-right"> {{ __('messages.quantidade') }} </th>
                                        <th class="text-right">IVA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $item->produto->codigo_barra }}</td>
                                        <td>{{ $item->produto->nome ?? "" }}</td>
                                        <td>{{ $item->produto->categoria->categoria ?? "" }}</td>
                                        <td>{{ $item->produto->marca->nome ?? "" }}</td>
                                        <td>{{ $item->produto->variacao->nome ?? "" }}</td>
                                        <td class="text-right">{{ $item->quantidade }}</td>
                                        <td class="text-right">{{ $item->produto->taxa_imposto->descricao ?? "" }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
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
                    url: `{{ route('requisacoes.destroy', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = "/requisacoes";
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
