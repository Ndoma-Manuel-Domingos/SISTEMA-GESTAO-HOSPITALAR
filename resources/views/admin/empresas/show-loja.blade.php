@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }} - {{ $loja->nome ?? "" }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('empresas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Loja</li>
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
                <div class="col-12 col-md-12 mb-3">
                    <a href="{{ route('empresas.exportar-fluxo-loja', ['empresa' => $empresa->id, 'loja' => $loja->id]) }}" class="btn btn-light-primary"><i class="fas fa-file-pdf"></i> Exportar</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-12 border-0">
                    <div class="small-box bg-light-success p-0" title="Receita">
                        <div class="inner">
                            <h5 class="fw-bold text-uppercase">Receitas</h5>
                            <h2 class="fw-bold">{{ number_format($receita, 2, ',', '.') }}</h2>
                            <p class="mb-0 text-white">Receitas</p>
                        </div>
                        <div class="icon">
                            <i class="ion fa-arrow-trend-up"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-12 border-0">
                    <div class="small-box bg-light-danger p-0" title="Despesa">
                        <div class="inner">
                            <h5 class="fw-bold text-uppercase">Dispesas</h5>
                            <h2 class="fw-bold">{{ number_format($despesa, 2, ',', '.') }}</h2>
                            <p class="mb-0 text-white">Dispesas</p>
                        </div>
                        <div class="icon">
                            <i class="ion fa-arrow-trend-down"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-12 border-0">
                    <div class="small-box {{ $lucro > 0 ? 'bg-light-success' : 'bg-light-danger' }} p-0" title="Lucro">
                        <div class="inner">
                            <h5 class="fw-bold text-uppercase">Lucro</h5>
                            <h2 class="fw-bold">{{ number_format($lucro, 2, ',', '.') }}</h2>
                            <p class="mb-0 text-white">Lucro</p>
                        </div>
                        <div class="icon">
                            <i class="ion fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card mt-3">
                    <div class="card-header bg-light-primary">
                        <h3 class="card-title">LOJA(POSTO) - {{ $loja->nome ?? "" }}</h3>
                        <button class="btn btn-light-success btn-sm btn-add-caixa float-right mx-2" data-loja="{{ $loja->id }}" data-empresa="{{ $empresa->id }}">
                            <i class="fas fa-plus"></i> Caixa
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($loja->caixas as $caixa)
                            <div class="col-md-3 caixa-item" style="cursor: pointer" data-id="{{ $caixa->id }}">
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

        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<div class="modal fade" id="modalCaixa">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">Criar Nova Caixa</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="empresa_id">
                <input type="hidden" id="loja_id">
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


</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    $(document).on('click', '.btn-add-caixa', function() {
        let empresa_id = $(this).data('empresa');
        let loja_id = $(this).data('loja');
        $('#empresa_id').val(empresa_id);
        $('#loja_id').val(loja_id);
        $('#nome_caixa').val('');
        $('#modalCaixa').modal('show');
    });

    $(document).on('click', '.caixa-item', function() {

        let id = $(this).data('id');
        let box = $(this);

        Swal.fire({
            title: 'Alterar estado da caixa?'
            , text: "Deseja ativar/desativar esta caixa?"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '# 16 a34a '
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
                , loja_id: $('#loja_id').val()
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
