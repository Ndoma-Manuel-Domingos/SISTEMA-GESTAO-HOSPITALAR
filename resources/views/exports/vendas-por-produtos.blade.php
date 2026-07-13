<table class="table table-hover text-nowrap" id="carregar_tabela">
    <tr>
        <th colspan="8" style="border:1px solid #000;background: #006699;color: #ffffff">{{ $titulo }}</th>
    </tr>
    <thead>
        <tr>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Ref</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.codigo_barras') }}</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.produtos') }}</th>
            {{-- <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Preco Custo</th> --}}
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff"> {{ __('messages.quantidade') }} </th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.preco_venda') }}</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.imposto') }}</th>
            {{-- <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff"> {{ __('messages.quantidade') }} devolvida</th> --}}
            {{-- <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Custo</th> --}}
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Lucro</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.total') }}</th>
        </tr>
    </thead>
    <tbody>
        @php
        $total = 0;
        $total_lucro = 0;
        $total_custo = 0;
        @endphp
        @foreach ($vendas as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->produto->codigo_barra ?? "" }}</td>
            <td>{{ $item->produto->nome ?? "" }}</td>
            {{-- <td>{{ $item->produto->preco_custo ?? 0 }}</td> --}}
            <td class="text-right">{{ $item->total_quantidade ?? 0 }}</td>
            <td>{{ (($item->total_valor ?? 0) / ($item->total_quantidade ?? 0)) }}</td>
            <td>{{ $item->produto->taxa ?? 0 }} %</td>
            {{-- <td class="text-right">{{ $item->total_quantidade_devolvidas ?? 0 }}</td> --}}
            {{-- <td class="text-right">{{ $item->total_custo ?? 0 }}</td> --}}
            <td class="text-right">{{ $item->total_lucro ?? 0 }}</td>
            <td class="text-right">{{ $item->total_valor ?? 0 }}</td>
        </tr>
        @php
        $total_custo += ($item->total_custo);
        $total_lucro += $item->total_lucro;
        $total += $item->total_valor;
        @endphp
        @endforeach

    </tbody>
    <tfoot>
        <tr>
            <td style="border:1px solid #000;width: 120px;background: #000000;color: #ffffff" colspan="6">{{ __('messages.total') }}</td>
            {{-- <td style="border:1px solid #000;width: 120px;background: #000000;color: #ffffff">{{ $total_custo ?? 0 }}</td> --}}
            <td style="border:1px solid #000;width: 120px;background: #000000;color: #ffffff">{{ $total_lucro ?? 0 }}</td>
            <td style="border:1px solid #000;width: 120px;background: #000000;color: #ffffff">{{ $total ?? 0 }}</td>
        </tr>
    </tfoot>
</table>

<table>
    <tbody>
        <tr>
            <th>Lorem ipsum dolor sit amet.</th>
        </tr>
    </tbody>
</table>
