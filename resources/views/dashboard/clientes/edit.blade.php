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
                    <div class="card">
                        <form action="{{ route('clientes.update', $cliente->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.nome') }}:</label>
                                    <input type="text" class="form-control" name="nome" value="{{ $cliente->nome }}" placeholder="Informe Nome">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">NIF:</label>
                                    <input type="text" class="form-control" name="nif" value="{{ $cliente->nif ?? old('nif') }}" placeholder="Informe NIF">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="tipo_cliente" class="form-label">Tipo Cliente <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="tipo_cliente">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        <option value="C" {{ $cliente->tipo_cliente == 'C' ? 'selected' : '' }}>
                                            Correntes</option>
                                        <option value="TR" {{ $cliente->tipo_cliente == 'TR' ? 'selected' : '' }}>
                                            Títulos a Receber</option>
                                        <option value="TD" {{ $cliente->tipo_cliente == 'TD' ? 'selected' : '' }}>
                                            Títulos Descontados</option>
                                        <option value="CD" {{ $cliente->tipo_cliente == 'CD' ? 'selected' : '' }}>
                                            Cobrança Duvidosa</option>
                                        <option value="SC" {{ $cliente->tipo_cliente == 'SC' ? 'selected' : '' }}>
                                            Saldos Credores</option>
                                    </select>
                                </div>
                                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Nome Pai <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="nome_do_pai" value="{{ $cliente->nome_do_pai ?? old('nome_do_pai') }}" placeholder="Informe Nome do Pai">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Nome Mãe <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="nome_da_mae" value="{{ $cliente->nome_da_mae ?? old('nome_da_mae') }}" placeholder="Informe Nome mãe">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.data_nascimento') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="date" class="form-control" name="data_nascimento" value="{{ $cliente->data_nascimento ?? old('data_nascimento') }}" placeholder="{{ __('messages.data_nascimento') }}">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Gênero <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="genero">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        <option value="Masculino" {{ $cliente->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ $cliente->genero == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                        <option value="Personalizado" {{ $cliente->genero == 'Personalizado' ? 'selected' : '' }}>Personalizado
                                        </option>
                                    </select>
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Província <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="provincia_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $cliente->provincia_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Município <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="municipio_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $cliente->municipio_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Distritos <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="distrito_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $cliente->distrito_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">País <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control" name="pais">
                                        @include('includes.paises')
                                    </select>
                                </div>

                                @endif

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.estado_civil') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="estado_civil_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($estados_civils as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $cliente->estado_civil_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="plano_id" class="form-label">Plano(Seguradora) <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" id="plano_id" name="plano_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($planos as $item)

                                        <option value="{{ $item->id ?? "" }}" {{ $item->id == ($cliente->plano ? $cliente->plano->plano_id  : null) ? 'selected' : '' }}>{{ $item->nome }} - {{ $item->seguradora->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Código Postal <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="codigo_postal" value="{{ $cliente->codigo_postal ?? old('codigo_postal') }}" placeholder="Informe codigo Postal">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Localidade <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="localidade" value="{{ $cliente->localidade ?? old('localidade') }}" placeholder="Informe  Localidade">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.telefone') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="telefone" value="{{ $cliente->telefone ?? old('telefone') }}" placeholder="{{ __('messages.telefone') }}">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.telemovel') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="telemovel" value="{{ $cliente->telemovel ?? old('telemovel') }}" placeholder="{{ __('messages.telemovel') }}">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label"> {{ __('messages.email') }} <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="email" class="form-control" name="email" value="{{ $cliente->email ?? old('email') }}" placeholder="{{ __('messages.email') }}...">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">Website <span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="website" value="{{ $cliente->website ?? old('website') }}" placeholder="Informe WebSite">
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="" class="form-label">{{ __('messages.observacao') }}<span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="observacao" value="{{ $cliente->observacao ?? old('observacao') }}" placeholder="Informe Observação">
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="responsavel_nome" class="form-label">Nome do Responsável<span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="responsavel_nome" name="responsavel_nome" value="{{ $cliente->responsavel_nome ?? old('responsavel_nome') }}" placeholder="Nome do Responsável">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="responsavel_contacto" class="form-label">Contacto do Responsável<span class="text-light-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" id="responsavel_contacto" name="responsavel_contacto" value="{{ $cliente->responsavel_contacto ?? old('responsavel_contacto') }}" placeholder="Nome do Responsável">
                                </div>



                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar cliente'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </form>
                    </div>
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
