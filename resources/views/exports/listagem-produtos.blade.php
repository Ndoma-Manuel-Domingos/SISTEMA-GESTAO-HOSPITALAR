<table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
    <thead>
        <tr>
            <th style="width: 120px">{{ __('messages.codigo_barras') }}</th>
            <th style="width: 170px">Nome</th>
            <th style="width: 120px">{{ __('messages.categoria') }}</th>
            <th style="width: 120px">{{ __('messages.tipo') }}</th>
            <th style="width: 120px">{{ __('messages.preco_venda') }}</th>
            <th style="width: 120px">Preço Custo Média</th>
            <th style="width: 120px">{{ __('messages.preco_custo') }}</th>
            <th style="width: 120px">IVA %</th>
            <th style="width: 120px">{{ __('messages.quantidade') }}</th>
            <th style="width: 120px">{{ __('messages.estados') }}</th>
            <th style="width: 120px">Lote</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produtos as $produto)
        <tr>
            <td>{{ $produto->codigo_barra }}</td>
            <td>{{ $produto->nome }}</td>
            <td>{{ $produto->categoria->categoria }}</td>
            <td>{{$produto->tipo }}</td>
            <td>{{ number_format($produto->preco_venda??0, 2, ',', '.') }}</td>
            <td>{{ number_format($produto->preco??0, 2, ',', '.') }}</td>
            <td>{{ number_format($produto->preco_custo??0, 2, ',', '.')  }}</td>
            <td>{{ $produto->taxa_imposto->valor??0 }}</td>
            <td>{{ $produto->total_produto_loja_activa() }}</td>
            <td>{{ $produto->status ?? 0 }}</td>
            <td>{{ $produto->lote_valicidade ?? 0 }}</td>
        </tr>
        @endforeach

    </tbody>
</table>
