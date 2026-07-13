@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Quartos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'REST')
                            <a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a>
                            @endif
                            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOTL')
                            <a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a>
                            @endif
                            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                            <a href="{{ route('dashboard-hospital') }}">{{ __('messages.voltar') }}</a>
                            @endif
                        </li>
                        <li class="breadcrumb-item active">Quartos</li>
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
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format(count($tipos), 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Tipos Quartos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('tipo-quartos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format(count($andares), 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Andares</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('andares.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary">
                        <div class=" inner">
                            <h3>{{ number_format(count($leitos), 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Leitos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('camas.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">

                            <h3 class="card-title">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar quarto'))
                                <button type="button" onclick="toggleModal()" class="btn btn-light-primary">
                                    <i class="fas fa-plus"></i> {{ __('messages.novo') }}
                                </button>
                                @endif
                            </h3>
                        </div>

                        @if ($quartos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> {{ __('messages.designacao') }} </th>
                                        <th>Tipo</th>
                                        <th>Andar</th>
                                        <th>Ocupação</th>
                                        <th> {{ __('messages.descricao') }} </th>
                                        <th><span class="float-right">{{ __('messages.accoes') }} </span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quartos as $item)
                                    <tr>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->nome ?? '' }}</td>
                                        <td>{{ $item->tipo->nome ?? '' }}</td>
                                        <td>{{ $item->andar->nome ?? '' }}</td>
                                        <td>{{ $item->solicitar_ocupacao ?? '' }}</td>
                                        <td>{{ $item->descricao }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('listar todos') || Auth::user()->can('listar quarto'))
                                                    <a class="dropdown-item" href="{{ route('quartos.show', $item->id) }}"><i class="fas fa-eye text-light-primary"></i> {{ __('messages.mais_detalhes') }} </a>
                                                    @endif

                                                    @if (Auth::user()->can('editar todos') || Auth::user()->can('editar quarto'))
                                                    <a href="#" data-id="{{ $item->id ?? "" }}" class="dropdown-item edit-folder text-light-success"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar quarto'))
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

    <form action="{{ route('quartos.store') }}" method="post" class="">
        @csrf
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-xl  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row py-4">
                        <div class="col-12 col-md-6">
                            <label for="nome" class="form-label"> {{ __('messages.designacao') }} </label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-lg" name="nome" id="nome" value="{{ old('nome') }}" placeholder="Informe a Designação">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="capacidade" class="form-label">Capacidade de Pacientes</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-lg" name="capacidade" id="capacidade" value="{{ old('capacidade') }}" placeholder="Informe a Capacidade">
                            </div>
                        </div>


                        <div class="col-12 col-md-6">
                            <label for="tipo_id" class="form-label">Tipo</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control form-control-lg" id="tipo_id" name="tipo_id">
                                    <option value="">{{ __('messages.escolher') }} </option>
                                    @foreach ($tipos as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-12 col-md-6">
                            <label for="andar_id" class="form-label">Andar</label>
                            <div class="input-group mb-3">
                                <select type="text" class="form-control form-control-lg" id="andar_id" name="andar_id">
                                    <option value="">{{ __('messages.escolher') }} </option>
                                    @foreach ($andares as $item)
                                    <option value="{{ $item->id ?? "" }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="inicio" class="form-label">{{ __('messages.inicio') }}</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-lg" name="inicio" id="inicio" value="{{ old('inicio') }}" placeholder="Ex: 1">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="quantidade" class="form-label">{{ __('messages.quantidade') }} ou limite</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-lg" name="quantidade" id="quantidade" value="{{ old('quantidade') }}" placeholder="Ex: 5">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="descricao" class="form-label"> {{ __('messages.descricao') }} </label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-lg" name="descricao" id="descricao" value="{{ old('descricao') }}" placeholder="Informe a Descrição">
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                        {{-- @if (Auth::user()->can('criar todos') || Auth::user()->can('criar departamento')) --}}
                        <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                        {{-- @endif --}}
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    let PastaID = null;
    let modalVisible = false;


    const modalElement = document.getElementById('modal-lg');
    const modalInstance = new bootstrap.Modal(modalElement);

    function toggleModal() {
        PastaID = null;
        document.getElementById('quantidade').disabled = false;
        if (modalVisible) {
            modalInstance.hide();
            modalVisible = false;
        } else {
            modalInstance.show();
            modalVisible = true;
        }
    }

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            let new_form = null;
            let method = null;

            if (PastaID == null) {
                new_form = form.attr('action');
                method = "post";
            } else {
                method = "put";
                new_form = form.attr('action') + "/" + PastaID;
            }

            $.ajax({
                url: new_form, // URL do endpoint no backend
                method: method, // Método HTTP definido no formulário
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
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });

        });
    });

    $(document).on('click', '.edit-folder', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // Envia a solicitação AJAX para excluir o registro
        $.ajax({
            url: `{{ route('quartos.edit', ':id') }}`.replace(':id', recordId)
            , method: 'GET'
            , data: {
                _token: '{{ csrf_token() }}', // Inclui o token CSRF
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {

                Swal.close();
                modalInstance.show();

                PastaID = response.data.id;
                document.getElementById('quantidade').disabled = true;
                document.getElementById('nome').value = response.data.nome;
                document.getElementById('capacidade').value = response.data.capacidade;
                document.getElementById('andar_id').value = response.data.andar_id;
                document.getElementById('tipo_id').value = response.data.tipo_id;
                document.getElementById('descricao').value = response.data.descricao;
                document.getElementById('quarto_id').value = response.data.quarto_id;

            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
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
                    url: `{{ route('quartos.destroy', ':id') }}`.replace(':id', recordId)
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
