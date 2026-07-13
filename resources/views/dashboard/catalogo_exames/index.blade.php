@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Catálogo de Exames</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
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
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Catálogo de Exames Disponíveis
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('catalogo-exames.store') }}">
                                @csrf
                                {{-- Pesquisa --}}
                                <div class="form-group">
                                    <input type="text" id="pesquisa" class="form-control" placeholder="Pesquisar exame...">
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="tabelaExames">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="50"><input type="checkbox" id="selecionarTodos"></th>
                                                <th>Código</th>
                                                <th>Exame</th>
                                                <th>Categoria</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($exames as $exame)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="exames[]" value="{{ $exame->id }}" class="checkExame">
                                                </td>
                                                <td>{{ $exame->codigo }}</td>
                                                <td>{{ $exame->nome }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $exame->categoria }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-light-success btn-lg">
                                        <i class="fas fa-save"></i>
                                        Salvar Exames Selecionados
                                    </button>
                                </div>
                            </form>
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
    // Selecionar todos

    $("#selecionarTodos").click(function() {
        $(".checkExame").prop('checked', this.checked);
    });

    // Pesquisa
    $("#pesquisa").keyup(function() {
        let valor = $(this).val().toLowerCase();
        $("#tabelaExames tbody tr").filter(function() {
            $(this).toggle(
                $(this).text().toLowerCase().indexOf(valor) > -1
            );
        });
    });


    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize();

            $.ajax({
                url: form.attr('action')
                , method: "POST"
                , data: formData
                , headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    Swal.close();

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';

                        $.each(errors, function(key, value) {
                            messages += `${value}\n* `;
                        });

                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            });

        });
    });

</script>
@endsection
