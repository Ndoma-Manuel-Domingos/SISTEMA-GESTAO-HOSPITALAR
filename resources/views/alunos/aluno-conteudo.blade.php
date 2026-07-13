@extends('layouts.alunos')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Conteúdos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-alunos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Conteúdos</li>
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
                        @if ($anuncios)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Titulo</th>
                                        <th> {{ __('messages.descricao') }} </th>
                                        <th>Turma</th>
                                        <th>Formador</th>
                                        <th>{{ __('messages.data_inicio') }}</th>
                                        <th>{{ __('messages.data_final') }}</th>
                                        <th>Situação</th>
                                        <th class="text-right">{{ __('messages.accoes') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($anuncios as $item)
                                    <tr>
                                        <td>{{ $item->nome ?? '---' }}</td>
                                        <td>{{ $item->descricao ?? '---' }}</td>
                                        <td>{{ $item->turma->nome ?? '---' }}</td>
                                        <td>{{ $item->formador->nome ?? '---' }}</td>
                                        <td>{{ $item->data_inicio ?? '---' }}</td>
                                        <td>{{ $item->data_final ?? '---' }}</td>
                                        <td>{{ $item->verificar_envio($entidade->aluno->id, $item->id) == true ? "Enviado" : "Não Enviado"  }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light-primary">{{ __('messages.opcoes') }}</button>
                                                <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a href="{{ route('alunos-enviar-conteudo', $item->id) }}" class="dropdown-item">Enviar Conteudo</a>
                                                    <div class="dropdown-divider"></div>
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
