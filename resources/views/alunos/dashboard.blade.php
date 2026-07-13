@extends('layouts.alunos')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Meu Perfil</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-alunos') }}">Home</a></li>
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
                <div class="col-12 col-md-6">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr>
                                <th>{{ __('messages.designacao') }}</th>
                                <td class="text-right">{{ $entidade->aluno->nome ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('messages.genero') }}</th>
                                <td class="text-right">{{ $entidade->aluno->genero ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('messages.estado_civil') }}</th>
                                <td class="text-right">{{ $entidade->aluno->estado_civil ?? '-------------' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-12 col-md-6">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr>
                                <th> {{ __('messages.bilhete_identidade') }} </th>
                                <td class="text-right">{{ $entidade->aluno->nif ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>País</th>
                                <td class="text-right">{{ $entidade->aluno->pais ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>{{ __('messages.telefone') }}/{{ __('messages.email') }}</th>
                                <td class="text-right">{{ $entidade->aluno->telefone ?? '-------------' }} / {{ $entidade->aluno->email ?? '-------------' }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Hospedes">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Solicitar Documentos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-documentos.index') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Hospedes">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Provas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-provas') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Reservas">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Matrículas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-matriculass') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Reservas">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Conteúdos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-videos.conteudo') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Reservas">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">vídeos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-videos.videos') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Hospedes">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Anuncios</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-anuncios') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box  bg-light-primary" title=" Envio de Conteudo">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Envio de Conteudo</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-envio-conteudo') }}" class="small-box-footer"> {{ __('messages.mais_informacoes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6><strong>Dados da Turma</strong></h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="tableaaaa" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Titulo</th>
                                        <th class="text-right"> {{ __('messages.descricao') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alunoTurmas as $item)
                                    <tr>
                                        <th>Curso</th>
                                        <td class="text-right"><strong>{{ $item->turma->curso->nome ?? '-------------' }} </td>
                                    </tr>

                                    <tr>
                                        <th>Sala</th>
                                        <td class="text-right">{{ $item->turma->sala->nome ?? '-------------' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Turno</th>
                                        <td class="text-right">{{ $item->turma->turno->nome ?? '-------------' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6><strong>Modulos/Disciplinas do Curso</strong></h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela1" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="text-right"> {{ __('messages.designacao') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alunoTurmas as $item)
                                    @if ($item->turma->curso->modulos && count($item->turma->curso->modulos) != 0)
                                    @foreach ($item->turma->curso->modulos as $i)
                                    <tr>
                                        <td class="text-right">
                                            {{ $i->nome }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6><strong>Dados dos Formadores</strong></h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela2" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Dados</th>
                                        <th class="text-right">Dados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alunoTurmas as $item)
                                    @if ($item->turma->formadores)
                                    @foreach ($item->turma->formadores as $it)
                                    <tr>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <td class="text-right"><strong>{{ $it->formador->nome ?? '-------------' }} </td>
                                    </tr>

                                    <tr>
                                        <th> {{ __('messages.telemovel') }} </th>
                                        <td class="text-right">{{ $it->formador->telefone?? '-------------' }}</td>
                                    </tr>

                                    <tr>
                                        <th> {{ __('messages.data_nascimento') }}</th>
                                        <td class="text-right">{{ $it->formador->email ?? '-------------' }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
    $(function() {
        $("#tableaaaa").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');

        $("#carregar_tabela1").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');

        $("#carregar_tabela2").DataTable({
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
