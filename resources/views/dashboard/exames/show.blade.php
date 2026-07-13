@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.mais_detalhes') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('exames.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Exame</li>
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
                    <div class="card card-primary card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="dados-exames-tab" data-toggle="pill" href="#dados-exames" role="tab" aria-controls="dados-exames" aria-selected="true">DADOS DO EXAME</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="detalhes-exame-tab" data-toggle="pill" href="#detalhes-exame" role="tab" aria-controls="detalhes-exame" aria-selected="false">DETALHES & LANÇAMENTO DE RESULTADOS</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="dados-exames" role="tabpanel" aria-labelledby="dados-exames-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Exame Nº:</th>
                                                        <td class="text-right">{{ $exame->id }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Data:</th>
                                                        <td class="text-right">{{ $exame->data_exame }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Hora:</th>
                                                        <td class="text-right">{{ $exame->hora_exame }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Estado do Pagamento:</th>
                                                        <td class="text-right">{{ $exame->pago }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Nome Paciente:</th>
                                                        <td class="text-right">{{ $exame->paciente->nome }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Genero:</th>
                                                        <td class="text-right">{{ $exame->paciente->genero ?? '-------------' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('messages.idade') }}:</th>
                                                        <td class="text-right">{{ $exame->paciente->idade($exame->paciente->data_nascimento) }} Anos</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Estado da exame:</th>
                                                        <td class="text-right">{{ $exame->status }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Prioridade:</th>
                                                        <td class="text-right">{{ $exame->prioridade->nome ?? '-------------' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Solicitante:</th>
                                                        <td class="text-right">
                                                            @if ($exame->solicitante_type == 'PACIENTE')
                                                            @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                                                            <a href="{{ route('clientes.show', $exame->solicitante_paciente ? $exame->solicitante_paciente->id : '') }}">{{ $exame->solicitante_paciente ? $exame->solicitante_paciente->nome : '' }}</a>
                                                            @endif
                                                            @endif
                                                            @if ($exame->solicitante_type == 'PROFISSIONAL')
                                                            @if (Auth::user()->can('listar todos') || Auth::user()->can('listar medico'))
                                                            <a href="{{ route('medicos.show', $exame->solicitante_medico ? $exame->solicitante_medico->id : '') }}">
                                                                {{ $exame->solicitante_medico ? $exame->solicitante_medico->funcionario->nome : '' }}
                                                                -
                                                                {{ $exame->solicitante_medico ? $exame->solicitante_medico->especialidade->nome : '' }}</a>
                                                            @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Consulta Nº</th>
                                                        <td class="text-right">
                                                            <a href="{{ route('consultas.show', $exame->consulta ? $exame->consulta->id : '#') }}">{{ $exame->consulta ? $exame->consulta->id : 'sem consulta' }}</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Profissional de saúde:</th>
                                                        <td class="text-right"><a href="{{ route('medicos.show', $exame->profissional ? $exame->profissional->id : '#') }}">{{ $exame->profissional ? $exame->profissional->id : 'sem profissional' }}</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Valor Total:</th>
                                                        <td class="text-right">{{ number_format($exame->total, 2, ',', '.') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="detalhes-exame" role="tabpanel" aria-labelledby="detalhes-exame-tab">
                                    @include('dashboard.exames._views.detalhe-exame', ["dados" => $exame, "editar" => false])
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex">
                            @if (Auth::user()->can('laboratorio'))
                            <a href="{{ route('exames-imprimir', $exame->id) }}" target="_blink" class="btn btn-light-warning mr-2"><i class="fas fa-print"></i> Imprimir</a>
                            @endif

                            @if ((Auth::user()->can('editar todos') || Auth::user()->can('editar exame')) && $exame->status !== 'CONCLUIDO')
                            <a class="btn btn-light-success mr-2" href="{{ route('exames.edit', $exame->id) }}"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                            @endif

                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar exame'))
                            <button class="btn btn-light-danger delete-record" data-id="{{ $exame->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>
                            @endif
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
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
                    url: `{{ route('exames.destroy', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = "/exames";
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

    });

</script>
@endsection
