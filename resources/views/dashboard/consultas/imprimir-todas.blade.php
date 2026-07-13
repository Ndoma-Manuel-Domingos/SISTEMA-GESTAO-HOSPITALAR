@extends('layouts.layout-relatorio')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Paciente</th>
            <th>{{ __('messages.idade') }}</th>
            <th>Médico</th>
            <th>Serviços</th>
            <th>{{ __('messages.estados') }}</th>
            <th>Hora</th>
            <th> {{ __('messages.data') }} </th>
        </tr>
    </thead>
    @foreach ($consultas as $key => $item)
    <tbody>
    </tbody>
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->paciente->nome ?? "" }}</td>
        <td>{{ $item->paciente->idade($item->paciente->data_nascimento) }}</td>
        <td>{{ $item->medico->funcionario->nome ?? "" }}</td>
        <td>
            @foreach($item->items as $exame)
            {{ $exame->produto->nome ?? "" }}
            @endforeach
        </td>
        <td>{{ $item->status }}</td>
        <td>{{ $item->hora_consulta }}</td>
        <td>{{ $item->data_consulta }}</td>
    </tr>
    @endforeach

</table>
<br>
<strong>Total de Registros:</strong> {{ count($consultas) }}
@endsection
