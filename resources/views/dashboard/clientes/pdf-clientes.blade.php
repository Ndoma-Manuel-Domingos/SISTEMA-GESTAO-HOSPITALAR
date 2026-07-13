@extends('layouts.layout-relatorio')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Conta</th>
            <th>Código</th>
            <th>Nome</th>
            <th>Sexo</th>
            <th>Nascimento</th>
            <th>BI</th>
            <th>Telefone</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->conta }}</td>
            <td>{{ $item->codigo ?? 'PAC-'.$item->id }}</td>
            <td>{{ $item->nome ?? "" }}</td>
            <td>{{ $item->genero ?? '-' }}</td>
            <td>{{ $item->data_nascimento ?? '-' }}</td>
            <td>{{ $item->nif ?? '-' }}</td>
            <td>{{ $item->telefone ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<br>
<strong>Total de Registros:</strong> {{ count($clientes) }}
@endsection
