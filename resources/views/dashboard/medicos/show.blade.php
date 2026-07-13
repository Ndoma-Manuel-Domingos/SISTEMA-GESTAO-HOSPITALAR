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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">

        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-12 col-md-6 col-lg-4">
                        <table class="table text-nowrap">
                            <tbody>
                                <tr>
                                    <th>{{ __('messages.designacao') }}</th>
                                    <td class="text-right">{{ $medico->funcionario->nome ?? '-------------' }}</td>
                                </tr>

                                <tr>
                                    <th>{{ __('messages.genero') }}</th>
                                    <td class="text-right">{{ $medico->funcionario->genero ?? '-------------' }}</td>
                                </tr>

                                <tr>
                                    <th>{{ __('messages.data_nascimento') }}</th>
                                    <td class="text-right">{{ $medico->funcionario->data_nascimento ?? '-------------' }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <table class="table text-nowrap">
                            <tbody>

                                <tr>
                                    <th>País</th>
                                    <td class="text-right">{{ $medico->funcionario->pais ?? '-------------' }}</td>
                                </tr>

                                <tr>
                                    <th>{{ __('messages.estado_civil') }}</th>
                                    <td class="text-right">{{ $medico->funcionario->estado_civil->nome ?? '-------------' }}</td>
                                </tr>

                                <tr>
                                    <th> {{ __('messages.bilhete_identidade') }} </th>
                                    <td class="text-right">{{ $medico->funcionario->nif ?? '-------------' }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <table class="table text-nowrap">
                            <tbody>

                                <tr>
                                    <th>Nome do Pai</th>
                                    <td class="text-right">{{ $medico->funcionario->nome_do_pai ?? '-------------' }}</td>
                                </tr>

                                <tr>
                                    <th>Nome da Mãe</th>
                                    <td class="text-right">{{ $medico->funcionario->nome_da_mae ?? '-------------' }}</td>
                                </tr>

                                <tr>
                                    <th>Seguradora</th>
                                    <td class="text-right">{{ $medico->funcionario->seguradora->nome ?? '-------------' }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 col-md-12">
                        <table class="table text-nowrap">
                            <tbody>
                                <tr>
                                    <th>Nº Número da Cédula</th>
                                    <th>Data Emissao Cédula</th>
                                    <th>Data Validade Cédula</th>
                                    <th>Estado Profissional</th>
                                    <th>Especialidade</th>
                                </tr>
                                <tr>
                                    <td>{{ $medico->numero_cedula ?? '-------------' }}</td>
                                    <td>{{ $medico->data_emissao_cedula ?? '-------------' }}</td>
                                    <td>{{ $medico->data_validade_cedula ?? '-------------' }}</td>
                                    <td>{{ $medico->status_profissional ?? '-------------' }}</td>
                                    <td>{{ $medico->especialidade->nome ?? '-------------' }}</td>
                                </tr>
                                {{-- ------------------------------------------------- --}}
                                <tr>
                                    <th>Morada</th>
                                    <th>Províncias</th>
                                    <th>Município</th>
                                    <th>Distrito</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>{{ $medico->funcionario->morada ?? '-------------' }} <br>{{ $medico->funcionario->codigo_postal ?? '-------------' }}</td>
                                    <td>{{ $medico->funcionario->provincia->nome ?? '-------------' }}</td>
                                    <td>{{ $medico->funcionario->municipio->nome ?? '-------------' }}</td>
                                    <td>{{ $medico->funcionario->distrito->nome ?? '-------------' }}</td>
                                    <td></td>
                                </tr>
                                {{-- -------------------------------------------- --}}
                                <tr>
                                    <th colspan="5">Contactos</th>
                                </tr>
                                <tr>
                                    <td colspan="3"> {{ __('messages.telemovel') }} </td>
                                    <td colspan="2"> {{ __('messages.telemovel') }} </td>
                                </tr>
                                <tr>
                                    <td colspan="3">{{ $medico->funcionario->telefone ?? '-------------' }}</td>
                                    <td colspan="2">{{ $medico->funcionario->telemovel ?? '-------------' }}</td>
                                </tr>
                                {{-- -------------------------------------------- --}}
                                <tr>
                                    <th colspan="5">Contactos</th>
                                </tr>
                                <tr>
                                    <td colspan="3"> {{ __('messages.data_nascimento') }}</td>
                                    <td colspan="2">Website</td>
                                </tr>
                                <tr>
                                    <td colspan="3">{{ $medico->funcionario->email ?? '-------------' }}</td>
                                    <td colspan="2">{{ $medico->funcionario->website ?? '-------------' }}</td>
                                </tr>

                                <tr>
                                    <th colspan="5">{{ __('messages.observacao') }}</th>
                                </tr>

                                <tr>
                                    <td colspan="5">{{ $medico->funcionario->observacao ?? '-------------' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
