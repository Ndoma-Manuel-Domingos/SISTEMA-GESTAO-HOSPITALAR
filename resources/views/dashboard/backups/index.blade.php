@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Backup e Restauração do Banco</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Historicos</li>
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

                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h3><i class="fas fa-broom"></i></h3>
                            <h3 style="margin-bottom: 185px">Adpatar o Sistema com Urgência</h3>
                        </div>
                        <div class="card-body text-center">
                            <a href="{{ route('dashboard.configuracao-urgentes') }}" class="btn btn-light-primary w-100 d-block my-4">{{ __('messages.salvar') }}</a>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h3><i class="fas fa-broom"></i></h3>
                            <h3>Configurações do Backup</h3>
                            <p style="margin-bottom: 50px">
                                Reestruture as configurações do banco de dados para melhorar o desempenho do sistema. Caso sejam identificadas irregularidades, solicitamos a reestruturação.<br>
                                <strong class="text-light-danger">Obs: Será realizada apenas a reestruturação.</strong>
                            </p>
                        </div>
                        <div class="card-body text-center">
                            <a href="{{ route('backup.settings') }}" class="btn btn-light-primary w-100 d-block my-4">{{ __('messages.salvar') }}</a>
                            <p>MMMMMMMM</p>
                        </div>
                    </div>
                </div>


                @if (Auth::user()->can('seguranca backup'))
                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h3><i class="fas fa-broom"></i></h3>
                            <h3>Estrutura Banco de dados</h3>
                            <p>
                                Reestruture as configurações do banco de dados para melhorar o desempenho do sistema. Caso sejam identificadas irregularidades, solicitamos a reestruturação.<br>
                                <strong class="text-light-danger">Obs: Será realizada apenas a reestruturação.</strong>
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <a href="{{ route('databases.restrutar') }}" class="btn btn-light-primary w-100 d-block my-4">Restruturar</a>
                            <p>xxxxxxxxxxxx</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h3><i class="fas fa-broom"></i></h3>
                            <h3>Limpar Historicos</h3>
                            <p>
                                Zerar todo banco de dados, isto é o historicos das vendas, operações financeiras, facturas, encomendas e todos outros registros do sistema definitivamente. <br>
                                <strong class="text-light-danger">OBS: excepto os produtos</strong>
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <button type="button" class="btn btn-light-primary w-100 d-block my-4" id="LimparBancoDados">Limpar</button>
                            <p>Caso precisa-se reiniciar a vendas</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h3><i class="fas fa-broom"></i></h3>
                            <h3>Limpar Historicos Geral</h3>
                            <p>
                                Zerar todo banco de dados, isto é o historicos das vendas, produtos, operações financeiras, facturas, encomendas e todos outros registros do sistema definitivamente. <br>
                                <strong class="text-light-danger">OBS: Apagamento tudo em geral</strong>
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <button type="button" class="btn btn-light-primary w-100 d-block my-4" id="LimparBancoDadosProdutos">Limpar</button>
                            <p>Caso precisa-se reiniciar a vendas</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h3><i class="fas fa-cloud-upload-alt"></i></h3>
                            <h3>{{ __('messages.titulo_backup_backup') }}</h3>
                            <p style="margin-bottom: 70px">
                                {{ __('messages.texto_backup_backup') }}
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <form id="formExport" action="{{ route('databases.export') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="name" value="{{ $dbName }}">
                                <button type="submit" class="btn btn-light-primary w-100 d-block my-4"><i class="fas fa-file-export"></i> {{ __('messages.export') }}</button>
                            </form>

                            <p>Envie até ao dia 15 de cada mês</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h3><i class="fas fa-cloud-download-alt"></i></h3>
                            <h3>{{ __('messages.titulo_backup_restauracao') }}</h3>
                            <p>
                                {{ __('messages.texto_backup_restauracao') }}
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <form id="formImport" action="{{ route('databases.import') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
                                @csrf
                                <input type="hidden" name="name" value="{{ $dbName }}">
                                <input type="file" name="sql_file" required>
                                <button type="submit" class="btn btn-light-primary w-100 d-block my-4"><i class="fas fa-file-import"></i> {{ __('messages.importar') }}</button>
                            </form>
                            <p>Envie até ao dia 15 de cada mês</p>
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

@endsection

@section('scripts')
<script>
    document.getElementById("LimparBancoDadosProdutos").addEventListener("click", function() {
        Swal.fire({
            title: "Confirme a senha orginial"
            , input: "password"
            , inputLabel: "Digite sua senha original para continuar"
            , inputPlaceholder: "Senha"
            , inputAttributes: {
                autocapitalize: "off"
                , autocorrect: "off"
            }
            , showCancelButton: true
            , confirmButtonText: "Confirmar"
            , cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                let senha = result.value;
                fetch("{{ route('backups-geral-banco-dados') }}", {
                        method: "POST"
                        , headers: {
                            "Content-Type": "application/json"
                            , "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                            , "Accept": "application/json"
                        }
                        , body: JSON.stringify({
                            password: senha
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.ok) {
                            Swal.fire("Sucesso", "Banco de dados restaurado com sucesso!", "success");
                        } else {
                            Swal.fire("Erro", data.message || "Senha incorreta", "error");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
                    });
            }
        });
    });

    document.getElementById("LimparBancoDados").addEventListener("click", function() {
        Swal.fire({
            title: "Confirme a senha orginial"
            , input: "password"
            , inputLabel: "Digite sua senha original para continuar"
            , inputPlaceholder: "Senha"
            , inputAttributes: {
                autocapitalize: "off"
                , autocorrect: "off"
            }
            , showCancelButton: true
            , confirmButtonText: "Confirmar"
            , cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                let senha = result.value;
                fetch("{{ route('backups-limpar-banco-dados') }}", {
                        method: "POST"
                        , headers: {
                            "Content-Type": "application/json"
                            , "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                            , "Accept": "application/json"
                        }
                        , body: JSON.stringify({
                            password: senha
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.ok) {
                            Swal.fire("Sucesso", "Banco de dados restaurado com sucesso!", "success");
                        } else {
                            Swal.fire("Erro", data.message || "Senha incorreta", "error");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
                    });
            }
        });
    });

</script>
@endsection
