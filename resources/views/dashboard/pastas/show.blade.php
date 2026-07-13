@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $departamento->nome }} » {{ $pasta->nome }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('departamentos-pastas.show', [$departamento->code]) }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item">
                            <button type="button" onclick="toggleUploadModal()" class="btn btn-outline-dark">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                            <button type="button" onclick="toggleModal()" class="btn btn-light-primary">
                                <i class="fas fa-folder"></i> <i class="fas fa-plus"></i> {{ __('messages.novo') }}
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

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="alert alert-light alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-folder"></i> Pastas</h5>
                    </div>
                </div>
            </div>

            @if (count($subpastas) != 0)
            <div class="row">
                <div class="col-12 col-md-12">
                    <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                        @foreach ($subpastas as $item)
                        <li>
                            <span class="mailbox-attachment-icon"><i class="far fa-folder"></i></span>
                            <div class="mailbox-attachment-info">
                                <a href="#" class="mailbox-attachment-name d-block py-2">
                                    <i class="fas fa-paperclip"></i> {{ $item->nome }}
                                </a>
                                <span class="mailbox-attachment-size clearfix mt-1">
                                    <span></span>
                                    <a href="{{ route('detalhes-pastas.show', [$departamento->code, $item->code]) }}" class="btn btn-default btn-sm"><i class="fas fa-eye"></i></a>
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
                        <div class="card-body text-center">
                            <p>Sem pastas cadastadas no momento clica aqui para criar novos pastas</p>
                            <button type="button" onclick="toggleModal()" class="btn btn-light-primary">
                                <i class="fas fa-folder"></i> <i class="fas fa-plus"></i> {{ __('messages.novo') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="alert alert-light alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-file"></i> Ficheiros</h5>
                    </div>
                </div>
            </div>

            @if (count($arquivos) != 0)
            <div class="row">
                <div class="col-12 col-md-12">
                    <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                        @foreach ($arquivos as $item)
                        <li>
                            @if ($item->extension == "xml" || $item->extension == "png" || $item->extension == "jpg" || $item->extension == "jpeg")
                            <span class="mailbox-attachment-icon has-img">
                                <img src="/images/documentos/{{ $item->nome }}" alt="Attachment">
                            </span>
                            @endif
                            @if ($item->extension == "pdf")
                            <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>
                            @endif
                            @if ($item->extension == "docx")
                            <span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>
                            @endif
                            @if ($item->extension == "xlsx")
                            <span class="mailbox-attachment-icon"><i class="far fa-file-excel"></i></span>
                            @endif
                            <div class="mailbox-attachment-info">
                                <a href="#" class="mailbox-attachment-name" data-toggle="modal" data-target="#viewModal" data-url="/images/documentos/{{ $item->nome }}" data-extension="{{ $item->extension }}">
                                    <i class="fas fa-paperclip"></i> {{ $item->nome }}
                                </a>
                                <span class="mailbox-attachment-size clearfix mt-1">
                                    <span>{{ $item->size_formatted }}</span>
                                    <a href="#" data-toggle="modal" data-target="#viewModal" data-url="/images/documentos/{{ $item->nome }}" data-extension="{{ $item->extension }}" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i>
                                    </a>

                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="btn btn-default btn-sm float-right mx-1 delete-record-arquivos">
                                        <i class="fas fa-trash"></i>
                                    </a>

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
                        <div class="card-body text-center">
                            <p>Sem pastas cadastadas no momento
                                clica aqui para criar novos arquivos
                            </p>
                            <button type="button" onclick="toggleUploadModal()" class="btn btn-outline-dark">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif


        </div><!-- /.container-fluid -->
    </div>

    <form action="{{ route('sub-pastas.store') }}" method="post" class="">
        @csrf
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $pasta->nome }} » Pasta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">
                        <div class="col-12 col-md-12">
                            <label for="nome" class="form-label">{{ __('messages.designacao') }}</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-lg" name="nome" id="nome" value="{{ old('nome') }}" placeholder="Informe o Nome da Pasta">
                            </div>
                            <p class="text-light-danger">
                                @error('nome')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <input type="hidden" name="pasta_id" id="pasta_id" value="{{ $pasta->id }}">

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

    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="modal-lg-upload">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Carregar Ficheiro</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="documento">Escolha Ficheiro</label>
                                <input type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,image/*" class="form-control" name="files[]" multiple id="documento">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento'))
                        <button type="button" onclick="uploadFicheiro()" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        @endif
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->


    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Visualização de Documento</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center" id="modalFileContent">
                    <!-- Conteúdo será injetado via JS -->
                </div>
            </div>
        </div>
    </div>


    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection


@section('scripts')
<script>
    let PastaID = null;
    let modalVisible = false;
    let modalUploadVisible = false;

    const modalElement = document.getElementById('modal-lg');
    const modalInstance = new bootstrap.Modal(modalElement);

    const modalUploadElement = document.getElementById('modal-lg-upload');
    const modalUploadInstance = new bootstrap.Modal(modalUploadElement);

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

    function toggleUploadModal() {
        if (modalUploadVisible) {
            modalUploadInstance.hide();
            modalUploadVisible = false;
        } else {
            modalUploadInstance.show();
            modalUploadVisible = true;
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
            url: `{{ route('pastas.edit', ':id') }}`.replace(':id', recordId)
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
                PastaID = response.data.id;

                // nome
                // status

                // console.log(response.data)

                //
                // // Exibe uma mensagem de sucesso
                // showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                // window.location.reload();
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
                    url: `{{ route('pastas.destroy', ':id') }}`.replace(':id', recordId)
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

    function uploadFicheiro() {
        let formData = new FormData();
        let files = $('input[type="file"]')[0].files;

        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        const pasta_id = document.getElementById('pasta_id').value;
        formData.append('pasta_id', pasta_id);

        $.ajax({
            url: '/gestao-documentos/upload-multifiles-pastas'
            , type: 'POST'
            , data: formData
            , processData: false
            , contentType: false
            , headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                showMessage('Sucesso!', 'Arquivos enviados com sucesso!', 'success');
                window.location.reload();
            }
            , error: function(error) {
                Swal.close();
                showMessage('Erro!', "Erro ao enviar arquivos", 'error');
            }
        });

    }


    $(document).on('click', '.delete-record-arquivos', function(e) {

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
                    url: `{{ route('sub-pastas.destroy', ':id') }}`.replace(':id', recordId)
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


    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('viewModal');
        $('#viewModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const fileUrl = button.data('url');
            const extension = button.data('extension').toLowerCase();

            let content = '';

            if (['jpg', 'jpeg', 'png', 'xml'].includes(extension)) {
                content = `<img src="${fileUrl}" class="img-fluid" alt="Imagem">`;
            } else if (extension === 'pdf') {
                content = `<iframe src="${fileUrl}" style="width:100%; height:80vh;" frameborder="0"></iframe>`;
            } else if (['doc', 'docx', 'xls', 'xlsx'].includes(extension)) {
                content = `<iframe src="https://docs.google.com/gview?url=${window.location.origin + fileUrl}&embedded=true" style="width:100%; height:80vh;" frameborder="0"></iframe>`;
            } else {
                content = `<p>Pré-visualização não suportada para este tipo de arquivo.</p>`;
            }

            document.getElementById('modalFileContent').innerHTML = content;
        });
    });

</script>
@endsection
