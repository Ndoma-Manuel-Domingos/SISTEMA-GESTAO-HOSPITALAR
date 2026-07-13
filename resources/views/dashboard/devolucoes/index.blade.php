@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Devolver Produtos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Devoluções</li>
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
                <div class="col-12 col-md-7">
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">

                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg" id="numero_fatura" placeholder="Digite o número da fatura">
                                <div class="input-group-append">
                                    <button onclick="buscarFatura()" type="button" class="btn btn-lg btn-default">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>

                            <div id="fatura_info" style="display:none">
                                <h5 class="my-3">Produtos da Fatura:</h5>
                                <form id="form_devolucao">
                                    <input type="hidden" name="fatura_id" id="fatura_id">
                                    <table class="table border">
                                        <thead>
                                            <tr>
                                                <th>Codigo Barra</th>
                                                <th>Produto</th>
                                                <th>Quantidade Comprada</th>
                                                <th>Quantidade Devolvida</th>
                                                <th>Devolver</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista_produtos"></tbody>
                                    </table>

                                    <div class="form-group">
                                        <label>Motivo da Devolução</label>
                                        <textarea name="motivo" class="form-control" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-light-danger">Confirmar Devolução</button>
                                </form>
                            </div>

                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    function buscarFatura() {
        let numero = $('#numero_fatura').val();
        $.ajax({
            url: "{{ route('devolucoes.buscarFatura') }}"
            , method: 'POST'
            , data: {
                _token: '{{ csrf_token() }}'
                , numero: numero
            }
            , success: function(data) {
                $('#fatura_info').show();
                $('#fatura_id').val(data.id);
                let html = '';
                data.items.forEach(item => {
                    html += `
                    <tr>
                        <td>${item.produto.codigo_barra}</td>
                        <td>${item.produto.nome}</td>
                        <td>${item.quantidade}</td>
                        <td>${item.quantidade_devolvida}</td>
                        <td><input type="number" name="produtos[]" class="form-control" min="0" max="${item.quantidade}" value="0"
                            data-produto_id="${item.produto.id}" data-item_id="${item.id}" data-lote_id="${item.lote_id}" data-quantidade="${item.quantidade}">
                        </td>
                    </tr>
                `;
                });
                $('#lista_produtos').html(html);
            }
            , error: function(xhr) {
                alert(xhr.responseJSON.erro);
            }
        });
    }

    $('#form_devolucao').submit(function(e) {
        e.preventDefault();

        let produtos = [];
        $('#lista_produtos input[type="number"]').each(function() {
            let qtd = parseInt($(this).val());
            if (qtd > 0) {
                produtos.push({
                    item_id: $(this).data('item_id')
                    , produto_id: $(this).data('produto_id')
                    , lote_id: $(this).data('lote_id')
                    , quantidade: qtd
                });
            }
        });

        $.ajax({
            url: "{{ route('devolucoes.store') }}"
            , method: 'POST'
            , data: {
                _token: '{{ csrf_token() }}'
                , fatura_id: $('#fatura_id').val()
                , motivo: $('textarea[name="motivo"]').val()
                , produtos: produtos
            }
            , success: function(res) {
                alert(res.mensagem);
                location.reload();
            }
        });
    });

</script>
@endsection
