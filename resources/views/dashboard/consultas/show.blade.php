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
                        <li class="breadcrumb-item"><a href="{{ route('consultas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Consultas</li>
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
                                    <a class="nav-link active" id="dados-consulta-tab" data-toggle="pill" href="#dados-consulta" role="tab" aria-controls="dados-consulta" aria-selected="true">DADOS DA CONSULTA</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="lista-consultas-tab" data-toggle="pill" href="#lista-consultas" role="tab" aria-controls="lista-consultas" aria-selected="false">LISTA DAS CONSULTA</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="lista-exames-tab" data-toggle="pill" href="#lista-exames" role="tab" aria-controls="lista-exames" aria-selected="false">LISTA DOS EXAMES SOLICITADOS</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-three-tabContent">

                                <div class="tab-pane fade show active" id="dados-consulta" role="tabpanel" aria-labelledby="dados-consulta-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-4 table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Consulta Nº</th>
                                                        <td class="text-right">{{ $consulta->id }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th> {{ __('messages.data') }} </th>
                                                        <td class="text-right">{{ $consulta->data_consulta }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Hora</th>
                                                        <td class="text-right">{{ $consulta->hora_consulta }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Estado do Pagamento</th>
                                                        <td class="text-right">{{ $consulta->pago }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-4 table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>

                                                    <tr>
                                                        <th>Nome Paciente</th>
                                                        <td class="text-right">{{ $consulta->paciente->nome }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.genero') }}</th>
                                                        <td class="text-right">
                                                            {{ $consulta->paciente->genero ?? '-------------' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('messages.idade') }}</th>
                                                        <td class="text-right">
                                                            {{ $consulta->paciente->idade($consulta->paciente->data_nascimento) }}
                                                            Anos</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Estado da Consulta</th>
                                                        <td class="text-right">{{ $consulta->status }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-4 table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>Nome do Médico</th>
                                                        <td class="text-right">
                                                            {{ $consulta->medico ? ($consulta->medico->funcionario ? $consulta->medico->funcionario->nome : '') : '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('messages.genero') }}</th>
                                                        <td class="text-right">
                                                            {{ $consulta->medico ? ($consulta->medico->funcionario ? $consulta->medico->funcionario->genero : '') : '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Especialidade</th>
                                                        <td class="text-right">
                                                            {{ $consulta->medico ? ($consulta->medico->especialidade ? $consulta->medico->especialidade->nome : '') : '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Valor Total</th>
                                                        <td class="text-right">
                                                            {{ number_format($consulta->total, 2, ',', '.') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-12 table-responsive">
                                            <div class="tab-custom-content">
                                                <p class="lead mb-0 pb-2">RESULTADO GERAL DA CONSULTA</p>
                                            </div>

                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <th>O que foi dignosticado</th>
                                                        <td class="text-right">{{ $consulta->diagnosticado ?? '-------------' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>O qua foi avaliado</th>
                                                        <td class="text-right">{{ $consulta->avaliado ?? '-------------' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Encaminhamento</th>
                                                        <td class="text-right">{{ $consulta->atendimento->nome ?? '-------------' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="lista-consultas" role="tabpanel" aria-labelledby="lista-consultas-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-12 table-responsive">
                                            <table class="table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ __('messages.designacao') }}</th>
                                                        <th>{{ __('messages.categoria') }}</th>
                                                        <th>------------</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @include('dashboard.atendimentos._views.detalhes-consultas', ["consulta" => $consulta])
                                                    <tr>
                                                        <td colspan="4">
                                                            <a target="_blank" href="{{ route('consultas-imprimir', $consulta->id) }}" class="btn btn-light-primary float-right"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }} </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-12 col-md-12 text-center">
                                            @if (Auth::user()->can('consultorio') || Auth::user()->can('criar consulta'))
                                            <a href="{{ route('consultas.create', ['origem' => 'atendimento', 'atendimento_id' => $consulta->atendimento_id]) }}" class="h3 py-3 my-5 btn btn-light-primary">Solicitar Novas consultas</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="lista-exames" role="tabpanel" aria-labelledby="lista-exames-tab">
                                    @if (count($consulta->atendimento->exames) != 0)
                                    @foreach ($consulta->atendimento->exames as $resultado)
                                    @include('dashboard.exames._views.detalhe-exame', ["dados" => $resultado, "editar" => false])
                                    @endforeach
                                    @endif
                                    <div class="col-12 col-md-12 text-center">
                                        @if (Auth::user()->can('consultorio') || Auth::user()->can('monitoramento consultorio'))
                                        <a href="{{ route('solicitacoes-medicas.create', ['origem' => 'atendimento', 'atendimento_id' => $consulta->atendimento_id]) }}" class=" h3 py-3 my-5 btn btn-light-primary">Solicitar Novos Exames</a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer d-flex">

                            @if (Auth::user()->can('listar atendimento') || Auth::user()->can('consultorio'))
                            <a class="btn btn-light-primary mr-2" href="{{ route('atendimentos.show', $consulta->atendimento_id) }}">
                                <i class="fas fa-table"></i> Detalhe do Atendimento
                            </a>
                            @endif

                            @if ($consulta->status == 'CONCLUIDO')
                            @if (Auth::user()->can('criar receita medica') || Auth::user()->can('consultorio'))

                            <a href="{{ route('consulta-atestado-medico', $consulta->id) }}" class="btn btn-light-warning mr-2" target="_blank"><i class="fas fa-file-medical-alt"></i> Atestado médico</a>

                            <a class="btn btn-light-primary mr-2" href="{{ route('consulta-receita-medica', $consulta->atendimento_id) }}"><i class="fas fa-table"></i> Receitar o paciente</a>
                            @endif

                            @if (Auth::user()->can('criar internamento') || Auth::user()->can('consultorio'))
                            <a class="btn btn-light-primary mr-2" href="{{ route('internamentos.create', ['atendimento_id' => $consulta->atendimento_id]) }}"><i class="fas fa-table"></i> Internar o paciente</a>
                            @endif

                            @if (Auth::user()->can('criar tratamento') || Auth::user()->can('consultorio'))
                            <a class="btn btn-light-primary mr-2" href="{{ route('planos-tratamentos.create', ['atendimento_id' => $consulta->atendimento_id]) }}"><i class="fas fa-table"></i> Criar Plano de Tratamento</a>
                            @endif
                            @endif

                            @if (Auth::user()->can('listar todos') || Auth::user()->can('listar consulta') || Auth::user()->can('consultorio'))
                            <a class="btn btn-light-primary mr-2" target="_blank" href="{{ route('consultas-imprimir', $consulta->id) }}"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }}</a>
                            @endif

                            @if ($consulta->status !== "CONCLUIDO")
                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar consulta'))
                            <a class="btn btn-light-success mr-2" href="{{ route('consultas.edit', $consulta->id) }}"><i class="fas fa-edit"></i> Editar</a>
                            @endif
                            @endif

                            @if ($consulta->status !== "CONCLUIDO")
                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar consulta'))
                            <button class="btn btn-light-danger delete-record" data-id="{{ $consulta->id }}">
                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                            </button>
                            @endif
                            @endif

                        </div>
                        <!-- /.card -->
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
                    url: `{{ route('consultas.destroy', ':id') }}`.replace(':id', recordId)
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
                        window.location.href = "/consultas";
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
