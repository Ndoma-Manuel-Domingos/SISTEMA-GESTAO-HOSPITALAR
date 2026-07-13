@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.controle') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.inicio') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')

            @if ($totalConsultaAgendadasHoje || $totalConsultaAgendadasSemana || $totalExameAgendadasHoje || $totalExameAgendadasSemana || $totalConsultaAtrazadas || $totalExameAtrazadas)
            <div class="row">
                @if ($totalConsultaAgendadasHoje && (auth()->user()->can('consultorio') || auth()->user()->can('monitoramento consultorio')))
                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('consultas.index', ['status' => 'AGENDADA']) }}" class="text-decoration-none">
                        <div class="small-box bg-light-warning shadow">
                            <div class="inner">
                                <h3>{{ $totalConsultaAgendadasHoje }}</h3>
                                <p>Consultas para Hoje</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if ($totalConsultaAgendadasSemana && (auth()->user()->can('consultorio') || auth()->user()->can('monitoramento consultorio')))
                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('consultas.index', ['status' => 'AGENDADA']) }}" class="text-decoration-none">
                        <div class="small-box bg-light-warning shadow">
                            <div class="inner">
                                <h3>{{ $totalConsultaAgendadasSemana }}</h3>
                                <p>Consultas da Semana</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if ($totalConsultaAtrazadas && (auth()->user()->can('consultorio') || auth()->user()->can('monitoramento consultorio')))
                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('consultas.index', ['status' => 'ATRASADA']) }}" class="text-decoration-none">
                        <div class="small-box bg-light-danger shadow">
                            <div class="inner">
                                <h3>{{ $totalConsultaAtrazadas }}</h3>
                                <p>Consultas Atrazadas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif


                @if ($totalExameAgendadasHoje && (auth()->user()->can('laboratorio') || auth()->user()->can('monitoramento laboratorio')))
                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('exames.index', ['status' => 'AGENDADA']) }}" class="text-decoration-none">
                        <div class="small-box bg-light-warning shadow">
                            <div class="inner">
                                <h3>{{ $totalExameAgendadasHoje }}</h3>
                                <p>Exames para Hoje</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-vials"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if ($totalExameAgendadasSemana && (auth()->user()->can('laboratorio') || auth()->user()->can('monitoramento laboratorio')))
                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('exames.index', ['status' => 'AGENDADA']) }}" class="text-decoration-none">
                        <div class="small-box bg-light-warning shadow">
                            <div class="inner">
                                <h3>{{ $totalExameAgendadasSemana }}</h3>
                                <p>Exames da Semana</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-microscope"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif


                @if ($totalExameAtrazadas && (auth()->user()->can('laboratorio') || auth()->user()->can('monitoramento laboratorio')))
                <div class="col-lg-2 col-md-3 col-12">
                    <a href="{{ route('exames.index', ['status' => 'ATRASADA']) }}" class="text-decoration-none">
                        <div class="small-box bg-light-danger shadow">
                            <div class="inner">
                                <h3>{{ $totalExameAtrazadas }}</h3>
                                <p>Exames Atrazadas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
            </div>
            @endif


            <div class="row">
                @can("listar cliente")
                <div class="col-md-3 col-12">
                    <a href="{{ route('tickets-gerar-senha') }}" class="text-decoration-none">
                        <div class="small-box bg-light-primary shadow">
                            <div class="inner">
                                <h3>Senhas</h3>
                                <p>Gerar Senha</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                <div class="col-md-3 col-12">
                    <a href="{{ route('agendas-medicas.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-primary shadow">
                            <div class="inner">
                                <h3>Agenda</h3>
                                <p>Agenda Médica</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </a>
                </div>


                @if(auth()->user()->can('listar cliente') || auth()->user()->can('monitoramento central atendimento'))
                <div class="col-md-3 col-12">
                    <a href="{{ route('clientes.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-primary shadow">
                            <div class="inner">
                                <h3>{{ $totalCliente }}</h3>
                                <p>{{ __('messages.paciente') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-injured"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('caixas.monitoramento-caixas') }}">
                        <div class="small-box bg-light-primary">
                            <div class=" inner">
                                <h3>Caixa</h3>
                                <p class="text-uppercase">Monitoramento do caixa</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-cash-register"></i>
                            </div>
                        </div>
                    </a>
                </div>

                @endif

                @can("listar medico")
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('medicos.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-success">
                            <div class=" inner">
                                <h3>{{ number_format($totalMedico, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.medico') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can("listar enfermeiro")
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('enfermeiros.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-success">
                            <div class=" inner">
                                <h3>{{ number_format($totalEnfermeiro, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.enfermeiro') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-nurse"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can("listar tecnico")
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('tecnicos.index') }}" class="text-decoration-none">
                        <div class="small-box  bg-light-success">
                            <div class=" inner">
                                <h3>{{ number_format($totalTecnio, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.tecnicos') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @if(auth()->user()->can('listar atendimento') || auth()->user()->can('monitoramento central atendimento'))
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('atendimentos.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-primary">
                            <div class=" inner">
                                <h3>{{ number_format($totalAtendimentos, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.atendimento') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hospital-user"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('listar triagem') || auth()->user()->can('monitoramento enfermagem triagem'))
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('triagens.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-warning">
                            <div class=" inner">
                                <h3>{{ number_format($totalTriagem, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.triagem') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('consultorio') || auth()->user()->can('monitoramento consultorio'))
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('consultorio.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-primary">
                            <div class=" inner">
                                <h3>{{ number_format(0, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.consultorio') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clinic-medical"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('laboratorio') || auth()->user()->can('monitoramento laboratorio'))
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('laboratorio.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-warning">
                            <div class=" inner">
                                <h3>{{ number_format(0, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.laboratorio') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-flask"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('laboratorio') || auth()->user()->can('monitoramento laboratorio') || auth()->user()->can('monitoramento consultorio') || auth()->user()->can('consultorio'))
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('resultados-exames.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-warning">
                            <div class=" inner">
                                <h3>{{ number_format($total_resultados_exames, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">Resultados de Exames</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-medical-alt"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('listar tratamento') )
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('planos-tratamentos.index') }}" class="text-decoration-none">
                        <div class="small-box  bg-light-success">
                            <div class=" inner">
                                <h3>{{ number_format($total_plano_tratamento, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.tratamento') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-notes-medical"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @can("listar quarto")
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('quartos.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-warning">
                            <div class=" inner">
                                <h3>{{ number_format($totalQuarto, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.quarto') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-bed"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can("listar tratamento")
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('internamentos.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-success">
                            <div class=" inner">
                                <h3>{{ number_format($totalInternamento, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.internamentos') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-procedures"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can("listar obito")
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('obitos.index') }}" class="text-decoration-none">
                        <div class="small-box  bg-light-danger">
                            <div class=" inner">
                                <h3>{{ number_format($totalObito, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.obitos') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-cross"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can("listar morgue")
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('morgues.index') }}" class="text-decoration-none">
                        <div class="small-box bg-light-primary">
                            <div class=" inner">
                                <h3>{{ number_format($totalMorgue, 0, ',', '.') }}</h3>
                                <p class="text-uppercase">{{ __('messages.morgue') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-archive"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can("monitoramento camaras")
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('camaras.visualizacao-gavetas-camaras') }}" class="text-decoration-none">
                        <div class="small-box bg-light-primary">
                            <div class=" inner">
                                <h3>Monit.</h3>
                                <p class="text-uppercase">{{ __('messages.monitoramento_camaras') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-video"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can("monitoramento quartos")
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('quartos.visualizacao-leitos-quartos') }}" class="text-decoration-none">
                        <div class="small-box bg-light-success">
                            <div class=" inner">
                                <h3>Monit.</h3>
                                <p class="text-uppercase">{{ __('messages.monitoramento_quartos') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-tv"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan


                @can("criar vendas")
                <div class="col-lg-3 col-md-3 col-12">
                    <a href="{{ route('pronto-venda') }}" class="text-decoration-none">
                        <div class="small-box  bg-light-primary" title=" Reservas">
                            <div class="inner">
                                <h3>::</h3>
                                <p class="text-uppercase">Venda a Farmácia</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-pills"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

            </div>
            @endif

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
@endsection
