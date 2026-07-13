@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</h1>
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
                <div class="col-12 col-md-12">
                    <form action="{{ route('medicos.store') }}" method="post" class="">
                        @csrf
                        <div class="card">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="numero_mecanografico" class="form-label">Número Mecanográfico: <span class="text-light-danger">*</span></label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="numero_mecanografico" name="numero_mecanografico" value="{{ old('numero_mecanografico') }}" placeholder="Informe número mecanográfico">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="nome" class="form-label">{{ __('messages.nome') }}: <span class="text-light-danger">*</span></label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="Informe Nome">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="nif" class="form-label">Nº Contribuente: <span class="text-light-danger">*</span></label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="nif" value="{{ old('nif') }}" placeholder="Informe NIF">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="categoria" class="form-label">{{ __('messages.categoria') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <select type="text" class="form-control select2" name="categoria" id="categoria">
                                            <option value="Empregados" {{ old('categoria') == "Empregados" ? 'selected' : '' }}>Empregados</option>
                                            <option value="Orgão Sociais" {{ old('categoria') == "Orgão Sociais" ? 'selected' : '' }}>Orgão Social</option>
                                            <option value="Pessoal" {{ old('categoria') == "Pessoal" ? 'selected' : '' }}>Pessoal</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_nascimento" class="form-label">{{ __('messages.data_nascimento') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}" placeholder="{{ __('messages.data_nascimento') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="idade" class="form-label">{{ __('messages.idade') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="idade" name="idade" value="{{ old('idade') }}" placeholder="informe a sua idade">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="genero" class="form-label">{{ __('messages.genero') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <select type="text" class="form-control select2" name="genero" id="genero">
                                            <option value="Masculino" {{ old('genero') == "Masculino" ? 'selected' : '' }} selected>Masculino</option>
                                            <option value="Femenino" {{ old('genero') == "Femenino" ? 'selected' : '' }}>Femenino</option>
                                            <option value="Personalizado" {{ old('genero') == "Personalizado" ? 'selected' : '' }}>Personalizado</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="estado_civil_id" class="form-label">{{ __('messages.estado_civil') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <select type="text" class="form-control select2" name="estado_civil_id" id="estado_civil_id">
                                            @foreach ($estados_civils as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="provincia_id" class="form-label">Província <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <select type="text" class="form-control select2" id="provincia_id" name="provincia_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($provincias as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ old('provincia_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="municipio_id" class="form-label">Município <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <select type="text" class="form-control select2" id="municipio_id" name="municipio_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($municipios as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ old('municipio_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="distrito_id" class="form-label">Distritos <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <select type="text" class="form-control select2" name="distrito_id" id="distrito_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($distritos as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ old('distrito_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="pais" class="form-label">País <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <select type="text" class="form-control" name="pais" id="pais">
                                            @include('includes.paises')
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="codigo_postal" class="form-label">Código Postal <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}" placeholder="Informe codigo Postal">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="localidade" class="form-label">Localidade <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="localidade" name="localidade" value="{{ old('localidade') }}" placeholder="Informe  Localidade">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="telefone" class="form-label">{{ __('messages.telefone') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone') }}" placeholder="{{ __('messages.telefone') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="telemovel" class="form-label">{{ __('messages.telemovel') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="telemovel" name="telemovel" value="{{ old('telemovel') }}" placeholder="{{ __('messages.telemovel') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="email" class="form-label"> {{ __('messages.email') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <div class="mb-3">
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('messages.email') }}">
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer"></div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>Dados Profissionais</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-3">
                                        <div class="row">
                                            <div class="col-12 col-md-6 mb-3">
                                                <label for="tipo" class="form-label">Tipo de Profissional:</label>
                                                <select type="text" class="form-control select2" name="tipo" id="tipo">
                                                    <option value="">{{ __('messages.escolher') }} </option>
                                                    <option value="Medico" selected>Médico</option>
                                                    <option value="Enfermeiro">Enfermeiro</option>
                                                    <option value="Tecnico">Técnico</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6 mb-3">
                                                <label for="tipo_acesso" class="form-label">Perfil de Acesso <span class="text-light-danger">*</span></label>
                                                <select type="text" class="form-control select2" name="tipo_acesso" id="tipo_acesso">
                                                    <option value="">Não Terá Acesso</option>
                                                    @foreach ($roles as $item)
                                                    <option value="{{ $item->id ?? "" }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="numero_cedula" class="form-label">Número da Cédula: <span class="text-light-danger">*</span></label>
                                        <input type="numero_cedula" class="form-control" name="numero_cedula" value="{{ old('numero_cedula') }}" placeholder="Número da cédula ou registro profissional">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="data_emissao_cedula" class="form-label">Data de Emissão da Cédula <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <input type="date" class="form-control" id="data_emissao_cedula" name="data_emissao_cedula" value="{{ old('data_emissao_cedula') }}" placeholder="Data em que o registro foi emitido">
                                    </div>


                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="entidade_registradora" class="form-label">Entidade Registradora <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <select type="text" class="form-control select2" name="entidade_registradora" aria-placeholder="Nome da entidade que forneceu o registro" id="entidade_registradora">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="MINSA" {{ old('entidade_registradora') == "MINSA" ? 'selected' : '' }}>MINSA</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="data_validade_cedula" class="form-label">Data de Validade da Cédula <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <input type="date" class="form-control" id="data_validade_cedula" name="data_validade_cedula" value="{{ old('data_validade_cedula') }}" placeholder="Data de validade, se houver">
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="status_profissional" class="form-label">Estado Profissional <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <select type="text" class="form-control select2" name="status_profissional" id="status_profissional">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="Activo" {{ old('status_profissional') == "Activo" ? 'selected' : '' }}>{{ __('messages.activo') }} </option>
                                            <option value="Inactivo" {{ old('status_profissional') == "Inactivo" ? 'selected' : '' }}>Inactivo</option>
                                            <option value="Suspenso" {{ old('status_profissional') == "Suspenso" ? 'selected' : '' }}>Suspenso</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="especialidade_id" class="form-label">Especialidades <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <select type="text" class="form-control select2" name="especialidade_id" id="especialidade_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($especialidades as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ old('especialidade_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="provincia_registro" class="form-label">Província Registro <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <select type="text" class="form-control select2" id="provincia_registro" name="provincia_registro">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($provincias as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ old('provincia_registro') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer"></div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>Documentos</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-3">
                                        <label for="numero_bilhete" class="form-label">Número do Bilhete de Identidade <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="numero_bilhete" id="numero_bilhete" value="{{ old('numero_bilhete') }}" placeholder="Informe Número do Bilhete de Identidade">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="local_emissao_bilhete" class="form-label">Local Emissão Bilhete <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="local_emissao_bilhete" id="local_emissao_bilhete" value="{{ old('local_emissao_bilhete') }}" placeholder="Informe local emissão bilhete">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_emissao_bilhete" class="form-label">Data Emissão B.I <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <div class="mb-3">
                                            <input type="date" class="form-control" name="data_emissao_bilhete" id="data_emissao_bilhete" value="{{ old('data_emissao_bilhete') }}" placeholder="Data">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="validade_bilhete" class="form-label">Data Validade B.I <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <div class="mb-3">
                                            <input type="date" class="form-control" id="validade_bilhete" name="validade_bilhete" value="{{ old('validade_bilhete') }}" placeholder="Validade">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-12 col-md-3">
                                        <label for="numero_passaporte" class="form-label">Número do Passaporte <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="numero_passaporte" id="numero_passaporte" value="{{ old('numero_passaporte') }}" placeholder="Informe Número do Passaporte">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="local_emissao_passaporte" class="form-label">Local Emissão Passaporte <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="local_emissao_passaporte" id="local_emissao_passaporte" value="{{ old('local_emissao_passaporte') }}" placeholder="Informe local emissão passaporte">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_emissao_passaporte" class="form-label">Data Emissão Passaporte <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <div class="mb-3">
                                            <input type="date" class="form-control" name="data_emissao_passaporte" id="data_emissao_passaporte" value="{{ old('data_emissao_passaporte') }}" placeholder="Data">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="validade_passaporte" class="form-label">Data Validade Passaporte <span class="text-light-secondary">(Opcional)</span>:</label>
                                        <div class="mb-3">
                                            <input type="date" class="form-control" id="validade_passaporte" name="validade_passaporte" value="{{ old('validade_passaporte') }}" placeholder="Validade">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer"></div>
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


    const dataNascimentoInput = document.getElementById('data_nascimento');
    const idadeInput = document.getElementById('idade');

    // Quando mudar a data de nascimento → calcula a idade
    dataNascimentoInput.addEventListener('change', function() {
        const dataNascimento = new Date(this.value);
        const hoje = new Date();
        let idade = hoje.getFullYear() - dataNascimento.getFullYear();
        const m = hoje.getMonth() - dataNascimento.getMonth();

        if (m < 0 || (m === 0 && hoje.getDate() < dataNascimento.getDate())) {
            idade--;
        }

        if (!isNaN(idade)) {
            idadeInput.value = idade;
        }
    });

    // Quando mudar a idade → calcula a data de nascimento (estimada)
    idadeInput.addEventListener('input', function() {
        const idade = parseInt(this.value);
        if (!isNaN(idade)) {
            const hoje = new Date();
            const anoNascimento = hoje.getFullYear() - idade;
            const dataEstimativa = new Date(anoNascimento, hoje.getMonth(), hoje.getDate());
            dataNascimentoInput.valueAsDate = dataEstimativa;
        }
    });

</script>
@endsection
