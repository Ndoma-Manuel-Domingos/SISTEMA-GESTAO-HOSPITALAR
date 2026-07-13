@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Histórico do Stock - {{ $estoque->produto->nome }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('produtos.show', $estoque->produto->id) }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Loja</li>
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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-3 text-center">
                                    <h2><i class="fas fa-database"></i> <br> <span class="h4">Stock</span></h2>
                                    <h6>{{ $estoque->produto->nome }}</h6>
                                </div>
                                <div class="col-12 col-sm-3 text-center">
                                    <h6>Stock {{ $estoque->loja->nome }}</h6>
                                    <h2 class="h4"> {{ $estoque->produto->converterDaBase($estoque->stock, $estoque->produto->unidade)  }} {{ $estoque->produto->unidade->sigla }}</h2>
                                    <a href="">Actualizar</a>
                                </div>
                                <div class="col-12 col-sm-3 text-center">
                                    <h6>Stock Mínimo</h6>
                                    <h2 class="h4"> {{ $estoque->stock_minimo }}</h2>
                                    <a href="">alterar</a>
                                </div>
                                <div class="col-12 col-sm-3 text-center">
                                    <h6>Stock Total</h6>
                                    <h2 class="h4">{{ number_format($estoque->produto->converterDaBase($totalStock, $estoque->produto->unidade), 2, ',', '.')}} {{ $estoque->produto->unidade->sigla }}</h2>
                                    <a href="">Consultar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <form action="{{ route('movimento-estoques.update', $estoque->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label">Operação</label>
                                        <select type="text" class="form-control" name="operacao">
                                            <option value="entrada_stock">Adicionar Stock</option>
                                            <option value="saida_stock">Remover Stock</option>
                                            <option value="actualizar_stock">Actualizar de Stock</option>
                                            <option value="transferir_stock">Transferir Stock</option>
                                            <option value="alterar_minimo">Alterar Minimo</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label">Lotes</label>
                                        <select type="text" class="form-control" name="lote_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($lotes as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->lote }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label"> {{ __('messages.quantidade') }} </label>
                                        <input type="text" class="form-control" name="stock" value="{{ old('stock') }}" placeholder="{{ __('messages.quantidade') }} ...">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label">{{ __('messages.observacao') }}</label>
                                        <input type="text" class="form-control" name="justificativo" value="{{ old('justificativo') }}" placeholder="{{ __('messages.observacao') }}">
                                    </div>
                                    <input type="hidden" value="{{ $estoque->id }}" name="estoque_id">
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-light-primary">Confirmar o Movimento</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            @if (count($registros) > 0)
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Histórico de Stock - {{ $estoque->loja->nome }}</h4>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px"></th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th>Operação</th>
                                        <th>{{ __('messages.observacao') }}</th>
                                        <th> {{ __('messages.quantidade') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($registros as $movimento)
                                    <tr>
                                        <td class="bg-light text-center">
                                            @if ($movimento->registro == "Entrada de Stock")
                                            <span class="text-light-success"><i class="fas fa-plus-circle"></i></span>
                                            @endif

                                            @if ($movimento->registro == "Saída de Stock")
                                            <span class="text-light-danger"><i class="fas fa-minus"></i></span>
                                            @endif

                                            @if ($movimento->registro == "Actualizar de Stock")
                                            <span class="text-light-secondary"><i class="far fa-edit"></i></span>
                                            @endif
                                            {{-- {{ $movimento->id }} --}}
                                        </td>
                                        <td>{{ date_format($movimento->created_at, "Y-m-d") }} <br>
                                            <small>{{ date_format($movimento->created_at, "h:i:s") }} </small>
                                        </td>
                                        <td>{{ $movimento->registro }} <br> <small class="text-light-secondary">{{ $movimento->name }}</small>
                                        </td>
                                        <td>{{ $movimento->observacao }}</td>
                                        <td>{{ number_format($estoque->produto->converterDaBase($movimento->quantidade, $estoque->produto->unidade), 2, ',', '.')}} {{ $estoque->produto->unidade->sigla }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
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
