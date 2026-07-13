@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Parametros e sub parametros de Exames</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Home</a></li>
                        <li class="breadcrumb-item active">Todos</li>
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
                    <form action="{{ route('sub-parametros-exames.index') }}" method="GET">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="pesquisar_exame_id" class="form-label">Exames</label>
                                        <select name="exame_id" id="pesquisar_exame_id" class="select2 form-control">
                                            <option value="">Escolher</option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id ?? "" }}">
                                                {{ $item->nome }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary"><i class="fas fa-search"></i> Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar exame'))
                                <button type="button" onclick="toggleModal()" class="btn btn-light-primary">
                                    <i class="fas fa-plus"></i> Novo Subparametro
                                </button>
                                @endif
                            </h3>
                            <div class="card-tools">
                                @if ($requests['exame_id'])
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('pdf-paramentros-exames', ['exame_id' => $requests['exame_id']]) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela1" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Parametro</th>
                                        <th>Ordem</th>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Unidade</th>
                                        <th>Referência</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipos as $item)
                                    <tr>
                                        <td>{{ $item->paramentro->nome ?? "N/A" }}</td>
                                        <td>{{ $item->ordem ?? "N/A" }}</td>
                                        <td>{{ $item->nome ?? "N/A" }}</td>
                                        <td>{{ $item->tipo }}</td>

                                        @if ($item->tipo == "lista")
                                        <td>{{ $item->opcoes }}</td>
                                        @else
                                        @if ($item->tipo == "booleano")
                                        <td>{{ $item->texto_sim }}/{{ $item->texto_nao }}</td>
                                        @else
                                        <td>{{ $item->unidade }}</td>
                                        @endif
                                        @endif

                                        <td>{{ $item->valor_referencia  }}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar exame'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item edit-folder text-light-success"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar exame'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" data-url="{{ route('sub-parametros-exames.destroy', $item->id) }}" class="dropdown-item delete-record text-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar exame'))
                                <button type="button" onclick="toggleModalParametro()" class="btn btn-light-primary">
                                    <i class="fas fa-plus"></i> Novo parametro
                                </button>
                                @endif
                            </h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Exame</th>
                                        <th>Parametro</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parametros as $item)
                                    <tr>
                                        <td>{{ $item->exame->nome ?? "N/A" }}</td>
                                        <td>{{ $item->nome ?? "N/A" }}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar exame'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item edit-folder-parametro text-light-success"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar exame'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" data-url="{{ route('parametros-exames.destroy', $item->id) }}" class="dropdown-item delete-record text-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <form action="{{ route('sub-parametros-exames.store') }}" method="post" id="FormPOST">
        @csrf
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Sub parametros de Exames</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- DADOS GERAIS -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <strong>Dados Gerais</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-3">
                                        <label class="form-label">Exame</label>
                                        <select name="exame_id" id="exame_id" class="select2 form-control" required>
                                            <option value="">Escolher</option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12 mb-3">
                                        <label for="parametro_id" class="form-label">Paramento</label>
                                        <select name="parametro_id" id="parametro_id" class="select2 form-control" required>
                                            <option value="">Escolher</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12  mb-3">
                                        <label class="form-label">Nome do Parâmetro</label>
                                        <input type="text" name="nome" id="nome" class="form-control" placeholder="Ex: Hemoglobina">
                                    </div>
                                    <div class="col-md-3 col-12 mb-3">
                                        <label class="form-label">Código</label>
                                        <input type="text" name="codigo" id="codigo" class="form-control" placeholder="HB">
                                    </div>

                                    <div class="col-md-6 col-12 mb-3">
                                        <label class="form-label">Tipo</label>
                                        <select name="tipo" id="tipo" class="form-control">
                                            <option value="">Escolher</option>
                                            <option value="numero">Número</option>
                                            <option value="texto">Texto</option>
                                            <option value="lista">Lista</option>
                                            <option value="booleano">Sim / Não</option>
                                            <option value="data">Data</option>
                                            <option value="textarea">Área de Texto</option>
                                            <option value="imagem">Imagem</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- CONFIGURAÇÃO NUMÉRICA -->
                        <div class="card mb-3" id="config_numero" style="display:none">
                            <div class="card-header">
                                <strong>Configuração Numérica</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Unidade</label>
                                        <input type="text" name="unidade" id="unidade" class="form-control" placeholder="g/dL">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Valor Referência</label>
                                        <input type="text" name="valor_referencia" id="valor_referencia" class="form-control" placeholder="12 - 16">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Valor Mínimo</label>
                                        <input type="number" step="0.01" name="valor_minimo" id="valor_minimo" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Valor Máximo</label>
                                        <input type="number" step="0.01" name="valor_maximo" id="valor_maximo" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CONFIGURAÇÃO LISTA -->
                        <div class="card mb-3" id="config_lista" style="display:none">
                            <div class="card-header">
                                <strong>Opções da Lista</strong>
                            </div>
                            <div class="card-body">
                                <textarea name="opcoes" id="opcoes" rows="5" class="form-control" placeholder="Positivo&#10;Negativo&#10;Indeterminado"></textarea>
                                <small class="text-muted">
                                    Uma opção por linha.
                                </small>
                            </div>
                        </div>

                        <!-- CONFIGURAÇÃO BOOLEANO -->
                        <div class="card mb-3" id="config_booleano" style="display:none">
                            <div class="card-header">
                                <strong>Configuração Sim / Não</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <label for="texto_sim">Texto Sim</label>
                                        <input type="text" class="form-control" name="texto_sim" id="texto_sim" value="Presente">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="texto_nao">Texto Não</label>
                                        <input type="text" class="form-control" name="texto_nao" id="texto_nao" value="Ausente">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CONFIGURAÇÃO TEXTO -->
                        <div class="card mb-3" id="config_texto" style="display:none">
                            <div class="card-header">
                                Configuração Texto
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <label for="tamanho_maximo">Tamanho Máximo</label>
                                        <input type="number" name="tamanho_maximo" id="tamanho_maximo" value="255" class="form-control">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="valor_padrao">Valor Padrão</label>
                                        <input type="text" name="valor_padrao" id="valor_padrao" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CONFIGURAÇÃO DATA -->
                        <div class="card mb-3" id="config_data" style="display:none">
                            <div class="card-header">
                                Configuração Data
                            </div>
                            <div class="card-body">
                                <div class="form-check">
                                    <input type="checkbox" name="permitir_passado" id="permitir_passado" value="1" checked class="form-check-input">
                                    <label for="permitir_passado" class="form-check-label">Permitir Datas Passadas</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="permitir_futuro" id="permitir_futuro" value="1" checked class="form-check-input">
                                    <label for="permitir_futuro" class="form-check-label">Permitir Datas Futuras</label>
                                </div>
                            </div>
                        </div>

                        <!-- CONFIGURAÇÃO TEXTAREA -->
                        <div class="card mb-3" id="config_textarea" style="display:none">
                            <div class="card-header">
                                Configuração Área de Texto
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <label for="linhas">Número de Linhas</label>
                                        <input type="number" name="linhas" id="linhas" value="5" class="form-control">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="tamanho_maximo_textarea">Tamanho Máximo</label>
                                        <input type="number" name="tamanho_maximo_textarea" id="tamanho_maximo_textarea" value="5000" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CONFIGURAÇÃO IMAGEM -->
                        <div class="card mb-3" id="config_imagem" style="display:none">
                            <div class="card-header">
                                Configuração Imagem
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <label for="extensoes_permitidas">Extensões Permitidas</label>
                                        <input type="text" name="extensoes_permitidas" id="extensoes_permitidas" value="jpg,jpeg,png,webp" class="form-control">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="tamanho_max_arquivo">Tamanho Máximo (MB)</label>
                                        <input type="number" name="tamanho_max_arquivo" id="tamanho_max_arquivo" value="10" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CONFIGURAÇÕES AVANÇADAS -->
                        <div class="card">
                            <div class="card-header">
                                <strong>Configurações Avançadas</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Ordem</label>
                                        <input type="number" value="1" name="ordem" id="ordem" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" class="form-check-input" name="obrigatorio" value="1" checked>
                                            <label class="form-check-label">Obrigatório</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" class="form-check-input" name="activo" value="1" checked>
                                            <label class="form-check-label">Activo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        @if (Auth::user()->can('criar todos') || Auth::user()->can('criar exame'))
                        <button type="submit" form="FormPOST" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        @endif
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>

    <form action="{{ route('parametros-exames.store') }}" method="post" id="FormPOSTParametro">
        @csrf
        <div class="modal fade" id="modal-parametro-lg">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Parametros de Exames</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- DADOS GERAIS -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <strong>Dados Gerais</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-12 mb-3">
                                        <label for="exame_parametro_id" class="form-label">Exame</label>
                                        <select name="exame_parametro_id" id="exame_parametro_id" class="select2 form-control" required>
                                            <option value="">Escolher</option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id ?? "" }}">
                                                {{ $item->nome }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12 mb-3">
                                        <label for="nome_parametro" class="form-label">Nome do Parâmetro</label>
                                        <input type="text" name="nome_parametro" id="nome_parametro" class="form-control" placeholder="Ex: exemplo">
                                    </div>
                                    <div class="col-md-6 col-12 mb-3">
                                        <label for="ordem_parametro" class="form-label">Ordem</label>
                                        <input type="text" name="ordem_parametro" id="ordem_parametro" class="form-control" placeholder="HB">
                                    </div>
                                    <div class="col-md-12 col-12 mb-3">
                                        <label for="observacoes_paramentro" class="form-label">Observações (Opcional)</label>
                                        <textarea name="observacoes_paramentro" id="observacoes_paramentro" rows="3" class="form-control" placeholder="Informe uma descrição"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        @if (Auth::user()->can('criar todos') || Auth::user()->can('criar exame'))
                        <button type="submit" form="FormPOSTParametro" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        @endif
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
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
    let modalVisibleParametro = false;

    const modalElement = document.getElementById('modal-lg');
    const modalInstance = new bootstrap.Modal(modalElement);

    const modalElementParametro = document.getElementById('modal-parametro-lg');
    const modalInstanceParametro = new bootstrap.Modal(modalElementParametro);

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

    function toggleModalParametro() {
        PastaID = null;
        if (modalVisibleParametro) {
            modalInstanceParametro.hide();
            modalVisibleParametro = false;
        } else {
            modalInstanceParametro.show();
            modalVisibleParametro = true;
        }
    }

    $(document).ready(function() {
        $('#FormPOST, #FormPOSTParametro').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize();

            let new_form;
            let method;

            if (PastaID == null) {
                new_form = form.attr('action');
                method = "post";
            } else {
                new_form = form.attr('action') + "/" + PastaID;
                method = "put";
            }

            $.ajax({
                url: new_form
                , method: method
                , data: formData
                , headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    Swal.close();

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';

                        $.each(errors, function(key, value) {
                            messages += `${value}\n* `;
                        });

                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            });

        });
    });

    $(document).on('click', '.edit-folder-parametro', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        PastaID = null;

        // Envia a solicitação AJAX para excluir o registro
        $.ajax({
            url: `{{ route('parametros-exames.edit', ':id') }}`.replace(':id', recordId)
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
                modalInstanceParametro.show();

                PastaID = response.data.id;

                $('#nome_parametro').val(response.data.nome);
                $('#ordem_parametro').val(response.data.ordem);
                $('#exame_parametro_id').val(response.data.exame_id).trigger('change');
                $('#observacoes_paramentro').val(response.data.observacao);

            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
    });

    $(document).on('click', '.edit-folder', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        PastaID = null;

        // Envia a solicitação AJAX para excluir o registro
        $.ajax({
            url: `{{ route('sub-parametros-exames.edit', ':id') }}`.replace(':id', recordId)
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

                PastaID = response.data.id;

                let opcoes = response.data.opcoes;

                if (response.data.tipo == 'lista' && typeof opcoes === 'string') {
                    opcoes = JSON.parse(opcoes);
                }

                $('#nome').val(response.data.nome);
                $('#tipo').val(response.data.tipo);
                $('#exame_id').val(response.data.exame_id).trigger('change');
                $('#parametro_id').val(response.data.parametro_id).trigger('change');
                $('#codigo').val(response.data.codigo);
                $('#unidade').val(response.data.unidade);
                $('#valor_referencia').val(response.data.valor_referencia);
                $('#valor_minimo').val(response.data.valor_minimo);
                $('#valor_maximo').val(response.data.valor_maximo);
                $('#texto_sim').val(response.data.texto_sim);
                $('#texto_nao').val(response.data.texto_nao);
                $('#ordem').val(response.data.ordem);
                $('#obrigatorio').val(response.data.obrigatorio);
                $('#activo').val(response.data.activo);
                if (response.data.tipo == 'lista' && typeof opcoes === 'string') {
                    $('#opcoes').val(opcoes.join('\n'));
                }
                $('#tamanho_maximo').val(response.data.tamanho_maximo);
                $('#valor_padrao').val(response.data.valor_padrao);
                $('#permitir_passado').val(response.data.permitir_passado);
                $('#permitir_futuro').val(response.data.permitir_futuro);
                $('#linhas').val(response.data.linhas);
                $('#extensoes_permitidas').val(response.data.extensoes_permitidas);
                $('#tamanho_max_arquivo').val(response.data.tamanho_max_arquivo);


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
        let url = $(this).data('url');

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
                    url: url
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

    $(document).ready(function() {
        $('#exame_id').on('change', function() {
            let exame_id = $(this).val();
            // Limpa o select
            $('#parametro_id').html('<option value="">Carregando...</option>');
            if (exame_id == '') {
                $('#parametro_id').html('<option value="">Escolher</option>');
                return;
            }
            $.ajax({
                url: '/parametros/exame/' + exame_id, // rota que retorna os parâmetros
                type: 'GET'
                , dataType: 'json'
                , success: function(response) {
                    let options = '<option value="">Escolher</option>';
                    $.each(response, function(index, parametro) {
                        options += `<option value="${parametro.id}">${parametro.nome}</option>`;
                    });
                    $('#parametro_id').html(options);
                    // Atualiza o Select2
                    $('#parametro_id').trigger('change');
                }
                , error: function() {
                    $('#parametro_id').html('<option value="">Erro ao carregar</option>');
                }
            });
        });
    });

    $(function() {
        $("#carregar_tabela").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6 col-12:eq(0)');
        $("#carregar_tabela1").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6 col-12:eq(0)');
    });

    $('#tipo').on('change', function() {
        $('#config_numero').hide();
        $('#config_lista').hide();
        $('#config_booleano').hide();
        $('#config_texto').hide();
        $('#config_data').hide();
        $('#config_textarea').hide();
        $('#config_imagem').hide();
        let tipo = $(this).val();
        $('#config_' + tipo).show();
    });

</script>
@endsection
