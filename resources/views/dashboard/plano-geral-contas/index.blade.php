@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.plano_conta') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
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
                        <div class="card-header row">
                            <div class="mb-4 col-12 col-md-7">
                                <input id="searchTerm" oninput="filterTable(this.value)" class="form-control" placeholder="Procurar conta ou subconta">
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody id="tableBody">
                                    @foreach ($plano as $classe)
                                    <tr data-target="#classe-{{ $classe->id }}" data-toggle="collapse" aria-expanded="false" aria-controls="classe-{{ $classe->id }}" style="cursor: pointer;" class="table-active">
                                        <td>
                                            <i class="fas fa-arrow-right mr-2 rotation-arrow ml-1" style="transition: transform 0.3s;"></i>
                                            <strong>{{ $classe->conta }} - {{ $classe->nome }}</strong>
                                            <a href="#" onclick='AbrirModalClasse(@json($classe ))'><i class="fas fa-edit ml-2"></i></a>
                                        </td>
                                    </tr>
                                    <tr id="classe-{{ $classe->id }}" class="collapse">
                                        <td style="padding-left: 40px">
                                            <table class="table table-hover table-bordered">
                                                <tbody>
                                                    @foreach ($classe->contas as $conta)
                                                    <tr data-target="#conta-{{ $conta->id }}" data-toggle="collapse" aria-expanded="false" aria-controls="conta-{{ $conta->id }}" style="cursor: pointer;" class="table-info">
                                                        <td>
                                                            <i class="fas fa-arrow-right mr-2 rotation-arrow ml-1" style="transition: transform 0.3s;"></i>
                                                            <strong>{{ $conta->conta }} - {{ $conta->nome }}</strong>
                                                            <a href="#" onclick='AbrirModalConta(@json($conta ))'><i class="fas fa-edit ml-2"></i></a>
                                                            <a href="{{ route('contas.index') }}">+</a>
                                                        </td>
                                                    </tr>

                                                    <tr id="conta-{{ $conta->id }}" class="collapse">
                                                        <td style="padding-left: 80px">
                                                            <table class="table table-hover">
                                                                <tbody>
                                                                    @foreach ($conta->subcontas as $subconta)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $subconta->numero }} - {{ $subconta->nome }}
                                                                            <a href="#" onclick='AbrirModalSubConta(@json($subconta ))' class=" ml-2"><i class="fas fa-edit ml-2"></i></a>
                                                                            <a href="{{ route('subcontas.index') }}">+</a>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <tbody>
                                    @foreach ($plano as $classe)
                                        <tr>
                                            <th class="text-uppercase"><a onclick="AbrirModalClasse({{ $classe }})" href="#"><strong class="text-light-dark">{{ $classe->conta }} - {{ $classe->nome }}</strong></a></th>
                            </tr>
                            @foreach ($classe->contas as $conta)
                            <tr>
                                <th style="padding-left: 70px"><a onclick="AbrirModalConta({{ $conta }})" href="#"><strong class="text-light-dark">{{ $conta->conta }} - {{ $conta->nome }}</strong></a> <a href="{{ route('contas.index') }}"><i class="fas fa-plus"></i></a></th>
                            </tr>
                            @foreach ($conta->subcontas as $subconta)
                            <tr>
                                @if ($subconta->tipo_conta == "G")
                                <td style="padding-left: 150px"><a onclick="AbrirModalSubConta({{ $subconta }})" href="#"><strong class="text-light-dark">{{ $subconta->numero }} - {{ $subconta->nome }}</strong></a> <a href="{{ route('subcontas.index') }}"><i class="fas fa-plus"></i></a></td>
                                @endif
                                @if ($subconta->tipo_conta == "E")
                                <td style="padding-left: 120px"><a onclick="AbrirModalSubConta({{ $subconta }})" href="#"><strong class="text-light-dark">{{ $subconta->numero }} - {{ $subconta->nome }}</strong></a> <a href="{{ route('subcontas.index') }}" class="ml-5"> {{ __('messages.novo') }} <i class="fas fa-plus"></i> </a></td>
                                @endif
                                @if ($subconta->tipo_conta == "M")
                                <td style="padding-left: 180px"><a onclick="AbrirModalSubConta({{ $subconta }})" href="#" class="text-light-dark">{{ $subconta->numero }} - {{ $subconta->nome }}</a></td>
                                @endif
                            </tr>
                            @endforeach
                            @endforeach
                            @endforeach
                            </tbody>
                            </table> --}}
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <form action="{{ route('classes.store') }}" method="post" id="formClasseStore">
        @csrf
        <div class="modal fade" id="modal-lg-classe">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('messages.subconta') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">
                        <div class="col-12 col-md-6">
                            <label for="nome" class="form-label"> {{ __('messages.designacao') }} </label>
                            <input type="text" class="form-control" name="nome" id="nome" value="{{ old('nome') }}" placeholder="{{ __('messages.designacao') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="conta" class="form-label">Número</label>
                            <input type="text" class="form-control" name="conta" id="conta" value="{{ old('conta') }}" placeholder="{{ __('messages.designacao') }}">
                        </div>

                        <input type="hidden" name="classe_id" id="classe_id">

                        <div class="col-12 col-md-6">
                            <label for="sigla" class="form-label">Sigla</label>
                            <input type="text" class="form-control" name="sigla" id="sigla" value="{{ old('sigla') }}" placeholder="{{ __('messages.designacao') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                            <select class="form-control" id="status" name="status">
                                <option value="activo">{{ __('messages.activo') }} </option>
                                <option value="desactivo">{{ __('messages.desactivo') }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        @if (Auth::user()->can('criar todos') || Auth::user()->can('criar subconta'))
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        @endif
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>

    <form action="{{ route('contas.store') }}" method="post" id="formContaStore">
        @csrf
        <div class="modal fade" id="modal-lg-conta">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('messages.conta') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">
                        <div class="col-12 col-md-12">
                            <label for="nome_conta" class="form-label"> {{ __('messages.designacao') }} </label>
                            <input type="text" class="form-control" name="nome_conta" id="nome_conta" value="{{ old('nome_conta') }}" placeholder="{{ __('messages.designacao') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="conta_classe" class="form-label">{{ __('messages.conta') }}</label>
                            <input type="text" class="form-control" name="conta_classe" id="conta_classe" value="{{ old('conta_classe') }}" placeholder="{{ __('messages.designacao') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="classe_conta_id" class="form-label">Classe</label>
                            <select type="text" class="form-control" id="classe_conta_id" name="classe_conta_id">
                                <option value="">{{ __('messages.escolher') }} </option>
                                @foreach ($plano as $item)
                                <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="conta_store_id" id="conta_store_id">

                        <div class="col-12 col-md-6">
                            <label for="serie_conta" class="form-label">Número Inicial</label>
                            <input type="text" class="form-control" name="serie_conta" id="serie_conta" value="{{ old('serie_conta') }}" placeholder="{{ __('messages.designacao') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                            <select class="form-control" id="status" name="status">
                                <option value="activo">{{ __('messages.activo') }} </option>
                                <option value="desactivo">{{ __('messages.desactivo') }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        @if (Auth::user()->can('criar todos') || Auth::user()->can('criar subconta'))
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

    <form action="{{ route('subcontas.store') }}" method="post" id="formSubContaStore">
        @csrf
        <div class="modal fade" id="modal-lg-subconta">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('messages.subconta') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">
                        <div class="col-12 col-md-6">
                            <label for="nome_subconta" class="form-label"> {{ __('messages.designacao') }} </label>
                            <input type="text" class="form-control" name="nome_subconta" id="nome_subconta" value="{{ old('nome_subconta') }}" placeholder="{{ __('messages.designacao') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="subonta_conta_id" class="form-label">{{ __('messages.conta') }}</label>
                            <select class="form-control" id="subonta_conta_id" name="subonta_conta_id">
                                <option value="">Associar uma conta</option>
                                @foreach ($contas as $item)
                                <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="subconta_store_id" id="subconta_store_id">

                        <div class="col-12 col-md-6">
                            <label for="subconta_numero" class="form-label">Número da Subconta</label>
                            <input type="text" class="form-control" name="subconta_numero" id="subconta_numero" value="{{ old('subconta_numero') }}" placeholder="Informe o número da sequência">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="subconta_tipo_conta" class="form-label">Tipo conta</label>
                            <select class="form-control" id="subconta_tipo_conta" name="subconta_tipo_conta">
                                <option value="M">Movimento</option>
                                <option value="E" selected>Entregadora</option>
                                <option value="G">Agrupadoras</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="subconta_tipo_operacao" class="form-label">Tipo Operação</label>
                            <select class="form-control" id="subconta_tipo_operacao" name="subconta_tipo_operacao">
                                <option value="">{{ __('messages.escolher') }}</option>
                                <option value="A">{{ __('messages.activo') }} </option>
                                <option value="AC">Activo Corrente</option>
                                <option value="ANC">Activo Não Corrente</option>
                                <option value="P">Passívo</option>
                                <option value="PC">Passívo Corrente</option>
                                <option value="PNC">Passívo Não Corrente</option>
                                <option value="CP">Capital</option>
                                <option value="OU" selected>Outras</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="subconta_status" class="form-label">{{ __('messages.estados') }}</label>
                            <select class="form-control" id="subconta_status" name="subconta_status">
                                <option value="activo">{{ __('messages.activo') }} </option>
                                <option value="desactivo">{{ __('messages.desactivo') }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        @if (Auth::user()->can('criar todos') || Auth::user()->can('criar subconta'))
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

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    function filterTable(term) {
        term = term.toLowerCase();

        // Primeiro, esconde tudo
        document.querySelectorAll("#tableBody tr").forEach(function(row) {
            row.style.display = "none";

            // reset nas setinhas
            let icon = row.querySelector("i.rotation-arrow");

            if (icon) {
                icon.style.transform = '';
            }
        });

        // Agora exibe todos que correspondem junto com seus pais
        document.querySelectorAll("#tableBody tr").forEach(function(row) {
            if (row.textContent.toLowerCase().includes(term) && term.length > 0) {
                row.style.display = '';

                // se for subconta, revela o conta que é parent
                let parentId = row.id.split('-')[1];
                let parent = document.querySelector("#conta-" + parentId);
                let grandParent = document.querySelector("#classe-" + parentId);

                // revela também os pais
                let parentRow = row.previousElementSibling;
                while (parentRow) {
                    parentRow.style.display = '';
                    parentRow = parentRow.previousElementSibling;

                    // se for um que tenha um collapse, expande-o
                    if (parentRow.dataset.bsTarget) {
                        let icon = parentRow.querySelector("i.rotation-arrow");

                        if (icon) {
                            icon.style.transform = "rotate(90deg)";
                        }
                        // revela também o collapse associado
                        let collapseId = parentRow.dataset.bsTarget.substr(1);
                        let collapse = document.getElementById(collapseId);
                        if (collapse) {
                            collapse.classList.add("show");

                        }
                    }
                }
            }
        });

        // Se o termo é vazio, revela tudo e fecha
        if (term.length == 0) {
            document.querySelectorAll("#tableBody tr").forEach(function(row) {
                row.style.display = '';
            });

            document.querySelectorAll("tr[data-target]").forEach(function(el) {
                let icon = el.querySelector("i.rotation-arrow");

                if (icon) {
                    icon.style.transform = '';
                }
                let collapseId = el.dataset.bsTarget.substr(1);
                let collapse = document.getElementById(collapseId);
                if (collapse) {
                    collapse.classList.remove("show");

                }
            });
        }
    }

    // Anima a seta de expansion
    document.querySelectorAll("tr[data-target]").forEach(function(el) {
        el.addEventListener("click", function() {
            let icon = el.querySelector("i.rotation-arrow");

            if (icon) {
                icon.style.transform = icon.style.transform === "rotate(90deg)" ? '' : 'rotate(90deg)';
            }
        });
    });

    function AbrirModalSubConta(subconta) {
        document.getElementById("subconta_store_id").value = subconta.id;
        document.getElementById("subonta_conta_id").value = subconta.conta_id;
        document.getElementById("nome_subconta").value = subconta.nome;
        document.getElementById("subconta_numero").value = subconta.numero;
        document.getElementById("subconta_tipo_conta").value = subconta.tipo_conta;
        document.getElementById("subconta_tipo_operacao").value = subconta.tipo_operacao;
        document.getElementById("subconta_status").value = subconta.status;

        $('#modal-lg-subconta').modal('show');
    }

    function AbrirModalConta(conta) {
        document.getElementById("classe_conta_id").value = conta.classe_id;
        document.getElementById("nome_conta").value = conta.nome;
        document.getElementById("status").value = conta.status;
        document.getElementById("conta_classe").value = conta.conta;
        document.getElementById("serie_conta").value = conta.serie;
        document.getElementById("conta_store_id").value = conta.id;

        $('#modal-lg-conta').modal('show');
    }

    function AbrirModalClasse(classe) {
        document.getElementById("nome").value = classe.nome;
        document.getElementById("conta").value = classe.conta;
        document.getElementById("sigla").value = classe.sigla;
        document.getElementById("status").value = classe.status;

        document.getElementById("classe_id").value = classe.id;

        $('#modal-lg-classe').modal('show');
    }

    $(document).ready(function() {
        $('#formClasseStore').on('submit', function(e) {
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

    $(document).ready(function() {
        $('#formContaStore').on('submit', function(e) {
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

    $(document).ready(function() {
        $('#formSubContaStore').on('submit', function(e) {
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

    $(function() {
        $("#carregar_tabela").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
