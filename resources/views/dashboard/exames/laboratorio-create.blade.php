@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laboratorio - Lançamento de Resultados</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('consultorio.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Consultório</li>
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

                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Preenchar dos dados - Resultados</h3>
                            <div class="card-tools">
                                @if (Auth::user()->can('adicionar item conta hospitalar'))
                                <a href="{{ route('atendimentos.show', $origem->id) }}" class="btn btn-light-primary">Actualizar conta Hospitalar do paciente</a>
                                @endif
                            </div>

                        </div>
                        <div class="card-body">
                            @if (count($origem->exames) !== 0)
                            @foreach ($origem->exames as $resultado)
                            @include('dashboard.exames._views.detalhe-exame', ['dados' => $resultado, 'editar' => true])
                            @endforeach
                            @endif
                        </div>

                        <div class="card-footer">
                            @if (Auth::user()->can('laboratorio'))
                            <a href="{{ route('exames-atendimento-imprimir', $origem->id) }}" target="_blink" class="btn btn-light-warning mr-2"><i class="fas fa-print"></i> Imprimir</a>
                            @endif

                            @if (Auth::user()->can('laboratorio'))
                            <a class="btn btn-light-primary mr-2 enviar-resultados" data-id="{{ $origem->id }}"><i class="fas fa-send"></i> Enviar Resultados</a>
                            @endif

                        </div>
                    </div>
                    <input type="hidden" name="origem_id" id="origem_id" value="{{ $origem->id }}">
                </div>

                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dados da Triagem</h5>
                        </div>

                        @include('dashboard.atendimentos._views.card-triagem-visualizacao')

                        <div class="card-footer"></div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="modal fade" id="modalImagens" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Imagens do Resultado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="lista-imagens"></div>
                    <hr>
                    <div class="text-center position-relative d-inline-block w-100">
                        <img id="imagem-grande" src="" style="max-width:100%; max-height:500px; display:none; border-radius:10px;">
                        <div class="mt-2">
                            <button id="btn-remover-imagem" class="btn btn-danger btn-sm" style="display:none;">
                                Remover Imagem
                            </button>
                        </div>
                    </div>
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
    let tipos = @json($tipos_atendimentos);

    let optionsDestinos = '';

    tipos.forEach(i => {
        optionsDestinos += `<option value="${i.id}">${i.nome}</option>`;
    });


    $(document).on('click', '.enviar-resultados', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id');

        let origem = $("#origem_id").val();

        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Deseja realmente enviar os resultados?"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonText: 'Sim, continuar'
            , cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Informações do envio'
                    , html: `
                        <div class="row">
                            <div class="col-12 col-md-12 mb-3  text-left">
                                <label class="form-label">Destino do Paciente</label>
                                <select id="swal_status" class="form-control" >
                                    <option value="">Selecione</option>
                                    ${optionsDestinos}
                                </select>
                            </div>
                            <div class="col-12 col-md-12  text-left">
                                <label class="form-label">Observação</label>
                                <textarea id="swal_observacao" class="form-control" placeholder="Digite uma observação"></textarea>
                            </div>

                        </div>
                    `
                    , showCancelButton: true
                    , confirmButtonText: 'Enviar'
                    , cancelButtonText: 'Cancelar'
                    , focusConfirm: false
                    , preConfirm: () => {
                        let status = $('#swal_status').val();
                        let observacao = $('#swal_observacao').val();
                        if (!status) {
                            Swal.showValidationMessage('Selecione uma opção');
                            return false;
                        }
                        return {
                            status: status
                            , observacao: observacao
                        };
                    }
                }).then((dados) => {
                    if (dados.isConfirmed) {
                        $.ajax({
                            url: `{{ route('exames.enviar-resultados', ':id') }}`.replace(':id', recordId)
                            , method: 'POST'
                            , data: {
                                _token: '{{ csrf_token() }}'
                                , status: dados.value.status
                                , origem_id: origem
                                , observacao: dados.value.observacao
                            }
                            , beforeSend: function() {
                                progressBeforeSend();
                            }
                            , success: function(response) {
                                Swal.close();
                                showMessage(
                                    'Sucesso!'
                                    , response ? response.message : 'Operação realizada com sucesso!'
                                    , 'success'
                                );
                                window.location.reload();
                            }
                            , error: function(xhr) {
                                Swal.close();
                                showMessage(
                                    'Erro!'
                                    , xhr.responseJSON ? xhr.responseJSON.message : 'Ocorreu um erro ao processar a operação.'
                                    , 'error'
                                );
                            }
                        });
                    }
                });
            }
        });
    });


    document.querySelectorAll('.linha-principal').forEach(row => {
        row.addEventListener('click', function() {

            let id = this.getAttribute('data-id');
            let dropdownAtual = document.getElementById('drop-' + id);

            // Fecha todos os dropdowns
            document.querySelectorAll('.dropdown-parametros').forEach(drop => {
                if (drop !== dropdownAtual) {
                    drop.style.display = 'none';
                }
            });

            // Toggle do atual
            if (dropdownAtual.style.display === 'table-row') {
                dropdownAtual.style.display = 'none';
            } else {
                dropdownAtual.style.display = 'table-row';
            }
        });
    });

    $(document).on('change', '.resultado-descricao', function() {
        let id = $(this).data('id');
        let descricao = $(this).val();

        $.ajax({
            url: '/sub-parametros-exames/atualizar-descricao'
            , method: 'POST'
            , data: {
                id: id
                , descricao: descricao
                , _token: '{{ csrf_token() }}'
            }
            , success: function(res) {
                console.log('Atualizado com sucesso');
            }
        });
    });

    $(document).on('change', '.resultado-input', function() {
        let id = $(this).data('id');
        let valor = $(this).val();

        $.ajax({
            url: '/sub-parametros-exames/atualizar-valor'
            , method: 'POST'
            , data: {
                id: id
                , valor: valor
                , _token: '{{ csrf_token() }}'
            }
            , success: function(res) {
                console.log('Atualizado com sucesso');
            }
        });
    });

    $(document).on('change', '.imagem-input', function() {
        let id = $(this).data('id');
        let files = this.files;

        let formData = new FormData();

        formData.append('id', id);
        formData.append('_token', '{{ csrf_token() }}');

        for (let i = 0; i < files.length; i++) {
            formData.append('imagens[]', files[i]);
        }

        $.ajax({
            url: '/sub-parametros-exames/upload-imagens'
            , method: 'POST'
            , data: formData
            , processData: false
            , contentType: false
            , success: function(res) {
                console.log('Imagens enviadas com sucesso');
            }
        });
    });

    let imagensAtuais = [];
    let imagemSelecionada = null;
    let parametroId = null;

    $(document).on('click', '.btn-ver-imagens', function() {

        parametroId = $(this).data('paramento');

        let imagens = $(this).attr('data-imagens') || '[]';
        imagens = JSON.parse(imagens);

        if (typeof imagens === 'string') {
            imagens = JSON.parse(imagens);
        }

        imagensAtuais = Array.isArray(imagens) ? imagens : [];

        renderImagens();

        $('#modalImagens').modal('show');
        $('#imagem-grande').hide();
        // $('#btn-remover-imagem').show();
    });

    function renderImagens() {

        let container = $('#lista-imagens');
        container.html('');

        imagensAtuais.forEach((img, index) => {
            container.append(`
                <div class="col-md-3 mb-2">
                    <img src="/${img}"
                         class="img-thumbnail img-click"
                         data-index="${index}"
                         style="cursor:pointer; height:120px; object-fit:cover;">
                </div>
            `);
        });
    }

    $(document).on('click', '.img-click', function() {
        let index = $(this).data('index');
        imagemSelecionada = index;
        let src = imagensAtuais[index];

        $('#imagem-grande').attr('src', '/' + src).fadeIn();

        $('#btn-remover-imagem').show();
    });


    $('#btn-remover-imagem').on('click', function() {

        if (imagemSelecionada === null) return;
        let imagem = imagensAtuais[imagemSelecionada];

        $.ajax({
            url: '/sub-parametros-exames/remover-imagem'
            , method: 'POST'
            , data: {
                id: parametroId
                , imagem: imagem
                , _token: '{{ csrf_token() }}'
            }
            , success: function() {
                imagensAtuais.splice(imagemSelecionada, 1);
                renderImagens();
                $('#imagem-grande').hide();
                $('#btn-remover-imagem').hide();
                imagemSelecionada = null;
            }
        });
    });

</script>
@endsection
