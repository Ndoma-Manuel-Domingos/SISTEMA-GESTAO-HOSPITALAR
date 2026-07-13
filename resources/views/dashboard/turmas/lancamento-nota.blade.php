@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Lancamento de Notas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('turma-visualizar-pautas', $pauta->turma->id) }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Pautas</li>
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
                        <form action="{{ route('turma-lancamento-pautas-store') }}" method="post">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">P1</label>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        </div>
                                        <input type="text" name="prova_1" value="{{ $pauta->prova_1 ?? old('prova_1') }}" class="form-control" placeholder="Informe a primeira nota">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">P2</label>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        </div>
                                        <input type="text" name="prova_2" value="{{ $pauta->prova_2 ?? old('prova_2') }}" class="form-control" placeholder="Informe a segunda nota">
                                    </div>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">P3</label>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        </div>
                                        <input type="text" name="prova_3" value="{{ $pauta->prova_3 ?? old('prova_3') }}" class="form-control" placeholder="Informe a terceira nota">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Exame</label>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        </div>
                                        <input type="text" value="{{ $pauta->exame ?? old('exame') }}" name="exame" class="form-control" placeholder="Informe a nota do exame">
                                    </div>
                                </div>

                                <input type="hidden" value="{{ $pauta->id }}" name="pauta_id">

                                <div class="col-12 col-md-3 pt-3">
                                    <label for="" class="form-label">Média: {{ $pauta->media }}</label> <br>

                                    @if ($pauta->resultado == "Nao Definido")
                                    <label class="form-label">Resultado Final: <span class="text-uppercase text-light-warning">{{ $pauta->resultado }}</span> </label>
                                    @endif

                                    @if ($pauta->resultado == "Aprovado")
                                    <label class="form-label">Resultado Final: <span class="text-uppercase text-light-success">{{ $pauta->resultado }}</span> </label>
                                    @endif

                                    @if ($pauta->resultado == "Reprovado")
                                    <label class="form-label">Resultado Final: <span class="text-uppercase text-light-danger">{{ $pauta->resultado }}</span> </label>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary ">{{ __('messages.salvar') }}</button>
                            </div>
                        </form>

                    </div>
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
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
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

                    showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');

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
                            messages += `${value}\n`; // Exibe os erros
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
