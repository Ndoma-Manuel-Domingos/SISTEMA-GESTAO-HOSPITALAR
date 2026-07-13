@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Triagens</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-hospital') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Triagens</li>
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
                        <div class="card-header">
                            <h1 class="h5">Triagens pendentes</h1>
                        </div>

                        @if ($atendimentos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela1" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Número</th>
                                        <th>Paciente</th>
                                        <th>Prioridade</th>
                                        <th>Cor</th>
                                        <th>Tipo</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($atendimentos as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->numero }}</td>
                                        <td>{{ $item->paciente->nome }}</td>
                                        <td>{{ $item->prioridade->nome }}</td>
                                        <td>{{ $item->prioridade->tipo_cor($item->prioridade->cor) }}</td>
                                        <td>{{ $item->tipo->nome }}</td>

                                        @if ($item->status == 'aguardando')
                                        <td><span class="badge" style="background-color: #FFF3CD;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'em atendimento')
                                        <td><span class="badge" style="background-color: #B8DAFF;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'atendido')
                                        <td><span class="badge" style="background-color: #D4EDDA;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        @if ($item->status == 'ausente')
                                        <td><span class="badge" style="background-color: #F8D7DA;">{{ $item->status }}</span>
                                        </td>
                                        @endif

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar triagem'))
                                                    <a href="{{ route('triagens.create', ['atendimento_id' => $item->id]) }}" class="dropdown-item text-light-primary update-record"><i class="fas fa-table"></i> Atender</a>
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

            <!-- /.row -->
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <div class="card-tools">
                                <a class="btn btn-light-danger" target="_blank" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                            </div> --}}
                        </div>

                        @if ($triagens)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Triagem Nº</th>
                                        <th>Paciente</th>
                                        <th>Médico</th>
                                        <th>{{ __('messages.estados') }}</th>
                                        <th>Peso</th>
                                        <th>Temperatura</th>
                                        <th>Altura</th>
                                        <th>Pressão</th>
                                        <th>Frequencia Respiratória</th>
                                        <th>Frenquencia Cardiaca</th>
                                        <th>IMC</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($triagens as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->paciente->nome }}</td>
                                        <td>asdas</td>
                                        @if ($item->status == 'AGENDADA')
                                        <td class="text-light-primary">{{ $item->status }}</td>
                                        @endif
                                        @if ($item->status == 'CONCLUIDO')
                                        <td class="text-light-success">{{ $item->status }}</td>
                                        @endif
                                        @if ($item->status == 'EM ATENDIMENTO')
                                        <td class="text-light-warning">{{ $item->status }}</td>
                                        @endif
                                        @if ($item->status == 'CANCELADA')
                                        <td class="text-light-danger">{{ $item->status }}</td>
                                        @endif
                                        <td>{{ $item->peso }}</td>
                                        <td>{{ $item->temperatura }}</td>
                                        <td>{{ $item->altura }}</td>
                                        <td>{{ $item->pressao }}</td>
                                        <td>{{ $item->freq_respiratoria }}</td>
                                        <td>{{ $item->freq_cardiaca }}</td>
                                        <td>{{ $item->imc }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">

                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar triagem'))
                                                    <a class="dropdown-item" target="_blink" href="{{ route('triangs.triagens-imprimir', $item->id) }}"><i class="fas fa-file-pdf text-light-primary"></i> {{ __('messages.imprimir') }}</a>
                                                    <a class="dropdown-item" href="{{ route('triagens.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar triagem'))
                                                    <a class="dropdown-item" href="{{ route('triagens.edit', $item->id) }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif

                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar triagem'))
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
    $(function() {
        $("#carregar_tabela1").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });



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
                    url: `{{ route('triagens.destroy', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

</script>
@endsection
