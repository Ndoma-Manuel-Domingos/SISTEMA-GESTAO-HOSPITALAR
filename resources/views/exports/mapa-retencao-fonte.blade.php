<table id="carregar_tabela">
    <tr>
        <th colspan="6" style="border:1px solid #000;background: #006699;color: #ffffff">{{ $titulo }}</th>
    </tr>
    <thead>
        <tr>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Ref</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Codigo</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Produto</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Categoria</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Total Documento</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Total Retido</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach ($vendas as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->produto->codigo_barra ?? "" }}</td>
            <td>{{ $item->produto->nome ?? "" }}</td>
            <td>{{ $item->produto->categoria->categoria ?? "" }}</td>
            <td class="text-right">{{ $item->total_valor_pagar ?? 0 }}</td>
            <td class="text-right">{{ $item->total_retencao_fonte ?? 0 }}</td>
        </tr>
        @php $total += $item->total_retencao_fonte; @endphp
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td style="border:1px solid #000;width: 120px;background: #000000;color: #ffffff" colspan="5">{{ __('messages.total') }}</td>
            <td style="border:1px solid #000;width: 120px;background: #000000;color: #ffffff">{{ $total ?? 0 }}</td>
        </tr>
    </tfoot>
</table>
