@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-file-invoice-dollar text-primary"></i> Cobrança da Seguradora <small class="text-muted">{{ $seguradora->nome ?? "N/A" }}</small></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-hospital') }}">Cobraças</a></li>
                        <li class="breadcrumb-item active"> Cobrança da Seguradora</li>
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
                <div class="col-md-3">
                    <div class="small-box bg-light-warning">
                        <div class="inner">
                            <h3>{{ $contas->count() }}</h3>
                            <p>Contas Hospitalares</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-light-primary">
                        <div class="inner">
                            <h3>{{ number_format($contas->sum('valor_seguradora'),2,",",".") }}</h3>
                            <p>Total a Cobrar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-light-success">
                        <div class="inner">
                            <h3>{{ number_format($contas->sum('valor_pago_seguradora'),2,",",".") }}</h3>
                            <p>Total Pago</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-light-danger">
                        <div class="inner">
                            <h3>{{ number_format($contas->sum('saldo_seguradora'),2,",",".") }}</h3>
                            <p>Em Dívida</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Contas Hospitalares
                    </h3>
                    <div class="card-tools">
                        <form action="{{ route('fechos-contas.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="seguradora_id" value="{{ $seguradora->id ?? "" }}">
                            <input type="hidden" name="mes" value="{{ $mes }}">
                            <input type="hidden" name="ano" value="{{ $ano }}">
                            <button class="btn btn-light-success" type="submit">
                                <i class="fas fa-file-invoice"></i>
                                Gerar Factura da Seguradora
                            </button>
                            <a class="btn btn-light-danger" href="{{ route('fechos-contas-seguradora.imprimir', $seguradora->id) }}">
                                <i class="fas fa-print"></i>
                                Imprimir Factura
                            </a>
                        </form>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered" id="carregar_tabela">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Conta</th>
                                <th>Data</th>
                                <th>Paciente</th>
                                <th>Plano</th>
                                <th>Total Conta</th>
                                <th>Seguradora</th>
                                <th>Pago</th>
                                <th>Dívida</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contas as $conta)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('contas-hospitalares.show', $conta->id) }}">{{ $conta->numero }}</a>
                                </td>
                                <td>
                                    {{ date('d/m/Y',strtotime($conta->created_at)) }}
                                </td>
                                <td>
                                    {{ $conta->paciente->nome }}
                                </td>
                                <td>
                                    {{ $conta->plano->nome }}
                                </td>
                                <td>
                                    {{ number_format($conta->total,2,",",".") }}
                                </td>
                                <td>
                                    {{ number_format($conta->valor_seguradora,2,",",".") }}
                                </td>
                                <td class="text-success font-weight-bold">
                                    {{ number_format($conta->valor_pago_seguradora,2,",",".") }}
                                </td>
                                <td class="text-danger font-weight-bold">
                                    {{ number_format($conta->saldo_seguradora,2,",",".") }}
                                </td>
                                <td>
                                    @if($conta->saldo_seguradora==0)
                                    <span class="badge badge-success">
                                        Liquidada
                                    </span>
                                    @else
                                    <span class="badge badge-warning">
                                        Pendente
                                    </span>
                                    @endif
                                </td>
                                <td width="80">
                                    <a href="{{ route('contas-hospitalares.show', $conta->id) }}" class="btn btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">
                                    Nenhuma conta encontrada.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">
                                    Totais
                                </th>
                                <th>
                                    {{ number_format($contas->sum('total'),2,",",".") }}
                                </th>
                                <th>
                                    {{ number_format($contas->sum('valor_seguradora'),2,",",".") }}
                                </th>
                                <th class="text-success">
                                    {{ number_format($contas->sum('valor_pago_seguradora'),2,",",".") }}
                                </th>
                                <th class="text-danger">
                                    {{ number_format($contas->sum('saldo_seguradora'),2,",",".") }}
                                </th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
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
                    showMessage('Sucesso!', 'Exportação concluída com sucesso!', 'success');
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
