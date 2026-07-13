@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.lixeira') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-financeiro') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.financeiro') }}</li>
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
                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('operacaoes-financeiras.lixeira') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo_movimento" class="form-label">Tipo movimento</label>
                                    <select type="text" class="form-control select2" name="tipo_movimento">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        <option value="R" {{ $requests['tipo_movimento'] == "R" ? 'selected' : ''}}>{{ __('messages.receita') }}</option>
                                        <option value="D" {{ $requests['tipo_movimento'] == "D" ? 'selected' : ''}}>{{ __('messages.despesa') }}</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <select type="text" class="form-control select2" name="status">
                                        <option value="">{{ __('messages.todos') }} </option>
                                        <option value="pendente" {{ $requests['status'] == "pendente" ? 'selected' : ''}}>Pendente</option>
                                        <option value="pago" {{ $requests['status'] == "pago" ? 'selected' : ''}}>Pago</option>
                                        <option value="atrasado" {{ $requests['status'] == "atrasado" ? 'selected' : ''}}>Atrasado</option>
                                    </select>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-light-primary btn-sm ml-2 text-right"> <i class="fas fa-filter"></i> {{ __('messages.filtrar') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <button id="save-selected" class="btn-sm btn-light-success"><i class="fas fa-undo-alt"></i> Restaurar todos</button>
                        </div>
                        @if ($operacoes)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>#</th>
                                        <th>Referência</th>
                                        <th>{{ __('messages.estados') }}</th>

                                        @if ($empresa_logada->empresa->tem_perfil("Gestão Contabilidade"))
                                        <th>Subconta</th>
                                        @else
                                        <th>Caixa/Conta Bancária</th>
                                        @endif

                                        <th>Dispesa/Receita</th>
                                        <th>Fornecedor/Cliente</th>
                                        <th class="text-right"> {{ __('messages.data') }} </th>
                                        <th class="text-right">Motante</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($operacoes as $item)
                                    <tr>
                                        <td><input type="checkbox" class="select-item" value="{{ $item->id ?? "" }}"></td>
                                        <td>{{ $item->id ?? "" }}</td>
                                        <td>{{ $item->nome ?? "" }}</td>
                                        <td>{{ $item->status }}</td>

                                        @if ($empresa_logada->empresa->tem_perfil("Gestão Contabilidade"))
                                        <td>{{ $item->subconta->numero ?? "" }} - {{ $item->subconta->nome ?? "" }}</td>
                                        @else
                                        @if ($item->formas == "C")
                                        <td>{{ $item->caixa->conta ?? "" }} - {{ $item->caixa->nome ?? "" }}</td>
                                        @else
                                        @if ($item->formas == "B")
                                        <td>{{ $item->contabancaria->conta ?? "" }} - {{ $item->contabancaria->nome ?? "" }}</td>
                                        @else
                                        <td>Outras</td>
                                        @endif
                                        @endif
                                        @endif

                                        <td>{{ $item->type == "D" ? $item->dispesa->nome  ?? "": $item->receita->nome ?? "" }}</td>
                                        <td>{{ $item->type == "D" ? ($item->fornecedor ? $item->fornecedor->nome ?? "" : "") : ($item->cliente ? $item->cliente->nome ?? "" : "") }}</td>
                                        <td class="text-right">{{ $item->date_at }}</td>
                                        @if ($item->type == "D")
                                        <td class="text-right text-light-danger">- {{ number_format($item->motante ?? 0, 2, ',', '.')  }}</td>
                                        @else
                                        <td class="text-right text-light-success">+ {{ number_format($item->motante ?? 0, 2, ',', '.')  }}</td>
                                        @endif

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

@section('scripts')
<script>
    document.getElementById("select-all").addEventListener("change", function() {
        let isChecked = this.checked;
        document.querySelectorAll(".select-item").forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });

    document.querySelectorAll(".select-item").forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            let allChecked = document.querySelectorAll(".select-item:checked").length === document.querySelectorAll(".select-item").length;
            document.getElementById("select-all").checked = allChecked;
        });
    });

    $(document).on('click', '#save-selected', function(e) {
        e.preventDefault();

        let selectedIds = [];
        $('.select-item:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            Swal.fire('Aviso', 'Selecione pelo menos um item.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Tem certeza?'
            , text: "Deseja restaurar os registros selecionados?"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#28a745'
            , cancelButtonColor: '#dc3545'
            , confirmButtonText: 'Sim, restaurar'
            , cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('operacaoes-financeiras.lixeira-recuperar') }}`
                    , method: 'POST'
                    , headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    , }
                    , contentType: 'application/json'
                    , data: JSON.stringify({
                        ids: selectedIds
                    })
                    , beforeSend: function() {
                        progressBeforeSend(); // opcional
                    }
                    , success: function(response) {
                        Swal.fire('Sucesso!', 'Registros restaurados com sucesso.', 'success')
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.fire('Erro!', xhr.responseJSON ? .message || 'Erro ao restaurar.', 'error');
                    }
                });
            }
        });
    });

    $(function() {
        $("#carregar_tabela").DataTable({
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
