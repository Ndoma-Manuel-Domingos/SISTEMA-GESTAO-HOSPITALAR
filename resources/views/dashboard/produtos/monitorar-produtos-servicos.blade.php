@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Monitoramento Produtos e Serviços</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
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
            <!-- /.row -->
            <div class="row">

                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('visualizacao-produtos-servicos') }}" method="get">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label class="label-form">Data</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" name="data_at" placeholder="{{ __('messages.filtrar') }}...">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="user_id" class="form-label">Operadores</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control select2" name="user_id" id="user_id">
                                            <option value="">{{ __('messages.todos') }}</option>
                                            @foreach ($empresa_logada->empresa->users as $user)
                                            <option value="{{ $user->id }}" {{ $requests['user_id'] == $user->id ? 'selected' : ''}}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-light-primary btn-sm ml-2 text-right"> <i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                                <a href="{{ route('visualizacao-produtos-servicos-pdf', ['data_at' => $data ?? NULL]) }}" target="_blink" class="btn btn-light-danger btn-sm ml-2 text-right"> <i class="fas fa-file-pdf"></i> IMPRIMIR PDF</a>
                            </div>

                        </form>
                    </div>
                </div>


                <div class="col-12 col-md-12">

                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                @foreach ($categorias as $categoria)
                                @if (count($categoria->produtos) != 0)
                                <thead>
                                    <tr class="bg-light">
                                        <th colspan="4">CATEGORIA: <strong style="text-transform: uppercase">{{ $categoria->categoria }}</strong></th>
                                    </tr>
                                    <tr class="bg-light">
                                        <th>Produto</th>
                                        <th>Quantidade Actual</th>
                                        <th>Quantidade Vendidas</th>
                                        <th>Quantidade Restantes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categoria->produtos as $produto)
                                    <tr>
                                        <td>{{ $produto->nome }}</td>
                                        <td>{{ number_format($produto->quantidade_entrada($loja->id), 2) }}</td>
                                        <td>{{ number_format($produto->quantidade_saida($loja->id, $user_id), 2) }}</td>
                                        <td>{{ number_format($produto->quantidade_entrada($loja->id) - $produto->quantidade_saida($loja->id, $user_id), 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @endif
                                @endforeach
                            </table>
                        </div>
                        <div class="card-footer"></div>
                    </div>

                </div>

            </div>
            <!-- /.row -->
        </div><!-- /.quartoiner-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
@endsection
