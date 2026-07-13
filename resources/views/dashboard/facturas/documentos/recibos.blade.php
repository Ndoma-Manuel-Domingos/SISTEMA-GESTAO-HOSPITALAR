@extends('layouts.pdf')

@section('pdf-content')

    <table style="border: 0">
        <tr>
            <td style="border: 0;">
                <img src="{{ $logotipo }}" alt="Logotipo da Empresa" style="text-align: center;height: 100px;width: 170px;">
            </td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0;padding: 20px 0">{{ $LOJAACTIVAOPERADOR->nome }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>NIF: </strong>{{ $LOJAACTIVAOPERADOR->nif }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Endereço: </strong>{{ $LOJAACTIVAOPERADOR->morada }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>{{ $empresa_logada->empresa->cidade }} -
                    {{ $empresa_logada->empresa->pais }}</strong></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
        </thead>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Factura</th>
                <th>Referente</th>
                <th> {{ __('messages.clientes') }} </th>
                <th> {{ __('messages.data') }} </th>
                <th>Vencimento</th>
                <th style="text-align: right">Dívida</th>
            </tr>
        </thead>
        <tbody>
            @if ($facturas)
                @foreach ($facturas as $item)
                    <tr>
                        <td>{{ $item->factura_next }}</td>
                        <td>{{ $item->facturas->factura_next }}</td>
                        <td>{{ $item->cliente->nome }}</td>
                        <td>{{ $item->data_emissao }}</td>
                        <td>{{ $item->data_vencimento }}</td>
                        <td style="text-align: right">{{ number_format($item->valor_total, 2, ',', '.') }}
                            {{ $empresa_logada->empresa->moeda ?? 'KZ' }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>


@endsection
