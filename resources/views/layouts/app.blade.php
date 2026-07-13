<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ?? '' }} | {{ $descricao ?? env('APP_NAME') }}</title>
    <!-- Google Font: Source Sans Pro -->
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('dist/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('dist/css/sweetalert2.min.css') }}">
    {{-- icono logotipo --}}
    <link rel="shortcut icon" href="images/empresa/icone-hospital.png">

    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- CSS do FullCalendar -->
    <link href="{{ asset('plugins/fullcalendar/main.min.css') }}" rel="stylesheet">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('styles')
</head>

@php
$setting = App\Models\BackupSetting::where('entidade_id', $empresa_logada->empresa->id)->first();
@endphp

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link h4" id="toggle-theme" href="#">
                        <i class="fas fa-moon"></i> <!-- Ícone de Lua -->
                    </a>
                    <input type="hidden" name="folder_path" id="folder_path" class="form-control" value="{{ $setting->folder_path ?? "" }}">
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-light-warning navbar-badge" id="alert-count">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="alert-list">
                        <span class="dropdown-item dropdown-header">Sem alertas</span>
                    </div>
                </li>

                <!-- Language Dropdown Menu -->
                <li class="nav-item dropdown">
                    <button style="border: none" class="nav-link h4 bg-light" type="button" id="langDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('flags/' . app()->getLocale() . '.svg') }}" width="30" style="margin-right: 8px;border-radius: 30px;">
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" aria-labelledby="langDropdown">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('lang.switch', 'br') }}">
                            <img src="{{ asset('flags/br.svg') }}" width="30" style="margin-right: 8px;border-radius: 30px;"> Português
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">
                            <img src="{{ asset('flags/en.svg') }}" width="30" style="margin-right: 8px;border-radius: 30px;"> English
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('lang.switch', 'fr') }}">
                            <img src="{{ asset('flags/fr.svg') }}" width="30" style="margin-right: 8px;border-radius: 30px;"> Français
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('lang.switch', 'es') }}">
                            <img src="{{ asset('flags/es.svg') }}" width="30" style="margin-right: 8px;border-radius: 30px;"> Español
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('lang.switch', 'er') }}">
                            <img src="{{ asset('flags/er.svg') }}" width="30" style="margin-right: 8px;border-radius: 30px;"> Tigrínia
                        </a>
                    </div>
                </li>

                @if (Auth::user()->can('configuracoes'))
                <li class="nav-item dropdown">
                    <a class="nav-link h4" href="{{ route('dashboard.configuracao') }}">
                        <i class="fas fa-cog"></i>
                    </a>
                </li>
                @endif

                <li class="nav-item dropdown">
                    <a class="nav-link h4" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right text-center">
                        <div class="bg-light-primary">
                            @if (!$empresa_logada->empresa->logotipo == null)
                            <img src="/images/empresa/{{ $empresa_logada->empresa->logotipo ?? "" }}" alt="Logotipo" class="img-size-64 ml-auto img-circle m-4" style="text-align: center;" />
                            @else

                            @if ($empresa_logada->empresa->tipo_entidade->sigla === 'HOSP')
                            <img src="{{ asset('images/empresa/icone-hospital.png') }}" alt="Logotipo" class="img-size-64 ml-auto img-circle m-4" style="text-align: center;" />
                            @endif

                            @if ($empresa_logada->empresa->tipo_entidade->sigla === 'REST')
                            <img src="{{ asset('images/empresa/icone-restaurante.png') }}" alt="Logotipo" class="img-size-64 ml-auto img-circle m-4" style="text-align: center;" />
                            @endif

                            @if ($empresa_logada->empresa->tipo_entidade->sigla === 'RH')
                            <img src="{{ asset('images/empresa/icone-recurso-humano.png') }}" alt="Logotipo" class="img-size-64 ml-auto img-circle m-4" style="text-align: center;" />
                            @endif

                            @if ($empresa_logada->empresa->tipo_entidade->sigla === 'HOTL')
                            <img src="{{ asset('images/empresa/icone-hotel.png') }}" alt="Logotipo" class="img-size-64 ml-auto img-circle m-4" style="text-align: center;" />
                            @endif

                            @if ($empresa_logada->empresa->tipo_entidade->sigla === 'CFAT')
                            <img src="{{ asset('images/empresa/icone-facturacao.png') }}" alt="Logotipo" class="img-size-64 ml-auto img-circle m-4" style="text-align: center;" />
                            @endif

                            @endif
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
                            <a href="{{ route('congelamento-pin-create') }}" class="dropdown-item">
                                <i class="fas fa-lock mr-2"></i> <span>{{ __('messages.congelar_aplicacao') }}</span>
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
        @include('includes.main-sidebar-container')
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
            <strong>Copyright &copy; 2021 - @php echo date("Y"); @endphp <a href="">{{ env('APP_NAME') }}</a>.</strong>
            Todos direitos Reservados.
        </footer>
    </div>
    <!-- ./wrapper -->

    {{-- @if (session('caixaAberto'))
        <div class="modal fade" id="caixaAbertoModal" tabindex="-1" role="dialog" aria-labelledby="caixaAbertoModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="caixaAbertoModalLabel">Caixa Aberto</h5>
                    </div>
                    <div class="modal-body">
                        Você deixou um caixa aberto anteriormente. Deseja fechá-lo ou continuar as vendas?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger" id="fecharCaixaBtn">Fechar Caixa</button>
                        <button type="button" class="btn btn-light-success" id="continuarVendasBtn">Continuar Vendas</button>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

    @if (session('FirstLoginSystem'))
    <div class="modal fade" id="FirstLoginSystemModal" tabindex="-1" role="dialog" aria-labelledby="FirstLoginSystemModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="FirstLoginSystemModalLabel">{{ __('messages.bem_vindo') }} {{ ENV('APP_NAME') }}
                    </h5>
                </div>
                <div class="modal-body">
                    {{ __('messages.mensagem_boas_vindas') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-success" id="AceitoConfigurarSistemaBTN">{{ __('messages.aceito') }}</button>
                </div>
            </div>
        </div>
    </div>
    @endif


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


    @if (Session::has('danger'))
    <!-- Modal Bootstrap -->
    <div class="modal fade" id="errorPermissaoModal" tabindex="-1" aria-labelledby="errorPermissaoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light-danger text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorPermissaoModalLabel">{{ __('messages.acesso_restrito') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    {{ Session::get('danger') }}
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

    <!-- REQUIRED SCRIPTS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('caixaAberto'))
            const caixaModalElement = document.getElementById('caixaAbertoModal');
            const caixaModal = new bootstrap.Modal(caixaModalElement, {
                backdrop: 'static', // Impede fechamento ao clicar fora
                keyboard: false // Impede fechamento ao pressionar "Esc"
            });

            // Verificar se o operador já clicou em "Continuar"
            if (!localStorage.getItem('continueSales')) {
                caixaModal.show();
            }

            // Mostrar a modal imediatamente ao carregar a página
            // Botão "Fechar Caixa"
            document.getElementById('fecharCaixaBtn').addEventListener('click', () => {
                // Simule o fechamento do caixa no back-end
                fetch(`/flcai/dashboard/fechamento-caixas`, {
                        method: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        caixaModal.hide();
                        // window.location.href = '/dashboard/fechamento-caixas'; // Redireciona para a página de login
                    })
                    .catch(error => console.error('Erro:', error));
            });

            // Botão "Continuar Vendas"
            document.getElementById('continuarVendasBtn').addEventListener('click', () => {
                // Simule o fechamento do caixa no back-end
                fetch(`/flcai/dashboard/continuar-com-caixas`, {
                        method: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        caixaModal.hide();
                    })
                    .catch(error => console.error('Erro:', error));
            });
            @endif

            @if(session('FirstLoginSystem'))
            const caixaModalElement = document.getElementById('FirstLoginSystemModal');
            const caixaModal = new bootstrap.Modal(caixaModalElement, {
                backdrop: 'static', // Impede fechamento ao clicar fora
                keyboard: false // Impede fechamento ao pressionar "Esc"
            });

            // Verificar se o operador já clicou em "Continuar"
            if (!localStorage.getItem('continueSalesR')) {
                caixaModal.show();
            }

            // Botão "Continuar Vendas"
            document.getElementById('AceitoConfigurarSistemaBTN').addEventListener('click', () => {
                $.ajax({
                    url: `{{ route('aceito-configurar-sistema') }}`, // URL do endpoint no backend
                    method: 'POST', // Método HTTP definido no formulário
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!'
                            , 'success');
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
            @endif
        });

    </script>


    <script src="https://cdn.jsdelivr.net/npm/three@0.165.0/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.165.0/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.165.0/examples/js/controls/OrbitControls.js"></script>


    <script src="{{ asset('assets/anatomia/js/anatomy-viewer.js') }}"></script>
    <script src="{{ asset('assets/anatomia/js/anatomy-click.js') }}"></script>
    <script src="{{ asset('assets/anatomia/js/anatomy-api.js') }}"></script>
    <script src="{{ asset('assets/anatomia/js/anatomy-modal.js') }}"></script>
    <script src="{{ asset('assets/anatomia/js/anatomy-highlight.js') }}"></script>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <script src="{{ asset('plugins/chart.js/Chart.js') }}"></script>

    <script src="{{ asset('plugins/chart.js/Chart.js') }}"></script>
    {{-- sweetalert2 --}}
    <script src="{{ asset('dist/js/sweetalert2@11.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('dist/js/html2canvas.min.js') }}"></script>
    <script src="{{ asset('dist/js/jspdf.umd.min.js') }}"></script>


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

    <!-- JS do FullCalendar -->
    <script src="{{ asset('plugins/fullcalendar/main.min.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <script>
        $(function() {
            $('.select2').select2()
        });

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

        function paginate(res) {
            let html = `<nav><ul class="pagination justify-content-end">`;

            if (res.current_page > 1) {
                html += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadSeries(${res.current_page - 1})">«</a>
                </li>`;
            }

            for (let i = 1; i <= res.last_page; i++) {
                html += `
                <li class="page-item ${res.current_page==i?'active':''}">
                    <a class="page-link" href="#" onclick="loadSeries(${i})">${i}</a>
                </li>`;
            }

            if (res.current_page < res.last_page) {
                html += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="loadSeries(${res.current_page + 1})">»</a>
                </li>`;
            }

            html += `</ul></nav>`;

            $("#pagination").html(html);
        }


        function carregarAlertas() {
            fetch('/contratos/alertas')
                .then(response => response.json())
                .then(alertas => {
                    let alertCount = alertas.length;
                    document.getElementById('alert-count').textContent = alertCount;

                    let alertList = document.getElementById('alert-list');
                    alertList.innerHTML = "";

                    if (alertCount === 0) {
                        alertList.innerHTML = '<span class="dropdown-item dropdown-header">Sem alertas</span>';
                    } else {
                        alertas.forEach(alerta => {
                            let item = `
                                <a href="/clients/clientes-contratos/${alerta.id}" class="dropdown-item">
                                    <i class="fas fa-exclamation-triangle text-light-danger"></i>
                                    <strong>${alerta.cliente}</strong> - <br/> ${alerta.status}
                                    <span class="float-right text-muted text-sm">${alerta.data_final}</span>
                                </a>
                            `;
                            alertList.innerHTML += item;
                        });
                        alertList.innerHTML += '<div class="dropdown-divider"></div><a href="/clients/clientes-contratos" class="dropdown-item dropdown-footer">Ver todos os contratos</a>';
                    }
                })
                .catch(err => console.error(err));
        }


        function verificarLicenca() {
            // Não mostrar novamente hoje
            const ignorarHoje = localStorage.getItem("ignorar_alerta_licenca_software");
            const hoje = new Date().toISOString().slice(0, 10);

            const route = "{{ route('verificar.licenca-validade') }}";

            if (ignorarHoje === hoje) {
                return; // já foi ignorado hoje
            }

            fetch(route)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Informação Importante: Licença do Software!'
                            , text: `A sua licença expirará em ${data.dias_restantes} dias. Renove-a para continuar a usufruir do sistema sem interrupções.`
                            , icon: 'warning'
                            , showDenyButton: true
                            , showCancelButton: true
                            , confirmButtonText: 'Renovar licença agora'
                            , denyButtonText: 'Não mostrar hoje'
                            , cancelButtonText: 'Ignorar por agora'
                        , }).then((result) => {
                            if (result.isConfirmed) {
                                // Mostra os detalhes dos lotes
                                let mensagem = "";
                                const url = `/licenses/upload`;
                                mensagem +=
                                    `<div style="text-align: center;"><a href="${url}" style="font-size: 20px;">Clique aqui para proceder <br/>com a renovação da sua licença</a></div>`;

                                Swal.fire({
                                    title: 'Renovar licença agora'
                                    , icon: 'info'
                                    , html: `<pre style="text-align:left">${mensagem}</pre>`
                                , });

                            } else if (result.isDenied) {
                                // Salva para não mostrar novamente hoje
                                localStorage.setItem("ignorar_alerta_licenca_software", hoje);
                            }
                            // Cancelado = ignorar por agora (não faz nada)
                        });
                    }
                })
                .catch(err => console.error("Erro ao validação de licença:", err));
        }

        function verificarLotesProximos() {
            // Não mostrar novamente hoje
            const ignorarHoje = localStorage.getItem("ignorar_alerta_lotes");
            const hoje = new Date().toISOString().slice(0, 10);

            if (ignorarHoje === hoje) {
                return; // já foi ignorado hoje
            }

            const route = "{{ route('lotes.proximos-validade') }}";

            fetch(route)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        Swal.fire({
                            title: 'Lotes a expirar!'
                            , text: `Há ${data.length} lote(s) que expiram nos próximos 4 dias.`
                            , icon: 'warning'
                            , showDenyButton: true
                            , showCancelButton: true
                            , confirmButtonText: 'Ver agora'
                            , denyButtonText: 'Não mostrar hoje'
                            , cancelButtonText: 'Ignorar por agora'
                        , }).then((result) => {
                            if (result.isConfirmed) {
                                // Mostra os detalhes dos lotes
                                let mensagem = "Lotes prestes a expirar:\n\n";
                                data.forEach(lote => {
                                    const url = `/prds/produtos/${lote.produto.id}`;
                                    mensagem +=
                                        `<a href="${url}">• Produto: ${lote.produto.nome} | ${lote.lote || 'sem nome'} | Validade: ${lote.data_validade}</a>\n`;
                                });

                                Swal.fire({
                                    title: 'Detalhes dos lotes'
                                    , icon: 'info'
                                    , html: `<pre style="text-align:left">${mensagem}</pre>`
                                    , confirmButtonText: 'Entendi'
                                });

                            } else if (result.isDenied) {
                                // Salva para não mostrar novamente hoje
                                localStorage.setItem("ignorar_alerta_lotes", hoje);
                            }
                            // Cancelado = ignorar por agora (não faz nada)
                        });
                    }
                })
                .catch(err => console.error("Erro ao buscar lotes:", err));
        }

        // Verifica agora e repete a cada 5 minutos
        verificarLotesProximos();
        setInterval(verificarLotesProximos, 300000); // 5 minutos


        verificarLicenca();
        setInterval(verificarLicenca, 100000); // 5 minutos

        // Carrega ao abrir e a cada 60 segundos
        carregarAlertas();
        setInterval(carregarAlertas, 10000);


        document.addEventListener("DOMContentLoaded", function() {
            const toggleThemeBtn = document.getElementById("toggle-theme");
            const icon = toggleThemeBtn.querySelector("i");
            const text = toggleThemeBtn.querySelector("span");

            // Verifica se o usuário já tem um tema salvo
            if (localStorage.getItem("theme") === "dark") {
                document.body.classList.add("dark-mode");
                icon.classList.replace("fa-moon", "fa-sun");
                // text.textContent = "Modo Claro";
            }

            toggleThemeBtn.addEventListener("click", function(event) {
                event.preventDefault();

                if (document.body.classList.contains("dark-mode")) {
                    document.body.classList.remove("dark-mode");
                    localStorage.setItem("theme", "light");
                    icon.classList.replace("fa-sun", "fa-moon");
                    // text.textContent = "Modo Escuro";
                } else {
                    document.body.classList.add("dark-mode");
                    localStorage.setItem("theme", "dark");
                    icon.classList.replace("fa-moon", "fa-sun");
                    // text.textContent = "Modo Claro";
                }
            });
        })


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

        /*document.addEventListener('DOMContentLoaded', function() {
            const userFrequency = {
                {
                    (int) $setting - > frequency_minutes
                }
            };
            const enabled = {
                {
                    $setting - > enabled ? 'true' : 'false'
                }
            };
            const intervalMs = userFrequency * 60 * 1000;

            // salva a pasta no localStorage para uso imediato no JS (opcional)
            localStorage.setItem('backup_folder', document.getElementById('folder_path').value);

            // dispara o AJAX agora e depois programado (se habilitado)
            if (enabled) {
                // dispara ao carregar (opcional) e depois a cada intervalMs
                triggerBackupAjax();
                setInterval(triggerBackupAjax, intervalMs);
            }

            function triggerBackupAjax() {

                const route = "{{ route('backup.trigger') }}";

                fetch(route, {
                    method: 'POST'
                    , headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        , 'Accept': 'application/json'
                        , 'Content-Type': 'application/json'
                    }
                    , body: JSON.stringify({})
                }).then(r => r.json()).then(data => {
                    console.log("BACKUP COMEÇOU")
                }).catch(err => {
                    console.error(err);
                });
            }
        });*/

    </script>

</body>

</html>

@yield('scripts')
