@extends('layouts.app')

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
                        <li class="breadcrumb-item"><a href="{{ route('equipamentos-activos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Equipamento/Activo</li>
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
                        @if ($equipamento_activo)
                        <!-- /.card-header -->
                        <div class="card-header">
                            <img src="/images/imobilizados/{{ $equipamento_activo->anexo }}" style="height: 150px;width: 150px">
                        </div>
                        <div class="card-body table-responsive">
                            <div class="row">

                                <div class="col-12 col-md-4 table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th> {{ __('messages.designacao') }} </th>
                                                <td class="text-right">{{ $equipamento_activo->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Nº Serie</th>
                                                <td class="text-right">{{ $equipamento_activo->numero_serie ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Codigo Barra</th>
                                                <td class="text-right">{{ $equipamento_activo->codigo_barra ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-4 table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>{{ __('messages.estados') }}</th>
                                                <td class="text-right">{{ $equipamento_activo->status ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th> {{ __('messages.descricao') }} </th>
                                                <td class="text-right">{{ $equipamento_activo->descricao ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Subconta</th>
                                                <td class="text-right">{{ $equipamento_activo->conta->numero ?? '-------------' }} - {{ $equipamento_activo->conta->nome ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-4 table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>Classificação</th>
                                                <td class="text-right">{{ $equipamento_activo->classificacao->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Referência</th>
                                                <td class="text-right">{{ $equipamento_activo->code ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Número da Factura</th>
                                                <td class="text-right">{{ $equipamento_activo->numero_factura ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12 table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            {{-- -------------------------------------------- --}}
                                            <tr>
                                                <th colspan="4">Informações Financeiras</th>
                                            </tr>
                                            <tr>
                                                <th>Base de Incidência</th>
                                                <th>Iva %</th>
                                                <th>Iva Dedutível %</th>
                                                <th>Iva Não Dedutível %</th>
                                            </tr>
                                            <tr>
                                                <td>{{ number_format($equipamento_activo->base_incidencia ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($equipamento_activo->iva ?? 0, 1, ',', '.') }} - {{ number_format($equipamento_activo->iva_total ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($equipamento_activo->iva_d ?? 0, 1, ',', '.') }} - {{ number_format($equipamento_activo->iva_dedutivel ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($equipamento_activo->iva_nd ?? 0, 1, ',', '.') }} - {{ number_format($equipamento_activo->iva_n_dedutivel ?? 0, 2, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Custo Aquisição</th>
                                                <th>Valor Contabilistico</th>
                                                <th>Data Aquisição</th>
                                                <th>Data Utilizaçao</th>
                                            </tr>
                                            <tr>
                                                <td>{{ number_format($equipamento_activo->custo_aquisicao ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($equipamento_activo->valor_contabilistico ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ $equipamento_activo->data_aquisicao ?? '-------------' }}</td>
                                                <td>{{ $equipamento_activo->data_utilizacao ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Data Registro</th>
                                                <th> {{ __('messages.quantidade') }} </th>
                                                <th>Valor Total</th>
                                                <th>{{ __('messages.estados') }}</th>
                                            </tr>
                                            <tr>
                                                <td>{{ $equipamento_activo->data_att ?? '-------------' }}</td>
                                                <td>{{ number_format($equipamento_activo->quantidade ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($equipamento_activo->total ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ $equipamento_activo->staus_financeiro ?? '-------------' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer clearfix d-flex">
                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('activo contabilidade'))
                            <a href="{{ route('equipamentos-activos.edit', $equipamento_activo->id) }}" class="btn btn-light-success mx-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" data-id="{{ $equipamento_activo->id }}" class="dropdown-item delete-record text-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                            @endif
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
            , confirmButtonText: 'Sim, excluir!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('equipamentos-activos.destroy', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = "/contabils/equipamentos-activos";
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
