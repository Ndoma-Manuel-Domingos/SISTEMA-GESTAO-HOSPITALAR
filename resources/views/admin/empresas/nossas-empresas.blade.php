@extends('layouts.pdf')

@section('pdf-content')

<header>
    <table style="border: 0">
        <tr style="border: 0">
            <td style="border: 0">EA VIEGAS - COMERCIO GERAL E PRESTAÇAO DE SERVIÇOS , LDA</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>NIF: </strong>5000987670</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Endereço: </strong>Rua Cónego Manuel das Neves</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Luanda - ANGOLA</strong></td>
        </tr>
    </table>
</header>


<table>
    <thead>
        <tr>
            <th style="text-transform: uppercase">#</th>
            <th style="text-transform: uppercase">Código</th>
            <th style="text-transform: uppercase">NIF</th>
            <th style="text-transform: uppercase">{{ __('messages.designacao') }}</th>
            <th style="text-transform: uppercase">Tipo</th>
            <th style="text-transform: uppercase">{{ __('messages.estados') }}</th>
            <th style="text-transform: uppercase"> {{ __('messages.telemovel') }} </th>
            <th style="text-transform: uppercase">{{ __('messages.data_inicio') }}</th>
            <th style="text-transform: uppercase">{{ __('messages.data_final') }}</th>
            <th style="text-transform: uppercase">Licença</th>
            @if ($user->level == '3')
            <th style="text-transform: uppercase">Controlo</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($empresas as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->id ?? "" }}</td>
            <td>{{ $item->nif }}</td>
            <td>{{ $item->nome ?? "" }}</td>
            @if ($item->tipo_entidade)
            <td><span class="badge badge-light-primary">{{ $item->tipo_entidade ? $item->tipo_entidade->tipo : '"' }}</span></td>
            @else
            <td><span class="badge badge-light-primary">Comerciante</span></td>
            @endif
            <td class="text-uppercase">{{ $item->status }}</td>
            <td>{{ $item->telefone ?? '000 000 000' }}</td>
            <td>{{ $item->controle->inicio }}</td>
            <td>{{ $item->controle->final }}</td>

            @if ($item->dias_licencas($item->id) > 30)
            <td class="text-light-success">Faltam {{ $item->dias_licencas($item->id) }} dias </td>
            @else
            <td class="text-light-danger">Faltam {{ $item->dias_licencas($item->id) }} dias </td>
            @endif

            @if ($user->level == '3')
            <td><a href="{{ route('empresas.controlo', $item->id) }}" title="Mudar para o controlo de Ndoma" class="text-light-primary">{{ $item->level == 2 ? 'Eluwidy' : 'Ndoma' }}</a></td>
            @endif
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="padding: 4px">{{ __('messages.total') }}: {{ count($empresas) }}</th>
        </tr>
    </tfoot>
</table>


@endsection
