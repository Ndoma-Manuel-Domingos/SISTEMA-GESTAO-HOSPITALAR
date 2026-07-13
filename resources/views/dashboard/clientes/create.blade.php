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
                        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">{{ __('messages.voltar') }}</a></li>
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
                    <form action="{{ route('clientes.store') }}" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-body row">
                                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                                <div class="col-12 col-md-6">
                                    <label for="tipo_atendimento_id" class="form-label">Tipos de Atendimentos (Destino):</label>
                                    <select type="text" class="form-control select2" id="tipo_atendimento_id" name="tipo_atendimento_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($tipos_atendimentos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ old('tipo_atendimento_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" value="{{ $parent_id }}" name="parent_id">

                                <div class="col-12 col-md-6">
                                    <label for="prioridade_id" class="form-label">Prioridade:</label>
                                    <select type="text" class="form-control select2" id="prioridade_id" name="prioridade_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($prioridades as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ old('prioridade_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <div class="col-12 col-md-3">
                                    <label for="nome" class="form-label">{{ __('messages.nome') }}:</label>
                                    <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Informe Nome">
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="nif" class="form-label">NIF:</label>
                                    <input type="text" class="form-control" id="nif" name="nif" value="{{ old('nif') }}" placeholder="Informe NIF">
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo_cliente" class="form-label">Tipo Cliente <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" id="tipo_cliente" name="tipo_cliente">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        <option value="C" {{ old('tipo_cliente') == "C" ? 'selected' : '' }} selected>Correntes</option>
                                        <option value="TR" {{ old('tipo_cliente') == "TR" ? 'selected' : '' }}>Títulos a Receber</option>
                                        <option value="TD" {{ old('tipo_cliente') == "TD" ? 'selected' : '' }}>Títulos Descontados</option>
                                        <option value="CD" {{ old('tipo_cliente') == "CD" ? 'selected' : '' }}>Cobrança Duvidosa</option>
                                        <option value="SC" {{ old('tipo_cliente') == "SC" ? 'selected' : '' }}>Saldos Credores</option>
                                    </select>
                                </div>


                                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="nome_do_pai" class="form-label">Nome Pai <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="nome_do_pai" name="nome_do_pai" value="{{ old('nome_do_pai') }}" placeholder="Informe Nome do Pai">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="nome_da_mae" class="form-label">Nome Mãe <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="nome_da_mae" name="nome_da_mae" value="{{ old('nome_da_mae') }}" placeholder="Informe Nome mãe">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="data_nascimento" class="form-label">{{ __('messages.data_nascimento') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}" placeholder="{{ __('messages.data_nascimento') }}">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="idade" class="form-label">{{ __('messages.idade') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="idade" name="idade" value="{{ old('idade') }}" placeholder="informe a sua idade">
                                </div>
                                @endif


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="genero" class="form-label">Gênero <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" id="genero" name="genero">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        <option value="Masculino" {{ old('genero') == "Masculino" ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ old('genero') == "Femenino" ? 'selected' : '' }}>Femenino</option>
                                        <option value="Personalizado" {{ old('genero') == "Personalizado" ? 'selected' : '' }}>Personalizado</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="estado_civil_id" class="form-label">{{ __('messages.estado_civil') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" id="estado_civil_id" name="estado_civil_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($estados_civils as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ old('estado_civil_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="plano_id" class="form-label">Plano(Seguradora) <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" id="plano_id" name="plano_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($planos as $item)
                                        <option value="{{ $item->id ?? "" }}">{{ $item->nome }} - {{ $item->seguradora->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="provincia_id" class="form-label">Província <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" id="provincia_id" name="provincia_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ old('provincia_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="municipio_id" class="form-label">Município <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" id="municipio_id" name="municipio_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ old('municipio_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="distrito_id" class="form-label">Distritos <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" id="distrito_id" name="distrito_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ old('distrito_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="pais" class="form-label">País <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control" name="pais" id="pais">
                                        @include('includes.paises')
                                    </select>
                                </div>
                                @endif

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="codigo_postal" class="form-label">Código Postal <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}" placeholder="Informe codigo Postal">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="localidade" class="form-label">Endereço <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="localidade" name="localidade" value="{{ old('localidade') }}" placeholder="Informe  Localidade">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="telefone" class="form-label">{{ __('messages.telefone') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone') }}" placeholder="{{ __('messages.telefone') }}">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="telemovel" class="form-label">Pessoa de contacto <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="telemovel" name="telemovel" value="{{ old('telemovel') }}" placeholder="{{ __('messages.telemovel') }}">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="email" class="form-label"> {{ __('messages.email') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('messages.email') }}">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="website" class="form-label">Website <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="website" name="website" value="{{ old('website') }}" placeholder="Informe WebSite">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="observacao" class="form-label">Regime<span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="observacao" name="observacao" value="{{ old('observacao') }}" placeholder="Regime">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="responsavel_nome" class="form-label">Nome do Responsável<span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="responsavel_nome" name="responsavel_nome" value="{{ old('responsavel_nome') }}" placeholder="Nome do Responsável">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="responsavel_contacto" class="form-label">Contacto do Responsável<span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="responsavel_contacto" name="responsavel_contacto" value="{{ old('responsavel_contacto') }}" placeholder="Nome do Responsável">
                                </div>

                            </div>
                        </div>

                        @if ($empresa_logada->empresa->tipo_entidade->sigla == "CFOR")
                        <div class="card">
                            <div class="card-body row">
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="curso_id" class="form-label">Curso</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="curso_id" name="curso_id">
                                            @foreach ($cursos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="turno_id" class="form-label">Turno</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="turno_id" name="turno_id">
                                            @foreach ($turnos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="sala_id" class="form-label">Sala</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="sala_id" name="sala_id">
                                            @foreach ($salas as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="ano_lectivo_id" class="form-label">Ano Lectivo</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" id="ano_lectivo_id" name="ano_lectivo_id">
                                            @foreach ($anos_lectivos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="card">
                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar cliente'))
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
