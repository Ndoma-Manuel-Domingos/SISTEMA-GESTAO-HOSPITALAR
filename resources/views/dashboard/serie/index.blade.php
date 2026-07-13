@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Serie</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Serie</li>
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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <div class="input-group mb-3">
                                        <input type="text" id="seriesCode" class="form-control" placeholder="Série">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="input-group mb-3">
                                        <input type="text" id="seriesYear" class="form-control" placeholder="Ano">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="input-group mb-3">
                                        <select id="documentType" class="form-control">
                                            <option value="">Tipo</option>
                                            <option>FT</option>
                                            <option>FR</option>
                                            <option>NC</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button onclick="loadSeries()" class="btn-sm btn-light-primary">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header">
                            <div class="card-tools">
                                <a href="#" class="btn btn-light-primary" onclick="toggleModal()"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-sm table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Série</th>
                                        <th>Ano</th>
                                        <th>Tipo</th>
                                        <th>Primeiro Nº</th>
                                        <th>Último Nº</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody"></tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label> Mostrar
                                                <select id="perPage" class="form-control form-control-sm d-inline w-auto">
                                                    <option value="5" selected>5</option>
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select> registos
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div id="pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <form action="{{ route('series.store') }}" method="post" class="">
                @csrf
                <div class="modal fade" id="modal-lg">
                    <div class="modal-dialog modal-lg  modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body row py-4">
                                <div class="col-12 col-md-6">
                                    <label for="seriesYear" class="form-label"> Serie do Ano </label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control form-control-lg" name="seriesYear" id="seriesYear" value="{{ date("Y") }}" placeholder="Ano da series">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="establishmentNumber" class="form-label">Número do Estabelecimento </label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control form-control-lg" name="establishmentNumber" id="establishmentNumber" value="{{ old('establishmentNumber') }}" placeholder="Estabelecimento">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="documentType" class="form-label">Tipo de Documento</label>
                                    <div class="input-group mb-3">
                                        <select id="documentType" name="documentType" class="form-control">
                                            <option value="">Tipo</option>
                                            <option value="FA">Factura de Adiantamento</option>
                                            <option value="FT">Factura</option>
                                            <option value="FR">Factura/Recibo</option>
                                            <option value="FG">Factura Global</option>
                                            <option value="GF">Factura Genérica</option>
                                            <option value="AC">Aviso de Cobrança</option>
                                            <option value="AR">Aviso de Cobrança/Recibo</option>
                                            <option value="TV">Talão de Venda</option>
                                            <option value="RC">Recibo em numerário (cash)</option>
                                            <option value="RG">Recibo Geral</option>
                                            <option value="RE">Estorno ou Recibo de Estorno</option>
                                            <option value="ND">Nota de Débito</option>
                                            <option value="NC">Nota de Crédito</option>
                                            <option value="AF">Factura/Recibo de Autofacturação</option>
                                            <option value="RP">Prémio ou Recibo de Prémio</option>
                                            <option value="RA">Resseguro Aceite</option>
                                            <option value="CS">Imputação a Co-seguradoras</option>
                                            <option value="LD">Imputação a Co-seguradora Líder</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="seriesContingencyIndicator" class="form-label">Indicador da série</label>
                                    <div class="input-group mb-3">
                                        <select id="seriesContingencyIndicator" name="seriesContingencyIndicator" class="form-control">
                                            <option value="N">Série do regime normal de emissão</option>
                                            <option value="C">Série criada para suportar a emissão em contingência</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                                @if (Auth::user()->can('criar todos'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </form>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
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


    $(document).ready(function() {
        loadSeries();
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
                    url: `{{ route('series.destroy', ':id') }}`.replace(':id', recordId)
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
                        loadSeries();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

    $("#documentType").change(function() {
        loadSeries(1);
    });

    $("#perPage").change(function() {
        loadSeries(1);
    });

    function loadSeries(page = 1) {

        $.ajax({
            type: "GET"
            , url: "/series"
            , data: {
                page: page
                , perPage: $("#perPage").val()
                , seriesCode: $("#seriesCode").val()
                , seriesYear: $("#seriesYear").val()
                , documentType: $("#documentType").val()
            }
            , dataType: "json"
            , beforeSend: function() {
                // opcional: mostrar loader
                progressBeforeSend("Carregando...");
            }
            , success: function(res) {
                Swal.close();
                let rows = "";
                res.data.forEach(s => {
                    rows += `
                        <tr>
                            <td>${s.seriesCode}</td>
                            <td>${s.seriesYear}</td>
                            <td>${s.documentType}</td>
                            <td>${s.firstDocumentNo}</td>
                            <td>${s.lastDocumentNo ?? ''}</td>
                        </tr>
                    `;
                });

                $("#tbody").html(rows);
                paginate(res);
            }
            , error: function(xhr) {
                Swal.close();
                console.error(xhr);
            }
        });

    }

</script>
@endsection
