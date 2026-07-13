@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Controle de Produção</h1>
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

    <div class="content">
        <div class="container-fluid">
            
            <div class="row mb-4">
                <div class="col-12 col-md-12">
                    <a href="{{ route('producao.create') }}" class="btn btn-lg btn-light-success">
                        Nova Produção
                    </a>
                </div>
            </div>
        
            <div class="row">
                <!-- PENDENTE -->
                <div class="col-md-3 col-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                PENDENTE
                            </h3>
                        </div>
                        <div class="card-body kanban-column" id="PENDENTE">
                            @foreach($productions->where('status', 'PENDENTE') as $production)
                            <div class="card production-card" style="cursor: pointer" data-id="{{ $production->id }}">
                                <div class="card-body">
                                    <strong>
                                        {{ $production->code }}
                                    </strong>
                                    <br>
                                    Receita: {{ $production->receita->nome }} Tipo de pão: {{ $production->receita->produto->nome }}
                                    <br>
                                    Quantidade Desejada:
                                    {{ number_format($production->quantidade_desejada, 2, ',', '.')  }}
                                    <br>
                                    Quantidade Estimada:
                                    {{ number_format($production->quantidade_estimada, 2, ',', '.')  }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            
                <!-- EM PRODUCAO -->
                <div class="col-md-3 col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                EM PRODUÇÃO
                            </h3>
                        </div>
                        <div class="card-body kanban-column" id="EM_PRODUCAO">
                            @foreach($productions->where('status', 'EM_PRODUCAO') as $production)
                            <div class="card production-card" data-id="{{ $production->id }}">
                                <div class="card-body">
                                    <strong>
                                        {{ $production->code }}
                                    </strong>
                                    <br>
                                    Receita: {{ $production->receita->nome }} Tipo de pão: {{ $production->receita->produto->nome }}
                                    <br>
                                    Quantidade Desejada:
                                    {{ number_format($production->quantidade_desejada, 2, ',', '.')  }}
                                    <br>
                                    Quantidade Estimada:
                                    {{ number_format($production->quantidade_estimada, 2, ',', '.')  }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            
                <!-- FINALIZADO -->
                <div class="col-md-3 col-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                FINALIZADO
                            </h3>
                        </div>
                        <div class="card-body kanban-column" id="FINALIZADO">
            
                            @foreach($productions->where('status', 'FINALIZADO') as $production)
            
                            <div class="card production-card" data-id="{{ $production->id }}">
            
                                <div class="card-body">
            
                                    <strong>
                                        {{ $production->code }}
                                    </strong>
                                    <br>
                                    Receita: {{ $production->receita->nome }} Tipo de pão: {{ $production->receita->produto->nome }}
                                    <br>
                                    Quantidade Desejada:
                                    {{ number_format($production->quantidade_desejada, 2, ',', '.')  }}
                                    <br>
                                    Quantidade Estimada:
                                    {{ number_format($production->quantidade_estimada, 2, ',', '.')  }}
                                </div>
                            </div>
            
                            @endforeach
            
                        </div>
                    </div>
                </div>
            
                <!-- CONFIRMADO -->
                <div class="col-md-3 col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                CONFIRMADO
                            </h3>
                        </div>
                        <div class="card-body kanban-column" id="CONFIRMADO">
                            @foreach($productions->where('status', 'CONFIRMADO') as $production)
                            <div class="card production-card" data-id="{{ $production->id }}">
                                <div class="card-body">
                                    <strong>
                                        {{ $production->code }}
                                    </strong>
                                    <br>
                                    Receita: {{ $production->receita->nome }} Tipo de pão: {{ $production->receita->produto->nome }}
                                    <br>
                                    Quantidade Desejada:
                                    {{ number_format($production->quantidade_desejada, 2, ',', '.')  }}
                                    <br>
                                    Quantidade Estimada:
                                    {{ number_format($production->quantidade_estimada, 2, ',', '.')  }}
                                </div>
                            </div>
                            @endforeach
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

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
    const columns = document.querySelectorAll('.kanban-column');

    columns.forEach(column => {

        new Sortable(column, {

            group: 'shared',

            animation: 150,

            onAdd: function(evt) {

                let productionId =
                    evt.item.dataset.id;

                let newStatus =
                    evt.to.id;

                updateStatus(
                    productionId
                    , newStatus
                );
            }
        });

    });

    function updateStatus(productionId, status) {
        $.ajax({
            url: '/producao-mudaStatus', // URL do endpoint no backend
            method: "POST", // Método HTTP definido no formulário
            data: {
                production_id: productionId
                , status: status
            }, // Dados do formulário
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
    }
</script>
@endsection
