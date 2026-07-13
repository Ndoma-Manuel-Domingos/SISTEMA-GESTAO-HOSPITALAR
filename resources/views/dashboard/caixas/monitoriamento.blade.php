@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Monitoramento de Caixa</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        @if($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-hospital') }}">{{ __('messages.voltar') }}</a></li>
                        @else
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        @endif
                        <li class="breadcrumb-item active">Caixas</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            {{-- <div class="row">
                @foreach($caixas as $caixa)
                @php
                $aberto = $caixa->status == 'aberto';
                $corCard = $aberto ? 'success' : 'danger';
                $icone = $aberto ? 'fa-lock-open' : 'fa-lock';
                @endphp

                <div class="col-md-3">
                    <div class="card card-{{ $corCard }} card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas {{ $icone }}"></i>
                    {{ $caixa->nome }}
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($aberto)
                    <span class="badge badge-success p-2">
                        CAIXA ABERTO
                    </span>
                    @else
                    <span class="badge badge-danger p-2">
                        CAIXA FECHADO
                    </span>
                    @endif
                </div>

                <table class="table table-sm">
                    <tr>
                        <th>Código</th>
                        <td>
                            {{ $caixa->codigo ?? '---' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Operador</th>
                        <td>
                            {{ $caixa->user_open ? $caixa->user_open->name : '---' }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="card-footer text-center">
                <button class="btn btn-light-success btn-sm delete-record" data-id="{{ $caixa->id }}">
                    <i class="fas fa-lock-open"></i>
                    Abrir Caixa
                </button>
            </div>

        </div>

    </div>

    @endforeach

</div> --}}

<div class="row">
    <!-- STATUS -->
    <div class="col-md-4">

        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">
                    Estado do Caixa
                </h3>
            </div>

            <div class="card-body text-center">

                @if($caixaAberto)

                <div class="mb-3">

                    <span class="badge badge-light-success p-3" style="font-size:18px;">
                        CAIXA ABERTO
                    </span>

                </div>

                <h4>
                    {{ $caixaAberto->nome }}
                </h4>

                <p class="text-muted">
                    Aberto em:
                    <br>
                    {{ date('d/m/Y H:i', strtotime($caixaAberto->created_at)) }}
                </p>

                <hr>

                <h3 class="text-light-success">
                    AKZ {{ number_format($caixaAberto->valor_inicial, 2, ',', '.') }}
                </h3>

                @else

                <div class="mb-3">

                    <span class="badge badge-light-danger p-3" style="font-size:18px;">
                        CAIXA FECHADO
                    </span>

                </div>

                <p class="text-muted">
                    Nenhum caixa aberto no momento.
                </p>

                @endif

            </div>

        </div>

    </div>

    <!-- AÇÕES -->
    <div class="col-md-8">

        @if(!$caixaAberto)

        <!-- ABRIR CAIXA -->
        <div class="card card-light-success">

            <div class="card-header">
                <h3 class="card-title">
                    Abrir Caixa
                </h3>
            </div>

            <form action="{{ route('caixa.abertura_caixa_create') }}" method="POST" id="aberturaCaixa">
                @csrf
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>
                                    Selecionar Caixa
                                </label>

                                <select name="caixa_id" class="form-control" required>
                                    <option value="">
                                        Selecionar
                                    </option>

                                    @foreach($caixas as $caixa)

                                    <option value="{{ $caixa->id }}">
                                        {{ $caixa->nome }}
                                    </option>

                                    @endforeach

                                </select>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>
                                    Valor Inicial
                                </label>

                                <input type="number" step="0.01" name="valor" class="form-control" placeholder="0,00" required>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-light-success" type="submit">
                        <i class="fas fa-lock-open"></i>
                        Abrir Caixa
                    </button>
                </div>
            </form>

        </div>

        @else

        <!-- FECHAR CAIXA -->
        <div class="card card-light-danger">
            <div class="card-header">
                <h3 class="card-title">
                    Fechar Caixa
                </h3>
            </div>
            <form action="{{ route('caixa.fechamento_caixa_create') }}" method="POST" id="fechoCaixa">
                @csrf
                <input type="hidden" name="caixa_id" id="caixa_id" value="{{ $caixaAberto->id }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="small-box bg-light-primary">
                                <div class="inner">
                                    <h3>
                                        AKZ {{ number_format($entradas, 2, ',', '.') }}
                                    </h3>
                                    <p>
                                        Entradas
                                    </p>
                                </div>

                                <div class="icon">
                                    <i class="fas fa-arrow-down"></i>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="small-box bg-light-danger">

                                <div class="inner">

                                    <h3>
                                        AKZ {{ number_format($saidas, 2, ',', '.') }}
                                    </h3>

                                    <p>
                                        Saídas
                                    </p>

                                </div>

                                <div class="icon">
                                    <i class="fas fa-arrow-up"></i>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="small-box bg-light-success">

                                <div class="inner">

                                    <h3>
                                        AKZ {{ number_format($saldoAtual, 2, ',', '.') }}
                                    </h3>

                                    <p>
                                        Saldo Atual
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Observação
                        </label>
                        <textarea name="observacao" class="form-control" rows="3" placeholder="Observação do fechamento..."></textarea>
                    </div>

                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-light-danger" onclick="return confirm('Deseja realmente fechar o caixa?')">
                        <i class="fas fa-lock"></i>
                        Fechar Caixa
                    </button>
                </div>
            </form>
        </div>

        @endif

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
    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let caixa_id = $(this).data('id'); // Obtém o ID do registro
        let valor = 0; // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Vais subistituir este caixa do outro operador para ti!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, tenho!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('caixa.abertura_caixa_create') }}`
                    , method: 'POST'
                    , data: {
                        _token: '{{ csrf_token() }}'
                        , 'caixa_id': caixa_id
                        , 'valor': valor
                    , }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
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

        $('#aberturaCaixa').on('submit', function(e) {
            e.preventDefault();

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
                    progressBeforeSend();
                }
                , success: function(response) {

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
            });
        });

        $('#fechoCaixa').on('submit', function(e) {
            e.preventDefault();

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
                    progressBeforeSend();
                }
                , success: function(response) {

                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');

                    console.log(response)

                    window.location.href = response.redirect;
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
            });
        });
    });

</script>
@endsection
