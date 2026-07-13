@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gerar Licença</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Configuração</li>
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
                        <form action="{{ route('gerar-licenca-configuracao-admin-post') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label for="data_inicio" class="form-label">{{ __('messages.data_inicio') }}</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') ?? '15' }}">
                                        </div>
                                        <p class="text-light-danger">
                                            @error('data_inicio')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    @php $dia_tempo = time(); @endphp

                                    <input type="hidden" class="form-control" id="dia_tempo" name="dia_tempo" value="{{ $dia_tempo }}">

                                    <div class="col-12 col-md-6">
                                        <label for="data_final" class="form-label">{{ __('messages.data_final') }}</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control" id="data_final" name="data_final" value="{{ old('data_final') ?? '15' }}">
                                        </div>
                                        <p class="text-light-danger">
                                            @error('data_final')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- @if ($codigo != null) --}}
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="h5" id="codigo_">{{ $codigo }}</p>
                        </div>
                    </div>
                </div>
                {{-- @endif --}}
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

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
                    document.getElementById('codigo_').innerText = response.codigo;

                    // window.location.reload();

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
