@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-edit"></i> {{ __('messages.editar') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('contratos.index') }}">{{ __('messages.voltar') }}</a></li>
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
                <div class="col-12 col-md-12">
                    <form action="{{ route('contratos.update', $contrato->id) }}" method="post" class="">
                        @csrf
                        @method('put')

                        <div class="card card-default">
                            <div class="card-body">
                                <div class="bs-stepper">
                                    <div class="bs-stepper-header" role="tablist">

                                        <div class="line"></div>

                                        <div class="step" data-target="#paconte-contrato">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="paconte-contrato" id="paconte-contrato-trigger">
                                                <span class="bs-stepper-circle">1</span>
                                                <span class="bs-stepper-label">{{ __('messages.contratos') }}</span>
                                            </button>
                                        </div>

                                        <div class="line"></div>

                                        <div class="step" data-target="#paconte-subsidios">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="paconte-subsidios" id="paconte-subsidios-trigger">
                                                <span class="bs-stepper-circle">2</span>
                                                <span class="bs-stepper-label">{{ __('messages.subsidio') }}</span>
                                            </button>
                                        </div>

                                        <div class="line"></div>

                                        <div class="step" data-target="#paconte-descontos">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="paconte-descontos" id="paconte-descontos-trigger">
                                                <span class="bs-stepper-circle">3</span>
                                                <span class="bs-stepper-label">{{ __('messages.desconto') }}</span>
                                            </button>
                                        </div>

                                        <div class="line"></div>

                                    </div>

                                    <div class="bs-stepper-content">

                                        <div id="paconte-contrato" class="content" role="tabpanel" aria-labelledby="paconte-contrato-trigger">
                                            <div class="row">
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <label for="funcionario_id" class="form-label">{{ __('messages.funcionario') }}</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control @error('funcionario_id') is-invalid @enderror" name="funcionario_id" id="funcionario_id">
                                                            @foreach ($funcionarios as $item)
                                                            <option value="{{ $item->id ?? "" }}" {{ $contrato->funcionario_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <label for="cargo_id" class="form-label">{{ __('messages.cargos') }}</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control select2 @error('cargo_id') is-invalid @enderror" name="cargo_id" id="cargo_id">
                                                            <option value="">{{ __('messages.escolher') }} </option>
                                                            @foreach ($cargos as $item)
                                                            <option value="{{ $item->id ?? "" }}" {{ $contrato->cargo_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <label for="categoria_id" class="form-label">{{ __('messages.categoria') }}</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control select2 @error('categoria_id') is-invalid @enderror" name="categoria_id" id="categoria_id">
                                                            @foreach ($categorias as $item)
                                                            <option value="{{ $item->id ?? "" }}" {{ $contrato->categoria_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="dias_processamento" class="form-label">Dias Processamento</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control select2 @error('dias_processamento') is-invalid @enderror" name="dias_processamento" id="dias_processamento">
                                                            <option value="">{{ __('messages.escolher') }} </option>
                                                            <option value="dias_uteis_variaveis" {{ $contrato->dias_processamento == 'dias_uteis_variaveis' ? 'selected' : '' }}>
                                                                Dias Úteis Variáveis</option>
                                                            <option value="dias_fixo" {{ $contrato->dias_processamento == 'dias_fixo' ? 'selected' : '' }}>
                                                                Dias Fixos (30)</option>
                                                            <option value="dias_uteis_fixo" {{ $contrato->dias_processamento == 'dias_uteis_fixo' ? 'selected' : '' }}>
                                                                Dias Úteis Fixos</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="forma_pagamento_id" class="form-label">Formas de Pagamento</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control select2 @error('forma_pagamento_id') is-invalid @enderror" name="forma_pagamento_id" id="forma_pagamento_id">
                                                            @foreach ($forma_pagamentos as $item)
                                                            <option value="{{ $item->id ?? "" }}" {{ $contrato->forma_pagamento_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->titulo }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="subsidio_natal" class="form-label">Subsídio de Natal (%)</label>
                                                    <div class="input-group mb-3">
                                                        <input type="number" class="form-control @error('subsidio_natal') is-invalid @enderror" name="subsidio_natal" id="subsidio_natal" value="{{ $contrato->subsidio_natal ?? (old('subsidio_natal') ?? 50) }}" placeholder="Informe o Valor em percentagem">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="forma_pagamento_natal" class="form-label">Forma de Pagamento
                                                        (Natal)</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control @error('forma_pagamento_natal') is-invalid @enderror" id="forma_pagamento_natal" name="forma_pagamento_natal">
                                                            <option value="">{{ __('messages.escolher') }} </option>
                                                            <option value="completa" {{ $contrato->forma_pagamento_natal == 'completa' ? 'selected' : '' }}>
                                                                Completa Mês Subsídio</option>
                                                            <option value="partes" {{ $contrato->forma_pagamento_natal == 'partes' ? 'selected' : '' }}>
                                                                Duodécimo</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="mes_pagamento_natal" class="form-label">Mês Pagamento (Natal)</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control @error('mes_pagamento_natal') is-invalid @enderror" id="mes_pagamento_natal" name="mes_pagamento_natal">
                                                            <option value="1" {{ $contrato->forma_pagamento_natal == '1' ? 'selected' : '' }}>Janeiro
                                                            </option>
                                                            <option value="2" {{ $contrato->forma_pagamento_natal == '2' ? 'selected' : '' }}>
                                                                Fevereiro</option>
                                                            <option value="3" {{ $contrato->forma_pagamento_natal == '3' ? 'selected' : '' }}>Março
                                                            </option>
                                                            <option value="4" {{ $contrato->forma_pagamento_natal == '4' ? 'selected' : '' }}>Abril
                                                            </option>
                                                            <option value="5" {{ $contrato->forma_pagamento_natal == '5' ? 'selected' : '' }}>Maio
                                                            </option>
                                                            <option value="6" {{ $contrato->forma_pagamento_natal == '6' ? 'selected' : '' }}>Junho
                                                            </option>
                                                            <option value="7" {{ $contrato->forma_pagamento_natal == '7' ? 'selected' : '' }}>Julho
                                                            </option>
                                                            <option value="8" {{ $contrato->forma_pagamento_natal == '8' ? 'selected' : '' }}>Agosto
                                                            </option>
                                                            <option value="9" {{ $contrato->forma_pagamento_natal == '9' ? 'selected' : '' }}>
                                                                Setembro</option>
                                                            <option value="10" {{ $contrato->forma_pagamento_natal == '10' ? 'selected' : '' }}>
                                                                Outubro</option>
                                                            <option value="11" {{ $contrato->forma_pagamento_natal == '11' ? 'selected' : '' }}>
                                                                Novembro</option>
                                                            <option value="12" {{ $contrato->forma_pagamento_natal == '12' ? 'selected' : '' }}>
                                                                Dezembro</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="subsidio_ferias" class="form-label">Subsídio de Ferias(%)</label>
                                                    <div class="input-group mb-3">
                                                        <input type="number" class="form-control @error('subsidio_ferias') is-invalid @enderror" name="subsidio_ferias" id="subsidio_ferias" value="{{ old('subsidio_ferias') ?? 50 }}" placeholder="Informe o Valor em percentagem">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="forma_pagamento_ferias" class="form-label">Forma de Pagamento
                                                        (Ferias)</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control @error('forma_pagamento_ferias') is-invalid @enderror" id="forma_pagamento_ferias" name="forma_pagamento_ferias">
                                                            <option value="">{{ __('messages.escolher') }} </option>
                                                            <option value="completa" {{ $contrato->forma_pagamento_ferias == 'completa' ? 'selected' : '' }}>
                                                                Completa Mês Subsídio</option>
                                                            <option value="partes" {{ $contrato->forma_pagamento_ferias == 'partes' ? 'selected' : '' }}>
                                                                Duodécimo</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="mes_pagamento_ferias" class="form-label">Mês Pagamento
                                                        (Ferias)</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control @error('mes_pagamento_ferias') is-invalid @enderror" id="mes_pagamento_ferias" name="mes_pagamento_ferias">
                                                            <option value="1" {{ $contrato->mes_pagamento_ferias == '1' ? 'selected' : '' }}>Janeiro
                                                            </option>
                                                            <option value="2" {{ $contrato->mes_pagamento_ferias == '2' ? 'selected' : '' }}>
                                                                Fevereiro</option>
                                                            <option value="3" {{ $contrato->mes_pagamento_ferias == '3' ? 'selected' : '' }}>Março
                                                            </option>
                                                            <option value="4" {{ $contrato->mes_pagamento_ferias == '4' ? 'selected' : '' }}>Abril
                                                            </option>
                                                            <option value="5" {{ $contrato->mes_pagamento_ferias == '5' ? 'selected' : '' }}>Maio
                                                            </option>
                                                            <option value="6" {{ $contrato->mes_pagamento_ferias == '6' ? 'selected' : '' }}>Junho
                                                            </option>
                                                            <option value="7" {{ $contrato->mes_pagamento_ferias == '7' ? 'selected' : '' }}>Julho
                                                            </option>
                                                            <option value="8" {{ $contrato->mes_pagamento_ferias == '8' ? 'selected' : '' }}>Agosto
                                                            </option>
                                                            <option value="9" {{ $contrato->mes_pagamento_ferias == '9' ? 'selected' : '' }}>Setembro
                                                            </option>
                                                            <option value="10" {{ $contrato->mes_pagamento_ferias == '10' ? 'selected' : '' }}>Outubro
                                                            </option>
                                                            <option value="11" {{ $contrato->mes_pagamento_ferias == '11' ? 'selected' : '' }}>
                                                                Novembro</option>
                                                            <option value="12" {{ $contrato->mes_pagamento_ferias == '12' ? 'selected' : '' }}>
                                                                Dezembro</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="tipo_contrato_id" class="form-label">Tipos de Contratos</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control select2 @error('tipo_contrato_id') is-invalid @enderror" name="tipo_contrato_id" id="tipo_contrato_id">
                                                            @foreach ($tipos_contratos as $item)
                                                            <option value="{{ $item->id ?? "" }}" {{ $contrato->tipo_contrato_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                                    <div class="input-group mb-3">
                                                        <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" name="data_inicio" id="data_inicio" value="{{ $contrato->data_inicio ?? old('data_inicio') }}" placeholder="Informe o contrato">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                                    <div class="input-group mb-3">
                                                        <input type="date" class="form-control @error('data_final') is-invalid @enderror" name="data_final" id="data_final" value="{{ $contrato->data_final ?? old('data_final') }}" placeholder="Informe o contrato">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="data_envio_previo" class="form-label">Data Envio Previo</label>
                                                    <div class="input-group mb-3">
                                                        <input type="date" class="form-control @error('data_envio_previo') is-invalid @enderror" name="data_envio_previo" id="data_envio_previo" value="{{ $contrato->data_envio_previo ?? (old('data_envio_previo') ?? 0) }}" placeholder="Informe o contrato">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="data_admissao" class="form-label">Data de Admissão</label>
                                                    <div class="input-group mb-3">
                                                        <input type="date" class="form-control @error('data_admissao') is-invalid @enderror" name="data_admissao" id="data_admissao" value="{{ $contrato->data_admissao ?? old('data_admissao') ?? 0 }}" placeholder="Informe o contrato">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="data_demissao" class="form-label">Data de Demissão</label>
                                                    <div class="input-group mb-3">
                                                        <input type="date" class="form-control @error('data_demissao') is-invalid @enderror" name="data_demissao" id="data_demissao" value="{{ $contrato->data_demissao ?? (old('data_demissao') ?? 0) }}" placeholder="Informe o contrato">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="hora_entrada" class="form-label">Hora Entrada</label>
                                                    <div class="input-group mb-3">
                                                        <input type="time" class="form-control @error('hora_entrada') is-invalid @enderror" name="hora_entrada" id="hora_entrada" value="{{ $contrato->hora_entrada ?? old('hora_entrada') }}" placeholder="Informe o contrato">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="hora_saida" class="form-label">Hora Saída</label>
                                                    <div class="input-group mb-3">
                                                        <input type="time" class="form-control @error('hora_saida') is-invalid @enderror" name="hora_saida" id="hora_saida" value="{{ $contrato->hora_saida ?? old('hora_saida') }}" placeholder="Informe o contrato">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                                            <option value="activo" {{ $contrato->status == 'activo' ? 'selected' : '' }}>{{ __('messages.activo') }} </option>
                                                            <option value="desactivo" {{ $contrato->status == 'desactivo' ? 'selected' : '' }}>Desactivo
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-4 col-lg-2">
                                                    <label for="salario_base" class="form-label">Salário Base</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control @error('salario_base') is-invalid @enderror" name="salario_base" id="salario_base" value="{{ $contrato->salario_base ?? old('salario_base') }}" placeholder="Informe o valor base da remuneração">
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-light-primary my-4" onclick="stepper.previous()">Anterior</button>
                                            <button type="button" class="btn btn-light-primary my-4" onclick="stepper.next()">Proxímo</button>
                                        </div>


                                        <div id="paconte-subsidios" class="content" role="tabpanel" aria-labelledby="paconte-subsidios-trigger">
                                            <div class="row">
                                                <div class="col-12 col-md-12" id="dynamic-fields">
                                                    <div class="field-group">

                                                        @if (count($contrato->subsidios_contrato) == 0)
                                                        <div class="row">
                                                            <div class="col-12 col-md-4">
                                                                <label for="subsidio_id_1" class="form-label">{{ __('messages.subsidio') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('subsidio_id') is-invalid @enderror" id="subsidio_id_1" name="subsidio_id[]">
                                                                        <option value="">{{ __('messages.escolher') }} </option>
                                                                        @foreach ($subsidios as $item)
                                                                        <option value="{{ $item->id ?? "" }}">
                                                                            {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-4">
                                                                <label for="salario_subsidio_1" class="form-label">{{ __('messages.valor') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control @error('salario_subsidio') is-invalid @enderror" name="salario_subsidio[]" id="salario_subsidio_1" placeholder="Informe o Valor da remuneração">
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-3">
                                                                <label for="processamento_id_1" class="form-label">{{ __('messages.tipo_processamento') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control @error('processamento_id') is-invalid @enderror" id="processamento_id_1" name="processamento_id[]">
                                                                        <option value="">{{ __('messages.escolher') }} </option>
                                                                        @foreach ($processamentos as $item)
                                                                        <option value="{{ $item->id ?? "" }}">
                                                                            {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-1">
                                                                <label class="form-label">.</label>
                                                                <div class="input-group mb-3">
                                                                    <button type="button" class="btn btn-light-danger remove-field"><i class="fas fa-trash"></i> {{ __('messages.eliminar') }}</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        @else
                                                        @foreach($contrato->subsidios_contrato as $index => $subsidio)
                                                        <div class="row">
                                                            <div class="col-12 col-md-4">
                                                                <label for="subsidio_id_1" class="form-label">{{ __('messages.subsidio') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('subsidio_id') is-invalid @enderror" id="subsidio_id_1" name="subsidio_id[]">
                                                                        <option value="">{{ __('messages.escolher') }} </option>
                                                                        @foreach ($subsidios as $item)
                                                                        <option value="{{ $item->id ?? "" }}" {{ $item->id == $subsidio->subsidio_id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-4">
                                                                <label for="salario_subsidio_1" class="form-label">{{ __('messages.valor') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control @error('salario_subsidio') is-invalid @enderror" name="salario_subsidio[]" id="salario_subsidio_1" value="{{ $subsidio->salario }}" placeholder="Informe o Valor da remuneração">
                                                                </div>
                                                            </div>


                                                            <div class="col-12 col-md-3">
                                                                <label for="processamento_id_1" class="form-label">{{ __('messages.tipo_processamento') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control @error('processamento_id') is-invalid @enderror" id="processamento_id_1" name="processamento_id[]">
                                                                        <option value="">{{ __('messages.escolher') }} </option>
                                                                        @foreach ($processamentos as $item)
                                                                        <option value="{{ $item->id ?? "" }}" {{ $subsidio->processamento_id == $item->id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-1">
                                                                <label class="form-label">.</label>
                                                                <div class="input-group mb-3">
                                                                    <button type="button" class="btn btn-light-danger remove-field"><i class="fas fa-trash"></i> {{ __('messages.eliminar') }}</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-light-primary my-4" onclick="stepper.previous()">Anterior</button>
                                            <button type="button" class="btn btn-light-primary my-4" onclick="stepper.next()">Proxímo</button>

                                            <button type="button" id="add-field-subsidio" class="btn btn-light-success my-4 mx-2 float-right"><i class="fas fa-plus"></i> {{ __('messages.subsidio') }}</button>
                                        </div>

                                        <div id="paconte-descontos" class="content" role="tabpanel" aria-labelledby="paconte-descontos-trigger">
                                            <div class="row">
                                                <div class="col-12 col-md-12" id="dynamic-fields-descontos">
                                                    <div class="field-group-desconto">

                                                        @if (count($contrato->descontos_contrato) == 0)
                                                        <div class="row">
                                                            <div class="col-12 col-md-4">
                                                                <label for="desconto_id_1" class="form-label">{{ __('messages.desconto') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('desconto_id') is-invalid @enderror" id="desconto_id_1" name="desconto_id[]">
                                                                        <option value="">{{ __('messages.escolher') }} </option>
                                                                        @foreach ($descontos as $item)
                                                                        <option value="{{ $item->id ?? "" }}">
                                                                            {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-4">
                                                                <label for="salario_desconto_1" class="form-label">{{ __('messages.valor') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control @error('salario_desconto') is-invalid @enderror" name="salario_desconto[]" id="salario_desconto_1" placeholder="Informe o Valor da remuneração">
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-3">
                                                                <label for="processamento_desconto_id_1" class="form-label">{{ __('messages.tipo_processamento') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control @error('processamento_desconto_id') is-invalid @enderror" id="processamento_desconto_id_1" name="processamento_desconto_id[]">
                                                                        <option value="">{{ __('messages.escolher') }} </option>
                                                                        @foreach ($processamentos as $item)
                                                                        <option value="{{ $item->id ?? "" }}">
                                                                            {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-1">
                                                                <label class="form-label">.</label>
                                                                <div class="input-group mb-3">
                                                                    <button type="button" class="btn btn-light-danger remove-field-desconto"><i class="fas fa-trash"></i> {{ __('messages.eliminar') }}</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        @else
                                                        @foreach($contrato->descontos_contrato as $index => $desconto)
                                                        <div class="row">
                                                            <div class="col-12 col-md-4">
                                                                <label for="desconto_id_1" class="form-label">{{ __('messages.desconto') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('desconto_id') is-invalid @enderror" id="desconto_id_1" name="desconto_id[]">
                                                                        <option value="">{{ __('messages.escolher') }} </option>
                                                                        @foreach ($descontos as $item)
                                                                        <option value="{{ $item->id ?? "" }}" {{ $item->id == $desconto->desconto_id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-4">
                                                                <label for="salario_desconto_1" class="form-label">{{ __('messages.valor') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control @error('salario_desconto') is-invalid @enderror" name="salario_desconto[]" id="salario_desconto_1" value="{{ $desconto->salario }}" placeholder="Informe o Valor da remuneração">
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-3">
                                                                <label for="processamento_desconto_id_1" class="form-label">{{ __('messages.tipo_processamento') }}</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control @error('processamento_desconto_id') is-invalid @enderror" id="processamento_desconto_id_1" name="processamento_desconto_id[]">
                                                                        <option value="">{{ __('messages.escolher') }} </option>
                                                                        @foreach ($processamentos as $item)
                                                                        <option value="{{ $item->id ?? "" }}" {{ $desconto->processamento_id == $item->id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-1">
                                                                <label class="form-label">.</label>
                                                                <div class="input-group mb-3">
                                                                    <button type="button" class="btn btn-light-danger remove-field-desconto"><i class="fas fa-trash"></i> {{ __('messages.eliminar') }}</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        @endforeach
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-light-primary my-4" onclick="stepper.previous()">Anterior</button>
                                            <button type="submit" class="btn btn-light-success">{{ __('messages.actualizar') }}</button>

                                            <button type="button" id="add-field-desconto" class="btn btn-light-success my-4 mx-2 float-right"><i class="fas fa-plus"></i> {{ __('messages.desconto') }}</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection


@section('scripts')
<script>
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n *`; // Exibe os erros
                        });

                        showMessage('Erro de Validação!', messages, 'error');

                    } else {

                        showMessage('Erro!', xhr.responseJSON.message, 'error');

                    }

                }
            , });
        });
    });

    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

    $(document).ready(function() {
        let maxFields = 10; // Limite de campos dinâmicos
        let fieldCount = 1; // Contador de campos dinâmicos

        let maxFieldsDesconto = 10; // Limite de campos dinâmicos
        let fieldCountDesconto = 1; // Contador de campos dinâmicos

        // Função para adicionar novo campo
        $('#add-field-subsidio').click(function() {
            if (fieldCount < maxFields) {
                fieldCount++;
                let newFieldGroup = $('.field-group:first').clone();
                newFieldGroup.find('input, select').each(function() {
                    let currentId = $(this).attr('id');
                    let currentName = $(this).attr('name');
                    let newId = currentId.replace(/_\d+$/, '_' + fieldCount);
                    let newName = currentName.replace(/\[\]$/, '[]');
                    $(this).attr('id', newId);
                    $(this).attr('name', newName);
                    $(this).val(''); // Limpar valores
                });
                newFieldGroup.appendTo('#dynamic-fields');
            } else {
                alert('Você só pode adicionar até 10 campos.');
            }
        });


        // Função para remover campo
        $('#dynamic-fields').on('click', '.remove-field', function() {
            if (fieldCount > 1) {
                $(this).closest('.field-group').remove();
                fieldCount--;
            } else {
                alert('Você deve ter pelo menos um campo.');
            }
        });


        // Função para adicionar novo campo
        $('#add-field-desconto').click(function() {
            if (fieldCountDesconto < maxFieldsDesconto) {
                fieldCountDesconto++;
                let newFieldGroup = $('.field-group-desconto:first').clone();
                newFieldGroup.find('input, select').each(function() {
                    let currentId = $(this).attr('id');
                    let currentName = $(this).attr('name');
                    let newId = currentId.replace(/_\d+$/, '_' + fieldCountDesconto);
                    let newName = currentName.replace(/\[\]$/, '[]');
                    $(this).attr('id', newId);
                    $(this).attr('name', newName);
                    $(this).val(''); // Limpar valores
                });
                newFieldGroup.appendTo('#dynamic-fields-descontos');
            } else {
                alert('Você só pode adicionar até 10 campos.');
            }
        });


        // Função para remover campo
        $('#dynamic-fields-descontos').on('click', '.remove-field-desconto', function() {
            if (fieldCountDesconto > 1) {
                $(this).closest('.field-group-desconto').remove();
                fieldCountDesconto--;
            } else {
                alert('Você deve ter pelo menos um campo.');
            }
        });
    });

</script>
@endsection
