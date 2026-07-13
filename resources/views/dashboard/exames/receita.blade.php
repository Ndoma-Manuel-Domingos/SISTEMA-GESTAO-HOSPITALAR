@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Receita médica da <a href="{{ route("consultas.show", $consulta->id) }}">consulta Nª {{ $consulta->id }}</a> - Paciente: <a href="{{ route("clientes.show", $consulta->paciente->id) }}">{{ $consulta->paciente->nome }}</a></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('consultas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Consultas</li>
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
                    <form action="{{ route('consulta-receita-medica-post') }}" method="post" class="">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label for="paciente_id" class="form-label">Paciente</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" disabled name="paciente_id" id="paciente_id" value="{{ $consulta->paciente->nome }}">
                                            <input type="hidden" class="form-control" name="consulta_id" id="consulta_id" value="{{ $consulta->id }}">
                                        </div>
                                        <p class="text-light-danger"> @error('paciente_id') {{ $message }} @enderror </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="cids_id" class="form-label">CIDS</label>
                                        <div class="input-group mb-3">
                                            <select type="text" class="form-control select2" name="cids_id" id="cids_id">
                                                <option value="activo">Selecionar</option>
                                                @foreach ($cids as $item)
                                                <option value="{{ $item->id ?? "" }}" {{ $item->id == $consulta->cids_id ? 'selected' : ''  }}>{{ $item->sigla }} - {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <p class="text-light-danger"> @error('cids_id') {{ $message }} @enderror </p>
                                    </div>

                                    <div class="col-12 col-md-12 my-4">
                                        <table id="tabela-medicamentos" class="table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Medicamento</th>
                                                    <th>Posologia</th>
                                                    <th>Duração (dias)</th>
                                                    <th>{{ __('messages.observacao') }}</th>
                                                    <th>{{ __('messages.accoes') }} </th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        <button type="button" class="btn btn-light-primary btn-sm" id="adicionar-medicamento">+ Adicionar Medicamento</button>
                                    </div>


                                    <div class="col-12 col-md-12">
                                        <label for="observacoes" class="form-label">{{ __('messages.observacao') }}:</label>
                                        <div class="input-group mb-3">
                                            <textarea name="observacoes" class="form-control" id="observacoes" cols="30" rows="3" placeholder="Durante ou ao final">{{ $consulta->observacoes }}</textarea>
                                        </div>
                                        <p class="text-light-danger"> @error('observacoes') {{ $message }} @enderror </p>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer d-flex">
                                <button type="submit" class="btn btn-light-success">{{ __('messages.salvar') }}</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('consulta-receita-medica', $consulta->id) }}" class="btn bg-light-dark  btn-app"><i class="fas fa-prescription"></i> Receita</a>
                            <a href="#" class="btn bg-light-dark  btn-app"><i class="fas fa-vials"></i> Exames</a>
                            <a href="{{ route('consulta-atestado-medico', $consulta->id) }}" class="btn bg-light-dark  btn-app" target="_blank"><i class="fas fa-file-medical-alt"></i> Atestado médico</a>
                            <a href="#" class="btn bg-light-dark  btn-app"><i class="fas fa-share-square"></i> Encaminhamento</a>
                            <a href="#" class="btn bg-light-dark  btn-app"><i class="fas fa-notes-medical"></i> Plano de Tratamento</a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    let contador = 0;

    $('#adicionar-medicamento').click(function() {
        contador++;
        $('#tabela-medicamentos tbody').append(`
            <tr>
                <td><input class="form-control col-md-12 col-12" type="text" name="medicamentos[${contador}][medicamento]" required></td>
                <td><input class="form-control col-md-12 col-12" type="text" name="medicamentos[${contador}][posologia]" required></td>
                <td><input class="form-control col-md-12 col-12" type="number" name="medicamentos[${contador}][duracao]" required></td>
                <td><input class="form-control col-md-12 col-12" type="text" name="medicamentos[${contador}][observacoes]"></td>
                <td><button type="button" class="remover btn-sm btn-light-primary"><i class="fas fa-trash"></i></button></td>
            </tr>
        `);
    });

    // Remover medicamento
    $(document).on('click', '.remover', function() {
        $(this).closest('tr').remove();
    });


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

                    Swal.close();

                    showMessage('Sucesso!', 'Dados actualizados com sucesso!', 'success');

                    window.open(`/consultas/receitas-medica/${response.receita.id}/imprimir`, '_blank');

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
