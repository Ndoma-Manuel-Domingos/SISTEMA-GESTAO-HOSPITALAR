@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhes do Membro</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('membros.index') }}">{{ __('messages.voltar') }}</a></li>
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

                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Empresas do Membro</h3>

                            <button class="btn btn-light-success float-right" data-toggle="modal" data-target="#modalMembroEmpresa">
                                Adicionar Empresa ao Membro
                            </button>

                        </div>

                        <div class="card-body">
                            <div class="row">
                                @foreach($membro->empresas as $empresa)
                                <div class="col-md-6">
                                    <button class="btn btn-light-danger btn-sm btn-remover-empresa" data-empresa="{{ $empresa->id }}" style="position:absolute; top:95px; right:20px; z-index:10;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <div class="small-box bg-light-primary">
                                        <div class="inner">
                                            <h4>{{ $empresa->nome }}</h4>
                                            <p>Facturamento: <b>{{ number_format( $empresa->facturamentos->where('type', 'R')->sum('motante') -  $empresa->facturamentos->where('type', 'D')->sum('motante')
                                                ,2,',','.') }} Kz</b></p>
                                            <p>Caixas: <b>{{ $empresa->caixas->count() }}</b></p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="card mt-3">
                                                <div class="card-header bg-light-warning">
                                                    <h3 class="card-title">Caixas (POS)</h3>
                                                    <button class="btn btn-light-success btn-sm btn-add-caixa float-right" data-empresa="{{ $empresa->id }}">
                                                        <i class="fas fa-plus"></i> Caixa
                                                    </button>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach($empresa->caixas as $caixa)
                                                        <div class="col-md-4 caixa-item" style="cursor: pointer" data-id="{{ $caixa->id }}">
                                                            <div class="info-box {{ $caixa->status_admin == "liberado" ? ' bg-light-success ' : ' bg-light-danger ' }}">
                                                                <span class="info-box-icon">
                                                                    <i class="fas fa-cash-register"></i>
                                                                </span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text">{{ $caixa->nome }}</span>
                                                                    <span class="info-box-number">{{ $caixa->status }}</span>
                                                                    <span class="info-box-number text-uppercase status_admin">{{ $caixa->status_admin }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="modal fade" id="modalMembroEmpresa">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Gestão de Membros da Empresa</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Selecionar empresa</label>
                        <select id="entidade_id" class="form-control select2">
                            @foreach($entidades as $item)
                            <option value="{{ $item->id ?? "" }}">{{ $item->nome }} - {{ $item->nif }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-success" id="btnAddMembro">
                        Adicionar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCaixa">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Criar Nova Caixa</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="empresa_id">
                    <div class="form-group">
                        <label>Nome da Caixa</label>
                        <input type="text" id="nome_caixa" class="form-control" placeholder="Ex: Caixa Principal">
                    </div>
                    <div class="form-group">
                        <label>Status Inicial</label>
                        <select id="status_admin" class="form-control">
                            <option value="liberado">Liberado</option>
                            <option value="bloqueado">Bloqueado</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" id="btnSalvarCaixa">
                        Criar Caixa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    $('#btnAddMembro').on('click', function() {
        $.ajax({
            url: '/membro/add-empresa'
            , type: 'POST'
            , data: {
                membro_id: '{{ $membro->id }}'
                , entidade_id: $('#entidade_id').val()
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function() {
                Swal.close();
                Swal.fire('Sucesso!', 'Empresa adicionado.', 'success');
                location.reload();
            }
        });
    });

    $(document).on('click', '.btn-add-caixa', function() {
        let empresa_id = $(this).data('empresa');
        $('#empresa_id').val(empresa_id);
        $('#nome_caixa').val('');
        $('#modalCaixa').modal('show');
    });

    $(document).on('click', '.btn-remover-empresa', function() {

        let empresa_id = $(this).data('empresa');
        let card = $(this).closest('.col-md-6');

        Swal.fire({
            title: 'Remover empresa?'
            , text: "Esta empresa será desvinculada do membro"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#198754'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, remover'
            , cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/membro/remover-empresa'
                    , type: 'POST'
                    , data: {
                        membro_id: '{{ $membro->id }}'
                        , empresa_id: empresa_id
                        , _token: $('meta[name="csrf-token"]').attr('content')
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function() {
                        Swal.close();
                        Swal.fire(
                            'Removido!'
                            , 'Empresa removida do membro com sucesso.'
                            , 'success'
                        );
                        // remover card sem reload
                        card.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '.caixa-item', function() {

        let id = $(this).data('id');
        let box = $(this);

        Swal.fire({
            title: 'Alterar estado da caixa?'
            , text: "Deseja ativar/desativar esta caixa?"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#16a34a'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, confirmar'
            , cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/caixa/toggle-status'
                    , type: 'POST'
                    , data: {
                        id: id
                        , _token: $('meta[name="csrf-token"]').attr('content')
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        if (response.success) {
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });


    $('#btnSalvarCaixa').on('click', function() {
        $.ajax({
            url: '/empresas-caixa/store'
            , type: 'POST'
            , data: {
                empresa_id: $('#empresa_id').val()
                , nome: $('#nome_caixa').val()
                , status_admin: $('#status_admin').val()
                , _token: $('meta[name="csrf-token"]').attr('content')
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                Swal.fire(
                    'Sucesso!'
                    , 'Caixa criada com sucesso.'
                    , 'success'
                );
                $('#modalCaixa').modal('hide');
                // opcional: atualizar página ou lista
                location.reload();
            }
        });
    });

</script>
@endsection
