@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-edit"></i> {{ __('messages.editar') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('lojas.index') }}">{{ __('messages.voltar') }}</a></li>
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
                        <form action="{{ route('lojas.update', $loja->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card-body row">
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="nome" class="form-label">{{ __('messages.designacao') }}</label>
                                    <input type="text" id="nome" class="form-control" name="nome" value="{{ $loja->nome }}" placeholder="Informe a nome">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="nif" class="form-label">NIF</label>
                                    <input type="text" id="nif" class="form-control" name="nif" value="{{ $loja->nif }}" placeholder="Informe a NIF">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <select type="text" id="status" class="form-control" name="status">
                                        <option value="activo" {{ $loja->status == "activo" ? 'selected' : '' }}>{{ __('messages.activo') }} </option>
                                        <option value="desactivo" {{ $loja->status == "desactivo" ? 'selected' : '' }}>{{ __('messages.desactivo') }} </option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="ramo_actividade_id" class="form-label">Ramo/Sector</label>
                                    <select name="ramo_actividade_id" id="ramo_actividade_id" class="form-control select2">
                                        @foreach ($ramos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $loja->ramo_actividade_id == $item->id ? 'selected' : '' }}>{{ $item->tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="codigo_postal" class="form-label">Codigo Postal <span class="text-light-secondary">(Opcional)</span></label>
                                    <input type="text" id="codigo_postal" class="form-control" name="codigo_postal" value="{{ $loja->codigo_postal }}" placeholder="Informe o Codigo Postal">
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="morada" class="form-label">Morada da Loja <span class="text-light-secondary">(Opcional)</span></label>
                                    <input type="text" id="morada" class="form-control" name="morada" value="{{ $loja->morada }}" placeholder="Informe a morada da Loja">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="telefone" class="form-label">Telefone <span class="text-light-secondary">(Opcional)</span></label>
                                    <input type="text" id="telefone" class="form-control" name="telefone" value="{{ $loja->telefone }}" placeholder="Informe o Telefone">
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="email" class="form-label"> {{ __('messages.email') }} <span class="text-light-secondary">(Opcional)</span></label>
                                    <input type="text" class="form-control" name="email" value="{{ $loja->email }}" placeholder="Informe o E-mail">
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="provincia_id" class="form-label">Provincias <span class="text-light-secondary">(Opcional)</span></label>
                                    <select name="provincia_id" id="provincia_id" class="form-control select2">
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $loja->provincia_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="municipio_id" class="form-label">Municípios <span class="text-light-secondary">(Opcional)</span></label>
                                    <select name="municipio_id" id="municipio_id" class="form-control select2">
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $loja->municipio_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="distrito_id" class="form-label">Distritos <span class="text-light-secondary">(Opcional)</span></label>
                                    <select name="distrito_id" id="distrito_id" class="form-control select2">
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id ?? "" }}" {{ $loja->distrito_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="modelo_factura" class="form-label">Modelo de Factura</label>
                                    <select type="text" id="modelo_factura" class="form-control" name="modelo_factura">
                                        <option value="modelo1" {{ $loja->modelo_factura == "modelo1" ? 'selected' : '' }}>Modelo 1</option>
                                        <option value="modelo2" {{ $loja->modelo_factura == "modelo2" ? 'selected' : '' }}>Modelo 2</option>
                                    </select>
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="logotipo" class="form-label">Logotipo</label>
                                    <input type="file" class="form-control" id="logotipo" name="logotipo" accept="image/*">
                                </div>

                                <div class="col-12 col-md-12 mb-3">
                                    <label for="" class="form-label"> {{ __('messages.descricao') }} <span class="text-light-secondary">(Opcional)</span></label>
                                    <textarea class="form-control" rows="2" name="descricao" placeholder="Informe a descricao da Loja ...">{{ $loja->descricao }}</textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                <button type="reset" class="btn btn-light-danger">{{ __('messages.cancelar') }} </button>
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
    $("#provincia_id").change(() => {
        let id = $("#provincia_id").val();
        $.get('../../carregar-municipios/' + id, function(data) {
            $("#municipio_id").html("")
            $("#municipio_id").html(data)
        })
    })

    $("#municipio_id").change(() => {
        let id = $("#municipio_id").val();
        $.get('../../carregar-distritos/' + id, function(data) {
            $("#distrito_id").html("")
            $("#distrito_id").html(data)
        })
    })

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = new FormData(this); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action')
                , method: form.attr('method')
                , data: formData
                , processData: false
                , contentType: false
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    showMessage(
                        'Sucesso!'
                        , 'Dados salvos com sucesso!'
                        , 'success'
                    );
                    window.location.reload();
                }
                , error: function(xhr) {
                    Swal.close();
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += value + '\n';
                        });
                        showMessage(
                            'Erro de Validação!'
                            , messages
                            , 'error'
                        );
                    }
                }
            });
        });
    });

</script>
@endsection
