<table class="table table-hover text-nowrap" id="carregar_tabela">
    <thead>
        <tr>
            <th colspan="5" style="border:1px solid #000;background: #006699;color: #ffffff">{{ $titulo }}</th>
        </tr>
        <tr>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Ref</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Operador</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">E-mail</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">Administrador</th>
            <th style="border:1px solid #000;width: 120px;background: #006699;color: #ffffff">{{ __('messages.total') }}</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach ($vendas as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->user->name }}</td>
            <td>{{ $item->user->email }}</td>
            <td>{{ $item->user->is_admin == 1 ? "Sim" : "Não" }}</td>
            <td class="text-right">{{ number_format($item->total_valor, 2, ',', '.') }}</td>
        </tr>
        @php $total += $item->total_valor; @endphp
        @endforeach

    </tbody>
    <tfoot>
        <tr>
            <td style="border:1px solid #000;width: 120px;background: #000000;color: #ffffff" colspan="4">{{ __('messages.total') }}</td>
            <td style="border:1px solid #000;width: 120px;background: #000000;color: #ffffff">{{ number_format($total ?? 0, 2, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
