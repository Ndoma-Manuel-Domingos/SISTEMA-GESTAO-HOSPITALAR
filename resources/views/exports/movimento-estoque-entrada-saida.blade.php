<table>
    <thead>
        <tr>
            <th colspan="6" style="text-transform: uppercase;border:1px solid #000;background: #006699;color: #ffffff"> {{ $titulo }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th colspan="3" style="text-transform: uppercase;border:1px solid #000;background: #006699;color: #ffffff">{{ __('messages.data_inicio') }}</th>
            <th colspan="3" style="text-transform: uppercase;border:1px solid #000;background: #006699;color: #ffffff">{{ __('messages.data_final') }}</th>
        </tr>

        <tr>
            <th colspan="3" style="text-transform: uppercase;border:1px solid #000;background: #006699;color: #ffffff"> {{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="3" style="text-transform: uppercase;border:1px solid #000;background: #006699;color: #ffffff"> {{ $requests['data_final'] ?? 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="width:120px;border: 1px solid #010101;padding: 2px;border:1px solid #000;background: #006699;color: #ffffff">Id</th>
            <th style="width:120px;border: 1px solid #010101;padding: 2px;border:1px solid #000;background: #006699;color: #ffffff">Sigla</th>
            <th style="width:120px;border: 1px solid #010101;padding: 2px;border:1px solid #000;background: #006699;color: #ffffff">{{ __('messages.clientes') }}</th>
            <th style="width:120px;border: 1px solid #010101;padding: 2px;border:1px solid #000;background: #006699;color: #ffffff">{{ __('messages.fornecedores') }}</th>
            <th style="width:120px;border: 1px solid #010101;padding: 2px;border:1px solid #000;background: #006699;color: #ffffff">{{ __('messages.tipo') }}</th>
            <th style="width:120px;border: 1px solid #010101;padding: 2px;border:1px solid #000;background: #006699;color: #ffffff">Referência</th>
            <th style="width:120px;border: 1px solid #010101;padding: 2px;border:1px solid #000;background: #006699;color: #ffffff">{{ __('messages.data') }}</th>
            <th style="width:300px;border: 1px solid #010101;padding: 2px;border:1px solid #000;background: #006699;color: #ffffff">Observação</th>
            <th style="width:300px;border: 1px solid #010101;padding: 2px;border:1px solid #000;background: #006699;color: #ffffff">{{ __('messages.total') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($movimentos as $key => $item)
        <tr>
            <td style="padding: 3px;text-align: left;border-bottom: 1px solid #000000;border-top: 1px solid #000000;">{{ $key + 1 }}</td>
            <td style="padding: 3px;text-align: left;border-bottom: 1px solid #000000;border-top: 1px solid #000000;">{{ $item->sigla }}</td>
            <td style="padding: 3px;text-align: left;border-bottom: 1px solid #000000;border-top: 1px solid #000000;">{{ $item->cliente->nome ?? "" }}</td>
            <td style="padding: 3px;text-align: left;border-bottom: 1px solid #000000;border-top: 1px solid #000000;">{{ $item->fornecedor->nome ?? "" }}</td>
            <td style="padding: 3px;text-align: left;border-bottom: 1px solid #000000;border-top: 1px solid #000000;">{{ $item->tipo_documento }}</td>
            <td style="padding: 3px;text-align: left;border-bottom: 1px solid #000000;border-top: 1px solid #000000;">{{ $item->codigo  }}</td>
            <td style="padding: 3px;text-align: left;border-bottom: 1px solid #000000;border-top: 1px solid #000000;">{{ $item->created_at  }}</td>
            <td style="padding: 3px;text-align: left;border-bottom: 1px solid #000000;border-top: 1px solid #000000;">{{ $item->observacao  }}</td>
            <td style="padding: 3px;text-align: left;border-bottom: 1px solid #000000;border-top: 1px solid #000000;">{{ number_format($item->total ?? 0, 2, ',', '.')  }}</td>
        </tr>
        <tr>
            <th style="padding: 3px;text-align: left;">{{ __('messages.codigo_barras') }}</th>
            <th style="padding: 3px;text-align: left;" colspan="4">{{ __('messages.produtos') }}</th>
            <th style="padding: 3px;text-align: left;">{{ __('messages.quantidade') }}</th>
            <th style="padding: 3px;text-align: left;">{{ __('messages.preco_custo') }}</th>
            <th style="padding: 3px;text-align: left;">{{ __('messages.lotes') }}</th>
            <th style="padding: 3px;text-align: left;">{{ __('messages.total') }}</th>
        </tr>
        @foreach ($item->items as $i)
        <tr>
            <td style="padding: 3px;text-align: left;">{{ $i->produto->codigo_barra }}</td>
            <td style="padding: 3px;text-align: left;" colspan="4">{{ $i->produto->nome }}</td>
            <td style="padding: 3px;text-align: left;">{{ number_format($i->quantidade, 2, ',', '.')  }}</td>
            <td style="padding: 3px;text-align: left;">{{ number_format($i->produto->preco_custo, 2, ',', '.') }}</td>
            <td style="padding: 3px;text-align: left;">{{ $i->lote->lote }}</td>
            <td style="padding: 3px;text-align: left;">{{ number_format($i->produto->preco_custo * $i->quantidade, 2, ',', '.') }}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>

</table>
