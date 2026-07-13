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
                        <li class="breadcrumb-item"><a href="{{ route('planos-tratamentos.index') }}">{{ __('messages.voltar') }}</a></li>
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
            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('planos-tratamentos.update', $tratamento->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                    @if (Auth::user()->can('adicionar item conta hospitalar'))
                                    <a href="{{ route('atendimentos.show', $tratamento->atendimento_id) }}" class="btn btn-light-primary">Actualizar conta Hospitalar do paciente</a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body row">

                                <div class="col-12 col-md-6">
                                    <label for="titulo" class="form-label">Titulo</label>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="titulo" id="titulo" cols="30" rows="3" placeholder="Ex: Plano de reabilitação pós-cirúrgia ">{{ $tratamento->titulo }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="descricao" class="form-label"> {{ __('messages.descricao') }} </label>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="descricao" id="descricao" cols="30" rows="3" placeholder="Descrição do plano geral: ">{{ $tratamento->descricao }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="paciente_id" class="form-label">Pacientes</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" style="width: 100%" id="paciente_id" name="paciente_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($pacientes as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $tratamento->paciente_id == $item->id ? "selected" : ""  }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="frequencia" class="form-label">Frequência</label>
                                    <input type="text" class="form-control" id="frequencia" value="{{ $tratamento->frequencia ?? old("frequencia") }}" placeholder="Ex: 3 vezes por semana, diário, semanal" name="frequencia">
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="duracao_semanas" class="form-label">Duração Semanas</label>
                                    <input type="number" class="form-control" placeholder="Duração total em semanas" value="{{ $tratamento->duracao_semanas ?? old("duracao_semanas") }}" id="duracao_semanas" name="duracao_semanas">
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <input type="date" class="form-control" id="data_inicio" value="{{ $tratamento->data_inicio ?? old('data_inicio') }}" name="data_inicio">
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo" class="form-label">Tipo</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control " id="tipo" name="tipo">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            <option value="medicamentoso" {{ $tratamento->tipo == "medicamentoso" ? "selected" : ""  }}>Medicamentoso</option>
                                            <option value="fisioterapia" {{ $tratamento->tipo == "fisioterapia" ? "selected" : ""  }}>Fisioterapia</option>
                                            <option value="psicologico" {{ $tratamento->tipo == "psicologico" ? "selected" : ""  }}>Psicológico</option>
                                            <option value="misto" {{ $tratamento->tipo == "misto" ? "selected" : ""  }}>Misto</option>
                                            <option value="nutricional" {{ $tratamento->tipo == "nutricional" ? "selected" : ""  }}>Nutricional</option>
                                            <option value="outros" {{ $tratamento->tipo == "outros" ? "selected" : ""  }}>Outros</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="equipa_id" class="form-label">Equipa</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control " id="equipa_id" name="equipa_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($equipas as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $tratamento->equipa_id == $item->id ? "selected" : ""  }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="objectivo" class="form-label">Objectivo</label>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="objectivo" id="objectivo" cols="30" rows="3" placeholder="Ex: Finalidade terpêutica ">{{ $tratamento->objectivo }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="orientacoes_gerais" class="form-label">Orientações Gerais</label>
                                    <div class="input-group mb-3">
                                        <textarea class="form-control" name="orientacoes_gerais" id="orientacoes_gerais" cols="30" rows="3" placeholder="Ex: -regras gerais para o paciente seguir">{{ $tratamento->orientacoes_gerais }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('editar todos') || Auth::user()->can('editar tratemento'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
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

                    showMessage('Sucesso!', response.message, 'success');
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
