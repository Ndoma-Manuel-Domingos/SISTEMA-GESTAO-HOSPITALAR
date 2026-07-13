@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Mostrador</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Mostrador</li>
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
                        <div class="card-header text-center py-4">
                            <label for="service" class="form-label">Escolha o serviço:</label>
                        </div>

                        <div class="card-body text-center">
                            <select id="service" class="form-control">
                                <option value="">-- selecione --</option>
                                @foreach($servicos as $s)
                                <option value="{{ $s->id }}" data-code="{{ $s->codigo_barra }}">{{ $s->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="card-footer">
                            <button id="btn-get" class="btn btn-light-primary" disabled>Obter Senha</button>
                            <div id="result" class="result"></div>
                        </div>
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

</script>
@endsection
