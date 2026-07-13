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
                        <li class="breadcrumb-item"><a href="{{ route('internamentos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Internamento</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-primary card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="dados-consulta-tab" data-toggle="pill" href="#dados-consulta" role="tab" aria-controls="dados-consulta" aria-selected="true">DADOS DA INTERNAMENTO</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="dados-paciente-tab" data-toggle="pill" href="#dados-paciente" role="tab" aria-controls="dados-paciente" aria-selected="false">DADOS DO PACIENTE</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="dados-triagem-tab" data-toggle="pill" href="#dados-triagem" role="tab" aria-controls="dados-triagem" aria-selected="false">DADOS DA TRIAGEM</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="lista-consultas-tab" data-toggle="pill" href="#lista-consultas" role="tab" aria-controls="lista-consultas" aria-selected="false">CONSULTAS SOLICITADAS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="lista-exames-tab" data-toggle="pill" href="#lista-exames" role="tab" aria-controls="lista-exames" aria-selected="false">LISTA DOS EXAMES SOLICITADOS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="lista-evolucao-medica-tab" data-toggle="pill" href="#lista-evolucao-medica" role="tab" aria-controls="lista-evolucao-medica" aria-selected="false">EVOLUÇÃO MÉDICA</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="lista-receitas-tab" data-toggle="pill" href="#lista-receitas" role="tab" aria-controls="lista-receitas" aria-selected="false">RECEITA MÉDICA</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="lista-plano-internamento-medico-tab" data-toggle="pill" href="#lista-plano-internamento-medico" role="tab" aria-controls="lista-plano-internamento-medico" aria-selected="false">PLANO DE INTERNAMENTO MÉDICO</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="dados-consulta" role="tabpanel" aria-labelledby="dados-consulta-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 table-responsive">
                                            <table class=" table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Internamento Nº</th>
                                                        <td class="text-right">{{ $internamento->numero ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th> {{ __('messages.data') }} </th>
                                                        <td class="text-right">{{ $internamento->data_internacao ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Data Alta</th>
                                                        <td class="text-right">{{ $internamento->data_alta ?? "" }}</td>
                                                    </tr>

                                                    @if ($internamento)

                                                    @if ($internamento->status == 'activo')
                                                    <tr>
                                                        <th>{{ __('messages.estados') }}</th>
                                                        <td class="text-right">{{ $internamento->status ?? "" }}</td>
                                                    </tr>
                                                    @endif

                                                    @if ($internamento->status == 'alta')
                                                    <tr>
                                                        <th>{{ __('messages.estados') }}</th>
                                                        <td class="text-right">{{ $internamento->status ?? "" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Resumo da Alta</th>
                                                        <td class="text-right">{{ $internamento->resumo_alta ?? "" }}</td>
                                                    </tr>
                                                    @endif

                                                    @if ($internamento->status == 'obito')
                                                    <tr>
                                                        <th>{{ __('messages.estados') }}</th>
                                                        <td class="text-right">{{ $internamento->status ?? "" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Resumo do Obito</th>
                                                        <td class="text-right">{{ $internamento->resumo_obito ?? "" }}
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    @if ($internamento->status == 'transferido')
                                                    <tr>
                                                        <th>{{ __('messages.estados') }}</th>
                                                        <td class="text-right">{{ $internamento->status ?? "" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Resumo da Transferência</th>
                                                        <td class="text-right">
                                                            {{ $internamento->resumo_transferencia ?? "" }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @endif


                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12 col-md-6  table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Equipa Médica</th>
                                                        <td class="text-right">{{ $internamento->equipa->nome ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Leito</th>
                                                        <td class="text-right">{{ $internamento->leito->nome ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Motivo</th>
                                                        <td class="text-right">{{ $internamento->motivo ?? "" }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Diagnóstico Inicial</th>
                                                        <td class="text-right">
                                                            {{ $internamento->diagnostico_inicial ?? "" }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="dados-paciente" role="tabpanel" aria-labelledby="dados-paciente-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Nome Paciente Nº</th>
                                                        <td class="text-right">{{ $internamento->paciente->nome ?? "" }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.data_nascimento') }}</th>
                                                        <td class="text-right">
                                                            {{ $internamento->paciente->data_nascimento ?? "" }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.idade') }}</th>
                                                        <td class="text-right">
                                                            {{ $internamento->paciente->idade($internamento->paciente->data_nascimento) }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Identificador</th>
                                                        <td class="text-right"><a href="{{ route('clientes.show', $internamento->paciente->id) }}">{{ $internamento->paciente->id ?? "" }}</a>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th> {{ __('messages.telemovel') }} </th>
                                                        <td class="text-right"><a href="{{ route('clientes.show', $internamento->paciente->id) }}">{{ $internamento->paciente->telefone ?? "" }}</a>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="dados-triagem" role="tabpanel" aria-labelledby="dados-triagem-tab">
                                    @if ($internamento->atendimento->triagem)

                                    @include('dashboard.atendimentos._views.detalhe-triagem', ["triagem" => $internamento->atendimento->triagem])
                                    <div class="col-12 col-md-12 text-center">
                                        <a target="_blink" href="{{ route('triangs.triagens-imprimir', $internamento->atendimento->triagem->id) }}" class="h3 py-3 my-5 btn btn-light-primary"><i class="fas fa-file-pdf"></i>
                                            {{ __('messages.imprimir') }} Ficha da Triagem Médica
                                        </a>
                                    </div>
                                    @else
                                    <div class="col-12 col-md-12 text-center">
                                        @if ($internamento->status == 'activo')
                                        @if (Auth::user()->can('criar todos') || Auth::user()->can('criar triagem'))
                                        <a href="{{ route('triagens.create', ['atendimento_id' => $internamento->atendimento_id]) }}" class="h3 py-3 my-5 btn btn-light-primary">Fazer Triagem</a>
                                        @endif
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                <div class="tab-pane fade" id="lista-consultas" role="tabpanel" aria-labelledby="lista-consultas-tab">
                                    <div class="row">
                                        @if (count($internamento->atendimento->consultas) !== 0)
                                        <div class="col-12 col-md-12 table-responsive">
                                            <table class="table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th colspan="6">LISTA DAS CONSULTAS SOLICITADAS</th>
                                                    </tr>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ __('messages.designacao') }}</th>
                                                        <th>{{ __('messages.categoria') }}</th>
                                                        <th class="text-right">---------</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($internamento->atendimento->consultas as $consulta)
                                                    @include('dashboard.atendimentos._views.detalhes-consultas', ["consulta" => $consulta])
                                                    <tr>
                                                        <td colspan="4">
                                                            <a target="_blink" href="{{ route('consultas-imprimir-individual', $consulta->id) }}" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }} </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12 col-md-12 text-center">
                                            @if ($internamento->status == 'activo')
                                            @if (Auth::user()->can('consultorio'))
                                            <a href="{{ route('solicitacoes-medicas.create', ['origem' => 'atendimento', 'atendimento_id' => $internamento->atendimento->id]) }}" class="h3 py-3 my-5 btn btn-light-primary">Solicitar Novas consultas</a>
                                            @endif
                                            @endif
                                            <a target="_blink" href="{{ route('internamentos.imprimir-lista-consultas', $internamento->id) }}" class=" h3 py-3 my-5 btn btn-light-primary"> <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}</a>
                                        </div>
                                        @else
                                        @if ($internamento->status == 'activo')
                                        <div class="col-12 col-md-12 text-center">
                                            <a href="{{ route('solicitacoes-medicas.create', ['origem' => 'atendimento', 'atendimento_id' => $internamento->atendimento->id]) }}" class="h3 py-3 my-5 btn btn-light-primary">Solicitar Novas consultas</a>
                                        </div>
                                        @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="lista-exames" role="tabpanel" aria-labelledby="lista-exames-tab">
                                    @if (count($internamento->atendimento->exames) !== 0)
                                    @foreach ($internamento->atendimento->exames as $resultado)
                                    @include('dashboard.exames._views.detalhe-exame', ["dados" => $resultado, "editar" => false])
                                    @endforeach
                                    @endif

                                    <div class="row">
                                        <div class="col-12 col-md-12 text-center">
                                            @if ($internamento->status == 'activo')
                                            @if (Auth::user()->can('consultorio'))
                                            <a href="{{ route('solicitacoes-medicas.create', ['origem' => 'atendimento', 'atendimento_id' => $internamento->atendimento->id]) }}" class=" h3 py-3 my-5 btn btn-light-primary">Solicitar Novos Exames</a>
                                            @endif
                                            @endif
                                            <a target="_blink" href="{{ route('internamentos.imprimir-lista-exames', $internamento->id) }}" class=" h3 py-3 my-5 btn btn-light-primary"> <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}</a>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="lista-evolucao-medica" role="tabpanel" aria-labelledby="lista-evolucao-medica-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-12 table-responsive">
                                            <table class="table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th colspan="10">EVOLUÇÃO MÉDICA</th>
                                                    </tr>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Tipo</th>
                                                        <th>Data Evolução</th>
                                                        <th>Data E Hora</th>
                                                        <th>{{ __('messages.observacao') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($internamento->evolucao_medica as $item)
                                                    <tr>
                                                        <td>{{ $item->id ?? "" }}</td>
                                                        <td>{{ $item->tipo ?? 'sem registro' }}</td>
                                                        <td>{{ $item->data_evolucao ?? 'sem registro' }}</td>
                                                        <td>{{ $item->created_at ?? 'sem registro' }}</td>
                                                        <td>{{ $item->observacoes ?? 'sem registro' }}</td>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-12 text-center">
                                            @if ($internamento->status == 'activo')
                                            @if (Auth::user()->can('criar evolucao medica'))
                                            <a href="#" onclick="toggleModal()" class=" h3 py-3 my-5 btn btn-light-primary">Actualizar Evolução Médica</a>
                                            @endif
                                            @endif

                                            <a target="_blink" href="{{ route('internamentos.imprimir-evolucao-medica', $internamento->id) }}" class=" h3 py-3 my-5 btn btn-light-primary"> <i class="fas fa-file-pdf"></i>
                                                {{ __('messages.imprimir') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="lista-receitas" role="tabpanel" aria-labelledby="lista-receitas-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-12 text-center">
                                            @if ($internamento->status == 'activo')
                                            @if (Auth::user()->can('consultorio'))
                                            <a href="{{ route('consulta-receita-medica', $internamento->atendimento_id) }}" class=" h3 py-3 my-5 btn btn-light-primary">Nova Receita
                                                Médica</a>
                                            @endif
                                            @endif

                                            <a target="_blink" href="{{ route('internamentos.imprimir-lista-receitas', $internamento->id) }}" class=" h3 py-3 my-5 btn btn-light-primary"> <i class="fas fa-file-pdf"></i>
                                                {{ __('messages.imprimir') }}</a>

                                        </div>
                                        @foreach ($internamento->atendimento->receitas as $receit)
                                        <div class="col-12 col-md-6 table-responsive">
                                            <table class="table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th colspan="5">
                                                            <a target="_blink" href="{{ route('consulta-receitas-medico-imprimir', $receit->id) }}">RECEITA Nº {{ $receit->id ?? ''}}</a>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="5">Observação {{ $receit->observacoes }} </th>
                                                    </tr>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Medicamento</th>
                                                        <th>Posologia</th>
                                                        <th>Duracao dias</th>
                                                        <th>{{ __('messages.observacao') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($receit->items as $item)
                                                    <tr>
                                                        <td>#</td>
                                                        <td>{{ $item->medicamento ?? 'sem registro' }}</td>
                                                        <td>{{ $item->posologia ?? 'sem registro' }}</td>
                                                        <td>{{ $item->duracao_dias ?? 'sem registro' }}</td>
                                                        <td>{{ $item->observacoes ?? 'sem registro' }}</td>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="col-12 col-md-12 text-left">
                                                <a target="_blink" href="{{ route('consulta-receitas-medico-imprimir', $receit->id) }}" class="h3 my-5 btn btn-light-primary">
                                                    <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach

                                    </div>
                                </div>

                                <div class="tab-pane fade" id="lista-plano-internamento-medico" role="tabpanel" aria-labelledby="lista-plano-internamento-medico-tab">
                                    <div class="row">
                                        @if (count($internamento->plano_internamento) != 0)

                                        <div class="col-12 col-md-12 table-responsive">
                                            <table class="table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Medicamento</th>
                                                        <th>Dose</th>
                                                        <th>Via</th>
                                                        <th>Frequência</th>
                                                        <th>Duração</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($internamento->plano_internamento as $item)
                                                    <tr>
                                                        <td>{{ $item->medicamento ?? 'sem registro' }}</td>
                                                        <td>{{ $item->dose ?? 'sem registro' }}</td>
                                                        <td>{{ $item->via ?? 'sem registro' }}</td>
                                                        <td>{{ $item->frequencia ?? 'sem registro' }}</td>
                                                        <td>{{ $item->duracao ?? 'sem registro' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-12 text-center">
                                            <a target="_blink" href="{{ route('internamentos.imprimir-plano-medico-internamento', $internamento->id) }}" class=" h3 py-3 my-5 btn btn-light-primary"> <i class="fas fa-file-pdf"></i>
                                                {{ __('messages.imprimir') }}
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">

                            @if (Auth::user()->can('criar todos') || Auth::user()->can('criar vendas'))
                            @if ($internamento->pago == 'NAO PAGO')
                            @if ($internamento->factura && $internamento->factura->factura_divida == "Y")
                            <a href="{{ route('facturas.show', [$internamento->factura->id, 'tipo_documentos' => $internamento->factura->factura]) }}" class="btn btn-lg btn-light-success">
                                <i class="fas fa-pager"></i> Emitir Recibo Pagamento
                            </a>
                            @endif
                            @endif
                            @endif


                            @if ($internamento->status == 'activo')
                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar internamento'))
                            <button class="btn btn-lg btn-light-danger delete-record" data-id="{{ $internamento->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>
                            @endif

                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar internamento'))
                            <a href="#" onclick="toggleModalAlta()" class="btn btn-lg btn-light-success">
                                <i class="fas fa-user"></i> Dar Alta
                            </a>
                            <a href="#" onclick="toggleModalObito()" class="btn btn-lg btn-light-danger">
                                <i class="fas fa-user"></i> Definir Como Obito
                            </a>
                            <a href="#" onclick="toggleModalTransferencia()" class="btn btn-lg btn-light-primary">
                                <i class="fas fa-user"></i> Transferir Paciente
                            </a>
                            @endif

                            @if (Auth::user()->can('listar todos') || Auth::user()->can('listar internamento'))
                            <a class="btn btn-lg btn-light-primary" href="{{ route('internamentos.imprimir', $internamento->id) }}" target="_blink">
                                <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}
                            </a>
                            @endif
                            @else

                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar internamento'))
                            <button class="btn btn-lg btn-light-danger delete-record" data-id="{{ $internamento->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>
                            @endif

                            @if (Auth::user()->can('listar todos') || Auth::user()->can('listar internamento'))
                            <a class="btn btn-lg btn-light-primary" href="{{ route('internamentos.imprimir', $internamento->id) }}" target="_blink">
                                <i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}
                            </a>
                            @endif

                            @endif

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>


<form action="{{ route('internamentos.actualizar-evolucao-media') }}" method="post" class="" id="form_atendimento">
    @csrf
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-xl  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Actualizar Dados</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row py-4">

                    <div class="col-12 col-md-6 mb-3">
                        <label for="data_evolucao" class="form-label">Data Evolução</label>
                        <input type="date" class="form-control" id="data_evolucao" name="data_evolucao" value="{{ date('Y-m-d') }}">
                        <input type="hidden" class="form-control" id="internamento_id" value="{{ $internamento->id }}" name="internamento_id">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="tipo_evolucao_medica" class="form-label">Tipo Evolução</label>
                        <select name="tipo_evolucao_medica" id="tipo_evolucao_medica" class="form-control">
                            <option value="medica">Médica</option>
                            <option value="enfermagem">Enfermagem</option>
                            <option value="fisioterapia">Fisioterapia</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-12">
                        <label for="observacao" class="form-label">Resumo</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="observacao" id="observacao" cols="30" rows="5" placeholder="Descrição: "></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                    {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                    <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                    {{-- @endif --}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- /.modal -->

<form action="{{ route('internamentos.dar-alta') }}" method="post" class="" id="form_alta">
    @csrf
    <div class="modal fade" id="modal-lg-alta">
        <div class="modal-dialog modal-xl  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Dar alta ao paciente</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row py-4">

                    <div class="col-12 col-md-12 mb-3">
                        <label for="data_alta" class="form-label">Data Alta</label>
                        <input type="date" class="form-control" id="data_alta" name="data_alta" value="{{ date('Y-m-d') }}">
                        <input type="hidden" class="form-control" id="internamento_id" value="{{ $internamento->id }}" name="internamento_id">
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label for="resumo" class="form-label">Resumo da Alta</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="resumo" id="resumo" cols="30" rows="5" placeholder="Descrição: "></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                    {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                    <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                    {{-- @endif --}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- /.modal -->

<form action="{{ route('internamentos.definir-obito') }}" method="post" class="" id="form_obito">
    @csrf
    <div class="modal fade" id="modal-lg-obito">
        <div class="modal-dialog modal-xl  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Definir Como Obito</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row py-4">

                    <div class="col-12 col-md-6 mb-3">
                        <label for="data_obito" class="form-label">Data do Obito</label>
                        <input type="date" class="form-control" id="data_obito" name="data_obito" value="{{ date('Y-m-d') }}">
                        <input type="hidden" class="form-control" id="internamento_id" value="{{ $internamento->id }}" name="internamento_id">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="hora_obito" class="form-label">Hora do Obito</label>
                        <input type="time" class="form-control" id="hora_obito" name="hora_obito" value="{{ date('H:i') }}">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="local_obito" class="form-label">Local do Obito</label>
                        <input type="text" class="form-control" id="local_obito" name="local_obito" placeholder="Local do obito">
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <label for="tipo_obito" class="form-label">Tipo do Obito</label>
                        <select name="tipo_obito" id="tipo_obito" class="form-control">
                            <option value="natural">Natural</option>
                            <option value="acidental">Acidental</option>
                            <option value="violento">Violento</option>
                            <option value="suspeito">Suspeito</option>
                            <option value="indefinido">Indefinido</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <label for="comunicacao_obito" class="form-label">Comunicado aos familiares</label>
                        <select name="comunicacao_obito" id="comunicacao_obito" class="form-control">
                            <option value="0"> {{ __('messages.nao') }} </option>
                            <option value="1"> {{ __('messages.sim') }} </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label for="resumo" class="form-label">Resumo do Obito (Causa do Obito)</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="resumo" id="resumo" cols="30" rows="5" placeholder="Descrição: "></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                    {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                    <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                    {{-- @endif --}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- /.modal -->

<form action="{{ route('internamentos.transferir-paciente') }}" method="post" class="" id="form_transferencia">
    @csrf
    <div class="modal fade" id="modal-lg-transferir">
        <div class="modal-dialog modal-xl  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Transferir o paciente</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row py-4">

                    <div class="col-12 col-md-12 mb-3">
                        <label for="data_transferencia" class="form-label">Data da Transferência</label>
                        <input type="date" class="form-control" id="data_transferencia" name="data_transferencia" value="{{ date('Y-m-d') }}">
                        <input type="hidden" class="form-control" id="internamento_id" value="{{ $internamento->id }}" name="internamento_id">
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label for="resumo" class="form-label">Resumo da Transferência</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="resumo" id="resumo" cols="30" rows="5" placeholder="Descrição: "></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                    {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                    <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                    {{-- @endif --}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- /.modal -->


<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    let PastaID = null;
    let modalVisible = false;

    const modalElement = document.getElementById('modal-lg');
    const modalInstance = new bootstrap.Modal(modalElement);

    const modalElementAlta = document.getElementById('modal-lg-alta');
    const modalInstanceAlta = new bootstrap.Modal(modalElementAlta);

    const modalElementObito = document.getElementById('modal-lg-obito');
    const modalInstanceObito = new bootstrap.Modal(modalElementObito);

    const modalElementTransferir = document.getElementById('modal-lg-transferir');
    const modalInstanceTransferir = new bootstrap.Modal(modalElementTransferir);

    function toggleModal() {
        PastaID = null;
        if (modalVisible) {
            modalInstance.hide();
            modalVisible = false;
        } else {
            modalInstance.show();
            modalVisible = true;
        }
    }

    function toggleModalAlta() {
        if (modalVisible) {
            modalInstanceAlta.hide();
            modalVisible = false;
        } else {
            modalInstanceAlta.show();
            modalVisible = true;
        }
    }

    function toggleModalObito() {
        if (modalVisible) {
            modalInstanceObito.hide();
            modalVisible = false;
        } else {
            modalInstanceObito.show();
            modalVisible = true;
        }
    }

    function toggleModalTransferencia() {
        if (modalVisible) {
            modalInstanceTransferir.hide();
            modalVisible = false;
        } else {
            modalInstanceTransferir.show();
            modalVisible = true;
        }
    }


    $(document).ready(function() {
        // Handler do form de atendimento
        $("#form_atendimento").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });

        // Handler do segundo form
        $("#form_alta").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });

        // Handler do segundo form
        $("#form_obito").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });

        // Handler do segundo form
        $("#form_transferencia").on('submit', function(e) {
            e.preventDefault();
            enviarFormularioAjax($(this));
        });
    });


    function enviarFormularioAjax(form) {
        let formData = form.serialize();

        $.ajax({
            url: form.attr('action')
            , method: form.attr('method')
            , data: formData
            , headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
            , beforeSend: function() {
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                showMessage('Sucesso!', 'Dados actualizados com sucesso!', 'success');
                window.location.reload();
            }
            , error: function(xhr) {
                Swal.close();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let messages = '';
                    $.each(errors, function(key, value) {
                        messages += `${value}\n *`;
                    });
                    showMessage('Erro de Validação!', messages, 'error');
                } else {
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            }
        });
    }

    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, excluir!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('internamentos.destroy', ':id') }}`.replace(':id'
                        , recordId)
                    , method: 'DELETE'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
                        window.location.href = "/internamentos";
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!'
                            , 'Ocorreu um erro ao excluir o registro. Tente novamente.'
                            , 'error');
                    }
                , });
            }
        });
    });



    document.querySelectorAll('.linha-principal').forEach(row => {
        row.addEventListener('click', function() {

            let id = this.getAttribute('data-id');
            let dropdownAtual = document.getElementById('drop-' + id);

            // Fecha todos os dropdowns
            document.querySelectorAll('.dropdown-parametros').forEach(drop => {
                if (drop !== dropdownAtual) {
                    drop.style.display = 'none';
                }
            });

            // Toggle do atual
            if (dropdownAtual.style.display === 'table-row') {
                dropdownAtual.style.display = 'none';
            } else {
                dropdownAtual.style.display = 'table-row';
            }
        });
    });

    let imagensAtuais = [];
    let imagemSelecionada = null;
    let parametroId = null;

    $(document).on('click', '.btn-ver-imagens', function() {

        parametroId = $(this).data('paramento');

        let imagens = $(this).attr('data-imagens') || '[]';
        imagens = JSON.parse(imagens);

        if (typeof imagens === 'string') {
            imagens = JSON.parse(imagens);
        }

        imagensAtuais = Array.isArray(imagens) ? imagens : [];

        renderImagens();

        $('#modalImagens').modal('show');
        $('#imagem-grande').hide();
    });

    function renderImagens() {

        let container = $('#lista-imagens');
        container.html('');

        imagensAtuais.forEach((img, index) => {
            container.append(`
                <div class="col-md-3 mb-2">
                    <img src="/${img}"
                        class="img-thumbnail img-click"
                        data-index="${index}"
                        style="cursor:pointer; height:120px; object-fit:cover;">
                </div>
            `);
        });
    }

    $(document).on('click', '.img-click', function() {
        let index = $(this).data('index');
        imagemSelecionada = index;
        let src = imagensAtuais[index];

        $('#imagem-grande').attr('src', '/' + src).fadeIn();

    });

</script>
@endsection
