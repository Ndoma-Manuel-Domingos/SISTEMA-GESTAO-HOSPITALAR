@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestão de Bancos de Dados</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">de Bancos de Dados</li>
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
                            <h4 class="card-title">Bancos disponíveis</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                @foreach($databases as $db)
                                @php $dbName = $db->Database; @endphp
                                <tr>
                                    <td>
                                        {{ $dbName }} @if($dbName == $currentDb) <strong>(Ativo)</strong> @endif

                                        <form action="{{ route('databases.export') }}" id="formImport" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="name" value="{{ $dbName }}">
                                            <button type="submit" class="btn btn-sm btn-light-success"><i class="fas fa-file-export"></i> Exportar</button>
                                        </form>

                                        <button class="btn btn-sm btn-light-primary" onclick="openModal('activate', '{{ $dbName }}')"><i class="fas fa-database"></i> Ativar</button>
                                        <button class="btn btn-sm btn-light-danger" onclick="openModalDelete('delete', '{{ $dbName }}')"><i class="fas fa-trash-alt"></i> Deletar</button>

                                        <form action="{{ route('databases.import') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="name" value="{{ $dbName }}">
                                            <input type="file" name="sql_file" required>
                                            <button type="submit" class="btn btn-sm btn-light-primary"><i class="fas fa-file-import"></i> Importar</button>
                                        </form>

                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('databases.create') }}" method="POST">
                            @csrf
                            <div class="card-header">
                                <h4 class="card-title">Criar novo banco</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="text" name="name" class="form-control form-control-lg" placeholder="Nome do banco" required>
                                </div>
                                <div class="mb-3">
                                    @if(session('success'))
                                    <p style="color:green">{{ session('success') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">Criar</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="confirmForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmação de Senha</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="name" id="dbName">
                        <div class="mb-3">
                            <label for="password" class="form-label">Digite sua senha:</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-light-primary">Confirmar</button>
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmModalDelete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="confirmFormDelete" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmação de Senha</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="name" id="dbName">
                        <div class="mb-3">
                            <label for="password" class="form-label">Digite sua senha:</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-light-primary">Confirmar</button>
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- /.content -->

</div>

@endsection

@section('scripts')
<script>
    function openModal(action, dbName) {
        let form = document.getElementById('confirmForm');
        let inputDb = document.getElementById('dbName');
        inputDb.value = dbName;

        if (action === 'delete') {
            form.action = `{{ route('databases.delete', ':id') }}`.replace(':id', dbName);
        } else if (action === 'activate') {
            form.action = `{{ route('databases.activate') }}`;
        }

        let modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
    }

    function openModalDelete(action, dbName) {
        let form = document.getElementById('confirmFormDelete');
        let inputDb = document.getElementById('dbName');
        inputDb.value = dbName;

        if (action === 'delete') {
            form.action = `{{ route('databases.delete', ':id') }}`.replace(':id', dbName);
        } else if (action === 'activate') {
            form.action = `{{ route('databases.activate') }}`;
        }

        let modal = new bootstrap.Modal(document.getElementById('confirmModalDelete'));
        modal.show();
    }

</script>
@endsection
