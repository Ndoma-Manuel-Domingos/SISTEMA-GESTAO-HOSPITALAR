@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-file-invoice-dollar text-primary"></i> Contas Hospitalares</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-hospital') }}">Valtar</a></li>
                        <li class="breadcrumb-item active">Contas</li>
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
                    <div class="card card-outline card-primary">

                        <div class="card-header">
                            <h3 class="card-title">Pesquisar Contas</h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="row" action="{{ route('contas-hospitalares.index') }}">

                                <!-- SEARCH GLOBAL -->
                                <div class="col-md-6">
                                    <label>Pesquisa Geral</label>
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Paciente, BI, Nº Conta, Referência, Atendimento">
                                </div>

                                <!-- STATUS -->
                                <div class="col-md-3">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="ABERTA" {{ request('status')=='ABERTA'?'selected':'' }}>Aberta</option>
                                        <option value="PARCIAL" {{ request('status')=='PARCIAL'?'selected':'' }}>Parcial</option>
                                        <option value="FECHADA" {{ request('status')=='FECHADA'?'selected':'' }}>Fechada</option>
                                        <option value="PAGA" {{ request('status')=='PAGA'?'selected':'' }}>Paga</option>
                                        <option value="CANCELADA" {{ request('status')=='CANCELADA'?'selected':'' }}>Cancelada</option>
                                    </select>
                                </div>

                                <!-- BOTÃO -->
                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i>
                                        Pesquisar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Contas</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-hover" id="carregar_tabela">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nº</th>
                                        <th>Paciente</th>
                                        <th>Atendimento</th>
                                        <th>Subtotal</th>
                                        <th>Pago</th>
                                        <th>Divida</th>
                                        <th>Status</th>
                                        <th width="150">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contas as $conta)
                                    <tr>
                                        <td><a href="{{ route('contas-hospitalares.show', $conta->id) }}">{{ $conta->numero }}</a></td>
                                        <td><a href="{{ route('clientes.show', $conta->paciente->id) }}">{{ $conta->paciente->nome }}</a></td>
                                        <td><a href="{{ route('atendimentos.show', $conta->atendimento_id) }}">{{ $conta->atendimento->numero }}</a></td>
                                        <td>
                                            {{ number_format($conta->subtotal,2,',','.') }}
                                        </td>
                                        <td>
                                            {{ number_format($conta->valor_pago,2,',','.') }}
                                        </td>
                                        <td>
                                            <span class="badge badge-danger">
                                                {{ number_format($conta->saldo,2,',','.') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($conta->status == 'PAGA')
                                            <span class="badge badge-light-success">{{ $conta->status }}</span>
                                            @elseif($conta->status == 'ABERTA')
                                            <span class="badge badge-light-primary">{{ $conta->status }}</span>
                                            @else
                                            <span class="badge badge-light-danger">{{ $conta->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('contas-hospitalares.show', $conta->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                                Ver
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>

</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
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
