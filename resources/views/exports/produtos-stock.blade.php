<table>
    <thead>
        <tr>
            <th colspan="6" style="border:1px solid #000;background: #006699;color: #ffffff">{{ $titulo }}</th>
        </tr>
        <tr>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.codigo_barras') }}</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.designacao') }}</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.quantidade') }}</th>
            @if ($tipo_preco == "PV")
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.preco_venda') }}</th>
            @else
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.preco_custo') }}</th>
            @endif
            
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.imposto') }}</th>
             
            @if ($tipo_preco == "PV")
            <th style="border: 1px solid #010101;padding: 2px;text-align: right">Desc.</th>
            <th style="border: 1px solid #010101;padding: 2px;text-right">Qtd. Vend.</th>
            <th style="border: 1px solid #010101;padding: 2px;text-align: right">T. Liq. Vendido</th>
            @endif
            
            @if ($tipo_preco == "PV")
            <th style="border: 1px solid #010101;padding: 2px;text-align: right">T. Geral</th>
            @else
            <th style="border: 1px solid #010101;padding: 2px;text-align: right">Total</th>
            @endif
            
            @if ($tipo_preco == "PV")
            <th style="border: 1px solid #010101;padding: 2px;text-align: right">Lucro</th>
            <th style="border: 1px solid #010101;padding: 2px;text-align: right">Custo</th>
            @endif
            
        </tr>
    </thead>

    <tbody>

        @php
        $total_liquido_vendido_valor = 0;
        $total_liquido_restante_valor = 0;
        $total_liquido_geral_valor = 0;
        $total_retencao_acumuada = 0;
        $total_liquido_geral = 0;
        $lucro = 0;
        $custo = 0;
        @endphp

        @foreach ($dados as $item)
        <tr>
            <td style="padding: 3px;text-align: left">{{ $item->codigo_barra ?? '' }}</td>
            <td style="padding: 3px;text-align: left">{{ $item->produto ?? '' }}</td>
            <td style="padding: 3px;text-align: right"> {{ number_format($item->quantidade_estoque ?? 0, 2, ',', '.') }}</td>
            @if ($tipo_preco == "PV")
            <td style="padding: 3px;text-align: right">{{ number_format($item->preco ?? 0, 2, ',', '.') }}</td>
            @else
            <td style="padding: 3px;text-align: right">{{ number_format($item->preco_custo ?? 0, 2, ',', '.') }}</td>
            @endif
            <td style="padding: 3px;text-align: right">{{ number_format($item->imposto ?? 0, 2, ',', '.') }}</td>

            @if ($tipo_preco == "PV")
            <td style="padding: 3px;text-align: right">{{ number_format($item->desconto, 2, ',', '.') }}</td>
            <td style="padding: 3px;text-align: right"> {{ number_format($item->quantidade_vendida, 2, ',', '.') }}</td>
            <td style="padding: 3px;text-align: right"> {{ number_format($item->total_liquido_vendido, 2, ',', '.') }}</td>
            @endif
            
            @if ($tipo_preco == "PV")
            <td style="padding: 3px;text-align: right"> {{ number_format($item->preco * $item->quantidade_estoque, 2, ',', '.') }}</td>
            @else
            <td style="padding: 3px;text-align: right"> {{ number_format($item->total_liquido_geral, 2, ',', '.') }}</td>
            @endif
            
            @if ($tipo_preco == "PV")
            <td style="padding: 3px;text-align: right"> {{ number_format($item->total_liquido_lucro, 2, ',', '.') }}</td>
            <td style="padding: 3px;text-align: right"> {{ number_format($item->total_liquido_custo, 2, ',', '.') }}</td>
            @endif
            
            @php
            $total_liquido_vendido_valor += $item->total_liquido_vendido ?? 0;
            $total_liquido_restante_valor += $item->preco * $item->quantidade_estoque ?? 0;
            $total_liquido_geral_valor += $item->total_liquido_geral ?? 0;
            $total_retencao_acumuada += $item->totalRetencaoAcumuada ?? 0;
            $custo += $item->total_liquido_custo ?? 0;
            $lucro += $item->total_liquido_lucro ?? 0;
            $total_liquido_geral += $item->total_liquido_geral ?? 0;
            @endphp
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;">{{ __('messages.total') }}</th>
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
            @if ($tipo_preco == "PV")
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;">---</th>
            @endif

            <th style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($total_liquido_vendido_valor, 2, ',', '.') }}</th>
            
            @if ($tipo_preco == "PV")
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($total_liquido_restante_valor, 2, ',', '.') }}</th>
            @else
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($total_liquido_geral, 2, ',', '.') }}</th>
            @endif
            
            @if ($tipo_preco == "PV")
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($lucro, 2, ',', '.') }}</th>
            <th style="border: 1px solid #010101;padding: 2px;text-align: right;"> {{ number_format($custo, 2, ',', '.') }}</th>
            @endif
        </tr>
    </tfoot>
</table>
