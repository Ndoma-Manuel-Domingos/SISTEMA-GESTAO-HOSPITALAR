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
                        <li class="breadcrumb-item">
                            @if ($produto->tipo_stock == "P")
                            <a href="{{ route('produtos.materia_primas') }}">{{ __('messages.voltar') }}</a>
                            @else
                            <a href="{{ route('produtos.index') }}">{{ __('messages.voltar') }}</a>
                            @endif
                        </li>
                        <li class="breadcrumb-item active">{{ __('messages.designacao') }}</li>
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
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    @if ($produto->imagem == null)
                                    <img class="img-fluid mb-3" src="../../dist/img/sem-imagem.jpg" style="height: 120px" alt="{{ $produto->nome }}">
                                    @else
                                    <img class="img-fluid mb-3" src='{{ asset("images/produtos/$produto->imagem") }}' style="height: 120px" alt="{{ $produto->nome }}">
                                    {{-- <img class="img-fluid mb-3" src='{{ asset("images/produtos/$produto->imagem") }}' style="height: 120px" alt="{{ $produto->nome }}"> --}}
                                    @endif
                                </div>
                                <div class="col-12 col-md-9">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 table-responsive">
                                            <h2 class="h6 text-white">{{ $produto->nome }}
                                                @if ($produto->tipo_stock != "P" && $produto->tipo == "P")
                                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar produtos'))
                                                <button type="button" onclick="toggleModal()" class="float-right btn btn-light-primary mb-2 mx-1">
                                                    <i class="fas fa-list"></i> Receitas
                                                </button>
                                                @endif

                                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar grupo preco'))
                                                <a href="{{ route('grupos_preco.produtos', $produto->id) }}" class="float-right btn btn-light-primary mb-2 mx-1">
                                                    <i class="fas fa-edit"></i> Grupos de Preços
                                                </a>
                                                @endif
                                                @endif
                                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar produtos'))
                                                <a href="{{ route('produtos.edit', $produto->id) }}" class="float-right btn btn-light-primary mb-2 mx-1">
                                                    <i class="fas fa-edit"></i> {{ __('messages.editar') }}
                                                </a>
                                                @endif
                                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar produtos'))
                                                @if ($produto->lote_valicidade === 'Sim')
                                                <a href="{{ route('lotes.index', ['produto_id' => $produto->id]) }}" class="float-right btn btn-light-success mb-2 mx-1">
                                                    <i class="fas fa-cog"></i> Gestão de Lotes
                                                </a>
                                                @endif
                                                @endif
                                            </h2>
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-left">{{ __('messages.codigo_barras') }}</th>
                                                        <th class="text-left">{{ __('messages.categoria') }}</th>
                                                        <th class="text-left">{{ __('messages.marcas') }}</th>
                                                        <th class="text-left">{{ __('messages.variacoes') }}</th>
                                                        <th class="text-left">Tipo</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">{{ $produto->codigo_barra }}</td>
                                                        <td class="text-left"><i class="fas fa-ticket-alt"></i> {{ $produto->categoria->categoria }}</td>
                                                        <td class="text-left"><i class="fas fa-ticket-alt"></i> {{ $produto->marca->nome }}</td>
                                                        <td class="text-left"><i class="fas fa-ticket-alt"></i> {{ $produto->variacao->nome }}</td>
                                                        @if($produto->tipo == 'P')
                                                        <td class="text-left"><i class="fas fa-ticket-alt"></i> {{ __('messages.produtos') }}</td>
                                                        @endif
                                                        @if($produto->tipo == 'S')
                                                        <td class="text-left"><i class="fas fa-ticket-alt"></i> {{ __('messages.servico') }}</td>
                                                        @endif
                                                        @if($produto->tipo == 'O')
                                                        <td class="text-left"><i class="fas fa-ticket-alt"></i> Outro (portes, adiantamentos, etc.)</td>
                                                        @endif
                                                        @if($produto->tipo == 'I')
                                                        <td class="text-left"><i class="fas fa-ticket-alt"></i> {{ __('messages.imposto') }} (excepto IVA e IS) ou Encargo Parafiscal</td>
                                                        @endif
                                                        @if($produto->tipo == 'E')
                                                        <td class="text-left"><i class="fas fa-ticket-alt"></i> {{ __('messages.imposto') }} Especial de Consumo (IABA, ISP e IT)</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <th>Descrição</th>
                                                        <th>Lote</th>
                                                        <th>Peso</th>
                                                        <th>Unidade</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $produto->descricao }}</td>
                                                        <td>{{ $produto->lote_valicidade ?? 0 }}</td>
                                                        <td>{{ $produto->peso ?? 0 }}</td>
                                                        <td>{{ $produto->unidade->sigla ?? "" }}</td>
                                                        <td>{{ $produto->status }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar produtos'))
                            <button class="btn btn-light-danger mx-1 float-right delete-record" data-id="{{ $produto->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-7">
                    @if ($produto->controlo_stock == "Sim")
                    <div class="card table-responsive">
                        <div class="card-header">
                            <h1><span class="h4">Stock </span><i class="fas fa-database"></i>
                                @if ($produto->verificar_lote($produto->id, $empresa_logada->empresa->id))
                                <span class="float-right h5 text-light-danger">Atualmente, existe uma quantidade deste produto que está expirado. Agradecemos se puder verificar o seu estoque para garantir a segurança dos consumidores. <br>
                                    <a class="h5 text-light-primary" href="{{ route('estoques-produtos', ['status' => 'expirado', 'produto_id' => $produto->id]) }}">{{ __('messages.mais_detalhes') }}</a>
                                </span>
                                @endif
                            </h1>
                        </div>
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Loja / Armazém</th>
                                    <th>Alert Stock</th>
                                    <th>Stock Minimo</th>
                                    <th>Stock</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($lojas)
                                @foreach ($lojas as $item)
                                @php
                                $stock = $produto->converterDaBase($item->stock, $produto->unidade);
                                @endphp
                                <tr>
                                    <td>{{ $item->loja->nome }}</td>
                                    @if ($stock > 50)
                                    <td class="text-light-warning">Excesso</td>
                                    @endif

                                    @if ($stock <= 10) <td class="text-light-danger">Alerta</td>@endif
                                        @if ($stock > 10 AND $stock <= 50) <td class="text-light-success">Normal</td> @endif
                                            <td>{{ $item->stock_minimo }}</td>
                                            <td>{{ number_format($stock, 2, ',', '.') }} {{ $produto->unidade->sigla ?? "" }}</td>
                                            <td style="width: 50px;">
                                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar stock'))
                                                <a href="{{ route('movimento-estoques.show', $item->id) }}" class="btn btn-default"><i class="fas fa-database"></i> Gerir Stock</a>
                                                @endif
                                            </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>

                            <tfoot>
                                <th></th>
                                <th></th>
                                <th>{{ __('messages.total') }}</th>
                                <th>{{ $produto->converterDaBase($totalStock, $produto->unidade) }} {{ $produto->unidade->sigla ?? "" }}</th>
                                <th></th>
                            </tfoot>
                        </table>
                    </div>
                    @endif
                </div>

                @if ($produto->tipo_stock != "P" && $produto->tipo == "P")
                <div class="col-12 col-md-5">
                    <div class="card table-responsive">
                        <div class="card-header">
                            <h1><span class="h4">Preço do produto </span><i class="fas fa-cog"></i></h1>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 col-sm-3">
                                    <h2 class="h5">KZ</h2>
                                    <h4>{{ number_format($produto->preco_venda, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</h4>
                                </div>

                                <div class="col-12 col-sm-3">
                                    <h6>PVP</h6>
                                    <h2 class="h4">{{ number_format($produto->preco_venda, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</h2>
                                    <h6 class="h6 text-light-secondary">
                                        @if ($produto->imposto == "")
                                        Automático
                                        @endif

                                        @if ($produto->imposto == "ISE")
                                        IVA - Isento (0%)
                                        @endif

                                        @if ($produto->imposto == "RED")
                                        IVA - Taxa Reduzida (2%)
                                        @endif

                                        @if ($produto->imposto == "INT")
                                        IVA - Taxa Intermédia (5%)
                                        @endif

                                        @if ($produto->imposto == "OUT")
                                        IVA - Taxa 7% (7%)
                                        @endif

                                        @if ($produto->imposto == "NOR")
                                        IVA - Taxa Normal (14%)
                                        @endif
                                    </h6>
                                </div>

                                <div class="col-12 col-sm-3">
                                    <h6>Preço Custo Média</h6>
                                    <h2 class="h4">{{ number_format($produto->preco, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</h2>
                                    @if ($produto->margem <= 0) <span class="text-light-danger">projuizo {{ $produto->margem }}</span>@endif
                                        @if ($produto->margem >= 1)
                                        <span class="text-light-success"><i class="fas fa-circle-check"></i> Margem {{ $produto->margem }} %</span>
                                        @endif
                                </div>

                                <div class="col-12 col-sm-3">
                                    <h6> {{ __('messages.fornecedores') }} </h6>
                                    <h2 class="h4">{{ number_format($produto->preco_custo, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                @endif

            </div>

            @if ($produto->tipo_stock != "P" && $produto->tipo == "P")
            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="card table-responsive">
                        <div class="card-header">
                            <h1 class="h4">Receitas</h1>
                        </div>
                        <table class="table table-hover text-nowrap">
                            <tbody>
                                @foreach ($produto->receitas as $item)
                                <tr>
                                    <th>{{ $item->nome ?? "" }}</th>
                                    <th>Quantidade pães: {{ number_format($item->rendimento_base ?? 0, 1, ',', '.') }}</th>
                                    <th>Peso: {{ number_format($item->peso ?? 0, 1, ',', '.') }}</th>
                                </tr>
                                @foreach ($item->items as $i)
                                <tr>
                                    <td>{{ $i->ingrediente->nome }}</td>
                                    <td>{{ number_format($i->quantidade, 1, ',', '.')  }} {{ $i->unidade->sigla  }}</td>
                                    <td>{{ number_format($i->quantidade_gramas, 1, ',', '.')  }}</td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar grupo preco'))
                <div class="col-12 col-md-5">
                    <div class="card table-responsive">
                        <div class="card-header">
                            <h1><span class="h4">Grupo de Preços </span><i class="fas fa-database"></i></h1>
                        </div>
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.preco') }}</th>
                                    <th>Preço S/IVA</th>
                                    <th>Preço Fornecedor</th>
                                    <th>IVA</th>
                                    <th>Margem de Lucro</th>
                                    <th>{{ __('messages.estados') }}</th>
                                    <th class="text-right">{{ __('messages.accoes') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($grupo_precos)
                                @foreach ($grupo_precos as $item)
                                <tr>
                                    <td>{{ number_format($item->preco_venda??0, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda ?? "" }}</span></td>
                                    <td>{{ number_format($item->preco??0, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa->moeda ?? "" }}</span></td>
                                    <td>{{ number_format($item->preco_custo??0, 2, ',', '.')  }} <span class="text-light-secondary">{{ $empresa->moeda ?? "" }}</span></td>
                                    <td>{{ $item->produto->taxa_imposto->valor??0 }} %</td>
                                    <td>{{ number_format($item->margem??0, 2, ',', '.')  }} <span class="text-light-secondary">%</span></td>
                                    <td>{{ $item->status??"" }}</td>
                                    <td style="width: 50px;">
                                        @if ($item->status == "desactivo")
                                        <a href="{{ route('definir_preco.produtos', $item->id) }}" class="btn btn-light-primary status-record" data-id="{{ $item->id ?? "" }}"><i class="fas fa-database"></i> {{ __('messages.activo') }}</a>
                                        @endif
                                        <a href="{{ route('grupos_preco.delete', $item->id) }}" class="btn btn-light-danger delete-record-preco" data-id="{{ $item->id ?? "" }}"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>

                        </table>
                    </div>
                </div>
                @endif
            </div>
            @endif

        </div>
    </section>

    <form action="{{ route('produtos-receitas.store') }}" method="post" class="">
        @csrf
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Receitas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">
                        <div class="col-12 col-md-4">
                            <label for="nome" class="form-label"> {{ __('messages.designacao') }} </label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="nome" id="nome" value="{{ old('nome') }}" placeholder="Escolhe um nome para a receita">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="rendimento_base" class="form-label">Rendimento base(Quantidade)</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" name="rendimento_base" id="rendimento_base" value="{{ old('rendimento_base') }}" placeholder="Rendimento base">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="peso" class="form-label">Peso</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" name="peso" id="peso" value="{{ old('peso') }}" placeholder="Peso da receita em gramas">
                            </div>
                        </div>
                        <input type="hidden" name="produto_id" id="produto_id" value="{{ $produto->id }}">
                        <div class="col-12 col-md-12">
                            <table class="table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>Matéria Prima</th>
                                        <th>Quantidade</th>
                                        <th>Unidade</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="row">
                                <button type="button" id="addItem" class="btn btn-light-secondary mr-2"><i class="fas fa-plus"></i> Adicionar Matéria</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        @if (Auth::user()->can('criar todos'))
                        <button type="submit" class="btn btn-light-primary"><i class="fas fa-save"></i> {{ __('messages.salvar') }}</button>
                        @endif
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    let materias_primas = @json($materias_primas);
    let unidades = @json($unidades);

    let optionsUnidades = '';

    unidades.forEach(i => {
        optionsUnidades += `<option value="${i.id}">${i.nome} (${i.sigla})</option>`;
    });

    let index = 0;

    $('#addItem').click(function() {
        let options = '';
        materias_primas.forEach(i => {
            options += `<option value="${i.id}">${i.nome}</option>`;
        });
        let row = `
            <tr>
                <td>
                    <select name="items[${index}][ingrediente_id]" class="form-control">
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${index}][quantidade]" step="0.01" class="form-control">
                </td>
                <td>
                    <select name="items[${index}][unidade]" class="form-control">
                        ${optionsUnidades}
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-light-danger remove"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;
        $('#itemsTable tbody').append(row);

        index++;
    });

    // remover linha
    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });

    let PastaID = null;
    let modalVisible = false;

    const modalElement = document.getElementById('modal-lg');
    const modalInstance = new bootstrap.Modal(modalElement);

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

    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);

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
                    url: `{{ route('produtos.destroy', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = `{{ route('produtos.index') }}`;
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.delete-record-preco', function(e) {

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
                    url: `{{ route('grupos_preco.delete', ':id') }}`.replace(':id', recordId)
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

    $(document).on('click', '.status-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Deseja activar este preçario para o produto!"
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
                    url: `{{ route('definir_preco.produtos', ':id') }}`.replace(':id', recordId)
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
    });

</script>
@endsection
