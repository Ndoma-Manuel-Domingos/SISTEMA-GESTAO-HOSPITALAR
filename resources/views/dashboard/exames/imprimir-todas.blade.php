@extends('layouts.layout-relatorio')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Codigo</th>
            <th>Paciente</th>
            <th>{{ __('messages.idade') }}</th>
            <th>Serviços</th>
            <th>Priodidade</th>
            <th>{{ __('messages.estados') }}</th>
            <th>Hora</th>
            <th> {{ __('messages.data') }} </th>
        </tr>
    </thead>
    @foreach ($exames as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->id ?? "" }}</td>
        <td>{{ $item->paciente->nome ?? "" }}</td>
        <td>{{ $item->paciente->idade($item->paciente->data_nascimento) }}</td>
        <td>
            @foreach($item->items as $exame)
            {{ $exame->produto->nome ?? "" }}
            @endforeach
        </td>
        <td>{{ $item->prioridade->nome ?? "" }}</td>
        <td>{{ $item->status }}</td>
        <td>{{ $item->hora_exame }}</td>
        <td>{{ $item->data_exame }}</td>
    </tr>
    @endforeach
</table>
<br>
<strong>Total de Registros:</strong> {{ count($exames) }}
@endsection
