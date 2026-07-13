@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Equipamentos e Activos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Equipamentos e Activos</li>
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
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('activo contabilidade'))
                                <a href="{{ route('equipamentos-activos.create') }}" class="btn btn-light-primary"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                                @endif
                            </h3>

                            {{-- <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div> --}}
                        </div>

                        @if ($equipamentos_activos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Conta</th>
                                        <th> {{ __('messages.designacao') }} </th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th> {{ __('messages.quantidade') }} </th>
                                        <th>Base Incidência</th>
                                        <th>IVA</th>
                                        <th>Custo Aquisição</th>
                                        <th>Valor Contabilistico</th>
                                        <th>Data da Aquisição</th>
                                        <th>Data da Utilizacao</th>
                                        <th><span class="float-right">{{ __('messages.accoes') }} </span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipamentos_activos as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->conta->numero }}</td>
                                        <td>{{ $item->nome ?? "" }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->quantidade }}</td>
                                        <td>{{ number_format($item->base_incidencia, 2, ',', '.') }}</td>
                                        <td>{{ $item->iva }}</td>
                                        <td>{{ number_format($item->custo_aquisicao, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->valor_contabilistico, 2, ',', '.') }}</td>
                                        <td>{{ $item->data_aquisicao }}</td>
                                        <td>{{ $item->data_utilizacao }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('activo contabilidade'))
                                                    <a class="dropdown-item" href="{{ route('equipamentos-activos.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('activo contabilidade'))
                                                    @if ($item->status == "desactivo")
                                                    <a class="dropdown-item" href="{{ route('activar-equipamentos-activos', $item->id) }}"><i class="fas fa-check text-light-primary"></i> {{ __('messages.activo') }}</a>
                                                    @endif

                                                    @if ($item->status == "activo")
                                                    <a class="dropdown-item" href="{{ route('desactivar-equipamentos-activos', $item->id) }}"><i class="fas fa-times text-light-primary"></i> {{ __('messages.desactivo') }}</a>
                                                    @endif
                                                    @endif

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('activo contabilidade'))
                                                    <a class="dropdown-item" href="{{ route('equipamentos-activos.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('activo contabilidade'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item delete-record text-light-danger"><i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        @endif
                    </div>
                    <!-- /.card -->
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
    $(document).on('click', '.delete-record', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, excluir!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('equipamentos-activos.destroy', ':id') }}`.replace(':id', recordId)
                    , method: 'DELETE'
                    , data: {
                        _token: '{{ csrf_token() }}', // Inclui o token CSRF
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
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
