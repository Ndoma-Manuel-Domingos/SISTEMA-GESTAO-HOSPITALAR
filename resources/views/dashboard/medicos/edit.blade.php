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
                <div class="col-md-12 col-12">
                    <form action="{{ route('medicos.update', $medico->id) }}" method="post" class="">
                        @csrf
                        @method('put')
                        <div class="card">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="numero_mecanografico" class="form-label">Número Mecanográfico:</label>
                                    <input type="text" class="form-control" id="numero_mecanografico" name="numero_mecanografico" value="{{ $medico->funcionario->numero_mecanografico ?? old('numero_mecanografico') }}" placeholder="Informe número mecanográfico">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.nome') }}:</label>
                                    <input type="text" class="form-control" name="nome" value="{{ $medico->funcionario->nome ?? old('nome') }}" placeholder="Informe Nome">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Nº Contribuente:</label>
                                    <input type="text" class="form-control" name="nif" value="{{ $medico->funcionario->nif ?? old('nif') }}" placeholder="Informe NIF">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="categoria" class="form-label">{{ __('messages.categoria') }}</label>
                                    <select type="text" class="form-control select2" name="categoria" id="categoria">
                                        <option value="Empregados" {{ $medico->funcionario->categoria == "Empregados" ? 'selected' : '' }}>Empregados</option>
                                        <option value="Orgão Sociais" {{ $medico->funcionario->categoria == "Orgão Sociais" ? 'selected' : '' }}>Orgão Social</option>
                                        <option value="Pessoal" {{ $medico->funcionario->categoria == "Pessoal" ? 'selected' : '' }}>Pessoal</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.data_nascimento') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="date" class="form-control" name="data_nascimento" value="{{ $medico->funcionario->data_nascimento ?? old('data_nascimento') }}" placeholder="{{ __('messages.data_nascimento') }}">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.genero') }}<span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="genero">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        <option value="Masculino" {{ $medico->funcionario->genero == "Masculino" ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ $medico->funcionario->genero == "Femenino" ? 'selected' : '' }}>Femenino</option>
                                        <option value="Personalizado" {{ $medico->funcionario->genero == "Personalizado" ? 'selected' : '' }}>Personalizado</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.estado_civil') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="estado_civil_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($estados_civils as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $medico->funcionario->estado_civil_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Província <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="provincia_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $medico->funcionario->provincia_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Município <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="municipio_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $medico->funcionario->municipio_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Distritos <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="distrito_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $medico->funcionario->distrito_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">País <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control" name="pais">
                                        @include('includes.paises')
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Código Postal <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="codigo_postal" value="{{ $medico->funcionario->codigo_postal ?? old('codigo_postal') }}" placeholder="Informe codigo Postal">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Localidade <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="localidade" value="{{ $medico->funcionario->localidade ?? old('localidade') }}" placeholder="Informe  Localidade">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.telefone') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="telefone" value="{{ $medico->funcionario->telefone ?? old('telefone') }}" placeholder="{{ __('messages.telefone') }}">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.telemovel') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="telemovel" value="{{ $medico->funcionario->telemovel ?? old('telemovel') }}" placeholder="{{ __('messages.telemovel') }}">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label"> {{ __('messages.email') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="email" class="form-control" name="email" value="{{ $medico->funcionario->email ?? old('email') }}" placeholder="{{ __('messages.email') }}">
                                </div>


                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>Dados Profissionais</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="tipo" class="form-label">Tipo de Profissional:</label>
                                        <select type="text" class="form-control select2" name="tipo" aria-placeholder="Nome da entidade que forneceu o registro" id="tipo">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="Medico" {{ $medico->tipo == "Medico" ? 'selected' : '' }}>Médico</option>
                                            <option value="Enfermeiro" {{ $medico->tipo == "Enfermeiro" ? 'selected' : '' }}>Enfermeiro</option>
                                            <option value="Tecnico" {{ $medico->tipo == "Tecnico" ? 'selected' : '' }}>Técnico</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="numero_cedula" class="form-label">Número da Cédula <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <input type="numero_cedula" class="form-control" name="numero_cedula" value="{{ $medico->numero_cedula ?? old('numero_cedula') }}" placeholder="Número da cédula ou registro profissional">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="entidade_registradora" class="form-label">Entidade Registradora:</label>
                                        <select type="text" class="form-control select2" name="entidade_registradora" aria-placeholder="Nome da entidade que forneceu o registro" id="entidade_registradora">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="MINSA" {{ $medico->entidade_registradora == "MINSA" ? 'selected' : '' }}>MINSA</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="data_emissao_cedula" class="form-label">Data de Emissão da Cédula:</label>
                                        <input type="date" class="form-control" id="data_emissao_cedula" name="data_emissao_cedula" value="{{ $medico->data_emissao_cedula ?? old('data_emissao_cedula') }}" placeholder="Data em que o registro foi emitido">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="data_validade_cedula" class="form-label">Data de Validade da Cédula:</label>
                                        <input type="date" class="form-control" id="data_validade_cedula" name="data_validade_cedula" value="{{ $medico->data_validade_cedula ?? old('data_validade_cedula') }}" placeholder="Data de validade, se houver">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="status_profissional" class="form-label">Estado Profissional:</label>
                                        <select type="text" class="form-control select2" name="status_profissional" id="status_profissional">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="Activo" {{ $medico->status_profissional == "Activo" ? 'selected' : '' }}>{{ __('messages.activo') }} </option>
                                            <option value="Inactivo" {{ $medico->status_profissional == "Inactivo" ? 'selected' : '' }}>Inactivo</option>
                                            <option value="Suspenso" {{ $medico->status_profissional == "Suspenso" ? 'selected' : '' }}>Suspenso</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="especialidade_id" class="form-label">Especialidades:</label>
                                        <select type="text" class="form-control select2" name="especialidade_id" id="especialidade_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($especialidades as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $medico->especialidade_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="provincia_registro" class="form-label">Província Registro:</label>
                                        <select type="text" class="form-control select2" id="provincia_registro" name="provincia_registro">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($provincias as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $medico->provincia_registro == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>Documentos</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="numero_bilhete" class="form-label">Número do Bilhete de Identidade:</label>
                                        <input type="text" class="form-control" name="numero_bilhete" id="numero_bilhete" value="{{ $medico->funcionario->numero_bilhete ?? old('numero_bilhete') }}" placeholder="Informe Número do Bilhete de Identidade">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="local_emissao_bilhete" class="form-label">Local Emissão Bilhete:</label>
                                        <input type="text" class="form-control" name="local_emissao_bilhete" id="local_emissao_bilhete" value="{{ $medico->funcionario->local_emissao_bilhete ?? old('local_emissao_bilhete') }}" placeholder="Informe local emissão bilhete">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="data_emissao_bilhete" class="form-label">Data Emissão B.I <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <input type="date" class="form-control" name="data_emissao_bilhete" id="data_emissao_bilhete" value="{{ $medico->funcionario->data_emissao_bilhete ?? old('data_emissao_bilhete') }}" placeholder="Data">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="validade_bilhete" class="form-label">Data Validade B.I <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <input type="date" class="form-control" id="validade_bilhete" name="validade_bilhete" value="{{ $medico->funcionario->validade_bilhete ?? old('validade_bilhete') }}" placeholder="Validade">
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="numero_passaporte" class="form-label">Número do Passaporte:</label>
                                        <input type="text" class="form-control" name="numero_passaporte" id="numero_passaporte" value="{{ $medico->funcionario->numero_passaporte ?? old('numero_passaporte') }}" placeholder="Informe Número do Passaporte">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="local_emissao_passaporte" class="form-label">Local Emissão Passaporte:</label>
                                        <input type="text" class="form-control" name="local_emissao_passaporte" id="local_emissao_passaporte" value="{{ $medico->funcionario->local_emissao_passaporte ?? old('local_emissao_passaporte') }}" placeholder="Informe local emissão passaporte">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="data_emissao_passaporte" class="form-label">Data Emissão Passaporte <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <input type="date" class="form-control" name="data_emissao_passaporte" id="data_emissao_passaporte" value="{{ $medico->funcionario->data_emissao_passaporte ?? old('data_emissao_passaporte') }}" placeholder="Data">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="validade_passaporte" class="form-label">Data Validade Passaporte <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <input type="date" class="form-control" id="validade_passaporte" name="validade_passaporte" value="{{ $medico->funcionario->validade_passaporte ?? old('validade_passaporte') }}" placeholder="Validade">
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="card">
                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar funcionario'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
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

</script>
@endsection
