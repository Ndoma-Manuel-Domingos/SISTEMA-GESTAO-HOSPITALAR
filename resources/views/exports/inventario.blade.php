<table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
    <thead class="">
        <tr>
            <th colspan="6" style="border:1px solid #000;background: #006699;color: #ffffff">{{ $titulo }}</th>
        </tr>
        <thead>
            <tr>
                <th style="border: 1px solid #010101;padding: 2px;">Descrição</th>
                <th style="border: 1px solid #010101;padding: 2px;">{{ __('messages.codigo_barras') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: center"> Existência</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: center">{{ __('messages.valor') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: center">{{ __('messages.total') }}</th>
            </tr>
        </thead>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach ($produtos as $item)
            @if ($item->produto)
                @php 
                    $stock = $item->produto->converterDaBase($item->produto->total_produto_loja_activa(), $item->produto->unidade);
                    $subtotal = $item->produto->preco_custo * $stock;
                    $total += $subtotal; 
                @endphp
            <tr>
                <td style="padding: 3px;text-align: left">{{ $item->produto->nome ?? "" }}</td>
                <td style="padding: 3px;text-align: left">{{ $item->produto->codigo_barra ?? "" }}</td>
                <td style="padding: 3px;text-align: right"> {{ number_format($stock, 1, ',', '.') }} {{ $item->produto->unidade->sigla }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($item->produto->preco_custo ?? 0, 2, ',', '.') }}</td>
                <td style="padding: 3px;text-align: right">{{ number_format($subtotal, 2, ',', '.') }}</td>
            </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="padding: 3px;text-align: right;font-size: 15px;">{{ number_format($total ?? 0, 2, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
