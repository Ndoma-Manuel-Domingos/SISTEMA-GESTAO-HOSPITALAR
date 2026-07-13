@foreach ($dados as $conta)
    @foreach ($conta->subcontas as $item)
        <tr>
            <td style="padding-left: 80px">{{ $item->numero }} - {{ $item->nome }}</td>
            <td class="text-right">
                @php $deb = $cred = 0; @endphp
                @foreach ($item->movimentos as $mov) @php $cred += $mov->credito; $deb += $mov->debito; @endphp @endforeach
                @if ($cred > $deb)
                    @if (($cred - $deb) == 0)
                    -
                    @else    
                    {{ number_format($cred - $deb, 2, ',', '.') }}
                    @endif
                @else
                    @if ($deb > $cred)
                        @if (($deb - $cred) == 0)
                        -
                        @else
                        {{ number_format($deb - $cred, 2, ',', '.') }}
                        @endif
                    @else
                    {{ number_format(0, 2, ',', '.') }}
                    @endif
                @endif
            </td>
            <td class="text-right">0</td>
        </tr>
    @endforeach
@endforeach
