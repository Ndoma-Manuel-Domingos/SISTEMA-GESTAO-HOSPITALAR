@foreach ($consulta->items as $item)
<tr>
    <td>{{ $item->id ?? "" }}</td>
    <td>{{ $item->produto->nome ?? "" }}</td>
    <td>{{ $item->produto ? $item->produto->categoria->categoria : "" }}</td>
    <td>________</td>
</tr>
@endforeach

<tr>
    <td colspan="">CONSULTA Nº: <a href="{{ route('consultas.show', $consulta->id) }}">{{ $consulta->id }} </a></td>
    <td colspan=""><strong>Diagnosticado:</strong> {{ $consulta->diagnosticado }} </td>
    <td colspan=""><strong>O que foi Avalido:</strong> {{ $consulta->avaliado }} </td>
    <td colspan=""><strong>{{ __('messages.observacao') }}:</strong> {{ $consulta->observacao }} </td>
</tr>

<tr>
    <td colspan=""><strong>Historico médico:</strong> {{ $consulta->historico_medico }} </td>
    <td colspan=""><strong>Exame Médico:</strong> {{ $consulta->exame_medico }} </td>
    <td colspan=""><strong>Alergias conhecidas:</strong> {{ $consulta->alergias_conhecidas }} </td>
    <td colspan=""><strong>Anotações Gerais:</strong> {{ $consulta->anotacoes_gerais }} </td>
</tr>

<tr>
    <td colspan=""><strong>Queixa principal:</strong> {{ $consulta->queixa_principal }} </td>
    <td colspan=""><strong>Historia doença actual:</strong> {{ $consulta->historia_doenca_actual }} </td>
    <td colspan=""><strong>Data E Hora da Consulta:</strong> {{ $consulta->data_consulta }} {{ $consulta->hora_consulta }}</td>
    <td colspan=""><strong>CIDs:</strong> {{ $consulta->cids->nome ?? '' }} </td>
</tr>

@foreach ($consulta->items as $it)
@foreach ($it->paramentos_consultas as $paramentos_consulta)
@if ($paramentos_consulta->paramentro->tipo !== "imagem")
<tr>
    <td colspan="2"><strong>{{ $paramentos_consulta->paramentro->nome }}:</strong></td>
    <td colspan="2">{{ $paramentos_consulta->valor }} </td>
</tr>
@endif
@endforeach

@foreach ($it->paramentos_consultas_imagem as $paramentos_consulta_imagem)
@if ($paramentos_consulta_imagem->paramentro->tipo === "imagem")
@php
$ficheiros = json_decode($paramentos_consulta_imagem->ficheiro, true) ?? [];
@endphp
<tr>
    <td colspan="4">
        @if(count($ficheiros))
        <div class="row">
            @foreach($ficheiros as $ficheiro)
            <div class="col-md-2 mb-3">
                <a href="{{ asset($ficheiro) }}" target="_blank">
                    <img src="{{ asset($ficheiro) }}" class="img-fluid rounded border" style="height:120px;width:100%;object-fit:cover;">
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </td>
</tr>
@endif
@endforeach
@endforeach
