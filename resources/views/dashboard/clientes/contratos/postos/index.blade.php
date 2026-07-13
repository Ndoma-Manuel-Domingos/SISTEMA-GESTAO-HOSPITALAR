@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        {{ __('messages.listagem') }}
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
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
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar cliente'))
                                <a href="{{ route('contratos-postos.create') }}" class="btn btn-light-primary"><i class="fas fa-plus"></i> {{ __('messages.novo') }}</a>
                                @endif
                            </h3>
                            <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('pdf-clientes') }}"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Posto</th>
                                        <th>Contrato Nº</th>
                                        <th>Cliente</th>
                                        <th>Responsável</th>
                                        <th>Equipa</th>
                                        <th>Uso de Arma</th>
                                        <th>Tipo Posto</th>
                                        <th>Endereço</th>
                                        <th class="text-right">{{ __('messages.accoes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($postos as $item)
                                    <tr>
                                        <td><a href="{{ route('contratos-postos.show', $item->id) }}">{{ $item->id ?? "" }}</a></td>
                                        <td><a href="{{ route('contratos-postos.show', $item->id) }}">{{ $item->nome ?? "" }}</a></td>

                                        <td><a href="{{ route('funcionarios.show', $item->equipa->responsavel->id) }}">{{ $item->equipa->responsavel->nome ?? "" }}</a></td>

                                        @if ($item->contrato)
                                        <td><a href="{{ route('clientes-contratos.show', $item->contrato->id) }}">{{ $item->contrato->codigo_contrato ?? "" }}</a></td>
                                        @else
                                        <td>-----</td>
                                        @endif

                                        @if ($item->contrato->cliente)
                                        <td><a href="{{ route('clientes.show', $item->contrato->cliente->id) }}">{{ $item->contrato->cliente->nome ?? "" }}</a></td>
                                        @else
                                        <td>-----</td>
                                        @endif
                                        <td><a href="{{ route('equipas.show', $item->equipa->id) }}">{{ $item->equipa->nome ?? "" }}</a></td>
                                        <td>{{ $item->uso_armas == "Y" ? "Sim" : "Não" }}</td>

                                        <td>{{ $item->tipo_posto->nome ?? "" }}</td>
                                        <td>{{ $item->endereco }}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <button class="btn btn-light-danger dropdown-item btn-atribuir" data-id="{{ $item->id ?? "" }}">
                                                        <i class="fas fa-eye text-light-primary"></i> Atribuir Equipa
                                                    </button>
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                                    <a class="dropdown-item" href="{{ route('contratos-postos.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif
                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar cliente'))
                                                    <a class="dropdown-item" href="{{ route('contratos-postos.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar cliente'))
                                                    <button class="btn btn-light-danger dropdown-item delete-record" data-id="{{ $item->id ?? "" }}">
                                                        <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

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

<!-- Modal -->
<div class="modal fade" id="modalAtribuir" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAtribuir">
                <div class="modal-header">
                    <h5 class="modal-title">Atribuir Equipa ao Posto</h5>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Equipa</label>
                        <select name="equipa_id" class="form-control equipa_id" id="equipa_id" required>
                            @foreach ($equipas as $equipa)
                            <option value="{{ $equipa->id }}">{{ $equipa->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Posto</label>
                        <select name="posto_id" class="form-control posto_id" id="posto_id" required>
                            @foreach ($postos as $posto)
                            <option value="{{ $posto->id }}">{{ $posto->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-light-success">{{ __('messages.salvar') }}</button>
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">{{ __('messages.cancelar') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>



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
                    url: `{{ route('contratos-postos.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!'
                            , 'Ocorreu um erro ao excluir o registro. Tente novamente.'
                            , 'error');
                    }
                , });
            }
        });
    });


    $(document).ready(function() {
        let modal = $("#modalAtribuir");

        // Abrir modal e preencher empresa_id
        $(".btn-atribuir").on("click", function() {
            let contrato_id = $(this).data("id");
            $("#posto_id").val(contrato_id);
            modal.modal("show");
        });

        // Submeter via AJAX
        $("#formAtribuir").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('contratos-postos.atribuir-equipa') }}"
                , type: "POST"
                , data: $(this).serialize()
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(res) {
                    modal.modal("hide");
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Equipa atribuída com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                }
            });
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
