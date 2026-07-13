@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header"> </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <!-- /.col-md-6 -->
                <div class="col-lg-4 col-md-4 col-12">
                    <a type="button" href="{{ route('facturas.create') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Voltar</a>
                    <form action="{{ route('actualizar-venda-factura-update', $movimento->id) }}" class="row" method="post">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <label for="">{{ __('messages.quantidade') }}</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend" onclick="decrementaValor(1); return false;">
                                                <span class="input-group-text px-5">-</span>
                                            </div>
                                            <input type="text" class="form-control" oninput="validateInput(this)" id="resultado" name="quantidade" value="{{ $movimento->quantidade }}">
                                            <input type="hidden" class="form-control" id="resultado_anterior" name="quantidade_anterior" value="{{ $movimento->quantidade }}">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text px-5" onclick="incrementaValor(10);return false;">+</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col12 col-md-6">
                                        <!-- Inputs para os números -->
                                        <label for="input1">Comprimento</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control input1" name="input1" id="input1" value="1" id="input1" oninput="validateInput(this)">
                                        </div>
                                    </div>

                                    <div class="col12 col-md-6">
                                        <label for="input2">Altura</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control input2" name="input2" value="1" id="input2" oninput="validateInput(this)">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="preco_unitario">{{ __('messages.preco') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="text" oninput="validateInput(this)" class="form-control" id="preco_unitario" name="preco_unitario" value="{{ $movimento->preco_unitario }}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="iva">IVA</label>
                                        <div class="input-group mb-3">
                                            <select type="text" class="form-control" name="iva" id="iva">
                                                <option value=''>Automático</option>
                                                <option value="ISE" {{ $movimento->iva == "ISE" ? 'selected' : '' }}>0%</option>
                                                <option value="RED" {{ $movimento->iva == "RED" ? 'selected' : '' }}>2%</option>
                                                <option value="INT" {{ $movimento->iva == "INT" ? 'selected' : '' }}>5%</option>
                                                <option value="OUT" {{ $movimento->iva == "OUT" ? 'selected' : '' }}>7%</option>
                                                <option value="NOR" {{ $movimento->iva == "NOR" ? 'selected' : '' }}>14%</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="desconto_aplicado">Desconto Aplicado %</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="desconto_aplicado" name="desconto_aplicado" value="{{ $movimento->desconto_aplicado }}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="tipo_desconto">Tipo de Desconto</label>
                                        <select type="text" class="form-control" name="tipo_desconto" id="tipo_desconto">
                                            <option value="P" {{ $movimento->tipo_desconto == "P" ? 'selected' : '' }}>Padrão</option>
                                            <option value="C" {{ $movimento->tipo_desconto == "C" ? 'selected' : '' }}>Comercial</option>
                                            <option value="F" {{ $movimento->tipo_desconto == "T" ? 'selected' : '' }}>Financeiro</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <label for="">Texto Opcional</label>
                                        <div class="input-group mb-3">
                                            <textarea name="texto_opcional" placeholder="Se for necessário detalhar, utilize este campo." class="form-control" id="" rows="2">{{ $movimento->texto_opcional }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <label for="">Número(s) de Série</label>
                                        <div class="input-group mb-3">
                                            <textarea name="numero_serie" placeholder="Se for mais do que um, utilize a virgula como separador." class="form-control" id="" rows="2">{{ $movimento->numero_serie }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">Confirmar</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-8 col-md-8 col-12">
                    <a type="button" href="{{ route('facturas.create') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Actualizar Grupo de Preços</a>
                    <div class="card">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-right">{{ __('messages.preco') }}</th>
                                    <th class="text-right">Preço S/IVA</th>
                                    <th class="text-right">{{ __('messages.preco_fornecedor') }}</th>
                                    <th class="text-right">{{ __('messages.imposto') }}</th>
                                    <th class="text-right">Margem de Lucro</th>
                                    <th class="text-right">{{ __('messages.estados') }}</th>
                                    <th class="text-right">{{ __('messages.accoes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($grupo_precos)
                                @foreach ($grupo_precos as $item)
                                <tr>
                                    <td>{{ $item->id ?? "" }}</td>
                                    <td class="text-right">{{ number_format($item->preco_venda, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa_logada->empresa->moeda }}</span></td>
                                    <td class="text-right">{{ number_format($item->preco, 2, ',', '.') }} <span class="text-light-secondary">{{ $empresa_logada->empresa->moeda }}</span></td>
                                    <td class="text-right">{{ number_format($item->preco_custo, 2, ',', '.')  }} <span class="text-light-secondary">{{ $empresa_logada->empresa->moeda }}</span></td>
                                    <td class="text-right">{{ $item->produto->taxa_imposto->valor }} %</td>
                                    <td class="text-right">{{ number_format($item->margem, 2, ',', '.')  }} <span class="text-light-secondary">%</span></td>
                                    <td class="text-right">{{ $item->status }}</td>

                                    <td style="width: 50px;">
                                        @if ($item->status == "desactivo")
                                        <a href="{{ route('definir_preco_factura.produtos', [$item->id, $movimento->id]) }}" class="btn btn-sm btn-light-primary"><i class="fas fa-database"></i> Activar</a>
                                        @endif
                                    </td>

                                </tr>
                                @endforeach
                                @endif
                            </tbody>

                        </table>
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
    // Função para validar o input
    function validateInput(input) {
        // Expressão regular para aceitar apenas números e pontos
        input.value = input.value.replace(/[^0-9.]/g, '');
        // Evita múltiplos pontos
        if ((input.value.match(/\./g) || []).length > 1) {
            input.value = input.value.slice(0, -1);
        }
    }


    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();

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
                    progressBeforeSend();
                }
                , success: function(response) {

                    Swal.close();
                    showMessage('Sucesso!', 'Operação realizada com sucesso.!', 'success');
                    window.location.href = response.redirect;

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

                    console.log("Erro detectado!");
                    console.log("Status:", xhr.status);
                    console.log("Resposta:", xhr.responseText); // Mostra a resposta detalhada
                }
            });
        });

    });

</script>
@endsection
