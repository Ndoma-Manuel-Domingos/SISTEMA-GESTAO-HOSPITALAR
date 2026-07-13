@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Mapa IRT & Mapa INSS</h1>
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
            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('processamentos.mapas-irt-mapa-inss') }}" method="GET">
                        <div class="card">
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                                    <select type="text" class="form-control select2" id="tipo_documento" name="tipo_documento">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        <option value="inss"> MAPA INSS</option>
                                        <option value="irt"> MAPA IRT</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="exercicio_id" class="form-label"> {{ __('messages.exercicio') }} </label>
                                    <select type="text" class="form-control select2" id="exercicio_id" name="exercicio_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($exercicios as $item)
                                        <option value="{{ $item->id ?? "" }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="periodo_id" class="form-label"> {{ __('messages.periodo') }} </label>
                                    <select type="text" class="form-control select2" id="periodo_id" name="periodo_id">
                                        <option value="">{{ __('messages.escolher') }} </option>
                                        @foreach ($periodos as $item)
                                        <option value="{{ $item->id ?? "" }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary"><i class="fas fa-filter"></i> Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.content -->
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
