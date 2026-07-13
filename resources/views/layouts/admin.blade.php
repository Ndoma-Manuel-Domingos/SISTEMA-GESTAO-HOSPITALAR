<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ?? '' }} | {{ $descricao ?? env('APP_NAME') }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Styles -->
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('dist/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('dist/css/sweetalert2.min.css') }}">

    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('styles')
</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed sidebar-collapse">
    <div class="wrapper">
        <div class="loading-modal d-flex" id="loading-modal">
            <div class="spinner"></div>
        </div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link h4" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                        <!-- <span class="badge badge-light-danger navbar-badge">3</span> -->
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right text-center">
                        <div class="bg-light-primary">
                            <img src="/images/empresa/icone-hospital.png" alt="Logotipo" class="img-size-64 ml-auto img-circle m-4" style="text-align: center;" />
                        </div>
                        <div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('privacidade') }}" class="dropdown-item">
                                <i class="fas fa-lock mr-2"></i> <span>{{ __('messages.alterar_senha') }}</span>
                            </a>

                            <div class="dropdown-divider"></div>
                            <a href="{{ route('utilizadores.edit', Auth::user()->id) }}" class="dropdown-item">
                                <i class="fas fa-user-edit mr-2"></i> <span>{{ __('messages.actualizar_dados') }}</span>
                            </a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-light-danger finiched-session-application" data-widget="control-sidebar" data-slide="true" role="button">
                                <i class="fas fa-sign-out-alt"></i> {{ __('messages.terminar_sessao') }}
                            </a>

                        </div>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        @include('includes.admin.main-sidebar-container')
        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        <!-- /.content-wrapper -->
        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Versão 1.0.0
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2021 - @php echo date("Y"); @endphp <a href="https://ango-info.com">{{ env('APP_NAME') }}</a>.</strong> Todos direitos Reservados.
        </footer>
        @if (Session::has('error-permissao'))
        <!-- Modal Bootstrap -->
        <div class="modal fade" id="errorPermissaoModal" tabindex="-1" aria-labelledby="errorPermissaoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-light-danger text-white">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorPermissaoModalLabel">{{ __('messages.acesso_restrito') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        {{ __('messages.sem_permissao') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('messages.fechar') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script para abrir o modal automaticamente -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var errorPermissaoModal = new bootstrap.Modal(document.getElementById('errorPermissaoModal'));
                errorPermissaoModal.show();
            });

        </script>
        @endif
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- JS TABELAS CARREGAMENTO DE DATA TABLE --}}
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    {{-- END JS TABELAS CARREGAMENTO DE DATA TABLE --}}
    {{-- sweetalert2 --}}
    <script src="{{ asset('dist/js/sweetalert2@11.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script>
        $(document).on('click', '.finiched-session-application', function(e) {
            e.preventDefault();
            // let recordId = $(this).data('id'); // Obtém o ID do registro
            // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);
            Swal.fire({
                title: 'Você tem certeza?'
                , text: "Esta ação não poderá ser desfeita!"
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#198754'
                , cancelButtonColor: '#dc3545'
                , confirmButtonText: 'Sim, desejo sair!'
                , cancelButtonText: 'Cancelar'
            , }).then((result) => {
                if (result.isConfirmed) {
                    // Envia a solicitação AJAX para excluir o registro
                    $.ajax({
                        url: `{{ route('logout') }}`
                        , method: 'POST'
                        , data: {
                            _token: '{{ csrf_token() }}', // Inclui o token CSRF
                        }
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function(response) {
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('O sucesso não espera por quem desiste!'
                                , 'Antes de sair, lembre-se: cada minuto que você investe aqui é um passo a mais rumo ao seu objetivo. Volte amanhã e continue avançando!'
                                , 'success');

                            window.location.href = response.redirect;

                        }
                        , error: function(xhr) {
                            Swal.close();

                            if (xhr.responseJSON.success == false) {
                                showMessage('Alerta!', xhr.responseJSON.message, 'warning');
                            }

                            window.location.href = xhr.responseJSON.redirect;

                        }
                    , });
                }
            });
        });

        function progressBeforeSend(title = "Processando...", text = "Por favor, aguarde.", icon = 'info') {
            Swal.fire({
                title: title
                , text: text
                , icon: icon
                , allowOutsideClick: false
                , showConfirmButton: false
                , didOpen: () => {
                    Swal.showLoading();
                }
            , });
        }

        function showMessage(title, text, icon) {
            Swal.fire({
                icon: icon
                , title: title
                , text: text
                , toast: true
                , position: 'top-end'
                , showConfirmButton: false
                , timer: 5000
            , });
        }

        function showProgressModal() {
            const modal = document.getElementById("loading-modal");
            modal.style.display = "flex";
            modal.style.zIndex = "999999"; // Corrigido: zIndex ao invés de z-index
        }

        function hideProgressModal() {
            const modal = document.getElementById("loading-modal");
            modal.style.display = "none";
            modal.style.zIndex = "-1"; // Corrigido: zIndex ao invés de z-index
        }

        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });

    </script>
    @include('sweetalert::alert')
</body>

</html>

@yield('scripts')
