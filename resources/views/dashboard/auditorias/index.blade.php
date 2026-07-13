@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Auditória</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Home</a></li>
                        <li class="breadcrumb-item active">Inicio</li>
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
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        {{-- <th>ID</th> --}}
                                        <th>Usuario</th>
                                        <th>Evento</th>
                                        <th>Em</th>
                                        <th>Novo dados</th>
                                        <th>Antigo dados</th>
                                        <th>Data Hora</th>
                                        <th>Ip Address</th>
                                        <th>URL</th>
                                        <th>{{ __('messages.accoes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                    <tr>
                                        @php
                                        $urlSemBase = Illuminate\Support\Str::replaceFirst(url('/') . '/', '', $log->url);
                                        @endphp

                                        {{-- <td>{{ $log->id }} </td> --}}
                                        <td>{{ $log->user->name ?? 'Sistema' }}</td>
                                        <td>
                                            @if ($log->event == "created")
                                            <span class="badge badge-light-success">{{ $log->event }}</span>
                                            @endif
                                            @if ($log->event == "deleted")
                                            <span class="badge badge-light-danger">{{ $log->event }}</span>
                                            @endif
                                            @if ($log->event == "updated")
                                            <span class="badge badge-light-primary">{{ $log->event }}</span>
                                            @endif
                                            @if ($log->event == "restored")
                                            <span class="badge badge-light-primary">{{ $log->event }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->auditable_type }}</td>
                                        <td>{{ json_encode($log->new_values) }}</td>
                                        <td>{{ json_encode($log->old_values) }}</td>
                                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td>{{ $log->url }}</td>
                                        <td>
                                            @if ($log->event == "created")
                                            <a href="/{{ $urlSemBase }}/{{ $log->auditable_id }}" class="btn-sm btn-outline-dark"><i class="fas fa-eye"></i></a>
                                            @endif

                                            @if ($log->event == "updated")
                                            <a href="/{{ $urlSemBase }}" class="btn-sm btn-outline-dark"><i class="fas fa-eye"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
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
    $(function() {
        $("#carregar_tabela").DataTable({
            language: {
                url: ""
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
