<table>
    <thead>
        <tr>
            <th colspan="8" style="text-transform: uppercase;padding: 3px;border:1px solid #000;width: 120px;background: #006699;color: #ffffff"> {{ $titulo }}</th>
        </tr>
        <tr>
            <th colspan="2" style="text-transform: uppercase;border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.data_inicio') }}</th>
            <th colspan="2" style="text-transform: uppercase;border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.data_final') }}</th>
            <th colspan="2" style="text-transform: uppercase;border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Loja</th>
            <th colspan="2" style="text-transform: uppercase;border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.designacao') }}</th>
        </tr>

        <tr>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $loja ? $loja->nome : 'TODOS' }}</th>
            <th colspan="2">{{ $produto ? $produto->nome : 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">ID</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.codigo_barras') }}</th>
            <th style="border:1px solid #000;width: 170px;background: #006699;color: #ffffff">{{ __('messages.designacao') }}</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff"><span class="float-right">{{ __('messages.preco') }}.</span></th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff"><span class="float-right">{{ __('messages.quantidade') }}</span></th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff"> {{ __('messages.data') }} </th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Operação</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.lojas') }}</th>
            <th colspan="2" style="border:1px solid #000;width: 270px;background: #006699;color: #ffffff">{{ __('messages.observacao') }}</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($movimentos as $key => $movimento)
        <tr>
            <td style="padding: 3px;">{{ $key + 1 }}</td>
            <td style="padding: 3px">{{ $movimento->produto->codigo_barra }}</td>
            <td style="padding: 3px">{{ $movimento->produto->nome }}</td>
            <td style="padding: 3px;text-align: right"><span class="float-right text-light-success">{{ number_format($movimento->preco_unitario, 2, ',', '.') }}</span> </td>
            <td style="padding: 3px;text-align: right"><span class="float-right text-light-success">{{ $movimento->produto->converterDaBase($movimento->quantidade, $movimento->produto->unidade) }} {{ $movimento->produto->unidade->sigla }}</span> </td>
            <td style="padding: 3px">{{ date_format($movimento->created_at, 'Y-m-d') }} <br>
                <small>{{ date_format($movimento->created_at, 'h:i:s') }}</small>
            </td>
            <td style="padding: 3px">{{ $movimento->registro }} <br>
                <small class="text-light-secondary">{{ $movimento->user->name }}</small>
            </td>
            <td style="padding: 3px">{{ $movimento->loja->nome }}</td>
            <td colspan="2">{{ $movimento->observacao }}</td>
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="9" style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.total') }}: <span style="float: right">{{ count($movimentos) }}</span> </th>
        </tr>
    </tfoot>
</table>
