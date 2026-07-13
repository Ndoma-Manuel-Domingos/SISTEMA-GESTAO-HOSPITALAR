@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> <i class="fas fa-edit"></i> {{ __('messages.editar') }} {{ $requisicao->numero }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('requisacoes.index') }}">{{ __('messages.voltar') }}</a></li>
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
            <div class="card">
                <form action="{{ route('requisacoes.update', $requisicao->id) }}" method="post" class="">
                    @csrf
                    @method('put')

                    <div class="card-body row">
                        <div class="col-12 col-md-3">
                            <label for="numero" class="form-label">Nº Requisição:</label>
                            <input type="text" class="form-control" id="numero" name="numero" value="{{ $requisicao->numero }}" placeholder="Número da Requisição:">
                            <input type="hidden" name="requisicao_id" id="requisicao_id" value="{{ $requisicao->id }}">
                            <p class="text-light-danger">
                                @error('numero')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="loja_id" class="form-label text-right">Loja/Armazém:</label>
                            <select class="form-control" id="loja_id" name="loja_id">
                                @foreach ($lojas as $loja)
                                <option value="{{ $loja->id}}" {{ $loja->id == $requisicao->loja->id  ? 'selected': '' }}>{{ $loja->nome }}</option>
                                @endforeach
                            </select>
                            <p class="text-light-danger">
                                @error('loja_id')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>


                        <div class="col-12 col-md-3">
                            <label for="observacao" class="form-label text-right">{{ __('messages.observacao') }}:</label>
                            <input type="text" class="form-control" id="observacao" name="observacao" value="{{ $requisicao->observacao }}" placeholder="{{ __('messages.observacao') }} ">
                            <p class="text-light-danger">
                                @error('observacao')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-4 col-lg-2">
                            <label for="produto" class="form-label text-right">{{ __('messages.produtos') }}:</label>
                            <select class="form-control select2" id="produto" name="produto">
                                <option value="">{{ __('messages.escolher') }}</option>
                                @if ($produtos)
                                @foreach ($produtos as $item2)
                                <option value="{{ $item2->id }}">{{ $item2->nome }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-1">
                            <a href="" class="btn btn-light-primary mt-4" id="salvarItem">{{ __('messages.salvar') }}</a>
                        </div>

                        @if ($items)
                        <div class="col-12 col-md-12">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 5px"></th>
                                        <th>{{ __('messages.designacao') }}</th>
                                        <th> {{ __('messages.quantidade') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                    <tr>
                                        <td class="bg-light">
                                            <a href="{{ route('items-nova-encomenda-remover-sem-fornecedora-ctualizar', $item->id) }}" id="remover_id" class="text-light-danger bg-light-danger p-1 img-circle"><i class="fas fa-close text-white"></i></a>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control produto_id" value="{{ $item->produto->nome ?? '' }}" name="produto_id{{ $item->id ?? "" }}" id="{{ $item->id ?? "" }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control quantidade quantidade{{ $item->id ?? "" }}" value="{{ $item->quantidade ?? 0 }}" data-custo="{{ $item->custo ?? 0 }}" data-total="{{ $item->total }}" name="quantidade{{ $item->id ?? "" }}" id="{{ $item->id ?? "" }}">
                                        </td>

                                        <input type="hidden" name="ids[]" value="{{ $item->id ?? "" }}">
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @endif
                    </div>

                    <div class="card-footer">
                        @if (Auth::user()->can('editar todos') || Auth::user()->can('editar requisacao'))
                        <button type="submit" class="btn btn-light-success">Actualizar</button>
                        @endif
                    </div>
                </form>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    $(function() {

        $("#salvarItem").on('click', function(e) {
            e.preventDefault();

            // Obter os valores dos campos
            const produtoId = $("#produto").val();
            const requisicaoId = $("#requisicao_id").val();

            if (produtoId != "") {
                // Gerar a URL com múltiplos parâmetros
                const url = `{{ route('requisacoes.editar-produto', [':produto', ':requisicao_id']) }}`
                    .replace(':produto', produtoId)
                    .replace(':requisicao_id', requisicaoId);

                // Redirecionar
                window.location.href = url;
            }
        })
    });

</script>
@endsection
