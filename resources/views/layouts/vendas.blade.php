<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ?? '' }} | {{ $descricao ?? env('APP_NAME') }}</title>
    <!-- Google Font: Source Sans Pro -->
    {{-- <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('dist/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    {{-- sweetalert2 --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('dist/css/sweetalert2.min.css') }}">

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

</head>
<body class="hold-transition layout-top-nav">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->

        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                @if($LOJAACTIVAOPERADOR)
                <h1 class="h3 text-uppercase text-danger">POSTO: {{ $LOJAACTIVAOPERADOR->nome }}</h1>
                @endif
            </div>
            <!-- Default to the left -->
            @if($LOJAACTIVAOPERADOR)
            <strong>EMPRESA: {{ $LOJAACTIVAOPERADOR->entidade->nome }}</strong>
            @endif
        </footer>

    </div>
    <!-- ./wrapper -->

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

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
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

    <script src="{{ asset('assets/js/chart-.js') }}"></script>

    <script>
        function verificarLotesProximos() {
            // Não mostrar novamente hoje
            const ignorarHoje = localStorage.getItem("ignorar_alerta_lotes");
            const hoje = new Date().toISOString().slice(0, 10);

            if (ignorarHoje === hoje) {
                return; // já foi ignorado hoje
            }

            fetch('/lotes/proximos-validade')
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

        function verificarLicenca() {
            // Não mostrar novamente hoje
            const ignorarHoje = localStorage.getItem("ignorar_alerta_licenca_software");
            const hoje = new Date().toISOString().slice(0, 10);

            if (ignorarHoje === hoje) {
                return; // já foi ignorado hoje
            }

            fetch('/verificar/licenca-validade')
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
                                const url = `/renovar-licenca`;
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

        // Verifica agora e repete a cada 5 minutos
        verificarLotesProximos();
        verificarLicenca();
        setInterval(verificarLotesProximos, 300000); // 5 minutos
        setInterval(verificarLicenca, 100000); // 5 minutos

    </script>

    <script>
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

    </script>

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
                            _token: '{{ csrf_token() }}'
                            , home: 'pronto', // Inclui o token CSRF
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
    @yield('scripts')
</body>
</html>
