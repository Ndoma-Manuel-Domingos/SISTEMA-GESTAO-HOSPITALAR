@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Registro de {{ $requests['tipo'] ?? "" }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        @if (!$empresa_logada->empresa->tem_perfil("Gestão Financeira"))
                        <li class="breadcrumb-item"><a href="{{ route('caixas.operacaoes-financeiras') }}">{{ __('messages.voltar') }}</a></li>
                        @else
                        <li class="breadcrumb-item"><a href="{{ route('operacaoes-financeiras.index') }}">{{ __('messages.voltar') }}</a></li>
                        @endif
                        <li class="breadcrumb-item active">{{ __('messages.financeiro') }}</li>
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
                    <div class="card">
                        <form action="{{ route('operacaoes-financeiras.store') }}" method="post" class="" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="referencia" class="form-label"> {{ __('messages.designacao') }} (<small>podes editar</small>)</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control  @error('referencia') is-invalid @enderror" name="referencia" id="referencia" value="{{ $observacao ?? old('referencia') }}" placeholder="Informe a referença. Ex FR AGT235/2352">
                                    </div>
                                </div>

                                @if ($requests['tipo'] == "receita")
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="data_recebimento" class="form-label">Data de Recebimento</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control  @error('data_recebimento') is-invalid @enderror" name="data_recebimento" id="data_recebimento" value="{{ old('data_recebimento') }}" placeholder="Informe a Data">
                                    </div>
                                </div>
                                @endif

                                @if ($requests['tipo'] == "dispesa")
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="data_pagamento" class="form-label">Data de Pagamento</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control  @error('data_pagamento') is-invalid @enderror" name="data_pagamento" id="data_pagamento" value="{{ old('data_pagamento') }}" placeholder="Informe a Data">
                                    </div>
                                </div>
                                @endif


                                @if ($requests['tipo'] == "receita")
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="forma_recebimento_id" class="form-label">Formas de Recebimento</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control @error('forma_recebimento_id') is-invalid @enderror" id="forma_recebimento_id" name="forma_recebimento_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($formas_pagamentos as $item)
                                            <option value="{{ $item->id ?? "" }}" data-id="{{ $item->tipo }}">{{ $item->titulo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                @if ($requests['tipo'] == "dispesa")
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="forma_pagamento_id" class="form-label">Formas de Pagamentos</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control @error('forma_pagamento_id') is-invalid @enderror" id="forma_pagamento_id" name="forma_pagamento_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($formas_pagamentos as $item)
                                            <option value="{{ $item->id ?? "" }}" data-id="{{ $item->tipo }}">{{ $item->titulo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <div class="col-12 col-md-4 col-lg-2" id="form_caixas">
                                    <label for="caixa_id" class="form-label">Caixas</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('caixa_id') is-invalid @enderror" id="caixa_id" name="caixa_id" style="width: 100%">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($caixas as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2" id="form_bancos">
                                    <label for="banco_id" class="form-label">Contas Bancárias</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('banco_id') is-invalid @enderror" id="banco_id" name="banco_id" style="width: 100%">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($bancos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 col-md-4 col-lg-2" id="form_caixas_valores">
                                    <label for="motante" class="form-label">Motante Caixa</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control  @error('motante') is-invalid @enderror" name="motante" id="motante" value="{{ old('motante') }}" placeholder="{{ __('messages.valor') }}">
                                    </div>
                                </div>


                                <div class="col-12 col-md-4 col-lg-2" id="form_bancos_valores">
                                    <label for="motante_banco" class="form-label"> Motante Conta Bancária</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control  @error('motante_banco') is-invalid @enderror" name="motante_banco" id="motante_banco" value="{{ old('motante_banco') }}" placeholder="{{ __('messages.valor') }}">
                                    </div>
                                </div>

                                <input type="hidden" name="tipo_servico_id" id="tipo_servico_id" value="{{ $requests['tipo'] ?? "" }}">

                                <div class="col-12 col-md-3 col-lg-4">
                                    <label for="status_pagamento" class="form-label">{{ __('messages.estados') }}</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('status_pagamento') is-invalid @enderror" id="status_pagamento" name="status_pagamento">
                                            <option value="pago">Pago</option>
                                            <option value="pendente">Pendente</option>
                                            <option value="atrasado">Atrasado</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 col-lg-4">
                                    <label for="centro_custo_id" class="form-label">{{ __('messages.centro_custos') }}</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('centro_custo_id') is-invalid @enderror" id="centro_custo_id" name="centro_custo_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($centro_custos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="parcelado" class="form-label">Parcelado</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('parcelado') is-invalid @enderror" id="parcelado" name="parcelado">
                                            <option value="N"> {{ __('messages.nao') }} </option>
                                            <option value="Y"> {{ __('messages.sim') }} </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-2">
                                    <label for="parcelas" class="form-label">Número da Parcela</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control  @error('parcelas') is-invalid @enderror" name="parcelas" id="parcelas" value="{{ old('parcelas') }}" placeholder="Informe o número da parcela">
                                    </div>
                                </div>


                                <div class="col-12 col-md-6 col-lg-4">
                                    @if ($requests['tipo'] == "receita")
                                    <label for="tipo_id" class="form-label">{{ __('messages.receita') }}</label>
                                    @endif

                                    @if ($requests['tipo'] == "dispesa")
                                    <label for="tipo_id" class="form-label">{{ __('messages.despesa') }}</label>
                                    @endif

                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('tipo_id') is-invalid @enderror" id="tipo_id" name="tipo_id">
                                            @foreach ($tipos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if ($requests['tipo'] == "receita")
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="cliente_id" class="form-label"> {{ __('messages.clientes') }} </label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id">
                                            @foreach ($clientes as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                @if ($requests['tipo'] == "dispesa")
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="fornecedor_id" class="form-label">Fornecedores</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2 @error('fornecedor_id') is-invalid @enderror" id="fornecedor_id" name="fornecedor_id">
                                            @foreach ($fornecedores as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <div class="col-12 col-md-6 {{ $requests['tipo'] != 'dispesa' ? 'col-lg-4' : 'col-lg-2' }}">
                                    <label for="descricao" class="form-label">Descrição (<small>podes editar</small>)</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control  @error('descricao') is-invalid @enderror" name="descricao" id="descricao" value="{{ $observacao ?? old('descricao') }}" placeholder="Descrição">
                                    </div>
                                </div>


                                @if ($requests['tipo'] == "dispesa")
                                <div class="col-12 col-md-6 col-lg-2">
                                    <label for="comprovativo" class="form-label">Comprovativo</label>
                                    <div class="input-group mb-3">
                                        <input type="file" name="comprovativo" id="comprovativo" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                    </div>
                                </div>
                                @endif

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar dispesa'))
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
    const forma_recebimento_id = document.getElementById('forma_recebimento_id');
    const forma_pagamento_id = document.getElementById('forma_pagamento_id');

    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');
    const form_caixas_valores = document.getElementById('form_caixas_valores');
    const form_bancos_valores = document.getElementById('form_bancos_valores');

    form_caixas.style.display = 'none';
    form_bancos.style.display = 'none';

    form_caixas_valores.style.display = 'none';
    form_bancos_valores.style.display = 'none';


    if (forma_recebimento_id) {
        forma_recebimento_id.addEventListener('change', function() {

            let selectedOption = this.options[this.selectedIndex]; // Obtém o <option> selecionado
            let tipo = selectedOption.getAttribute('data-id'); // Pega o data-id

            if (tipo === 'NU') {
                form_caixas.style.display = 'block';
                form_bancos.style.display = 'none';

                form_caixas_valores.style.display = 'block';
                form_bancos_valores.style.display = 'none';

            } else if (tipo === 'MB' || tipo === 'DE' || tipo === 'TE') {
                form_bancos.style.display = 'block';
                form_caixas.style.display = 'none';

                form_caixas_valores.style.display = 'none';
                form_bancos_valores.style.display = 'block';

            } else if (tipo === 'OU') {
                form_caixas.style.display = 'block';
                form_bancos.style.display = 'block';
                form_caixas_valores.style.display = 'block';
                form_bancos_valores.style.display = 'block';
            }
        });
    } else {
        console.error("Elemento com ID 'forma_recebimento_id' não encontrado!");
    }

    if (forma_pagamento_id) {
        forma_pagamento_id.addEventListener('change', function() {

            let selectedOption = this.options[this.selectedIndex]; // Obtém o <option> selecionado
            let tipo = selectedOption.getAttribute('data-id'); // Pega o data-id

            if (tipo === 'NU') {
                form_caixas.style.display = 'block';
                form_bancos.style.display = 'none';

                form_caixas_valores.style.display = 'block';
                form_bancos_valores.style.display = 'none';

            } else if (tipo === 'MB' || tipo === 'DE' || tipo === 'TE') {
                form_bancos.style.display = 'block';
                form_caixas.style.display = 'none';

                form_caixas_valores.style.display = 'none';
                form_bancos_valores.style.display = 'block';

            } else if (tipo === 'OU') {
                form_caixas.style.display = 'block';
                form_bancos.style.display = 'block';
                form_caixas_valores.style.display = 'block';
                form_bancos_valores.style.display = 'block';
            }
        });
    } else {
        console.error("Elemento com ID 'forma_pagamento_id' não encontrado!");
    }


    document.getElementById("motante").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número
        let numericValue = parseFloat(value) / 100; // Ajusta casas decimais

        // Formata para exibição no padrão brasileiro (10.000,50)
        e.target.value = numericValue.toLocaleString("pt-BR", {
            minimumFractionDigits: 2
        });
    });

    document.getElementById("motante_banco").addEventListener("input", function(e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número
        let numericValue = parseFloat(value) / 100; // Ajusta casas decimais

        // Formata para exibição no padrão brasileiro (10.000,50)
        e.target.value = numericValue.toLocaleString("pt-BR", {
            minimumFractionDigits: 2
        });
    });


    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário
            //=======================================================
            // formatar valores
            let motante = document.getElementById("motante");
            // Converter de "10.000,50" para "10000.50"
            let rawValue = motante.value.replace(/\./g, "").replace(",", ".");
            motante.value = parseFloat(rawValue).toFixed(2); // Garantir 2 casas decimais

            // formatar valores
            let motante_banco = document.getElementById("motante_banco");
            // Converter de "10.000,50" para "10000.50"
            let rawValue1 = motante_banco.value.replace(/\./g, "").replace(",", ".");
            motante_banco.value = parseFloat(rawValue1).toFixed(2); // Garantir 2 casas decimais
            // //==============================================================

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

                    showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');

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
                            messages += `${value}\n`; // Exibe os erros
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
