@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Solicitações {{ request()->get('status') ?? "" }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('atendimentos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">solicitações médicas</li>
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
                        <div class="card-header">
                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route("pdf.solicitacoes-medicas", ['status' => request()->get('status') ?? "" ]) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        @if ($solicitacoes)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Solicitação Nº</th>
                                        <th>Paciente</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>Prioridade</th>
                                        <th> {{ __('messages.data') }} </th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($solicitacoes as $item)
                                    <tr>
                                        <td>{{ $item->solicitacao }}</td>
                                        <td><a href="{{ route('clientes.show', $item->paciente_id) }}">{{ $item->paciente->nome }}</a></td>
                                        @if ($item->status == 'pendente')
                                        <td class="text-light-primary">{{ $item->status }}</td>
                                        @endif
                                        @if ($item->status == 'executado')
                                        <td class="text-light-success">{{ $item->status }}</td>
                                        @endif
                                        @if ($item->status == 'agendado')
                                        <td class="text-light-warning">{{ $item->status }}</td>
                                        @endif
                                        @if ($item->status == 'cancelado')
                                        <td class="text-light-danger">{{ $item->status }}</td>
                                        @endif
                                        <td>{{ $item->prioridade->nome }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td class="text-right">
                                            @if ($item->status == 'pendente')
                                            <button class="btn btn-light-primary" type="button" onclick="abrirPaciente({{$item}})"> <i class="fas fa-info"></i> Mais detalhe</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        @endif

                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div id="drawerPaciente" class="drawer p-2">
        <form id="formAgendarReceitas" method="POST" action="{{ route('confirmar.items-solicitacoes-medicas') }}">
            @csrf
            <input type="hidden" name="solicitacao_id" id="solicitacao_id">

            <div class="p-4">
                <div class="text-center mb-4">
                    <div class="avatar-lg">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                    <h4 id="paciente_nome"></h4>
                </div>
                <hr>
                <p><b>Bilhete:</b> <span id="paciente_nif"></span></p>
                <p><b>Género:</b> <span id="paciente_genero"></span></p>
                <p><b>Número Solicitação:</b> <span id="numero_solicitacao"></span></p>
                <p><b>Data Solicitação:</b> <span id="data_solicitacao"></span></p>
                <p><b>Prioridade:</b> <span id="prioridade"></span></p>
                <p><b>Estado:</b> <span id="estado"></span></p>
            </div>

            <div class="p-3">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selecionarTodos">
                            </th>
                            <th>Serviço</th>
                            <th>Tipo</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                    <tbody id="tabela_exames"></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th id="valor_total">0</th>
                        </tr>
                    </tfoot>
                </table>

                <div class="text-end mt-3">

                    <button type="submit" class="btn btn-light-success">
                        <i class="fas fa-calendar-check"></i>
                        Confirmar os dados
                    </button>

                    <button type="button" class="btn btn-light-danger float-right" onclick="fecharDrawer()">
                        Fechar
                    </button>

                </div>
            </div>
        </form>
    </div>

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
@section('scripts')
<script>
    function formatarDataHora(dataIso) {
        return new Date(dataIso).toLocaleString('pt-PT', {
            day: '2-digit'
            , month: '2-digit'
            , year: 'numeric'
            , hour: '2-digit'
            , minute: '2-digit'
            , second: '2-digit'
        });
    }

    function abrirDrawer() {
        $('#drawerPaciente').addClass('open');
    }

    function fecharDrawer() {
        $('#drawerPaciente').removeClass('open');
    }

    function abrirPaciente(item) {

        progressBeforeSend();

        abrirDrawer();

        $('#solicitacao_id').val(item.id);

        $('#paciente_nome').text(item.paciente.nome);
        $('#paciente_nif').text(item.paciente.nif);
        $('#paciente_genero').text(item.paciente.genero);
        $('#numero_solicitacao').text(item.solicitacao);
        $('#data_solicitacao').text(formatarDataHora(item.created_at));
        $('#prioridade').text(item.prioridade.nome);
        $('#estado').text(item.status);
        let html = '';
        let total = 0;
        item.items.forEach(i => {
            total += parseFloat(i.produto.preco_venda || 0);
            html += `
            <tr>
                <td>
                    <input type="checkbox" class="item-exame" name="itens[]" value="${i.id}">
                </td>
                <td>${i.produto?.nome ?? '-'}</td>
                <td>${i.produto?.categoria?.categoria ?? '-'}</td>
                <td>${Number(i.produto?.preco_venda || 0).toLocaleString()}</td>
            </tr>
        `;
        });

        $('#tabela_exames').html(html);
        $('#valor_total').text(total.toLocaleString());

        Swal.close();
    }

    $('#selecionarTodos').change(function() {
        $('.item-exame').prop('checked', $(this).is(':checked'));
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
                    // Feche o alerta de carregamento
                    Swal.close();
                    showMessage('Sucesso!', response.message, 'success');

                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }

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
