@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Receber Encomenda ou Produto</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active"> {{ __('messages.fornecedores') }} </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="{{ route('encomenda-receber-produto-store') }}" method="post" class="">
                    @csrf
                    <div class="card-header">
                        <h5>Produtos a Rececionar</h5>
                        <p>Seleccione os produtos que pretende rececionar e indique a respetiva quantidade. Poderá efetuar tantas receções quantas necessárias.</p>

                        <button type="button" class="btn-light-primary btn-sm" id="selectAll">Selecionar Todos</button>
                        <button type="button" class="btn-light-primary btn-sm" id="deselectAll">Desmarcar Todos</button>
                    </div>
                    <input type="hidden" name="encomenda_id" value="{{ $encomenda->id }}">
                    <div class="card-body row">
                        @if ($items)
                        <div class="col-12 col-md-12">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 15%"></th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th class="text-center">Stock Atual</th>
                                        <th class="text-center">Margem de Lucro</th>
                                        <th class="text-center">IVA</th>
                                        <th style="width: 15%"> {{ __('messages.quantidade') }} </th>
                                        <th style="width: 15%">Preço Custo</th>
                                        <th style="width: 15%">Atualizar PVP (Kz)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="condicao{{ $item->id ?? "" }}" value="nao">
                                            <input type="checkbox" id="fornecedor_selecionado{{ $item->id ?? "" }}" class="checkbox-item" name="condicao{{ $item->id ?? "" }}" value="sim">
                                        </td>
                                        <td><a href="{{ route('produtos.show', $item->produto->id) }}">{{ $item->produto->nome ?? '' }}</a></td>
                                        <td class="text-center">{{ $item->produto->total_produto($item->produto->id)  }}</td>
                                        <td class="text-center">{{ $item->margem }}%</td>
                                        <td class="text-center">{{ $item->iva }}%</td>
                                        <td><input type="text" class="form-control" name="quantidade{{ $item->id ?? "" }}" value="{{ $item->quantidade }}"></td>
                                        <td><input type="text" class="form-control" value="{{ $item->custo }}"></td>
                                        <td><input type="text" class="form-control" value="{{ $item->preco_venda }}" name="preco_venda{{ $item->id ?? "" }}"></td>
                                        <input type="hidden" name="ids[]" value="{{ $item->id ?? "" }}">
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <div class="col-12 text-center">
                            <p> Ao rececionar os produtos irá atualizar o stock na <strong>{{ $encomenda->loja->nome }}</strong> assim como definir o atualizar PVP de cada produto.</p>
                            <p> O Preço de Custo será calculado com base no preço de custo médio atual e segundo as unidades disponíveis em stock (preço de custo médio).</p>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                    </div>
                </form>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
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
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');

                    window.location.href = response.redirect;
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
    });


    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('.checkbox-item').forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('.checkbox-item').forEach(checkbox => {
            checkbox.checked = false;
        });
    });

</script>
@endsection
