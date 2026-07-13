@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pagamento de Parcela</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">Inicio</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <form action="{{ route('mensalidade.pagamento.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <!-- ID OCULTO -->
                                <input type="hidden" name="mensalidade_id" value="{{ $mensalidade->id }}">
                                <!-- INFO -->
                                <div class="alert alert-info">
                                    <strong>Empresa:</strong>
                                    {{ $mensalidade->entidade->nome }}
                                    <br>
                                    <strong>Valor em dívida:</strong>
                                    AKZ
                                    {{ number_format($mensalidade->saldo_devedor,2,',','.') }}

                                </div>
                                <!-- VALOR -->
                                <div class="form-group">
                                    <label>Valor a pagar</label>
                                    <input type="number" step="0.01" name="valor_pago" class="form-control" required>
                                </div>

                                <!-- METODO -->
                                <div class="form-group">
                                    <label>Método de Pagamento</label>
                                    <select name="metodo_pagamento" class="form-control" required>
                                        <option value="cash">Dinheiro</option>
                                        <option value="transferencia">Transferência</option>
                                        <option value="multicaixa">Multicaixa</option>
                                        <option value="pos">POS</option>
                                    </select>
                                </div>

                                <!-- REFERÊNCIA -->
                                <div class="form-group">
                                    <label>Referência (opcional)</label>
                                    <input type="text" name="referencia" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-success">
                                    <i class="fas fa-check"></i>
                                    Confirmar Pagamento
                                </button>
                                <a href="{{ url()->previous() }}" class="btn btn-light-secondary">
                                    Voltar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
