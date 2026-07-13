@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('funcionarios.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md.12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('ficha-funcionario', $funcionario->id) }}" target="blink" class="btn btn-light-danger"><i class="fas fa-file-pdf"></i> Ficha do Funcionário</a>
                            <a href="{{ route('carregar-foto-funcionario', $funcionario->id) }}" class="btn btn-outline-dark"><i class="fas fa-image"></i> Carregar Foto do Funcionário</a>
                            <a href="{{ route('show.cartao_funcionario', [$funcionario->id, 'horizontal']) }}" class="btn btn-light-primary"><i class="fas fa-cart"></i> Ver Cartão do Funcionário</a>

                            @if ($empresa_logada->empresa->tipo_entidade->sigla == "CFOR")
                            <a href="{{ route('turma-adicionar-formador', $funcionario->id) }}" class="btn btn-light-primary btn-sm">Adicionar a Turma</a>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-2">
                                    <div class="row">
                                        <div class="col-12 col-md-12 mb-3">
                                            @if ($funcionario->foto == null)
                                            <img src="{{ asset('images/funcionarios/user.png') }}" alt="Foto do Funcionário" style="height: 270px;width: 270px; object-fit: cover; border:1px solid #ccc; border-radius:5px;padding: 40px;border-radius: 30px">
                                            @else
                                            <img src="{{ asset('images/funcionarios/' . $funcionario->foto) }}" alt="Foto do Funcionário" style="height: 290px;width: 290px; object-fit: cover; border:1px solid #ccc; border-radius:5px;border-radius: 30px">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-10">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>{{ __('messages.designacao') }}</th>
                                                        <td class="text-right">{{ $funcionario->nome ?? '-------------' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th> {{ __('messages.genero') }} </th>
                                                        <td class="text-right">{{ $funcionario->genero ?? '-------------' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.data_nascimento') }}</th>
                                                        <td class="text-right">
                                                            {{ $funcionario->data_nascimento ?? '-------------' }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <table class="table text-nowrap">
                                                <tbody>

                                                    <tr>
                                                        <th>País</th>
                                                        <td class="text-right">{{ $funcionario->pais ?? '-------------' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.estado_civil') }}</th>
                                                        <td class="text-right">
                                                            {{ $funcionario->estado_civil->nome ?? '-------------' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th> {{ __('messages.bilhete_identidade') }} </th>
                                                        <td class="text-right">{{ $funcionario->nif ?? '-------------' }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Tipo Pessoal</th>
                                                        <td class="text-right">{{ $funcionario->categoria ?? '-------------' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Nome do Pai & Mãe</th>
                                                        <td class="text-right">{{ $funcionario->nome_do_pai ?? '-------------' }} & {{ $funcionario->nome_da_mae ?? '-------------' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Seguradora</th>
                                                        <td class="text-right">
                                                            {{ $funcionario->seguradora->nome ?? '-------------' }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>

                                            <div id="card" style="
                                                  width: {{ $template->width }}px;
                                                  height: {{ $template->height }}px;
                                                  background: {{ $template->background_color }};
                                                  font-family: {{ $template->font_family }};
                                                  color: {{ $template->text_color }};
                                                  font-size: {{ $template->font_size }};
                                                  display:flex;
                                                  align-items:center;
                                                  justify-content:space-between;
                                                  border:1px solid #ccc;
                                                  padding:10px;
                                            ">
                                                <div style="width:30%;">
                                                    <img src="{{ $funcionario->foto ? asset('images/funcionarios/'.$funcionario->foto) : 'https://via.placeholder.com/120x160?text=Foto' }}" alt="foto" style="max-width:100%; height:auto;">
                                                </div>

                                                <div style="width:40%;">
                                                    <div style="font-weight:700;">{{ $funcionario->nome }}</div>
                                                    <div>{{ $funcionario->contrato->cargo->nome }}</div>
                                                    <div>MEC: {{ $funcionario->numero_mecanografico }}</div>
                                                    <div>Validade: {{ optional($funcionario->created_at)->format('Y-m-d') }}</div>
                                                </div>

                                                <div style="width:25%; text-align:center;">
                                                    <div style="max-width: 50%; height:auto;">
                                                        {!! $qrCode !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <a href="#" id="printBtn" class="btn btn-light-primary">Imprimir</a>
                                                <a href="#" id="downloadBtn" class="btn btn-light-success">Download PNG</a>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Morada</th>
                                                        <th>Províncias</th>
                                                        <th>Município</th>
                                                        <th>Distrito</th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $funcionario->morada ?? '-------------' }} <br>{{ $funcionario->codigo_postal ?? '-------------' }} </td>
                                                        <td>{{ $funcionario->provincia->nome ?? '-------------' }}</td>
                                                        <td>{{ $funcionario->municipio->nome ?? '-------------' }}</td>
                                                        <td>{{ $funcionario->distrito->nome ?? '-------------' }}</td>
                                                    </tr>
                                                    {{-- -------------------------------------------- --}}
                                                    <tr>
                                                        <th colspan="4">Contactos</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"> {{ __('messages.telemovel') }} </td>
                                                        <td colspan="2"> {{ __('messages.telemovel') }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">{{ $funcionario->telefone ?? '-------------' }}</td>
                                                        <td colspan="2">{{ $funcionario->telemovel ?? '-------------' }}</td>
                                                    </tr>
                                                    {{-- -------------------------------------------- --}}
                                                    <tr>
                                                        <th colspan="4">Contactos</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"> {{ __('messages.data_nascimento') }}</td>
                                                        <td colspan="2">Website</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">{{ $funcionario->email ?? '-------------' }}</td>
                                                        <td colspan="2">{{ $funcionario->website ?? '-------------' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th colspan="4">{{ __('messages.observacao') }}</th>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="4">{{ $funcionario->observacao ?? '-------------' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ( $contrato )
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Dados do Contrato</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Contrato Nº </th>
                                                <td class="text-right">{{ $contrato->numero ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Tipo Contrato</th>
                                                <td class="text-right">{{ $contrato->tipo_contrato->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.categoria') }}</th>
                                                <td class="text-right"> {{ $contrato->cargo->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.categoria') }}</th>
                                                <td class="text-right"> {{ $contrato->categoria->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Tipo Funcionário</th>
                                                <td class="text-right"> {{ $funcionario->tipo_funcionario->nome ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('messages.data_inicio') }} & {{ __('messages.data_final') }}</th>
                                                <td class="text-right">{{ $contrato->data_inicio ?? '-------------' }} - {{ $contrato->data_final ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Hora Entrada & Saída </th>
                                                <td class="text-right">{{ $contrato->hora_entrada ?? '-------------' }} - {{ $contrato->hora_saida ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('messages.estados') }}</th>
                                                <td class="text-right"> {{ $contrato->status ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Salário Base</th>
                                                <td class="text-right"> {{ number_format($contrato->salario_base ?? 0, 2, ',', '.' ) ?? '-------------' }} AKZ</td>
                                            </tr>
                                            <tr>
                                                <th>Forma Pagamento</th>
                                                <td class="text-right"> {{ $contrato->forma_pagamento->titulo ?? "" }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Subsídio de Natal</th>
                                                <th>Subsídio de Ferias</th>
                                            </tr>

                                            <tr>
                                                <td class="text-left">{{ number_format($contrato->subsidio_natal ?? 0, 1, ',', '.' ) }} % => {{ number_format(($contrato->salario_base * ($contrato->subsidio_natal / 100)) ?? 0, 2, ',', '.' ) ?? '-------------' }} AKZ</td>
                                                <td class="text-left">{{ number_format($contrato->subsidio_ferias ?? 0, 1, ',', '.' ) }} % => {{ number_format(($contrato->salario_base * ($contrato->subsidio_ferias / 100)) ?? 0, 2, ',', '.' ) ?? '-------------' }} AKZ</td>
                                            </tr>

                                            <tr>
                                                <th class="text-left" colspan="2">Mês Pagamento & Forma Pagamento</th>
                                            </tr>

                                            <tr>
                                                <td class="text-left">{{ $contrato->descricao_mes($contrato->mes_pagamento_natal) }} - {{ $contrato->forma_pagamento_subcidio($contrato->forma_pagamento_natal) }}</td>
                                                <td class="text-left">{{ $contrato->descricao_mes($contrato->mes_pagamento_ferias) }} - {{ $contrato->forma_pagamento_subcidio($contrato->forma_pagamento_ferias) }}</td>
                                            </tr>

                                            <tr>
                                                <th>Dias Processamentos:</th>
                                                <td class="text-right">{{ $contrato->dias_processamentos($contrato->dias_processamento) }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th colspan="{{ 1 * count($contrato->subsidios_contrato) }}"> OUTROS SUBSÍDIOS</th>
                                            </tr>
                                            @foreach ($contrato->subsidios_contrato as $item)
                                            <tr>
                                                <th>{{ $item->subsidio->nome ?? "" }}</th>
                                                <td class="text-right">{{ number_format($item->salario ?? 0, 1, ',', '.' ) ?? 0 }} - AKZ</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th colspan="{{ 1 * count($contrato->descontos_contrato) }}"> OUTROS DESCONTOS</th>
                                            </tr>
                                            @foreach ($contrato->descontos_contrato as $item)
                                            <tr>
                                                <th>{{ $item->desconto->nome ?? "" }}</th>
                                                <td class="text-right">{{ number_format($item->salario ?? 0, 1, ',', '.' ) ?? 0 }} - AKZ</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script src="https://unpkg.com/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
    document.getElementById('printBtn').addEventListener('click', () => {
        const card = document.getElementById('card');
        const w = window.open('', 'PRINT', 'height=600,width=800');
        w.document.write('<html><head><title>Cartão</title>');
        w.document.write('</head><body>');
        w.document.write(card.outerHTML);
        w.document.write('</body></html>');
        w.document.close();
        w.focus();
        w.print();
        w.close();
    });

    document.getElementById('downloadBtn').addEventListener('click', () => {
        html2canvas(document.getElementById('card')).then(canvas => {
            const link = document.createElement('a');
            link.download = 'cartao_{{ $funcionario->numero_mecanografico }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });

</script>
@endsection
