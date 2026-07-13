@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.pagamentos_processamentos') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">{{ __('messages.voltar') }}</a></li>
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
            @if (count($processamentos) == 0)
            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('pagamentos-processamentos') }}" method="GET">
                        <div class="card">
                            <div class="card-body row">
                                <div class="col-12 col-md-4">
                                    <label for="proc_id" class="form-label">{{ __('messages.tipo_processamento') }}</label>
                                    <select type="text" class="form-control select2" id="proc_id" name="proc_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($tipo_processamentos as $item)
                                        <option value="{{ $item->id ?? "" }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="exer_id" class="form-label"> {{ __('messages.exercicio') }} </label>
                                    <select type="text" class="form-control select2" id="exer_id" name="exer_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($exercicios as $item)
                                        <option value="{{ $item->id ?? "" }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="per_id" class="form-label"> {{ __('messages.periodo') }} </label>
                                    <select type="text" class="form-control select2" id="per_id" name="per_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($periodos as $item)
                                        <option value="{{ $item->id ?? "" }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">Carregar processamentos</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('pagamentos-processamentos-store') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="processamento_id" class="form-label">{{ __('messages.tipo_processamento') }}</label>
                                    <select type="text" class="form-control select2" style="width: 100%" id="processamento_id" name="processamento_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($tipo_processamentos as $item)
                                        <option value="{{ $item->id ?? "" }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="exercicio_id" class="form-label"> {{ __('messages.exercicio') }} </label>
                                    <select type="text" class="form-control select2" style="width: 100%" id="exercicio_id" name="exercicio_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($exercicios as $item)
                                        <option value="{{ $item->id ?? "" }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="periodo_id" class="form-label"> {{ __('messages.periodo') }} </label>
                                    <select type="text" class="form-control select2" style="width: 100%" id="periodo_id" name="periodo_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($periodos as $item)
                                        <option value="{{ $item->id ?? "" }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="dias_processados" class="form-label">Dias processados</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" value="22" name="dias_processados" id="dias_processados" placeholder="Dias processados">
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label for="forma_de_pagamento" class="form-label">Formas de Pagamentos</label>
                                    <div class="form-group">
                                        <select name="forma_de_pagamento" id="forma_de_pagamentos" class="form-control">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($forma_pagmento as $forma)
                                            <option value="{{ $forma->tipo }}" class="text-uppercase"> {{ $forma->titulo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3" id="form_caixas" style="display: none">
                                    <label for="caixa_id" class="form-label">Escolha o Caixa</label>
                                    <div class="form-group">
                                        <select class="form-control" id="caixa_id" name="caixa_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($caixas as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3" id="form_bancos" style="display: none">
                                    <label for="caixa_id" class="form-label">Escolha a Conta Bancária</label>
                                    <div class="form-group">
                                        <select class="form-control" id="banco_id" name="banco_id">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($bancos as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label for="dispesa_id" class="form-label">Tipos de Custos</label>
                                    <div class="form-group">
                                        <select name="dispesa_id" id="dispesa_id" class="form-control">
                                            <option value="">{{ __('messages.escolher') }}</option>
                                            @foreach ($dispesas as $item)
                                            <option value="{{ $item->id ?? "" }}" class="text-uppercase"> {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">Pagamento</button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                {{-- <div class="card-tools">
                                    <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                </div> --}}
                            </div>

                            @if ($processamentos)
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Nº MAC</th>
                                            <th>{{ __('messages.nome') }}</th>
                                            <th>{{ __('messages.tipo_processamento') }}</th>
                                            <th> {{ __('messages.estados') }} </th>
                                            <th>Salário Base</th>
                                            <th>Salário Iliquido</th>
                                            <th>{{ __('messages.desconto') }}</th>
                                            <th>Salário líquido</th>
                                            <th> {{ __('messages.exercicio') }} </th>
                                            <th> {{ __('messages.periodo') }} </th>
                                            {{-- <th>Operador</th> --}}
                                            <th> {{ __('messages.data') }} </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($processamentos as $item)
                                        <tr>
                                            <td>{{ $item->id ?? "" }}</td>
                                            <td><a href="{{ route('funcionarios.show', $item->funcionario->id) }}">{{ $item->funcionario->numero_mecanografico }}</a>
                                            </td>
                                            <td>{{ $item->funcionario->nome }}</td>
                                            <td>{{ $item->processamento->nome }}</td>
                                            @if ($item->status == 'Pendente')
                                            <td><span class="badge  bg-light-primary">{{ $item->status }}</span></td>
                                            @endif
                                            @if ($item->status == 'Pago')
                                            <td><span class=" badge bg-light-success">{{ $item->status }}</span></td>
                                            @endif
                                            @if ($item->status == 'Anulado')
                                            <td><span class="badge bg-light-warning">{{ $item->status }}</span></td>
                                            @endif
                                            <td>{{ number_format($item->valor_base, 2, ',', '.') }}</td>
                                            <td>{{ number_format($item->valor_iliquido, 2, ',', '.') }}</td>
                                            <td>{{ number_format($item->total_desconto, 2, ',', '.') }}</td>
                                            <td>{{ number_format($item->valor_liquido, 2, ',', '.') }}</td>

                                            <td>{{ $item->exercicio->nome }}</td>
                                            <td>{{ $item->periodo->nome }}</td>
                                            {{-- <td>{{ $item->user->name }}</td> --}}
                                            <td>{{ $item->data_registro }}</td>

                                            <td><a href="{{ route('recibo-processamentos', $item->id) }}" class="text-center" target="_blank"><i class="fas fa-print"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            @endif

                        </div>
                        <!-- /.card -->
                    </form>
                </div>
            </div>
            @endif
        </div>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');

    $('#forma_de_pagamentos').on('change', function(e) {
        e.preventDefault();

        var forma_pagamento = $('#forma_de_pagamentos').val();

        if (forma_pagamento == "NU") {
            form_caixas.style.display = 'block';
            form_bancos.style.display = 'none';

        } else if (forma_pagamento == "MB" || forma_pagamento == "TE" || forma_pagamento == "DE") {
            form_bancos.style.display = 'block';
            form_caixas.style.display = 'none';

        } else if (forma_pagamento == "OU") {
            form_bancos.style.display = 'block';
            form_caixas.style.display = 'block';
        } else {
            form_caixas.style.display = 'none';
            form_bancos.style.display = 'none';
        }
    })


    $("#exercicio_id").change(() => {
        let id = $("#exercicio_id").val();
        $.get('../carregar-periodos/' + id, function(data) {
            $("#periodo_id").html("")
            $("#periodo_id").html(data)
        })
    })

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
