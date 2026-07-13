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
                        <li class="breadcrumb-item"><a href="{{ route('contratos.index') }}">{{ __('messages.voltar') }}</a></li>
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
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Dados do Contrato
                            </h3>

                            <div class="card-tools">
                                <button class="btn btn-light-primary" type="button" onclick="toggleModalDesconto()"><i class="fas fa-plus"></i> {{ __('messages.desconto') }}</button>
                                <button class="btn btn-light-primary" type="button" onclick="toggleModalSubsidio()"><i class="fas fa-plus"></i> {{ __('messages.subsidio')}}</button>
                                <a class="btn btn-light-danger" target="_blank" href="{{ route('ficha-funcionario', $contrato->funcionario->id) }}"><i class="fas fa-file-pdf"></i> Ficha do Funcionário</a>
                                <a class="btn btn-light-success" href="{{ route('contratos.edit', $contrato->id) }}"><i class="fas fa-edit"></i> {{ __('messages.actualizar') }}</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Contrato Nº </th>
                                                <td class="text-right">{{ $contrato->numero ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Tipo Contrato</th>
                                                <td class="text-right">{{ $contrato->tipo_contrato->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.cargos') }}</th>
                                                <td class="text-right"> {{ $contrato->cargo->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.categoria') }}</th>
                                                <td class="text-right"> {{ $contrato->categoria->nome ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('messages.data_inicio') }} & {{ __('messages.data_final') }} </th>
                                                <td class="text-right">{{ $contrato->data_inicio ?? '-------------' }} - {{ $contrato->data_final ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Hora Entrada & Saída </th>
                                                <td class="text-right">{{ $contrato->hora_entrada ?? '-------------' }} - {{ $contrato->hora_saida ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Estado Contrato</th>
                                                <td class="text-right"> {{ $contrato->status ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Salário Base</th>
                                                <td class="text-right"> {{ number_format($contrato->salario_base ?? 0, 2, ',', '.' ) ?? '-------------' }} AKZ</td>
                                            </tr>
                                            <tr>
                                                <th>Forma Pagamento</th>
                                                <td class="text-right"> {{ $contrato->forma_pagamento->titulo ?? "" }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Subsídio de Natal</th>
                                                <th>Subsídio de Ferias</th>
                                            </tr>

                                            <tr>
                                                <td class="text-left">{{ number_format($contrato->subsidio_natal ?? 0, 1, ',', '.' ) }} % => {{ number_format(($contrato->salario_base * ($contrato->subsidio_natal / 100)) ?? 0, 2, ',', '.' ) ?? '-------------' }} AKZ</td>
                                                <td class="text-left">{{ number_format($contrato->subsidio_ferias ?? 0, 1, ',', '.' ) }} % => {{ number_format(($contrato->salario_base * ($contrato->subsidio_ferias / 100)) ?? 0, 2, ',', '.' ) ?? '-------------' }} AKZ</td>
                                            </tr>

                                            <tr>
                                                <th class="text-left" colspan="2">Mês Pagamento & Forma Pagamento</th>
                                            </tr>

                                            <tr>
                                                <td class="text-left">{{ $contrato->descricao_mes($contrato->mes_pagamento_natal) }} - {{ $contrato->forma_pagamento_subcidio($contrato->forma_pagamento_natal) }}</td>
                                                <td class="text-left">{{ $contrato->descricao_mes($contrato->mes_pagamento_ferias) }} - {{ $contrato->forma_pagamento_subcidio($contrato->forma_pagamento_ferias) }}</td>
                                            </tr>

                                            <tr>
                                                <th>Dias Processamentos:</th>
                                                <td class="text-right">{{ $contrato->dias_processamentos($contrato->dias_processamento) }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>OUTROS SUBSÍDIOS</th>
                                                <th class="text-right">SUJEITO A INSS</th>
                                                <th class="text-right">SUJEITO A IRT</th>
                                                <th class="text-right">TIPO PROCESSAMENTO</th>
                                                <th class="text-right">LIMITE ISENÇÃO</th>
                                                <th class="text-right text-uppercase"> {{ __('messages.valor') }}</th>

                                                <th class="text-right">{{ __('messages.accoes') }}</th>
                                            </tr>
                                            @foreach ($contrato->subsidios_contrato as $item)
                                            <tr>
                                                <th>{{ $item->subsidio->nome ?? "" }}</th>
                                                <th class="text-right">{{ $item->subsidio->inss == "Y" ? __('messages.sim') : __('messages.nao') }}</th>
                                                <th class="text-right">{{ $item->subsidio->irt == "Y" ? __('messages.sim') : __('messages.nao') }}</th>
                                                <th class="text-right">{{ $item->processamento->nome ?? "" }}</th>
                                                <td class="text-right">{{ number_format($item->subsidio->limite_isencao ?? 0, 1, ',', '.' ) ?? 0 }} - AKZ</td>
                                                <td class="text-right">{{ number_format($item->salario ?? 0, 1, ',', '.' ) ?? 0 }} - AKZ</td>

                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                                        <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu">
                                                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar contrato'))
                                                            <a class="dropdown-item editar-subsidio-contrato" data-id="{{ $item->id ?? "" }}" href="#"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                            @endif
                                                            <div class="dropdown-divider"></div>
                                                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar contrato'))
                                                            <button class="btn btn-light-danger dropdown-item delete-subsidio-contrato" data-id="{{ $item->id ?? "" }}">
                                                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>DESCONTOS</th>
                                                <th class="text-right">SUJEITO A INSS</th>
                                                <th class="text-right">SUJEITO A IRT</th>
                                                <th class="text-right">TIPO PROCESSAMENTO</th>
                                                <th class="text-right">LIMITE ISENÇÃO</th>
                                                <th class="text-right text-uppercase"> {{ __('messages.valor') }}</th>

                                                <th class="text-right">{{ __('messages.accoes') }}</th>

                                            </tr>
                                            @foreach ($contrato->descontos_contrato as $item)
                                            <tr>
                                                <th>{{ $item->desconto->nome ?? "" }}</th>
                                                <th class="text-right">{{ $item->desconto->inss == "Y" ? __('messages.sim') : __('messages.nao') }}</th>
                                                <th class="text-right">{{ $item->desconto->irt == "Y" ? __('messages.sim') : __('messages.nao') }}</th>
                                                <th class="text-right">{{ $item->processamento->nome ?? "" }}</th>
                                                <th class="text-right">-</th>
                                                <td class="text-right">{{ number_format($item->salario ?? 0, 1, ',', '.' ) ?? 0 }}{{ $item->desconto->tipo_valor == "P" ? '%' : " - AKZ" }}</td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-light-primary">{{ __('messages.accoes') }} </button>
                                                        <button type="button" class="btn btn-light-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu">
                                                            @if (Auth::user()->can('editar todos') || Auth::user()->can('editar contrato'))
                                                            <a class="dropdown-item editar-desconto-contrato" data-id="{{ $item->id ?? "" }}"><i class="fas fa-edit text-light-success"></i> {{ __('messages.actualizar') }}</a>
                                                            @endif
                                                            <div class="dropdown-divider"></div>
                                                            @if (Auth::user()->can('eliminar todos') || Auth::user()->can('eliminar contrato'))
                                                            <button class="btn btn-light-danger dropdown-item delete-desconto-contrato" data-id="{{ $item->id ?? "" }}">
                                                                <i class="fas fa-trash text-light-danger"></i> {{ __('messages.eliminar') }}
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Dados Pessoais</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('messages.nome') }}</th>
                                                <td class="text-right">{{ $contrato->funcionario->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th> {{ __('messages.genero') }} </th>
                                                <td class="text-right">{{ $contrato->funcionario->genero ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.data_nascimento') }}</th>
                                                <td class="text-right"> {{ $contrato->funcionario->data_nascimento ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>{{ __('messages.estado_civil') }}</th>
                                                <td class="text-right">{{ $contrato->funcionario->estado_civil->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Nome da Mãe</th>
                                                <td class="text-right">{{ $contrato->funcionario->nome_da_mae ?? '-------------' }} </td>
                                            </tr>

                                            <tr>
                                                <th>Seguradora</th>
                                                <td class="text-right">{{ $contrato->funcionario->seguradora->nome ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>País</th>
                                                <td class="text-right">{{ $contrato->funcionario->pais ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('messages.estado_civil') }}</th>
                                                <td class="text-right">
                                                    {{ $contrato->funcionario->estado_civil->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th> {{ __('messages.bilhete_identidade') }} </th>
                                                <td class="text-right">{{ $contrato->funcionario->nif ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Morada</th>
                                                <th>Províncias</th>
                                                <th>Município</th>
                                                <th>Distrito</th>
                                            </tr>
                                            <tr>
                                                <td>{{ $contrato->funcionario->morada ?? '-------------' }}
                                                    <br>{{ $contrato->funcionario->codigo_postal ?? '-------------' }}
                                                </td>
                                                <td>{{ $contrato->funcionario->provincia->nome ?? '-------------' }}</td>
                                                <td>{{ $contrato->funcionario->municipio->nome ?? '-------------' }}</td>
                                                <td>{{ $contrato->funcionario->distrito->nome ?? '-------------' }}</td>
                                            </tr>
                                            {{-- -------------------------------------------- --}}
                                            <tr>
                                                <th colspan="4">Contactos</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2"> {{ __('messages.telemovel') }} </th>
                                                <th colspan="2"> {{ __('messages.telemovel') }} </th>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ $contrato->funcionario->telefone ?? '-------------' }}</td>
                                                <td colspan="2">{{ $contrato->funcionario->telemovel ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="2"> {{ __('messages.email') }}</th>
                                                <th colspan="2">Website</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ $contrato->funcionario->email ?? '-------------' }}</td>
                                                <td colspan="2">{{ $contrato->funcionario->website ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<form action="{{ route('recurso-humanos.store-subsidio-contrato') }}" id="put_subsidio_contrato" method="post" class="">
    @csrf
    <div class="modal fade" id="modal-subsidio-lg">
        <div class="modal-dialog modal-lg  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('messages.subsidio') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-4">

                    <div class="row">
                        <div class="col-12 col-md-12">
                            <label for="subsidio_id" class="form-label">{{ __('messages.subsidio') }}</label>
                            <div class="input-group mb-3">
                                <select class="form-control" id="subsidio_id" name="subsidio_id">
                                    <option value="">{{ __('messages.escolher') }} </option>
                                    @foreach ($subsidios as $item)
                                    <option value="{{ $item->id ?? "" }}">
                                        {{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="salario_subsidio" class="form-label">{{ __('messages.valor') }}</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="salario_subsidio" id="salario_subsidio" placeholder="Informe o Valor da remuneração">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="processamento_id_subsidio" class="form-label">{{ __('messages.tipo_processamento') }}</label>
                            <div class="input-group mb-3">
                                <select class="form-control" id="processamento_id_subsidio" name="processamento_id_subsidio">
                                    <option value="">{{ __('messages.escolher') }} </option>
                                    @foreach ($processamentos as $item)
                                    <option value="{{ $item->id ?? "" }}">
                                        {{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="contrato_id_subsidio" id="contrato_id_subsidio" value="{{ $contrato->id }}">

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                    @if (Auth::user()->can('criar todos') || Auth::user()->can('criar contrato'))
                    <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                    @endif
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- /.modal -->

<form action="{{ route('recurso-humanos.store-desconto-contrato') }}" id="put_desconto_contrato" method="post" class="">
    @csrf
    <div class="modal fade" id="modal-desconto-lg">
        <div class="modal-dialog modal-lg  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('messages.desconto') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row py-4">

                    <div class="col-12 col-md-12">
                        <label for="desconto_id" class="form-label">{{ __('messages.desconto') }}</label>
                        <div class="input-group mb-3">
                            <select class="form-control" id="desconto_id" name="desconto_id">
                                <option value="">{{ __('messages.escolher') }} </option>
                                @foreach ($descontos as $item)
                                <option value="{{ $item->id ?? "" }}">
                                    {{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="salario_desconto" class="form-label">{{ __('messages.valor') }}</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="salario_desconto" id="salario_desconto" placeholder="Informe o Valor da remuneração">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="processamento_desconto_id" class="form-label">{{ __('messages.tipo_processamento') }}</label>
                        <div class="input-group mb-3">
                            <select class="form-control" id="processamento_desconto_id" name="processamento_desconto_id">
                                <option value="">{{ __('messages.escolher') }} </option>
                                @foreach ($processamentos as $item)
                                <option value="{{ $item->id ?? "" }}">
                                    {{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <input type="hidden" name="contrato_id_desconto" id="contrato_id_desconto" value="{{ $contrato->id }}">


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light-danger" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                    @if (Auth::user()->can('criar todos') || Auth::user()->can('criar contrato'))
                    <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                    @endif
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- /.modal -->


@endsection

@section('scripts')
<script>
    let modalVisibleSubsidio = false;
    let modalVisibleDesconto = false;
    let PastaIDSubsidio = null;
    let PastaIDDesconto = null;

    const modalElementSubsidio = document.getElementById('modal-subsidio-lg');
    const modalInstanceSubsidio = new bootstrap.Modal(modalElementSubsidio);

    const modalElementDesconto = document.getElementById('modal-desconto-lg');
    const modalInstanceDesconto = new bootstrap.Modal(modalElementDesconto);


    function toggleModalSubsidio() {
        PastaIDSubsidio = null;
        if (modalVisibleSubsidio) {
            modalInstanceSubsidio.hide();
            modalVisibleSubsidio = false;
        } else {
            modalInstanceSubsidio.show();
            modalVisibleSubsidio = true;
        }
    }


    function toggleModalDesconto() {
        PastaIDDesconto = null;
        if (modalVisibleDesconto) {
            modalInstanceDesconto.hide();
            modalVisibleDesconto = false;
        } else {
            modalInstanceDesconto.show();
            modalVisibleDesconto = true;
        }
    }


    $(document).on('click', '.delete-subsidio-contrato', function(e) {
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
                    url: `{{ route('recurso-humanos.delete-subsidio-contrato', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.delete-desconto-contrato', function(e) {
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
                    url: `{{ route('recurso-humanos.delete-desconto-contrato', ':id') }}`.replace(':id', recordId)
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
    });

    $(document).on('click', '.editar-subsidio-contrato', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // Envia a solicitação AJAX para excluir o registro
        $.ajax({
            url: `{{ route('recurso-humanos.get-subsidio-contrato', ':id') }}`.replace(':id', recordId)
            , method: 'GET'
            , data: {
                _token: '{{ csrf_token() }}', // Inclui o token CSRF
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {

                Swal.close();
                modalInstanceSubsidio.show();

                document.getElementById('subsidio_id').value = response.data.subsidio_id;
                document.getElementById('salario_subsidio').value = response.data.salario;
                document.getElementById('processamento_id_subsidio').value = response.data.processamento_id;

                PastaIDSubsidio = response.data.id;

            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
    });

    $(document).on('click', '.editar-desconto-contrato', function(e) {
        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro

        // Envia a solicitação AJAX para excluir o registro
        $.ajax({
            url: `{{ route('recurso-humanos.get-desconto-contrato', ':id') }}`.replace(':id', recordId)
            , method: 'GET'
            , data: {
                _token: '{{ csrf_token() }}', // Inclui o token CSRF
            }
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {

                Swal.close();
                modalInstanceDesconto.show();

                document.getElementById('desconto_id').value = response.data.desconto_id;
                document.getElementById('salario_desconto').value = response.data.salario;
                document.getElementById('processamento_desconto_id').value = response.data.processamento_id;

                PastaIDDesconto = response.data.id;

            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
    });


    $(document).ready(function() {
        $("#put_subsidio_contrato").on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            let new_form = null;
            let method = null;

            if (PastaIDSubsidio == null) {
                new_form = form.attr('action');
                method = "post";
            } else {
                method = "put";
                new_form = form.attr('action') + "/" + PastaIDSubsidio;
            }

            $.ajax({
                url: new_form, // URL do endpoint no backend
                method: method, // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso

                    PastaIDSubsidio = null;
                    PastaIDDesconto = null;

                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });

        });
    });

    $(document).ready(function() {
        $("#put_desconto_contrato").on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            let new_form = null;
            let method = null;

            if (PastaIDDesconto == null) {
                new_form = form.attr('action');
                method = "post";
            } else {
                method = "put";
                new_form = form.attr('action') + "/" + PastaIDDesconto;
            }

            $.ajax({
                url: new_form, // URL do endpoint no backend
                method: method, // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso

                    PastaIDSubsidio = null;
                    PastaIDDesconto = null;

                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });

        });
    });

</script>
@endsection
