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

    <table>
        <thead>
            <tr>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_inicio') }}</th>
                <th colspan="2" style="text-transform: uppercase">{{ __('messages.data_final') }}</th>
                <th colspan="2" style="text-transform: uppercase"> {{ __('messages.clientes') }} </th>
            </tr>

            <tr>
                <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
                <th colspan="2">{{ $cliente ? $cliente->nome : 'TODOS' }}</th>
            </tr>
        </thead>
    </table>

    @if ($dadosClientes)
        <!-- /.card-header -->
        <table>
            <tbody>
                @foreach ($dadosClientes as $clienteData)
                    <tr>
                        <td style="text-align: left;text-transform: uppercase;background-color: #aeaeae" colspan="7">
                            {{ $clienteData->codigo }} - {{ $clienteData->cliente }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><strong>Artigo</strong></td>
                        <td style="text-align: left"><strong> {{ __('messages.descricao') }}  </strong></td>
                        <td style="text-align: right"><strong> {{ __('messages.quantidade') }} </strong></td>
                        <td style="text-align: right"><strong>{{ __('messages.total') }}</strong></td>
                        <td style="text-align: right"><strong>Total Descontos</strong></td>
                        <td style="text-align: right"><strong>Custo</strong></td>
                        <td style="text-align: right"><strong>Lucro</strong></td>
                    </tr>
                    @php
                        $quantidade = 0;
                        $valor_pagar = 0;
                        $desconto_aplicado_valor = 0;
                        $custo = 0;
                        $custo_ = 0;
                    @endphp
                    @foreach ($clienteData->produtos as $produto)
                        <tr>
                            <td>#</td>
                            <td style="text-transform: uppercase;">{{ $produto['produto'] }}</td>
                            <td style="text-align: right">{{ number_format($produto['quantidade'], 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($produto['valor_pagar'], 2, ',', '.') }}</td>
                            <td style="text-align: right">
                                {{ number_format($produto['desconto_aplicado_valor'], 2, ',', '.') }}</td>
                            <td style="text-align: right">
                                {{ number_format($produto['custo'] * $produto['quantidade'], 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($produto['custo'] ?? 0, 2, ',', '.') }}
                            </td>
                        </tr>

                        @php
                            $quantidade += $produto['quantidade'];
                            $valor_pagar += $produto['valor_pagar'];
                            $desconto_aplicado_valor += $produto['desconto_aplicado_valor'];
                            $custo += $produto['custo'] * $produto['quantidade'];
                            $custo_ += $produto['custo'];
                        @endphp
                    @endforeach

                    <tr>
                        <td style="text-align: right" colspan="2"><strong>Totais</strong></td>
                        <td style="text-align: right"><strong>{{ number_format($quantidade, 2, ',', '.') }}</strong></td>
                        <td style="text-align: right"><strong>{{ number_format($valor_pagar, 2, ',', '.') }}</strong></td>
                        <td style="text-align: right">
                            <strong>{{ number_format($desconto_aplicado_valor, 2, ',', '.') }}</strong></td>
                        <td style="text-align: right"><strong>{{ number_format($custo, 2, ',', '.') }}</strong></td>
                        <td style="text-align: right"><strong>{{ number_format($custo_, 2, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

@endsection
