@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestão de Documento</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Home</a></li>
                        <li class="breadcrumb-item active">
                            <button type="button" onclick="toggleModal()" class="btn btn-light-primary">
                                <i class="fas fa-folder"></i> {{ __('messages.novo') }}
                            </button>
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            @if (count($documentos) != 0)
            <div class="row">
                <div class="col-12 col-md-12">
                    <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                        @foreach ($documentos as $item)
                        <li>
                            <span class="mailbox-attachment-icon"><i class="far fa-folder"></i></span>
                            <div class="mailbox-attachment-info">
                                <a href="#" class="mailbox-attachment-name d-block py-2">
                                    <i class="fas fa-paperclip"></i> {{ $item->nome }}
                                </a>
                                <span class="mailbox-attachment-size clearfix mt-1">
                                    <span></span>
                                    <a href="{{ route('departamentos-pastas.show', $item->code) }}" class="btn btn-default btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="btn btn-default btn-sm edit-folder"><i class="fas fa-edit"></i></a>
                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="btn btn-default btn-sm delete-record"><i class="fas fa-trash"></i></a>
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body text-center">
                            <p>Sem pastas cadastadas no momento clica aqui para criar novos pastas</p>
                            <button type="button" onclick="toggleModal()" class="btn btn-light-primary">
                                <i class="fas fa-folder"></i> {{ __('messages.novo') }}
                            </button>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
            @endif
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <form action="{{ route('departamentos-pastas.store') }}" method="post" class="">
        @csrf
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Departamento</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">
                        <div class="col-12 col-md-12">
                            <label for="nome" class="form-label">Nome da Pasta</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-lg" name="nome" id="nome" value="{{ old('nome') }}" placeholder="Informe o Nome da Pasta">
                            </div>
                            <p class="text-light-danger">
                                @error('nome')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-12">
                            <label for="type" class="form-label">Tipo</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control form-control-lg" id="type" name="type">
                                    <option value="D">Departamento</option>
                                    <option value="T">Time</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-12">
                            <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control form-control-lg" id="status" name="status">
                                    <option value="activo">{{ __('messages.activo') }} </option>
                                    <option value="desactivo">{{ __('messages.desactivo') }} </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento'))
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        @endif
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->


</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    let PastaID = null;
    let modalVisible = false;

    const modalElement = document.getElementById('modal-lg');
    const modalInstance = new bootstrap.Modal(modalElement);

    function toggleModal() {
        PastaID = null;
        if (modalVisible) {
            modalInstance.hide();
            modalVisible = false;
        } else {
            modalInstance.show();
            modalVisible = true;
        }
    }

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            let new_form = null;
            let method = null;

            if (PastaID == null) {
                new_form = form.attr('action');
                method = "post";
            } else {
                method = "put";
                new_form = form.attr('action') + "/" + PastaID;
            }

            $.ajax({
                url: new_form, // URL do endpoint no backend
                method: method, // Método HTTP definido no formulário
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

    $(document).on('click', '.edit-folder', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // Envia a solicitação AJAX para excluir o registro
        $.ajax({
            url: `{{ route('departamentos-pastas.edit', ':id') }}`.replace(':id', recordId)
            , method: 'GET'
            , data: {
                _token: '{{ csrf_token() }}', // Inclui o token CSRF
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {

                Swal.close();
                modalInstance.show();

                document.getElementById('nome').value = response.data.nome;
                document.getElementById('status').value = response.data.status;
                document.getElementById('type').value = response.data.type;
                PastaID = response.data.id;

            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
    });

    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

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
                    url: `{{ route('departamentos-pastas.destroy', ':id') }}`.replace(':id', recordId)
                    , method: 'DELETE'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

</script>
@endsection
