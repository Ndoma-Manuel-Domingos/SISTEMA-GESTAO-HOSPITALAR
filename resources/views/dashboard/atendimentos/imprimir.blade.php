@extends('layouts.layout-relatorio')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Codigo</th>
            <th>Paciente</th>
            <th>{{ __('messages.idade') }}</th>
            <th>Priodidade</th>
            <th>Tipo Atendimento</th>
            <th>{{ __('messages.estados') }}</th>
            <th> {{ __('messages.data') }} </th>
        </tr>
    </thead>
    @foreach ($atendimentos as $key => $item)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $item->id ?? "" }}</td>
        <td>{{ $item->paciente->nome ?? "" }}</td>
        <td>{{ $item->paciente->idade($item->paciente->data_nascimento) }}</td>
        <td>{{ $item->prioridade->nome ?? "" }}</td>
        <td>{{ $item->tipo->nome ?? "" }}</td>
        <td>{{ $item->status }}</td>
        <td>{{ $item->created_at->format("Y-m-d") }}</td>
    </tr>
    @endforeach
</table>
<br>
<strong>Total de Registros:</strong> {{ count($atendimentos) }}
@endsection
