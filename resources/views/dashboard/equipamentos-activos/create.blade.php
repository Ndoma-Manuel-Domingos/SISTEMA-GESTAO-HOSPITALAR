@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-plus"></i> {{ __('messages.novo') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('equipamentos-activos.index') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Equipamento e Activo</li>
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
                        <form action="{{ route('equipamentos-activos.store') }}" method="post" class="" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="nome" class="form-label"> {{ __('messages.designacao') }} </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control  @error('nome') is-invalid @enderror" name="nome" id="nome" value="{{ old('nome') }}" placeholder="Informe a designação do activo">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="numero_serie" class="form-label">Número da Seríe</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control  @error('numero_serie') is-invalid @enderror" name="numero_serie" id="numero_serie" value="{{ old('numero_serie') }}" placeholder="Informe o número da Serie">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="codigo_barra" class="form-label">{{ __('messages.codigo_barras') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control  @error('codigo_barra') is-invalid @enderror" name="codigo_barra" id="codigo_barra" value="{{ old('codigo_barra') }}" placeholder="{{ __('messages.codigo_barras') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="quantidade" class="form-label"> {{ __('messages.quantidade') }} </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control  @error('quantidade') is-invalid @enderror" name="quantidade" id="quantidade" value="{{ old('quantidade') ?? 1 }}" placeholder="{{ __('messages.quantidade') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_aquisicao" class="form-label">Data da Aquisição</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="date" class="form-control @error('data_aquisicao') is-invalid @enderror" name="data_aquisicao" id="data_aquisicao" value="{{ old('data_aquisicao') ?? date("Y-m-d") }}" placeholder="">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_utilizacao" class="form-label">Data da utilização</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="date" class="form-control @error('data_utilizacao') is-invalid @enderror" name="data_utilizacao" id="data_utilizacao" value="{{ old('data_utilizacao') ?? date("Y-m-d") }}" placeholder="">
                                    </div>
                                </div>


                                <div class="col-12 col-md-6">
                                    <label for="conta_id" class="form-label">Contas</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="select2 form-control @error('conta_id') is-invalid @enderror" id="conta_id" name="conta_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($contas as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->numero }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 col-md-12">
                                    <label for="classificacao_id" class="form-label">Classificações</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="select2 form-control @error('classificacao_id') is-invalid @enderror" id="classificacao_id" name="classificacao_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($classificacoes as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->numero }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="status" class="form-label">{{ __('messages.estados') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="select2 form-control @error('status') is-invalid @enderror" id="status" name="status">
                                            <option value="activo" selected>{{ __('messages.activo') }} </option>
                                            <option value="desactivo">{{ __('messages.desactivo') }} </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="staus_financeiro" class="form-label">Estado Financeiro</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="select2 form-control @error('staus_financeiro') is-invalid @enderror" id="staus_financeiro" name="staus_financeiro">
                                            <option value="Pago">Pago</option>
                                            <option value="Nao Pago" selected>Não Pago</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="base_incidencia" class="form-label">Base de Incidência</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('base_incidencia') is-invalid @enderror" name="base_incidencia" id="base_incidencia" value="{{ old('base_incidencia') ?? 0 }}" placeholder="Informe o valor da Base de Incidência">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="iva" class="form-label">Taxa do IVA (%)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('iva') is-invalid @enderror" name="iva" id="iva" value="{{ old('iva') ?? 0 }}" placeholder="Informe a Taxa do IVA EX: 14">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="iva_d" class="form-label">Taxa do IVA Dedutível (%)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('iva_d') is-invalid @enderror" name="iva_d" id="iva_d" value="{{ old('iva_d') ?? 0 }}" placeholder="Informe a Taxa do Iva Dedutível EX: 14">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="iva_nd" class="form-label">Taxa do IVA Não Dedutível (%)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('iva_nd') is-invalid @enderror" name="iva_nd" id="iva_nd" value="{{ old('iva_nd') ?? 0 }}" placeholder="Informe a Taxa Se o Iva Não Dedutível EX: 14">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="desconto" class="form-label">Taxa do Desconto (%)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('desconto') is-invalid @enderror" name="desconto" id="desconto" value="{{ old('desconto') ?? 0 }}" placeholder="Informe a Taxa do Desconto EX: 14">
                                    </div>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="fornecedor_id" class="form-label"> {{ __('messages.fornecedores') }} </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="select2 form-control @error('fornecedor_id') is-invalid @enderror" id="fornecedor_id" name="fornecedor_id">
                                            <option value="">{{ __('messages.escolher') }} </option>
                                            @foreach ($fornecedores as $item)
                                            <option value="{{ $item->id ?? "" }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="numero_factura" class="form-label">Número da Factura</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control  @error('numero_factura') is-invalid @enderror" name="numero_factura" id="numero_factura" value="{{ old('numero_factura') }}" placeholder="Informe o número da Factura">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="descricao" class="form-label"> {{ __('messages.descricao') }} </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control  @error('descricao') is-invalid @enderror" name="descricao" id="descricao" value="{{ old('descricao') }}" placeholder="Informe uma descrição">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="anexo" class="form-label">Anexo</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="file" class="form-control  @error('anexo') is-invalid @enderror" name="anexo" id="anexo" value="{{ old('anexo') }}" placeholder="Informe um Anexo">
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar todos') || Auth::user()->can('activo contabilidade'))
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                                @endif
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
                    // Exibe uma mensagem de sucesso
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
