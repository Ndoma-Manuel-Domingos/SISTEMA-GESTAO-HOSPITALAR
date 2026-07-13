@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.monitoramento_mesas') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
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
            <div class="row">
                <div class="col-12 col-md-12 mb-3">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <p>
                                <span class="border border-4 bg-transparent border-success rounded p-2" style="background-color: rgba(25, 135, 84, 1)"><i class="nav-icon far fa-circle text-light-success"></i> {{ $mesas_disponivel }} Disponíveis</span>
                                <span class="border border-4 bg-transparent border-warning rounded p-2" style="background-color: rgba(255, 193, 7, 1)"><i class="nav-icon far fa-circle text-light-warning"></i> {{ $mesas_ocupadas }} Ocupadas</span>
                                <span class="border border-4 bg-transparent border-primary rounded p-2" style="background-color: rgba(13, 110, 253, 1)"><i class="nav-icon far fa-circle text-light-primary"></i> {{ $mesas_reservadas }} Reservadas</span>
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            @if ($checkCaixa)
                            <a href="{{ route('contabilidade-diarios') }}" class="btn btn-light-primary float-right"> Ver Movimentos do Caixa</a>
                            @else
                            <a href="{{ route('caixa.abertura_caixa') }}" class="btn btn-light-primary float-right"> Abrir Caixa</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    @foreach ($salas as $sala)
                    <div class="card mb-4">
                        <div class="card-header bg-light-dark  text-white">
                            <strong>Sala: {{ $sala->nome }}</strong>
                            <span class="float-end">Capacidade: {{ $sala->mesas->count() }}</span>
                            <a class="float-right btn btn-light-primary btn-sm" href="{{ route('mesas.create', ['createLoja' => $sala->id]) }}"> <i class="fas fa-plus"></i> NOVA MESA</a>
                        </div>
                        <div class="card-body">
                            <div class="row row-cols-auto">
                                @foreach ($sala->mesas as $mesa)
                                <div class="col mb-3">
                                    <div class="text-center mesa-hover" data-itens="@foreach($mesa->pedidos as $p) {{ $p->produto->nome }} ({{ $p->quantidade }})\n @endforeach">
                                        <a href="{{ route('pronto-venda-mesas-pedidos', Crypt::encrypt($mesa->id)) }}">
                                            <div class="p-3 rounded text-light-dark" style="
                                                background-color: {{ $mesa->solicitar_ocupacao === "LIVRE" ? 'rgba(25, 135, 84, .2)' : ($mesa->solicitar_ocupacao === "RESERVADA" ? 'rgba(13, 110, 253, .2)' : 'rgba(255, 193, 7, .2)') }};
                                                border: 2px solid {{ $mesa->solicitar_ocupacao === "LIVRE" ? 'rgba(25, 135, 84, 1)' : ($mesa->solicitar_ocupacao === "RESERVADA" ? 'rgba(13, 110, 253, 1)' : 'rgba(255, 193, 7, 1)') }};
                                                width: 120px; height: 120px; display: flex; align-items: center; justify-content: center;">
                                                <div>
                                                    <strong>{{ $mesa->nome }}</strong>
                                                    <br>
                                                    <i class="fas fa-users"></i> {{ $mesa->ocupacao }}
                                                    <br>
                                                    <small style="margin-top: 10px">{{ ucfirst($mesa->solicitar_ocupacao) }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div id="mesaTooltip"></div>

            </div>
            <!-- /.row -->
        </div><!-- /.quartoiner-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    const tooltip = document.getElementById("mesaTooltip");

    document.querySelectorAll(".mesa-hover").forEach(mesa => {

        mesa.addEventListener("mouseenter", function(e) {
            const itens = this.getAttribute("data-itens") ? .trim();

            tooltip.style.display = "block";
            tooltip.innerText = itens !== "" ? itens : "Nenhum item solicitado";

            tooltip.style.top = (e.pageY + 10) + "px";
            tooltip.style.left = (e.pageX + 10) + "px";
        });

        mesa.addEventListener("mousemove", function(e) {
            tooltip.style.top = (e.pageY + 10) + "px";
            tooltip.style.left = (e.pageX + 10) + "px";
        });

        mesa.addEventListener("mouseleave", function() {
            tooltip.style.display = "none";
        });

    });

</script>

@endsection
