@extends('layouts.formadores')

@section('content')

@php
$meuSaldo = 5000;
@endphp

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
                        <li class="breadcrumb-item"><a href="{{ route('formadores-provas.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Prova</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 col-md-6">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th> {{ __('messages.designacao') }} </th>
                                                <td class="text-right">{{ $prova->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th> {{ __('messages.descricao') }} </th>
                                                <td class="text-right">{{ $prova->descricao ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Nota Maxima</th>
                                                <td class="text-right">{{ $prova->nota_maxima ?? '-------------' }} V</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-6">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th> {{ __('messages.data') }} </th>
                                                <td class="text-right">{{ $prova->data_at ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Formador</th>
                                                <td class="text-right">{{ $prova->formador->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Turma</th>
                                                <td class="text-right">{{ $prova->turma->nome ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Nº </th>
                                                <th>Questão</th>
                                                <th>{{ __('messages.opcoes') }} A</th>
                                                <th>{{ __('messages.opcoes') }} B</th>
                                                <th>{{ __('messages.opcoes') }} C</th>
                                                <th>{{ __('messages.opcoes') }} D</th>
                                                <th>{{ __('messages.opcoes') }} E</th>
                                                <th class="text-center">Nota</th>
                                                <th class="text-center">{{ __('messages.opcoes') }}</th>
                                            </tr>
                                            @foreach ($prova->questoes as $item)
                                            <tr>
                                                <td class="text-left">#</td>
                                                <td class="text-left">{{ $item->questao ?? '-------------' }}</td>
                                                <td class="text-left">{{ $item->opcao_a ?? '-------------' }}</td>
                                                <td class="text-left">{{ $item->opcao_b ?? '-------------' }}</td>
                                                <td class="text-left">{{ $item->opcao_c ?? '-------------' }}</td>
                                                <td class="text-left">{{ $item->opcao_d ?? '-------------' }}</td>
                                                <td class="text-left">{{ $item->opcao_e ?? '-------------' }}</td>
                                                <td class="text-center">{{ $item->nota ?? '-------------' }}</td>
                                                <td class="text-center">{{ $item->opcao_certa ?? '-------------' }}</td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer clearfix d-flex">
                            <a href="{{ route('formadores-provas.edit', $prova->id) }}" class="btn btn-light-success mx-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('formadores-provas.destroy', $prova->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light-danger mx-1" onclick="return confirm('Tens Certeza que Desejas excluir esta prova?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
