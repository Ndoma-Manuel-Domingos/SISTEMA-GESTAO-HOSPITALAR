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
                        <li class="breadcrumb-item"><a href="{{ route('equipas.index') }}">{{ __('messages.voltar') }}</a></li>
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
                    <form action="{{ route('equipas.update', $equipa->id) }}" method="post" class="">
                        <div class="card">
                            @csrf
                            @method('put')
                            <div class="card-body row">
                                <input type="hidden" name="equipa_id" id="equipa_id" value="{{ $equipa->id }}">

                                <div class="col-12 col-md-3">
                                    <label for="nome" class="form-label"> {{ __('messages.designacao') }} </label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="nome" name="nome" value="{{ $equipa->nome ?? old('nome') }}" placeholder="Informe a uma designação">
                                    </div>
                                    <p class="text-light-danger"> @error('nome') {{ $message }} @enderror </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="responsavel_id" class="form-label">Selecionar Responsável</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="responsavel_id" id="responsavel_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($profissionais as $item)
                                            <option value="{{ $item->id ?? "" }}" {{ $item->id ==  $equipa->responsavel_id ? "selected" : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="area_atuacao" class="form-label">Área de Atuação</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="area_atuacao" name="area_atuacao" value="{{ $equipa->area_atuacao ?? old('area_atuacao') }}" placeholder="Informe  area atuação">
                                    </div>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="cargo" class="form-label">{{ __('messages.cargos') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="cargo" name="cargo" value="{{ old('cargo') }}" placeholder="Informe um cargo">
                                    </div>
                                    <p class="text-light-danger"> @error('cargo') {{ $message }} @enderror </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="profissional_id" class="form-label">Selecionar Membros</label>
                                    <div class="input-group mb-3">
                                        <select type="text" class="form-control select2" name="profissional_id" id="profissional_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($profissionais as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-light-danger"> @error('medico_id') {{ $message }} @enderror </p>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </div>
                    </form>

                    @php $total = 0; @endphp
                    @foreach ($equipa->membros as $item1)
                    @php $total++; @endphp
                    @endforeach

                    <div class="card">
                        <div class="card-header">
                            <h5>Membros:
                                <span id="valor_total" class="float-right">{{ number_format($total, 0, ',', '.') }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="col-12 col-md-12 my-3">
                                <table id="tabela-exames-update" class="table table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.designacao') }}</th>
                                            <th>Cargo</th>
                                            <th class="text-right">{{ __('messages.accoes') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($equipa->membros as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{$item->profissional->nome}}</td>
                                            <td>{{$item->cargo}}</td>
                                            <td><a href="#" data-id="{{ $item->id ?? "" }}" class="float-right delete-record text-light-danger"><i class="fas fa-trash"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer"></div>
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
    let total_exames_preco = 0;
    $('#profissional_id').on('change', function() {

        const profissional_id = $(this).val();
        const equipa_id = $("#equipa_id").val();
        const cargo = $("#cargo").val();

        if (profissional_id) {
            $.ajax({
                url: '/actualizar-membros-equipas'
                , method: 'POST'
                , data: {
                    _token: '{{ csrf_token() }}'
                    , profissional_id
                    , equipa_id
                    , cargo
                , }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(res) {
                    Swal.close();

                    $("#tabela-exames-update tbody").html("")

                    for (let index = 0; index < res.items.length; index++) {
                        let item = res.items[index];

                        $("#tabela-exames-update tbody").append(`
                    <tr>
                      <td>${ index + 1 }</td>
                      <td>${item.profissional.nome}</td>
                      <td>${item.cargo}</td>
                      <td><a href="#" data-id="${item.id}" class="float-right delete-record text-light-danger"><i class="fas fa-trash"></i></a></td>
                    </tr>
                  `);
                    }
                    $("#valor_total").text(formatarMoeda(res.total));
                }
                , error: function(err) {
                    Swal.close();
                    // showMessage('Erro de Validação!', "ocorreu um erro ao adicionar um serviço" 'error');
                    console.log(err);
                }
            });
        }
    });

    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
        const equipaId = $("#equipa_id").val();

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
                    url: `{{ route('actualizar-membros-equipas-delete', ['id' => ':id', 'equipa_id' => ':equipa_id']) }}`.replace(':id', recordId).replace(':equipa_id', equipaId)
                    , method: 'DELETE'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(res) {
                        Swal.close();
                        $("#tabela-exames-update tbody").html("")
                        for (let index = 0; index < res.items.length; index++) {
                            let item = res.items[index];

                            $("#tabela-exames-update tbody").append(`
                        <tr>
                          <td>${ index + 1 }</td>
                          <td>${item.profissional.nome}</td>
                          <td>${item.cargo}</td>
                          <td><a href="#" data-id="${item.id}" class="float-right delete-record text-light-danger"><i class="fas fa-trash"></i></a></td>
                        </tr>
                      `);
                        }

                        $("#valor_total").text(formatarMoeda(res.total));
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
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

    function formatarMoeda(valor) {
        return valor.toLocaleString('pt-AO', {
            style: 'currency'
            , currency: 'AOA' // Kwanza (Angola)
        });
    }

</script>
@endsection
