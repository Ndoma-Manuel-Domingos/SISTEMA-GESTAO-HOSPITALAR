@extends('layouts.pdf')

@section('pdf-content')

    <table style="border: 0">
        <tr>
            <td style="border: 0;">
                {{-- <img src="{{ $logotipo }}" alt="Logotipo da Empresa" style="text-align: center;height: 100px;width: 170px;"> --}}
            </td>
        </tr>
        <tr style="border: 0">
            <td style="padding: 20px 0;border: 0">{{ $LOJAACTIVAOPERADOR->nome }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>NIF: </strong>{{ $LOJAACTIVAOPERADOR->nif }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Endereço: </strong>{{ $LOJAACTIVAOPERADOR->morada }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>{{ $empresa_logada->empresa->cidade }} - {{ $empresa_logada->empresa->pais }}</strong></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
            </tr>
            <tr>
                <th colspan="8" style="text-transform: uppercase">REFERÊNCIA: {{ $operacao->nome }}</th>
                <th colspan="2" style="text-transform: uppercase;text-align: right">DATA: {{ $operacao->date_at }}</th>
            </tr>
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th style="text-align: left"> {{ __('messages.fornecedores') }} </th>
                <th style="text-align: left"> {{ __('messages.clientes') }} </th>
                <th style="text-align: left">Centro Custo</th>
                <th style="text-align: left">Operador</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left">{{ $operacao->fornecedor->nome ?? '-' }}</td>
                <td style="text-align: left">{{ $operacao->cliente->nome ?? '-' }}</td>
                <td style="text-align: left">{{ $operacao->centro_custo->nome ?? '-' }}</td>
                <td style="text-align: left">{{ $operacao->user->name ?? '-' }}</td>
            </tr>
        </tbody>

        {{-- --------------------------------------------- --}}
        <thead>
            <tr>
                <th style="text-align: left" colspan="4">REFERENTE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @if ($operacao->dispesa)
                    <td style="text-align: left" colspan="4">{{ $operacao->dispesa->nome ?? '' }}</td>
                @else
                    @if ($operacao->receita)
                        <td style="text-align: left" colspan="4">{{ $operacao->receita->nome ?? '' }}</td>
                    @endif
                @endif
            </tr>
        </tbody>

        {{-- ------------------------------------------ --}}
        <thead>
            <tr>
                <th style="text-align: left" colspan="4"> {{ __('messages.descricao') }}  </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left" colspan="4">{{ $operacao->descricao ?? '-' }}</td>
            </tr>
        </tbody>
        {{-- ------------------------------------------ --}}
        <thead>
            <tr>
                <th style="text-align: left">Forma de Entrada/Saída</th>
                <th style="text-align: right" colspan="3">Motante Entrada/Saída</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($itemOperacoes as $item)
                @php
                    $total += $item->motante;
                @endphp
                <tr>
                    <td>{{ $item->subconta->numero ?? '' }} - {{ $item->subconta->nome ?? '' }}</td>
                    <td style="text-align: right" colspan="3">{{ number_format($item->motante ?? 0, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th style="text-align: right">{{ __('messages.total') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: right">{{ number_format($total ?? 0, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

@endsection
